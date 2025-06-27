@extends('user.layouts.master')

@section('title', 'Products - ShopNow')

@section('content')
<x-breadcrumbs :items="[
    ['label' => 'Home', 'url' => url('/')],
    ['label' => 'Products', 'url' => '']
]" />

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">All Products</h2>

    <!-- Category Filter -->
    <select id="categoryFilter" class="form-select w-auto">
        <option value="">All Categories</option>
        <!-- Categories will be injected via AJAX -->
    </select>
</div>

<!-- Products Grid -->
<div class="row" id="product-list">
    <!-- Products will be injected here dynamically -->
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Load categories
        $.ajax({
            url: '/api/categories',
            method: 'GET',
            success: function (categories) {
                categories.forEach(category => {
                    $('#categoryFilter').append(`<option value="${category.id}">${category.name}</option>`);
                });
            }
        });

        // Function to load products (optionally by category)
        function loadProducts(categoryId = '') {
            $.ajax({
                url: '/api/products',
                method: 'GET',
                data: { category_id: categoryId },
                success: function (products) {
                    let html = '';
                    if (products.length > 0) {
                        products.forEach(product => {
                            html += `
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <img src="${product.image}" class="card-img-top" alt="${product.name}" style="height: 250px; object-fit: cover;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">${product.name}</h5>
                                            <p class="card-text text-muted mb-2">${product.category}</p>
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
                    } else {
                        html = '<p class="text-center text-muted">No products found.</p>';
                    }
                    $('#product-list').html(html);
                },
                error: function () {
                    $('#product-list').html('<p class="text-danger text-center">Failed to load products.</p>');
                }
            });
        }

        // Initial product load
        loadProducts();

        // Category filter event
        $('#categoryFilter').on('change', function () {
            const selected = $(this).val();
            loadProducts(selected);
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
