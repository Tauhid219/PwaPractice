<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SslCommerzService
{
    protected $storeId;
    protected $storePassword;
    protected $isSandbox;
    protected $baseUrl;

    public function __construct()
    {
        $this->storeId = config('services.sslcommerz.store_id');
        $this->storePassword = config('services.sslcommerz.store_password');
        $this->isSandbox = config('services.sslcommerz.is_sandbox', true);
        
        $this->baseUrl = $this->isSandbox 
            ? 'https://sandbox.sslcommerz.com' 
            : 'https://securepay.sslcommerz.com';
    }

    public function initiatePayment($paymentData)
    {
        $url = $this->baseUrl . "/gwprocess/v4/api.php";
        
        $postData = [
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePassword,
            'total_amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'] ?? 'BDT',
            'tran_id' => $paymentData['transaction_id'],
            'success_url' => route('payment.success'),
            'fail_url' => route('payment.fail'),
            'cancel_url' => route('payment.cancel'),
            'ipn_url' => route('payment.ipn'),
            'cus_name' => $paymentData['name'],
            'cus_email' => $paymentData['email'],
            'cus_add1' => 'Dhaka',
            'cus_add2' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '1000',
            'cus_country' => 'Bangladesh',
            'cus_phone' => $paymentData['phone'] ?? '01711111111',
            'cus_fax' => 'Not-Applicable',
            'shipping_method' => 'NO',
            'product_name' => $paymentData['product_name'],
            'product_category' => 'App Access',
            'product_profile' => 'non-physical-goods',
            'value_a' => $paymentData['value_a'] ?? null,
        ];

        try {
            $response = Http::timeout(15)->asForm()->post($url, $postData);
            $result = $response->json();

            if (isset($result['status']) && $result['status'] === 'SUCCESS') {
                return $result['GatewayPageURL'];
            }

            Log::error('SSLCommerz Initialization Failed', ['response' => $response->body()]);
        } catch (\Exception $e) {
            Log::error('SSLCommerz Initialization Exception', ['exception' => $e->getMessage()]);
        }

        return false;
    }

    public function validatePayment($valId)
    {
        $url = $this->baseUrl . "/validator/api/validationserverAPI.php";

        try {
            $response = Http::timeout(15)->get($url, [
                'val_id' => $valId,
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'v' => 1,
                'format' => 'json'
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('SSLCommerz Validation Exception', ['exception' => $e->getMessage(), 'val_id' => $valId]);
            return ['status' => 'FAILED', 'error' => 'API Error'];
        }
    }
}
