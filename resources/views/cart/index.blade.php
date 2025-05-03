@extends('layouts.app')

@section('title', 'Cart')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <i class="fas fa-shopping-cart"> კალათა </i>
            @if (!$cartItems->isEmpty())
                <button class="btn btn-warning clear-cart">
                    <i class="fas fa-trash me-1"></i>კალათის გასუფთავება
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
                                            @else
                                                <img src="{{ asset('storage/' . $item->product->image1) }}"
                                                    alt="Product image" class="me-3"
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
                                        <input type="number" min="1" class="form-control cart-qty-input"
                                        value="{{ $item->quantity }}" data-id="{{ $item->id }}"
                                        data-price="{{ $item->product->price }}"
                                        data-max="{{ $item->product->quantity }}"
                                        style="width: 70px; display: inline-block;">

                                    </td>
                                    <td class="text-center align-middle">{{ number_format($item->product->price) }} ლარი
                                    </td>
                                    <td class="text-center align-middle fw-bold item-total"
                                        id="item-total-{{ $item->id }}">
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

            <div class="d-flex justify-content-between align-items-center" style="text-align: right;">
                <div>
                    <div id="product-total-text">პროდუქციის ფასი: {{ number_format($cartItems->sum('total_price')) }} ლარი</div>
                    <div id="delivery-price-text">ემატება მიწოდების ფასი: თბილისში - 5 ლარი, რეგიონებში - 7 ლარი</div>
                    <strong id="grand-total-text">ჯამური: {{ number_format($cartItems->sum('total_price')) }} ლარი</strong>

                </div>
                
            </div>

            <div class="dropdown" style="position: relative; top:10px;">
                <!-- Payment Button -->
                <button id="paymentButton" class="btn btn-success" type="button">
                    გადახდა
                </button>

                <!-- Hidden Form -->
                <div id="paymentDropdown" class="dropdown-menu show p-4" style="min-width: 300px; display: none; background-color: #e7e2e2">
                    <form action="{{ route('payment.pay') }}">
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


                        <div class="mb-2" style="margin-bottom: 17px !important">
                            <label for="address" class="form-label">ქალაქი</label>
                            <div class="input-group">
                                <select name="city" class="form-control chosen-select" id="city" data-placeholder="მონიშნე ქალაქი" required>
                                     <option value="">  მონიშნე ქალაქი</option> 
                                <option value="თბილისი">თბილისი</option>
                                <option value="ბათუმი">ბათუმი</option>
                                <option value="ქუთაისი">ქუთაისი</option>
                                <option value="გურჯაანის მუნიციპალიტეტი">გურჯაანის მუნიციპალიტეტი</option>
                                <option value="თელავის მუნიციპალიტეტი">თელავის მუნიციპალიტეტი</option>
                                <option value="ზუგდიდის მუნიციპალიტეტი">ზუგდიდის მუნიციპალიტეტი</option>
                                <option value="ბაკურიანი">ბაკურიანი</option>
                                <option value="გორის მუნიციპალიტეტი">გორის მუნიციპალიტეტი</option>
                                <option value="რუსთავი">რუსთავი</option>
                                <option value="ფოთი">ფოთი</option>
                                <option value="აბაშის მუნიციპალიტეტი">აბაშის მუნიციპალიტეტი</option>
                                <option value="ადიგენის მუნიციპალიტეტი">ადიგენის მუნიციპალიტეტი</option>
                                <option value="ამბროლაურის მუნიციპალიტეტი">ამბროლაურის მუნიციპალიტეტი</option>
                                <option value="ასპინძის მუნიციპალიტეტი">ასპინძის მუნიციპალიტეტი</option>
                                <option value="ახალგორის მუნიციპალიტეტი">ახალგორის მუნიციპალიტეტი</option>
                                <option value="ახალქალაქის მუნიციპალიტეტი">ახალქალაქის მუნიციპალიტეტი</option>
                                <option value="ახალციხის მუნიციპალიტეტი">ახალციხის მუნიციპალიტეტი</option>
                                <option value="ახმეტის მუნიციპალიტეტი">ახმეტის მუნიციპალიტეტი</option>
                                <option value="ბაღდათის მუნიციპალიტეტი">ბაღდათის მუნიციპალიტეტი</option>
                                <option value="ბოლნისის მუნიციპალიტეტი">ბოლნისის მუნიციპალიტეტი</option>
                                <option value="ბორჯომის მუნიციპალიტეტი">ბორჯომის მუნიციპალიტეტი</option>
                                <option value="გარდაბნის მუნიციპალიტეტი">გარდაბნის მუნიციპალიტეტი</option>
                                <option value="დედოფლისწყაროს მუნიციპალიტეტი">დედოფლისწყაროს მუნიციპალიტეტი</option>
                                <option value="დმანისის მუნიციპალიტეტი">დმანისის მუნიციპალიტეტი</option>
                                <option value="დუშეთის მუნიციპალიტეტი">დუშეთის მუნიციპალიტეტი</option>
                                <option value="ვანის მუნიციპალიტეტი">ვანის მუნიციპალიტეტი</option>
                                <option value="ზესტაფონის მუნიციპალიტეტი">ზესტაფონის მუნიციპალიტეტი</option>
                                <option value="თეთრი წყაროს მუნიციპალიტეტი">თეთრი წყაროს მუნიციპალიტეტი</option>
                                <option value="თერჯოლის მუნიციპალიტეტი">თერჯოლის მუნიციპალიტეტი</option>
                                <option value="თიანეთის მუნიციპალიტეტი">თიანეთის მუნიციპალიტეტი</option>
                                <option value="კასპის მუნიციპალიტეტი">კასპის მუნიციპალიტეტი</option>
                                <option value="ლაგოდეხის მუნიციპალიტეტი">ლაგოდეხის მუნიციპალიტეტი</option>
                                <option value="ლანჩხუთის მუნიციპალიტეტი">ლანჩხუთის მუნიციპალიტეტი</option>
                                <option value="ლენტეხის მუნიციპალიტეტი">ლენტეხის მუნიციპალიტეტი</option>
                                <option value="მარნეულის მუნიციპალიტეტი">მარნეულის მუნიციპალიტეტი</option>
                                <option value="მარტვილის მუნიციპალიტეტი">მარტვილის მუნიციპალიტეტი</option>
                                <option value="მესტიის მუნიციპალიტეტი">მესტიის მუნიციპალიტეტი</option>
                                <option value="მცხეთის მუნიციპალიტეტი">მცხეთის მუნიციპალიტეტი</option>
                                <option value="ნინოწმინდის მუნიციპალიტეტი">ნინოწმინდის მუნიციპალიტეტი</option>
                                <option value="ოზურგეთის მუნიციპალიტეტი">ოზურგეთის მუნიციპალიტეტი</option>
                                <option value="ონის მუნიციპალიტეტი">ონის მუნიციპალიტეტი</option>
                                <option value="საგარეჯოს მუნიციპალიტეტი">საგარეჯოს მუნიციპალიტეტი</option>
                                <option value="სამტრედიის მუნიციპალიტეტი">სამტრედიის მუნიციპალიტეტი</option>
                                <option value="საჩხერის მუნიციპალიტეტი">საჩხერის მუნიციპალიტეტი</option>
                                <option value="სენაკის მუნიციპალიტეტი">სენაკის მუნიციპალიტეტი</option>
                                <option value="სიღნაღის მუნიციპალიტეტი">სიღნაღის მუნიციპალიტეტი</option>
                                <option value="ტყიბულის მუნიციპალიტეტი">ტყიბულის მუნიციპალიტეტი</option>
                                <option value="ქარელის მუნიციპალიტეტი">ქარელის მუნიციპალიტეტი</option>
                                <option value="ქედის მუნიციპალიტეტი">ქედის მუნიციპალიტეტი</option>
                                <option value="ქობულეთის მუნიციპალიტეტი">ქობულეთის მუნიციპალიტეტი</option>
                                <option value="ყაზბეგის მუნიციპალიტეტი">ყაზბეგის მუნიციპალიტეტი</option>
                                <option value="ყვარლის მუნიციპალიტეტი">ყვარლის მუნიციპალიტეტი</option>
                                <option value="შუახევის მუნიციპალიტეტი">შუახევის მუნიციპალიტეტი</option>
                                <option value="ჩოხატაურის მუნიციპალიტეტი">ჩოხატაურის მუნიციპალიტეტი</option>
                                <option value="ჩხოროწყუს მუნიციპალიტეტი">ჩხოროწყუს მუნიციპალიტეტი</option>
                                <option value="ცაგერის მუნიციპალიტეტი">ცაგერის მუნიციპალიტეტი</option>
                                <option value="წალენჯიხის მუნიციპალიტეტი">წალენჯიხის მუნიციპალიტეტი</option>
                                <option value="წალკის მუნიციპალიტეტი">წალკის მუნიციპალიტეტი</option>
                                <option value="წყალტუბოს მუნიციპალიტეტი">წყალტუბოს მუნიციპალიტეტი</option>
                                <option value="ჭიათურის მუნიციპალიტეტი">ჭიათურის მუნიციპალიტეტი</option>
                                <option value="ხარაგაულის მუნიციპალიტეტი">ხარაგაულის მუნიციპალიტეტი</option>
                                <option value="ხაშურის მუნიციპალიტეტი">ხაშურის მუნიციპალიტეტი</option>
                                <option value="ხელვაჩაურის მუნიციპალიტეტი">ხელვაჩაურის მუნიციპალიტეტი</option>
                                <option value="ხობის მუნიციპალიტეტი">ხობის მუნიციპალიტეტი</option>
                                <option value="ხონის მუნიციპალიტეტი">ხონის მუნიციპალიტეტი</option>
                                <option value="ხულოს მუნიციპალიტეტი">ხულოს მუნიციპალიტეტი</option>
                                <option value="ჯავის მუნიციპალიტეტი">ჯავის მუნიციპალიტეტი</option> 
        
                            </select>  
                         </div>
                    </div>
                    <!-- Hidden Delivery Price Field -->
<input type="hidden" id="delivery_price" name="delivery_price" value="0">

                        <div class="mb-2">
                            <label for="address" class="form-label">ზუსტი მისამართი</label>
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
                document.getElementById("paymentButton").addEventListener("click", function() {
                    this.style.display = "none"; // Hide button
                    document.getElementById("paymentDropdown").style.display = "block"; // Show dropdown

                    document.body.classList.add("payment-open"); // Push footer down
                });

                document.getElementById("closeDropdown").addEventListener("click", function() {
                    document.getElementById("paymentDropdown").style.display = "none"; // Hide dropdown
                    document.getElementById("paymentButton").style.display = "block"; // Show button again

                    document.body.classList.remove("payment-open"); // Restore footer
                });


                document.querySelectorAll(".cart-qty-input").forEach(input => {
                    input.addEventListener("change", function() {
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

                //CHosen 
                $(document).ready(function() {
    $(".chosen-select").chosen({
        width: "100%",
        no_results_text: "ვერ მოიძებნა",
        placeholder_text_single: "მონიშნე ქალაქი"
    });

    // Listen when city is changed
    $("#city").on("change", function() {
        const selectedCity = $(this).val();
        let deliveryPrice = 0;

        if (selectedCity === "თბილისი") {
            deliveryPrice = 5;
        } else if (selectedCity) {
            deliveryPrice = 7;
        }

        // Set hidden input
        $("#delivery_price").val(deliveryPrice);

        // Update Grand Total
        updateGrandTotal(deliveryPrice);
    });

    function updateGrandTotal(deliveryPrice) {
    let cartTotal = 0;

    document.querySelectorAll(".cart-qty-input").forEach(input => {
        const price = parseFloat(input.dataset.price);
        const quantity = parseInt(input.value);
        if (!isNaN(price) && !isNaN(quantity)) {
            cartTotal += price * quantity;
        }
    });

    let totalWithDelivery = cartTotal + deliveryPrice;

    // Update product total
    document.getElementById("product-total-text").textContent = 
        "პროდუქციის ფასი: " + new Intl.NumberFormat().format(cartTotal) + " ლარი";

    // Update delivery price text
    if (deliveryPrice === 0) {
        document.getElementById("delivery-price-text").textContent = 
            "ემატება მიწოდების ფასი: თბილისში - 5 ლარი, რეგიონებში - 7 ლარი";
    } else {
        document.getElementById("delivery-price-text").textContent = 
            "მიწოდების ფასი: " + new Intl.NumberFormat().format(deliveryPrice) + " ლარი";
    }

    // Update grand total
    document.getElementById("grand-total-text").textContent = 
        "ჯამური: " + new Intl.NumberFormat().format(totalWithDelivery) + " ლარი";
}
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



            document.querySelectorAll(".cart-qty-input").forEach(input => {
    input.addEventListener("change", function() {
        const id = this.dataset.id;
        const unitPrice = parseFloat(this.dataset.price);
        const maxQuantity = parseInt(this.dataset.max);
        let qty = parseInt(this.value);

        if (isNaN(qty) || qty < 1) {
            qty = 1;
            this.value = 1;
        } else if (qty > maxQuantity) {
            qty = maxQuantity;
            this.value = maxQuantity;
            alert("მარაგში მხოლოდ " + maxQuantity + " ცალია.");
        }

        // Update item's total price
        const itemTotal = unitPrice * qty;
        const formattedTotal = new Intl.NumberFormat().format(itemTotal) + " ლარი";
        const totalElem = document.getElementById(`item-total-${id}`);
        if (totalElem) {
            totalElem.textContent = formattedTotal;
        }

        // Update overall grand total
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
