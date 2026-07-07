<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use App\Models\PaymentTransaction;
use App\Services\SslCommerzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $sslCommerz;

    public function __construct(SslCommerzService $sslCommerz)
    {
        $this->sslCommerz = $sslCommerz;
    }

    public function checkout()
    {
        if (auth()->check() && auth()->user()->is_paid) {
            return redirect()->route('home');
        }

        return view('payments.checkout');
    }

    public function pay(Request $request)
    {
        $user = auth()->user();

        // Check if user is already paid
        if ($user->is_paid) {
            return redirect()->route('home')->with('success', 'You have already paid for app access.');
        }

        // Create an internal order
        $order = PaymentOrder::create([
            'user_id' => $user->id,
            'reference' => 'APP_' . uniqid() . '_' . Str::random(5),
            'item_name' => 'App Access',
            'amount' => 50.00, // Small amount
            'currency' => 'BDT',
            'status' => 'pending',
        ]);

        $paymentData = [
            'amount' => $order->amount,
            'currency' => $order->currency,
            'transaction_id' => $order->reference,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '01711111111',
            'product_name' => $order->item_name,
            'value_a' => session()->getId(), // Preserve session across cross-site POST
        ];

        $paymentUrl = $this->sslCommerz->initiatePayment($paymentData);

        if ($paymentUrl) {
            return redirect()->away($paymentUrl);
        }

        return redirect()->back()->with('error', 'Failed to initialize payment gateway.');
    }

    protected function safeRedirect($url, $status = null, $message = null)
    {
        if ($status && $message) {
            session()->flash($status, $message);
        }
        return view('payments.redirect', compact('url'));
    }

    protected function processSuccessfulPayment(PaymentOrder $order, array $validation)
    {
        $order->update(['status' => 'paid']);

        PaymentTransaction::create([
            'payment_order_id' => $order->id,
            'provider' => 'sslcommerz',
            'provider_reference' => $validation['tran_id'],
            'amount' => $validation['amount'],
            'currency' => $validation['currency'],
            'status' => 'paid',
            'payload' => $validation,
            'verified_at' => now(),
        ]);

        $order->user->update(['is_paid' => true]);
    }

    public function success(Request $request)
    {
        if ($request->filled('value_a')) {
            session()->setId($request->input('value_a'));
            session()->start();
        }

        $tran_id = $request->input('tran_id');
        $val_id = $request->input('val_id');

        $order = PaymentOrder::where('reference', $tran_id)->first();

        if (!$order) {
            return $this->safeRedirect(route('home'), 'error', 'Invalid Transaction');
        }

        if ($order->status == 'pending') {
            $validation = $this->sslCommerz->validatePayment($val_id);

            if (isset($validation['status']) && ($validation['status'] == 'VALID' || $validation['status'] == 'VALIDATED')) {
                // Strict amount and currency validation
                if ($validation['amount'] != $order->amount || $validation['currency'] != $order->currency) {
                    DB::transaction(function () use ($order) {
                        $lockedOrder = PaymentOrder::where('id', $order->id)->lockForUpdate()->first();
                        if ($lockedOrder->status == 'pending') {
                            $lockedOrder->update(['status' => 'failed']);
                        }
                    });
                    return $this->safeRedirect(route('home'), 'error', 'Payment amount mismatch. Validation failed.');
                }

                $success = DB::transaction(function () use ($order, $validation) {
                    $lockedOrder = PaymentOrder::where('id', $order->id)->lockForUpdate()->first();
                    if ($lockedOrder->status == 'pending') {
                        $this->processSuccessfulPayment($lockedOrder, $validation);
                        return true;
                    }
                    return $lockedOrder->status == 'paid';
                });

                if ($success) {
                    return $this->safeRedirect(route('home'), 'success', 'Payment successful. You can now access the app!');
                }
            } else {
                DB::transaction(function () use ($order) {
                    $lockedOrder = PaymentOrder::where('id', $order->id)->lockForUpdate()->first();
                    if ($lockedOrder->status == 'pending') {
                        $lockedOrder->update(['status' => 'failed']);
                    }
                });
                return $this->safeRedirect(route('home'), 'error', 'Payment validation failed.');
            }
        } else if ($order->status == 'paid') {
            return $this->safeRedirect(route('home'), 'success', 'Payment successful. You can now access the app!');
        } else {
            return $this->safeRedirect(route('home'), 'error', 'Invalid Payment Order.');
        }
    }

    public function fail(Request $request)
    {
        if ($request->filled('value_a')) {
            session()->setId($request->input('value_a'));
            session()->start();
        }

        $tran_id = $request->input('tran_id');
        
        $order = PaymentOrder::where('reference', $tran_id)->first();
        if ($order) {
            if ($order->status == 'pending') {
                DB::transaction(function () use ($order, $request) {
                    $lockedOrder = PaymentOrder::where('id', $order->id)->lockForUpdate()->first();
                    if ($lockedOrder->status == 'pending') {
                        $lockedOrder->update(['status' => 'failed']);
                        
                        PaymentTransaction::create([
                            'payment_order_id' => $lockedOrder->id,
                            'provider' => 'sslcommerz',
                            'provider_reference' => $request->input('bank_tran_id'),
                            'amount' => $request->input('amount'),
                            'currency' => $request->input('currency'),
                            'status' => 'failed',
                            'payload' => $request->all(),
                        ]);
                    }
                });
            }
        }

        return $this->safeRedirect(route('payment.checkout'), 'error', 'Payment failed.');
    }

    public function cancel(Request $request)
    {
        if ($request->filled('value_a')) {
            session()->setId($request->input('value_a'));
            session()->start();
        }

        $tran_id = $request->input('tran_id');

        $order = PaymentOrder::where('reference', $tran_id)->first();
        if ($order) {
            if ($order->status == 'pending') {
                DB::transaction(function () use ($order, $request) {
                    $lockedOrder = PaymentOrder::where('id', $order->id)->lockForUpdate()->first();
                    if ($lockedOrder->status == 'pending') {
                        $lockedOrder->update(['status' => 'cancelled']);
                        
                        PaymentTransaction::create([
                            'payment_order_id' => $lockedOrder->id,
                            'provider' => 'sslcommerz',
                            'provider_reference' => null,
                            'amount' => $request->input('amount'),
                            'currency' => $request->input('currency'),
                            'status' => 'cancelled',
                            'payload' => $request->all(),
                        ]);
                    }
                });
            }
        }

        return $this->safeRedirect(route('payment.checkout'), 'error', 'Payment cancelled.');
    }

    public function ipn(Request $request)
    {
        Log::info('SSLCommerz IPN Hit', $request->all());

        if ($request->input('tran_id')) {
            $tran_id = $request->input('tran_id');
            $order = PaymentOrder::where('reference', $tran_id)->first();

            if ($order && $order->status == 'pending') {
                if ($request->input('status') == 'VALID') {
                    $val_id = $request->input('val_id');
                    $validation = $this->sslCommerz->validatePayment($val_id);

                    if (isset($validation['status']) && ($validation['status'] == 'VALID' || $validation['status'] == 'VALIDATED')) {
                        if ($validation['amount'] == $order->amount && $validation['currency'] == $order->currency) {
                            DB::transaction(function () use ($order, $validation) {
                                $lockedOrder = PaymentOrder::where('id', $order->id)->lockForUpdate()->first();
                                if ($lockedOrder->status == 'pending') {
                                    $this->processSuccessfulPayment($lockedOrder, $validation);
                                }
                            });
                        } else {
                            DB::transaction(function () use ($order) {
                                $lockedOrder = PaymentOrder::where('id', $order->id)->lockForUpdate()->first();
                                if ($lockedOrder->status == 'pending') {
                                    $lockedOrder->update(['status' => 'failed']);
                                }
                            });
                        }
                    }
                }
            }
        }

        return response('IPN Processed', 200);
    }
}
