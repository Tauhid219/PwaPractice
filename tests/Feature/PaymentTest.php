<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Level;
use App\Models\PaymentOrder;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Services\SslCommerzService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_unpaid_user_can_access_checkout_page()
    {
        $user = User::factory()->create(['is_paid' => false]);

        $response = $this->actingAs($user)->get(route('payment.checkout'));

        $response->assertStatus(200);
        $response->assertViewIs('payments.checkout');
    }

    public function test_paid_user_cannot_access_checkout_page()
    {
        $user = User::factory()->create(['is_paid' => true]);

        $response = $this->actingAs($user)->get(route('payment.checkout'));

        $response->assertRedirect(route('home'));
    }

    public function test_unpaid_user_cannot_access_paid_routes()
    {
        $user = User::factory()->create(['is_paid' => false]);
        $category = Category::factory()->create();
        $level = Level::factory()->create(['category_id' => $category->id, 'is_free' => false]);

        $response = $this->actingAs($user)->get(route('level.questions', ['slug' => $category->slug, 'level' => $level->id]));

        $response->assertRedirect(route('payment.checkout'));
    }

    public function test_payment_initiation_redirects_to_gateway()
    {
        $user = User::factory()->create(['is_paid' => false]);

        // Mock SslCommerzService
        $mockSslCommerz = Mockery::mock(SslCommerzService::class);
        $mockSslCommerz->shouldReceive('initiatePayment')
            ->once()
            ->andReturn('https://sandbox.sslcommerz.com/gwprocess/v4/gw.php?Q=pay&SESSIONKEY=123');
        $this->app->instance(SslCommerzService::class, $mockSslCommerz);

        $response = $this->actingAs($user)->post(route('payment.pay'));

        // Assert redirect to external gateway
        $response->assertRedirect('https://sandbox.sslcommerz.com/gwprocess/v4/gw.php?Q=pay&SESSIONKEY=123');

        // Assert pending order created
        $this->assertDatabaseHas('payment_orders', [
            'user_id' => $user->id,
            'status' => 'pending',
            'amount' => 50.00
        ]);
    }

    public function test_payment_success_callback_updates_status()
    {
        $user = User::factory()->create(['is_paid' => false]);
        $order = PaymentOrder::create([
            'user_id' => $user->id,
            'reference' => 'APP_TEST_123',
            'item_name' => 'App Access',
            'amount' => 50.00,
            'currency' => 'BDT',
            'status' => 'pending',
        ]);

        // Mock SslCommerzService validation
        $mockSslCommerz = Mockery::mock(SslCommerzService::class);
        $mockSslCommerz->shouldReceive('validatePayment')
            ->once()
            ->with('VAL_123')
            ->andReturn([
                'status' => 'VALID',
                'tran_id' => 'BANK_TX_123',
                'amount' => 50.00,
                'currency' => 'BDT'
            ]);
        $this->app->instance(SslCommerzService::class, $mockSslCommerz);

        // Call the success webhook without being logged in (simulating cross-site POST)
        $response = $this->post(route('payment.success'), [
            'tran_id' => 'APP_TEST_123',
            'val_id' => 'VAL_123'
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('payments.redirect');

        // Assert order updated
        $this->assertDatabaseHas('payment_orders', [
            'id' => $order->id,
            'status' => 'paid',
        ]);

        // Assert transaction logged
        $this->assertDatabaseHas('payment_transactions', [
            'payment_order_id' => $order->id,
            'provider_reference' => 'BANK_TX_123',
            'status' => 'paid',
        ]);

        // Assert user is paid
        $this->assertTrue((bool)$user->fresh()->is_paid);
    }

    public function test_payment_success_callback_fails_on_amount_mismatch()
    {
        $user = User::factory()->create(['is_paid' => false]);
        $order = PaymentOrder::create([
            'user_id' => $user->id,
            'reference' => 'APP_TEST_123',
            'item_name' => 'App Access',
            'amount' => 50.00,
            'currency' => 'BDT',
            'status' => 'pending',
        ]);

        // Mock SslCommerzService validation returning different amount
        $mockSslCommerz = Mockery::mock(SslCommerzService::class);
        $mockSslCommerz->shouldReceive('validatePayment')
            ->once()
            ->with('VAL_123')
            ->andReturn([
                'status' => 'VALID',
                'tran_id' => 'BANK_TX_123',
                'amount' => 10.00, // MISMATCH!
                'currency' => 'BDT'
            ]);
        $this->app->instance(SslCommerzService::class, $mockSslCommerz);

        $response = $this->post(route('payment.success'), [
            'tran_id' => 'APP_TEST_123',
            'val_id' => 'VAL_123'
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('payments.redirect');
        
        // Assert order is failed
        $this->assertDatabaseHas('payment_orders', [
            'id' => $order->id,
            'status' => 'failed',
        ]);
        
        $this->assertFalse((bool)$user->fresh()->is_paid);
    }

    public function test_payment_fail_callback()
    {
        $user = User::factory()->create(['is_paid' => false]);
        $order = PaymentOrder::create([
            'user_id' => $user->id,
            'reference' => 'APP_TEST_123',
            'item_name' => 'App Access',
            'amount' => 50.00,
            'currency' => 'BDT',
            'status' => 'pending',
        ]);

        $response = $this->post(route('payment.fail'), [
            'tran_id' => 'APP_TEST_123',
            'bank_tran_id' => 'BANK_FAIL_123',
            'amount' => 50.00,
            'currency' => 'BDT'
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('payments.redirect');

        // Assert order updated
        $this->assertDatabaseHas('payment_orders', [
            'id' => $order->id,
            'status' => 'failed',
        ]);

        // Assert user still unpaid
        $this->assertFalse((bool)$user->fresh()->is_paid);
    }

    public function test_payment_cancel_callback()
    {
        $user = User::factory()->create(['is_paid' => false]);
        $order = PaymentOrder::create([
            'user_id' => $user->id,
            'reference' => 'APP_TEST_123',
            'item_name' => 'App Access',
            'amount' => 50.00,
            'currency' => 'BDT',
            'status' => 'pending',
        ]);

        $response = $this->post(route('payment.cancel'), [
            'tran_id' => 'APP_TEST_123',
            'amount' => 50.00,
            'currency' => 'BDT'
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('payments.redirect');

        // Assert order updated
        $this->assertDatabaseHas('payment_orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);

        // Assert user still unpaid
        $this->assertFalse((bool)$user->fresh()->is_paid);
    }

    public function test_ipn_webhook_validates_and_updates_status()
    {
        $user = User::factory()->create(['is_paid' => false]);
        $order = PaymentOrder::create([
            'user_id' => $user->id,
            'reference' => 'APP_TEST_123',
            'item_name' => 'App Access',
            'amount' => 50.00,
            'currency' => 'BDT',
            'status' => 'pending',
        ]);

        // Mock SslCommerzService validation
        $mockSslCommerz = Mockery::mock(SslCommerzService::class);
        $mockSslCommerz->shouldReceive('validatePayment')
            ->once()
            ->with('VAL_123')
            ->andReturn([
                'status' => 'VALID',
                'tran_id' => 'BANK_TX_123',
                'amount' => 50.00,
                'currency' => 'BDT'
            ]);
        $this->app->instance(SslCommerzService::class, $mockSslCommerz);

        $response = $this->post(route('payment.ipn'), [
            'tran_id' => 'APP_TEST_123',
            'status' => 'VALID',
            'val_id' => 'VAL_123'
        ]);

        $response->assertStatus(200);

        // Assert order updated
        $this->assertDatabaseHas('payment_orders', [
            'id' => $order->id,
            'status' => 'paid',
        ]);

        // Assert transaction logged
        $this->assertDatabaseHas('payment_transactions', [
            'payment_order_id' => $order->id,
            'provider_reference' => 'BANK_TX_123',
            'status' => 'paid',
        ]);

        // Assert user is paid
        $this->assertTrue((bool)$user->fresh()->is_paid);
    }
}
