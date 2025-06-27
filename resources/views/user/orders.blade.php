@extends('user.layouts.master')

@section('title', 'My Orders - ShopNow')

@section('content')
<x-breadcrumbs :items="[
    ['label' => 'Home', 'url' => url('/')],
    ['label' => 'My Orders', 'url' => '']
]" />

<h2 class="fw-bold mb-4">My Orders</h2>
<a href="/orders/${order.id}/invoice" class="btn btn-sm btn-outline-primary">
    <i class="fas fa-file-download me-1"></i> Download Invoice
</a>

<div id="orders-container">
    <!-- Orders will be loaded here via AJAX -->
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $.ajax({
            url: '/api/orders',
            method: 'GET',
            success: function (orders) {
                if (orders.length === 0) {
                    $('#orders-container').html('<p class="text-muted">You have no orders yet.</p>');
                    return;
                }

                let html = '';
                orders.forEach(order => {
                    let itemsHtml = '';
                    order.items.forEach(item => {
                        itemsHtml += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${item.name}</strong><br>
                                    <small>Qty: ${item.quantity}</small>
                                </div>
                                <span>PKR ${item.price * item.quantity}</span>
                            </li>
                        `;
                    });

                    html += `
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between">
                                <div>
                                    <strong>Order #${order.order_number}</strong><br>
                                    <small>${order.date}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-${getStatusColor(order.status)}">${order.status}</span><br>
                                    <strong>Total: PKR ${order.total_amount}</strong>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                ${itemsHtml}
                            </ul>
                        </div>
                    `;
                });

                $('#orders-container').html(html);
            },
            error: function () {
                $('#orders-container').html('<p class="text-danger">Failed to load orders.</p>');
            }
        });

        function getStatusColor(status) {
            switch (status) {
                case 'pending': return 'warning';
                case 'completed': return 'success';
                case 'cancelled': return 'danger';
                default: return 'secondary';
            }
        }
    });
</script>
@endpush
