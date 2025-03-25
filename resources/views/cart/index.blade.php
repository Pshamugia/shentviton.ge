@extends('layouts.app')

@section('title', 'Cart')
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">კალათა</h1>
            @if (!$cartItems->isEmpty())
                <button class="btn btn-warning clear-cart">
                    <i class="fas fa-trash me-2"></i>კალათის გასუფთავება
                </button>
            @endif
        </div>

        @if ($cartItems->isEmpty())
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="lead mb-0">შენი კალათა ცარიელია</p>
                </div>
            </div>
        @else
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="table-dark">
                                <th scope="col" style="width: 40%">პროდუქცია</th>
                                <th scope="col" class="text-center">ტიპი</th>
                                <th scope="col" class="text-center">რაოდენობა</th>
                                <th scope="col" class="text-center">ფასი</th>
                                <th scope="col" class="text-center">სულ</th>
                                <th scope="col" class="text-center">ქმედება</th>
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
                                        <input type="number" min="1" class="form-control cart-qty-input" value="{{ $item->quantity }}"
    data-id="{{ $item->id }}" data-price="{{ $item->product->price }}"
    style="width: 70px; display: inline-block;">
                                       
                                    </td>
                                    <td class="text-center align-middle">{{ number_format($item->product->price) }} ლარი </td>
                                    <td class="text-center align-middle fw-bold item-total" id="item-total-{{ $item->id }}">
                                        {{ number_format($item->total_price) }} ლარი
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
                        <h5 class="mb-0">სრული გადასახდელი თანხა</h5>
                        <h5 class="mb-0" id="grand-total">{{ number_format($cartItems->sum('total_price')) }} ლარი</h5>
                    </div>
                </div>
            </div>
 
            <div class="dropdown">
                <!-- Payment Button -->
                <button id="paymentButton" class="btn btn-success" type="button">
                    გადახდა
                </button>
            
                <!-- Hidden Form -->
                <div id="paymentDropdown" class="dropdown-menu show p-4" style="min-width: 300px; display: none;">
                    <form>
                        <!-- Name Field -->
                        <div class="mb-2">
                            <label for="name" class="form-label">სახელი</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <label for="email" class="form-label">ელ.ფოსტა</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <label for="phone" class="form-label">ტელეფონი</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <label for="address" class="form-label">მისამართი</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                        </div>
            
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-success w-100">გადახდა</button>
            
                        <!-- Close Button -->
                        <button id="closeDropdown" class="btn btn-danger w-100 mt-2">დახურვა</button>
                    </form>
                </div>
            </div>
            <!-- JavaScript -->
            <script>
         document.getElementById("paymentButton").addEventListener("click", function () {
    this.style.display = "none"; // Hide button
    document.getElementById("paymentDropdown").style.display = "block"; // Show dropdown

    document.body.classList.add("payment-open"); // Push footer down
});

document.getElementById("closeDropdown").addEventListener("click", function () {
    document.getElementById("paymentDropdown").style.display = "none"; // Hide dropdown
    document.getElementById("paymentButton").style.display = "block"; // Show button again

    document.body.classList.remove("payment-open"); // Restore footer
});


document.querySelectorAll(".cart-qty-input").forEach(input => {
    input.addEventListener("change", function () {
        const id = this.dataset.id;
        const unitPrice = parseFloat(this.dataset.price);
        const qty = parseInt(this.value);

        // Prevent invalid numbers
        if (qty < 1 || isNaN(qty)) {
            this.value = 1;
            return;
        }

        const itemTotal = unitPrice * qty;
        const formattedTotal = new Intl.NumberFormat().format(itemTotal) + " ლარი";

        // Update item's total price
        const totalElem = document.getElementById(`item-total-${id}`);
        if (totalElem) {
            totalElem.textContent = formattedTotal;
        }

        // Update overall total
        let grandTotal = 0;
        document.querySelectorAll(".cart-qty-input").forEach(input => {
            const price = parseFloat(input.dataset.price);
            const quantity = parseInt(input.value);
            if (!isNaN(price) && !isNaN(quantity)) {
                grandTotal += price * quantity;
            }
        });

        document.getElementById("grand-total").textContent =
            new Intl.NumberFormat().format(grandTotal) + " ლარი";
    });
});

            </script>
            

        


        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Delete single item
            document.querySelectorAll(".delete-cart-item").forEach(button => {
                button.addEventListener("click", function() {
                    const cartItemId = this.getAttribute("data-id");
                    if (confirm("Are you sure you want to remove this item?")) {
                        axios.delete(`/cart/${cartItemId}`)
                            .then(response => {
                                const row = document.getElementById(`cart-item-${cartItemId}`);
                                row.classList.add('fade');
                                setTimeout(() => row.remove(), 300);
        
                                // ✅ Update cart count in menu
                                if (response.data.cartCount !== undefined) {
                                    const cartCountElem = document.getElementById("cart-count");
                                    if (cartCountElem) {
                                        cartCountElem.textContent = response.data.cartCount;
                                    }
                                }
        
                                // Reload if cart is now empty
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
        
            // Clear entire cart
            document.querySelector(".clear-cart")?.addEventListener("click", function() {
                if (confirm("Are you sure you want to clear your cart?")) {
                    axios.post(`/cart/clear`)
                        .then(response => {
                            // ✅ Update cart count to 0
                            const cartCountElem = document.getElementById("cart-count");
                            if (cartCountElem) {
                                cartCountElem.textContent = 0;
                            }
        
                            location.reload(); // optional
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
