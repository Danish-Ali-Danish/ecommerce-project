@extends('user.layouts.master')

@section('title', 'Your Cart - ShopNow')

@section('content')
<x-breadcrumbs :items="[
    ['label' => 'Home', 'url' => url('/')],
    ['label' => 'Cart', 'url' => '']
]" />

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Your Shopping Cart</h2>
    <span id="cart-count" class="badge bg-primary rounded-pill"></span>
</div>

<div id="cart-container" class="bg-white rounded shadow-sm p-4">
    <!-- Loading spinner -->
    <div id="cart-loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<!-- Empty cart state (hidden by default) -->
<div id="empty-cart" class="text-center py-5 d-none">
    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
    <h4 class="text-muted">Your cart is empty</h4>
    <a href="{{ url('/products') }}" class="btn btn-primary mt-3">Continue Shopping</a>
</div>
@endsection

@push('scripts')
<script>
    function loadCart() {
        $.ajax({
            url: '/api/cart',
            method: 'GET',
            success: function (cartItems) {
                if (cartItems.length === 0) {
                    $('#cart-container').html('<p class="text-muted">Your cart is empty.</p>');
                    return;
                }

                let total = 0;
                let html = `
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                cartItems.forEach(item => {
                    const subtotal = item.price * item.quantity;
                    total += subtotal;
                    html += `
                        <tr data-id="${item.id}">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="${item.image}" width="60" height="60" style="object-fit: cover;" class="rounded">
                                    <span>${item.name}</span>
                                </div>
                            </td>
                            <td>PKR ${item.price}</td>
                            <td>
                                <div class="input-group" style="max-width: 120px;">
                                    <button class="btn btn-outline-secondary btn-sm updateQty" data-action="decrease">-</button>
                                    <input type="text" class="form-control text-center quantityInput" value="${item.quantity}" readonly>
                                    <button class="btn btn-outline-secondary btn-sm updateQty" data-action="increase">+</button>
                                </div>
                            </td>
                            <td>PKR ${subtotal}</td>
                            <td>
                                <button class="btn btn-danger btn-sm removeItem"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-4">
                        <h4>Total: PKR ${total}</h4>
                        <a href="/checkout" class="btn btn-primary btn-lg mt-2">Proceed to Checkout</a>
                    </div>
                `;

                $('#cart-container').html(html);
            },
            error: function () {
                $('#cart-container').html('<p class="text-danger">Failed to load cart.</p>');
            }
        });
    }

    // Initial cart load
    $(document).ready(loadCart);

    // Update Quantity
    $(document).on('click', '.updateQty', function () {
        const row = $(this).closest('tr');
        const productId = row.data('id');
        const input = row.find('.quantityInput');
        let qty = parseInt(input.val());

        const action = $(this).data('action');
        if (action === 'increase') qty++;
        else if (action === 'decrease' && qty > 1) qty--;

        $.ajax({
            url: '/api/cart/update',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: qty
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function () {
                loadCart();
            },
            error: function () {
                showToast('Failed to update quantity.');
            }
        });
    });

    // Remove Item
    $(document).on('click', '.removeItem', function () {
        const row = $(this).closest('tr');
        const productId = row.data('id');

        $.ajax({
            url: '/api/cart/remove',
            method: 'POST',
            data: { product_id: productId },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function () {
                loadCart();
            },
            error: function () {
                showToast('Failed to remove item.');
            }
        });
    });
</script>
@endpush
