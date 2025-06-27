@extends('user.layouts.master')

@section('title', 'Checkout - ShopNow')

@section('content')
<x-breadcrumbs :items="[
    ['label' => 'Home', 'url' => url('/')],
    ['label' => 'Checkout', 'url' => '']
]" />

<h2 class="fw-bold mb-4">Checkout</h2>

<div class="row">
    <!-- Customer Info Form -->
    <div class="col-md-6">
        <form id="checkoutForm">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Full Name *</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone *</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Shipping Address *</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-success btn-lg w-100">Place Order</button>
        </form>
    </div>

    <!-- Order Summary -->
    <div class="col-md-6">
        <h5 class="fw-bold mb-3">Order Summary</h5>
        <ul class="list-group mb-3" id="order-summary">
            <!-- Items will be injected via AJAX -->
        </ul>
        <h5 class="text-end">Total: PKR <span id="order-total">0</span></h5>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Load cart summary
        $.ajax({
            url: '/api/cart',
            method: 'GET',
            success: function (items) {
                let total = 0;
                let summaryHtml = '';

                if (items.length === 0) {
                    summaryHtml = '<li class="list-group-item">Cart is empty.</li>';
                    $('#checkoutForm').hide();
                } else {
                    items.forEach(item => {
                        const subtotal = item.price * item.quantity;
                        total += subtotal;

                        summaryHtml += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${item.name}</strong><br>
                                    <small>Qty: ${item.quantity}</small>
                                </div>
                                <span>PKR ${subtotal}</span>
                            </li>
                        `;
                    });
                }

                $('#order-summary').html(summaryHtml);
                $('#order-total').text(total);
            },
            error: function () {
                $('#order-summary').html('<li class="list-group-item text-danger">Error loading cart.</li>');
            }
        });

        // Place order
        $('#checkoutForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '/api/orders/place',
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    showToast('Order placed successfully!');
                    window.location.href = '/orders';
                },
                error: function () {
                    showToast('Failed to place order.');
                }
            });
        });
    });
</script>
@endpush
