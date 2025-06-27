@extends('user.layouts.master')

@section('title', 'Home - ShopNow')

@section('content')

<!-- Hero Section -->
<div class="py-5 text-center" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 1rem;">
    <h1 class="display-5 fw-bold">Welcome to ShopNow</h1>
    <p class="lead">Discover the best deals on top-rated products.</p>
    <a href="{{ url('/products') }}" class="btn btn-light btn-lg mt-3">Shop Now</a>
</div>

<!-- Featured Products -->
<div class="mt-5">
    <h2 class="mb-4 text-center fw-bold">Featured Products</h2>
    <div class="row" id="featured-products">
        <!-- Products will be loaded here via AJAX -->
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $.ajax({
    url: '/api/featured-products',
    method: 'GET',
    success: function (response) {
        if (response.products && response.products.length > 0) {
            let html = '';
            response.products.forEach(product => {
                html += `
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="${product.image}" class="card-img-top" alt="${product.name}" style="height: 250px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text text-muted mb-2">${product.category?.name || ''}</p>
                                <div class="mt-auto">
                                    <p class="fw-bold mb-1">PKR ${product.price}</p>
                                    <div class="d-flex justify-content-between">
                                        <a href="/product/${product.id}" class="btn btn-primary flex-grow-1 me-2">View</a>
                                        <button class="btn btn-outline-danger wishlist-btn" data-id="${product.id}">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            $('#featured-products').html(html);
        } else {
            $('#featured-products').html('<p class="text-center text-muted">No featured products found.</p>');
        }
    },
    error: function (xhr) {
        console.error('Error:', xhr.responseText);
        $('#featured-products').html(
            '<p class="text-danger text-center">Error loading products. ' + 
            (xhr.responseJSON?.message || '') + '</p>'
        );
    }
});
    });
</script>
<script>
    $(document).on('click', '.wishlist-btn', function () {
        const productId = $(this).data('id');
        $.ajax({
            url: '/api/wishlist/toggle',
            method: 'POST',
            data: { product_id: productId },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (res) {
                showToast(res.message || 'Wishlist updated!');
            },
            error: function () {
                showToast('Failed to update wishlist.');
            }
        });
    });
</script>

@endpush
