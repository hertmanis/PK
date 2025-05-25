<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentController extends Controller
{
    // Apstrādā maksājumus, ko var redzēt trenētāji (role 0)
    public function coachIndex()
    {
        if (auth()->user()->role != 0) {
            abort(403, 'Unauthorized');
        }

        $payments = Payment::all(); // varat filtrēt pēc vajadzības
        return view('dashboard.payment.coach-payment', compact('payments'));
    }

    // Maksājuma radīšana
    public function store(Request $request)
    {
        // Validācija (ja nepieciešams)
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string|max:255',
        ]);

        // Izveido maksājumu, pievienojot 'created_by' kā pašreizējā lietotāja ID
        $payment = Payment::create([
            'amount' => $request->amount,
            'description' => $request->description,
            'created_by' => auth()->user()->id, // Pievienojam lietotāja ID
        ]);

        return redirect()->route('coach.payments')->with('success', 'Maksājums izveidots veiksmīgi.');
    }

    // Apstrādā maksājumus, ko var redzēt spēlētāji (role 1)
    public function playerIndex()
{
    $player = auth()->user();

    if ($player->role != 1) {
        abort(403, 'Unauthorized');
    }

    // Atrodi visus trenerus šajā komandā
    $coachIds = \App\Models\User::where('team_id', $player->team_id)
        ->where('role', 0)
        ->pluck('id');

    // Atlasīt maksājumus, kurus radījuši šīs komandas treneri
    $payments = \App\Models\Payment::whereIn('created_by', $coachIds)->get();

    return view('dashboard.payment.player-payment', compact('payments'));
}

public function showPaymentPage($paymentId)
{
    $payment = Payment::findOrFail($paymentId);

    // Autentificēts lietotājs
    $user = auth()->user();

    // Pārbaude: tikai lietotāji no tās pašas komandas var skatīt maksājumu
    if ($user->team_id !== $payment->team_id) {
        abort(403, 'Jums nav piekļuves šim maksājumam.');
    }

    return view('dashboard.payment.pay', compact('payment'));

}


    // Maksājuma Checkout sesijas izveide ar Stripe
public function checkout($paymentId)
{
    $payment = Payment::findOrFail($paymentId);


    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));




    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $payment->description,
                    ],
                    'unit_amount' => $payment->amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['paymentId' => $payment->id]),
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect()->away($session->url);

    } catch (\Exception $e) {
        return back()->with('error', 'Kļūda: ' . $e->getMessage());
    }
}






    // Apstiprinājums par veiksmīgu maksājumu
public function success(Request $request, $paymentId)
{
    $payment = Payment::findOrFail($paymentId);

    // Optional: verifikācija ar Stripe API (drošāks)
    if ($request->has('session_id')) {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));

            if ($session && $session->payment_status === 'paid') {
                $payment->paid = 1; 
                $payment->save();
            }
        } catch (\Exception $e) {
            // Handle error silently or log
        }
    }

    return view('dashboard.payment.success', compact('payment'));

}

    // Maksājuma atcelšana
    public function cancel()
    {
        // Parādām maksājuma atcelšanas lapu
        return view('payment.cancel');
    }
}
