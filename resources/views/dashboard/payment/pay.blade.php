@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Maksājuma lapa</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <p><strong>Apraksts:</strong> {{ $payment->description }}</p>
    <p><strong>Summa:</strong> €{{ number_format($payment->amount / 100, 2) }}</p>

    <form action="{{ route('payment.checkout', ['paymentId' => $payment->id]) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Veikt maksājumu ar Stripe</button>
    </form>
</div>
@endsection
