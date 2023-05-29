<?php

namespace App\Http\Controllers\User;

use App\Models\Camp;
use App\Models\Checkout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\Checkout\Store;

class checkoutController extends Controller
{
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
            return redirect(route('dashboard'));
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
        return $request->all();

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
        Checkout::create($data);


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
}
