@extends('layouts.app')

@section('content')
    <div class="container my-5 min-vh-100">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">შენი შენაძენი</h5>
                    </div>
                    <div class="card-body">
                        @if (optional($payment->carts)->isNotEmpty())
                            <ul class="list-group">
                                @foreach ($payment->carts as $item)
                                    @php
                                        $product = $item->baseProduct ?? $item->product;
                                        $image = $item->design_front_image ?? optional($product)->image1;
                                        $imageUrl = $image ? Storage::url($image) : 'https://via.placeholder.com/50';
                                    @endphp

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $imageUrl }}" alt="Product Image" class="me-3" width="50"
                                                height="50" style="object-fit: cover; border-radius: 5px;">
                                            <div>
                                                <h6 class="mb-0">{{ optional($product)->title ?? 'Unnamed Product' }}</h6>
                                                <small>Type: {{ optional($product)->type ?? 'N/A' }}</small><br>
                                                <small>Subtype: {{ optional($product)->subtype ?? 'N/A' }}</small><br>
                                                <small>Quantity: {{ $item->quantity ?? 'N/A' }}</small><br>
                                                <small>
                                                    Color:
                                                    <span style="color: {{ optional($item->product)->color_code ?? '#000' }}">
                                                        {{ optional($item->product)->color_name ?? 'N/A' }}
                                                    </span>
                                                </small>
                                            </div>
                                        </div>
                                        <span class="badge bg-secondary">{{ $item->total_price ?? '0.00' }} GEL</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">შენი კალათა ცარიელია.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">გადახდის სტატუსი</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            @php $status = $payment->status ?? 'unknown'; @endphp

                            @if ($status === 'success')
                                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-success">წარმატებული გადახდა</h5>
                            @elseif ($status === 'pending')
                                <i class="bi bi-hourglass-split text-warning" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-warning">გადახდა არ არის დასრულებული</h5>
                                <p>გადახდა მიმდინარეობს.</p>
                            @else
                                <i class="bi bi-x-circle text-danger" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-danger">Payment Failed</h5>
                                <p>შეფერხებაა. გთხოვთ ხელახლა სცადოთ.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

