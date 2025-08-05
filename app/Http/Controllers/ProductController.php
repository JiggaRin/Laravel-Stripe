<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UnexpectedValueException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('product.index', compact('products'));
    }

    public function checkout()
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        $products = Product::all();
        $lineItems = [];
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->price;
            $lineItems[] = [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $product->name
                        ],
                        'unit_amount' => $product->price * 100,
                    ],
                    'quantity' => 1,
                ]
            ];
        }


        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('checkout.cancel'),
        ]);

        $order = new Order();
        $order->status = 'unpaid';
        $order->total_price = $totalPrice;
        $order->session_id = $checkout_session->id;
        $order->save();

        return redirect($checkout_session->url);
    }

    /**
     * @throws Exception
     */
    public function success(Request $request)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        $sessionId = $request->get('session_id');

        try {
            $session = $stripe->checkout->sessions->retrieve($sessionId);

            if (!isset($session)) {
                throw new NotFoundHttpException();
            }
            $customer = $session->customer_details;

            $order = Order::where('session_id', $session->id)->first();

            if (!$order) {
                throw new NotFoundHttpException();
            }

            if ($order->status === 'unpaid') {
                $order->status = 'paid';
                $order->save();
            }

            return view('product.checkout-success', compact('customer'));
        } catch (Exception $e) {
            throw new NotFoundHttpException($e);
        }
    }

    public function cancel()
    {
        //
    }

    public function webhook()
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            return response('', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                $order = Order::where('session_id', $session->id)->first();
                if ($order && $order->status === 'unpaid') {
                    $order->status = 'paid';
                    $order->save();
                    // Send email to customer
                }

            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('');
    }
}
