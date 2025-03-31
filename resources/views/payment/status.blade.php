@extends('layouts.app')
@section('content')
    <div class="container my-5 min-vh-100">
        <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Your Purchase</h5>
                </div>
                <div class="card-body">
                    @if ($payment->carts->isNotEmpty())
                        <ul class="list-group">
                            @foreach ($payment->carts as $item)
                                @php
                                    $product = $item->baseProduct ?? $item->product;
                                    $image = $item->design_front_image ?? $product->image1;
                                @endphp

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ Storage::url($image) }}" alt="Product Image" class="me-3" width="50"
                                            height="50" style="object-fit: cover; border-radius: 5px;">
                                        <div>
                                            <h6 class="mb-0">{{ $product->title ?? 'Unnamed Product' }}</h6>
                                            <small>Type: {{ $product->type }}</small><br>
                                            <small>Subtype: {{ $product->subtype }}</small><br>
                                            <small>Quantity: {{ $item->quantity }}</small><br>
                                            <small>Color: <span
                                                    style="color: {{ $item->product->color_code }}">{{ $item->product->color_name }}</span></small>
                                        </div>
                                    </div>
                                    <span class="badge bg-secondary">{{ $item->total_price }} GEL</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Your cart is empty.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Payment Status</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        @if ($payment->status === 'success')
                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-success">Payment Successful</h5>
                        @elseif($payment->status === 'pending')
                            <i class="bi bi-hourglass-split text-warning" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-warning">Payment Pending</h5>
                            <p>Your payment is being processed.</p>
                        @else
                            <i class="bi bi-x-circle text-danger" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-danger">Payment Failed</h5>
                            <p>Please try again.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
