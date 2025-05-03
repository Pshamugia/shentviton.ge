@extends('layouts.admin')

@section('title', 'მომხმარებლის დეტალები')

@section('content')
<div class="container">
    <!-- User Information -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">მომხმარებლის ინფორმაცია</h5>
            <p><strong>სახელი:</strong> {{ $transaction->u_name ?? 'უცნობი' }}</p>
            <p><strong>ელფოსტა:</strong> {{ $transaction->u_email ?? 'უცნობი' }}</p>
            <p><strong>ტელეფონი:</strong> {{ $transaction->u_phone ?? 'უცნობი' }}</p>
            <p><strong>თანხა:</strong> {{ number_format($transaction->amount, 2) }} ლარი</p>
            <p><strong>სტატუსი:</strong> {{ $transaction->status ?? 'უცნობი' }}</p>
        </div>
    </div>
    <h4 class="mt-5">შეძენილი პროდუქტები</h4>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>პროდუქტის სახელი</th>
            <th>რაოდენობა</th>
            <th>ერთეულის ფასი</th> <!-- New column -->
            <th>სულ ფასი</th> <!-- New column -->
        </tr>
    </thead>
    <tbody>
        @forelse($products as $cart)
            <tr>
                <td>{{ $cart->baseProduct->title ?? 'პროდუქტი არ მოიძებნა' }}</td>
                <td>{{ $cart->quantity }}</td>
                <td>{{ number_format($cart->total_price / $cart->quantity, 2) }} ლარი</td> <!-- Price per unit -->
                <td>{{ number_format($cart->total_price, 2) }} ლარი</td> <!-- Total price -->
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">არ არის შეძენილი პროდუქტი.</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection
