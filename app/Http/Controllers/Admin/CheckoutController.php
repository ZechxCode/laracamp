<?php

namespace App\Http\Controllers\Admin;

use App\Models\Checkout;
use App\Mail\Checkout\Paid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function update(Request $request, Checkout $checkout)
    {
        $checkout->is_paid = true;
        $checkout->save();
        // send email to user
        Mail::to($checkout->user->email)->send(new Paid($checkout));
        $request->session()->flash('success', "Checkout Camp {$checkout->camp->title}, INV-{$checkout->id} has been updated successfully");
        return redirect(route('admin.dashboard'));
    }
}
