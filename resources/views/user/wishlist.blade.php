@extends('user.layouts.master')

@section('title', 'Wishlist - ShopNow')

@section('content')

<h2 class="fw-bold mb-4">My Wishlist</h2>

<div class="row" id="wishlist-container">
    <!-- Wishlist products will load here -->
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $.ajax({
            url: '/api/wishlist',
            method: 'GET',
            success: function (products) {
                let html = '';
                if (products.length === 0) {
                    html = '<p class="text-muted">Your wishlist is empty.</p>';
                } else {
                    products.forEach(product => {
                        html += `
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <img src="${product.image}" class="card-img-top" style="height: 250px; object-fit: cover;">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">${product.name}</h5>
                                        <p class="card-text text-muted">${product.category}</p>
                                        <p class="fw-bold">PKR ${product.price}</p>
                                        <a href="/product/${product.id}" class="btn btn-primary mt-auto">View</a>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                $('#wishlist-container').html(html);
            },
            error: function () {
                $('#wishlist-container').html('<p class="text-danger">Error loading wishlist.</p>');
            }
        });
    });
</script>
@endpush
