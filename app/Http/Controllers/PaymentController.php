<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Validator;
use App\Order;
use App\Painting;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $useLocalCerts = false;

    const PAYPAL_VERIFY_URI = 'https://ipnpb.paypal.com/cgi-bin/webscr';
    const PAYPAL_VALID = 'VERIFIED';
    const PAYPAL_INVALID = 'INVALID';

    public function formHandler(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'zip' => $request->input('zip'),
            'paymentMethod' => $request->input('method'),
        ];

        $rules = [
            'name' => [
                'required',
                'regex:/^[A-z ]+$/',
            ],
            'email' => [
                'required',
                'email',
            ],
            'address' => [
                'required',
                'regex:/[A-Za-z0-9\'\.\-\s\,]/',
            ],
            'city' => [
                'required',
                'regex:/^[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$/',
            ],
            'state' => [
                'required',
                'regex:/^[A-z ]+$/',
            ],
            'zip' => [
                'required',
                'regex:/^\d{5}(?:[-\s]\d{4})?$/',
            ],
            'paymentMethod' => [
                'required',
                'in:paypal,stripe',
            ],
        ];

        $messages = [
            'name.required' => __('ui.paymentNameRequired'),
            'name.regex' => __('ui.paymentNameRegex'),
            'email.required' => __('ui.paymentEmailRequired'),
            'email.email' => __('ui.paymentEmailEmail'),
            'address.required' => __('ui.paymentAddressRequired'),
            'address.regex' => __('ui.paymentAddressRegex'),
            'city.required' => __('ui.paymentCityRequired'),
            'city.regex' => __('ui.paymentCityRegex'),
            'state.required' => __('ui.paymentStateRequired'),
            'state.regex' => __('ui.paymentStateRegex'),
            'zip.required' => __('ui.paymentZIPRequired'),
            'zip.regex' => __('ui.paymentZIPRegex'),
            'paymentMethod.required' => __('ui.paymentMethodRequired'),
            'paymentMethod.in' => __('ui.paymentMethodIn'),
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {

            return [
                'status' => false,
                'data' => [
                    'name' => $validator->errors()->first('name'),
                    'email' => $validator->errors()->first('email'),
                    'address' => $validator->errors()->first('address'),
                    'city' => $validator->errors()->first('city'),
                    'state' => $validator->errors()->first('state'),
                    'zip' => $validator->errors()->first('zip'),
                    'paymentMethod' => $validator->errors()->first('paymentMethod'),
                ],
            ];

        } else {

            $method = $request->input('method');
            $items = Session::get('cart');

            foreach ($items as $item) {
                $ids[] = $item['id'];
            }

            $items = Painting::whereIn('id', $ids)->get();
            $price = $items->sum('price');

            $orderData = json_encode([
                'ids' => $ids,
                'price' => $price,
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'zip' => $request->input('zip'),
                'method' => $method,
            ]);

            $order = new Order();
            $order->type = 'painting';
            $order->price = $price;
            $order->email = $request->input('email');
            $order->comment = null;
            $order->data = $orderData;
            $order->save();

            switch ($method) {

                case 'paypal':

                    $paymentForm = $this->generatePaypalForm($items, $order->id);

                    return Response()->json([
                        'status' => true,
                        'data' => [
                            'form' => $paymentForm,
                        ],
                    ]);

                    break;
                
                case 'stripe':

                    if ($request->input('token') !== null) {

                        $response = $this->stripePay(
                            $order,
                            $request->input('email'),
                            $request->input('token'),
                            $price
                        );

                        if ($response['failure_code'] !== 'null') {

                            foreach ($items as $item) {
                                $item->availability = 0;
                                $item->save();
                            }

                            Session::forget('cart');

                            return Response()->json([
                                'status' => true,
                                'token' => true,
                                'stripeSuccess' => true,
                                'data' => [
                                    'successMessage' => 'OK',
                                    'errorMessage' => false,
                                    'redirect' => route('payment::success'),
                                ],
                            ]);

                        } else {

                            return Response()->json([
                                'status' => true,
                                'token' => true,
                                'stripeSuccess' => false,
                                'data' => [
                                    'successMessage' => false,
                                    'errorMessage' => $response['failure_message'],
                                ],
                            ]);

                        }

                    } else {

                        return Response()->json([
                            'status' => true,
                            'token' => false,
                        ]);

                    }
                    
                    break;

            }

        }
    }

    private function generatePaypalForm($items, $orderId)
    {
        $paypalUrl = config('app.paypal_payment_url');
        $paypalNotifyUrl = config('app.paypal_notify_url');
        $paypalReturnUrl = config('app.paypal_return_url');
        $merchantEmail = config('app.paypal_merchant_email');
        $paypalCurrencyCode = config('app.paypal_currency_code');

        return view('payment.paypalForm', [
            'paypalUrl' => $paypalUrl,
            'paypalNotifyUrl' => $paypalNotifyUrl,
            'paypalReturnUrl' => $paypalReturnUrl,
            'merchantEmail' => $merchantEmail,
            'paypalCurrencyCode' => $paypalCurrencyCode,
            'items' => $items,
            'customData' => $orderId,
        ])->render();
    }

    public function paypalIpn(Request $request)
    {
        if (!count($_POST)) {
            throw new Exception("Missing POST Data");
        }

        $rawPostData = file_get_contents('php://input');
        $rawPostArray = explode('&', $rawPostData);
        $myPost = [];

        foreach ($rawPostArray as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                if ($keyval[0] === 'payment_date') {
                    if (substr_count($keyval[1], '+') === 1) {
                        $keyval[1] = str_replace('+', '%2B', $keyval[1]);
                    }
                }
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }

        $req = 'cmd=_notify-validate';
        $getMagicQuotesExists = false;

        if (function_exists('get_magic_quotes_gpc')) {
            $getMagicQuotesExists = true;
        }

        foreach ($myPost as $key => $value) {
            if ($getMagicQuotesExists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        $ch = curl_init(self::PAYPAL_VERIFY_URI);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        if ($this->useLocalCerts) {
            curl_setopt($ch, CURLOPT_CAINFO, __DIR__."/cert/cacert.pem");
        }

        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Close']);
        $res = curl_exec($ch);

        if (!($res)) {
            $errno = curl_errno($ch);
            $errstr = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: [$errno] $errstr");
        }

        $info = curl_getinfo($ch);
        $httpCode = $info['http_code'];

        if ($httpCode != 200) {
            throw new Exception("PayPal responded with http code $httpCode");
        }

        curl_close($ch);

        if ($res == self::PAYPAL_VALID) {
            
            $orderId = $_POST['custom'];
            $order = Order::find($orderId);
            $orderData = json_decode($order->data, true);

            $ids = explode(',', $orderData['ids']);
            $items = Painting::whereIn('id', $ids)->get();

            foreach ($items as $item) {
                $item->availability = 0;
                $item->save();
            }

            Session::forget('cart');
            
            $orderData['orderId'] = $order->id;
            $order->data = json_encode($orderData);
            $order->status = 1;
            $order->save();

        } else {

            $orderId = $_POST['custom'];

            $order = Order::find($orderId);
            $order->status = 2;
            $order->save();

        }
    }

    public function paypalSuccess(Request $request)
    {
        return redirect()->route('payment::success');
    }

    public function stripePay($order, $email, $token, $price)
    {
        \Stripe\Stripe::setApiKey(config('app.stripe_secret_key'));

        try {

            $customer = \Stripe\Customer::create([
                'email' => $email,
                'source'  => $token,
            ]);

            $charge = \Stripe\Charge::create([
                'customer' => $customer->id,
                'amount'   => $price.'00',
                'currency' => 'usd',
            ]);

            $stripeResponse = json_decode($charge->__toJSON(), true);

            $orderData = json_decode($order->data, true);
            $orderData['orderId'] = $stripeResponse['id'];

            $order->status = 1;
            $order->data = json_encode($orderData);
            $order->save();

            return $stripeResponse;

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function successPage()
    {
        return view('payment.success');
    }
}
