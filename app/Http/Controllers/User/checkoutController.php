<?php

namespace App\Http\Controllers\User;

use App\Models\Camp;
use App\Models\Checkout;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Checkout\AfterCheckout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\User\Checkout\Store;
use Midtrans;


class CheckoutController extends Controller
{
    //men-define variable midtrans
    public function __construct()
    {
        Midtrans\Config::$serverKey = env('MIDTRABNS_SERVERKEY');
        Midtrans\Config::$isProduction = env('MIDTRABNS_IS_PRODUCTION');
        Midtrans\Config::$isSanitized = env('MIDTRABNS_IS_SANITIZED');
        Midtrans\Config::$is3ds = env('MIDTRABNS_IS_3DS');
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Camp $camp)
    {
        // pada tahap ini slug sudah di seleksi oleh db model Camp
        // sehingga data yang di parsing seudah sesuai dengan db camps
        // return $camp;
        // isRegistered didapat dari Model Camp
        if ($camp->isRegistered) {
            $request->session()->flash('error', "You Already Registered on {$camp->title} camp.");
            return redirect(route('user.dashboard'));
        }

        $user = Auth::user();
        return view('checkout.create', [
            'camp' => $camp,
            'user' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, Camp $camp)
    {
        // return $camp;
        // return $request->all();

        //mapping request data
        $data = $request->all();
        //Auth::id(); -> mengambil session id yg login
        $data['user_id'] = Auth::id();
        $data['camp_id'] = $camp->id;

        //update user data
        $user = Auth::user();
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->occupation = $data['occupation'];
        $user->save();


        //create checkout
        $checkout = Checkout::create($data);
        $this->getSnapRedirect($checkout);
        Mail::to(Auth::user()->email)->send(new AfterCheckout($checkout));

        return redirect(route('checkout.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function show(Checkout $checkout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function edit(Checkout $checkout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkout $checkout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkout $checkout)
    {
        //
    }

    public function success()
    {
        return view('checkout.success');
    }

    public function getSnapRedirect(Checkout $checkout)
    {
        $orderId = $checkout->midtrans_booking_code = $checkout->id . '-' . Str::random(5);
        $price = $checkout->Camp->price * 1000;
        $checkout->midtrans_booking_code = $orderId;
        $transaction_details = [
            'order_id' => $orderId,
            'gross_amount' => $price
        ];

        $item_details[] = [
            'id' => $orderId,
            'price' => $price,
            'quantity' => 1,
            'name' => "Payment for {$checkout->Camp->title} Camp",
        ];

        $userData = [
            "first_name" => $checkout->User->name,
            "last_name" => "",
            "address" => $checkout->User->address,
            "city" => "",
            "postal_code" => "",
            "phone" => $checkout->User->phone,
            "country_code" => "IDN",
        ];

        $customer_details = [
            "first_name" => $checkout->User->name,
            "last_name" => "",
            "email" => $checkout->User->email,
            "phone" => $checkout->User->phone,
            "billing_address" => $userData,
            "shipping_address" => $userData,
        ];

        $midtrans_params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        ];

        try {
            //get Snap Payment Page URL
            $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
            $checkout->midtrans_url = $paymentUrl;
            $checkout->save();

            return $paymentUrl;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function midtransCallback(Request $request)
    {
        $notif = new Midtrans\Notification();

        $transaction_status = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        $checkout_id = explode('-', $notif->order_id[0]);
        $checkout = Checkout::find($checkout_id);

        if ($transaction_status == 'capture') {
            if ($fraud == 'challenge') {
                // TODO Set payment status in merchant's database to 'challenge'
                $checkout->payment_status = 'pending';
            } elseif ($fraud == 'accept') {
                // TODO Set payment status in merchant's database to 'success'
                $checkout->payment_status = 'paid';
            }
        } elseif ($transaction_status == 'cancel') {
            if ($fraud == 'challenge') {
                // TODO Set payment status in merchant's database to 'failure'
                $checkout->payment_status = 'failed';
            } elseif ($fraud == 'accept') {
                // TODO Set payment status in merchant's database to 'failure'
                $checkout->payment_status = 'failed';
            }
        } elseif ($transaction_status == 'deny') {
            // TODO Set payment status in merchant's database to 'failure'
            $checkout->payment_status = 'failed';
        } elseif ($transaction_status == 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'
            $checkout->payment_status = 'paid';
        } elseif ($transaction_status == 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            $checkout->payment_status = 'pending';
        } elseif ($transaction_status == 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            $checkout->payment_status = 'failed';
        }

        $checkout->save();
        return view('checkout/success');
    }
}
