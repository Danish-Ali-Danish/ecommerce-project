@extends('user.layouts.master')

@section('title', 'Product Details - ShopNow')

@section('content')
<x-breadcrumbs :items="[
    ['label' => 'Home', 'url' => url('/')],
    ['label' => 'Products', 'url' => url('/products')],
    ['label' => 'Product Detail', 'url' => '']
]" />

<div id="product-detail-container">
    <!-- Product details will load here via AJAX -->
</div>
<hr class="my-5">

<h4 class="mb-3">Customer Reviews</h4>
<div id="reviews-section" class="mb-4">
    <!-- Reviews will load here -->
</div>

<h5>Leave a Review</h5>
<form id="reviewForm" class="mb-5">
    <div class="mb-3">
        <label class="form-label">Your Rating</label>
        <div id="star-rating" class="text-warning fs-4">
            <i class="far fa-star" data-value="1"></i>
            <i class="far fa-star" data-value="2"></i>
            <i class="far fa-star" data-value="3"></i>
            <i class="far fa-star" data-value="4"></i>
            <i class="far fa-star" data-value="5"></i>
        </div>
        <input type="hidden" name="rating" id="rating" value="0">
    </div>
    <div class="mb-3">
        <label class="form-label">Your Comment</label>
        <textarea name="comment" class="form-control" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit Review</button>
</form>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Load existing reviews
$.ajax({
    url: `/api/products/${productId}/reviews`,
    method: 'GET',
    success: function (reviews) {
        let html = '';
        if (reviews.length === 0) {
            html = '<p class="text-muted">No reviews yet.</p>';
        } else {
            reviews.forEach(review => {
                html += `
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>${review.name || 'User'}</strong>
                            <div class="text-warning">
                                ${'<i class="fas fa-star"></i>'.repeat(review.rating)}
                                ${'<i class="far fa-star"></i>'.repeat(5 - review.rating)}
                            </div>
                        </div>
                        <p class="mb-0">${review.comment}</p>
                    </div>
                `;
            });
        }
        $('#reviews-section').html(html);
    }
});

// Handle star selection
$('#star-rating i').on('click', function () {
    const value = $(this).data('value');
    $('#rating').val(value);
    $('#star-rating i').removeClass('fas').addClass('far');
    $('#star-rating i').each(function () {
        if ($(this).data('value') <= value) {
            $(this).removeClass('far').addClass('fas');
        }
    });
});

// Submit review
$('#reviewForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: `/api/products/${productId}/reviews`,
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function () {
            showToast('Review submitted!');
            location.reload();
        },
        error: function () {
            showToast('Failed to submit review.');
        }
    });
});

        const productId = window.location.pathname.split('/').pop();

        // Load product details via AJAX
        $.ajax({
            url: `/api/products/${productId}`,
            method: 'GET',
            success: function (product) {
                const html = `
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <img src="${product.image}" alt="${product.name}" class="img-fluid rounded shadow" style="max-height: 500px; object-fit: cover;">
                        </div>
                        <div class="col-md-6">
                            <h2 class="fw-bold">${product.name}</h2>
                            <p class="text-muted">${product.category}</p>
                            <h4 class="text-primary fw-bold">PKR ${product.price}</h4>
                            <p class="mt-3">${product.description}</p>

                            <div class="mt-4 d-flex align-items-center gap-2">
                                <input type="number" id="quantity" class="form-control w-25" min="1" value="1">
                                <button class="btn btn-success" id="addToCartBtn">
                                    <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#product-detail-container').html(html);
            },
            error: function () {
                $('#product-detail-container').html('<p class="text-danger">Failed to load product details.</p>');
            }
        });

        // Add to Cart (AJAX)
        $(document).on('click', '#addToCartBtn', function () {
            const qty = $('#quantity').val();
            $.ajax({
                url: '/api/cart/add',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: qty,
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (res) {
                    showToast('Product added to cart!');
                },
                error: function () {
                    showToast('Something went wrong. Try again.');
                }
            });
        });
    });
</script>
@endpush
