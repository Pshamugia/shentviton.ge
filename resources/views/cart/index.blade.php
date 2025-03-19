@extends('layouts.app')

@section('title', 'Cart')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Your Cart</h1>
            @if (!$cartItems->isEmpty())
                <button class="btn btn-warning clear-cart">
                    <i class="fas fa-trash me-2"></i>Clear Cart
                </button>
            @endif
        </div>

        @if ($cartItems->isEmpty())
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="lead mb-0">Your cart is empty</p>
                </div>
            </div>
        @else
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="table-dark">
                                <th scope="col" style="width: 40%">Product</th>
                                <th scope="col" class="text-center">Type</th>
                                <th scope="col" class="text-center">Quantity</th>
                                <th scope="col" class="text-center">Price</th>
                                <th scope="col" class="text-center">Total</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                <tr id="cart-item-{{ $item->id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($item->design_front_image)
                                                <img src="{{ asset('storage/' . $item->design_front_image) }}"
                                                    alt="Product design" class="me-3"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->product->title }}</h6>
                                                @if (!$item->default_img)
                                                    <small class="text-muted">Custom Design</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($item->default_img)
                                            <span class="badge bg-secondary">Standard</span>
                                        @else
                                            <span class="badge bg-primary">Custom</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-light text-dark border">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">${{ number_format($item->product->price, 2) }}</td>
                                    <td class="text-center align-middle fw-bold">${{ number_format($item->total_price, 2) }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-sm btn-outline-danger delete-cart-item"
                                            data-id="{{ $item->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        @if (!$item->default_img)
                                            <a href="{{ route('cart.item.show', ['id' => $item->id]) }}"
                                                class="btn btn-sm btn-outline-danger show-cart-item">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Cart Total</h5>
                        <h5 class="mb-0">${{ number_format($cartItems->sum('total_price'), 2) }}</h5>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".delete-cart-item").forEach(button => {
                button.addEventListener("click", function() {
                    const cartItemId = this.getAttribute("data-id");
                    if (confirm("Are you sure you want to remove this item?")) {
                        axios.delete(`/cart/${cartItemId}`)
                            .then(response => {
                                const row = document.getElementById(`cart-item-${cartItemId}`);
                                row.classList.add('fade');
                                setTimeout(() => row.remove(), 300);

                                // Check if cart is empty after removal
                                if (document.querySelectorAll('tbody tr').length === 1) {
                                    location.reload();
                                }
                            })
                            .catch(error => {
                                console.error(error);
                                alert('Failed to remove item from cart');
                            });
                    }
                });
            });

            document.querySelector(".clear-cart")?.addEventListener("click", function() {
                if (confirm("Are you sure you want to clear your cart?")) {
                    axios.post(`/cart/clear`)
                        .then(response => {
                            location.reload();
                        })
                        .catch(error => {
                            console.error(error);
                            alert('Failed to clear cart');
                        });
                }
            });
        });
    </script>

    <style>
        .fade {
            opacity: 0;
            transition: opacity 0.3s ease-out;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        .badge {
            padding: 0.5em 0.8em;
        }
    </style>
@endsection
