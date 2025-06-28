@extends('user.layouts.master')

@section('title', 'Product Details - Fruitables')

@section('content')
<!-- Single Page Header Start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Shop Detail</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/products') }}">Products</a></li>
        <li class="breadcrumb-item active text-white">Shop Detail</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Single Product Start -->
<div class="container-fluid py-5 mt-5">
    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-lg-8 col-xl-9">
                <div id="product-detail-container">
                    <!-- Product details will load here via AJAX -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading product details...</p>
                    </div>
                </div>
                
                <!-- Reviews Section -->
                <div class="col-lg-12 mt-5">
                    <nav>
                        <div class="nav nav-tabs mb-3">
                            <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                                id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                aria-controls="nav-about" aria-selected="true">Description</button>
                            <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission"
                                aria-controls="nav-mission" aria-selected="false">Reviews</button>
                        </div>
                    </nav>
                    <div class="tab-content mb-5">
                        <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                            <div id="product-description">
                                <!-- Description will be loaded via AJAX -->
                            </div>
                        </div>
                        <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                            <div id="reviews-section">
                                <!-- Reviews will be loaded via AJAX -->
                            </div>
                            
                            <!-- Review Form -->
                            <h5 class="mt-5">Leave a Review</h5>
                            @auth
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
                            @else
                            <div class="alert alert-info">
                                Please <a href="{{ route('login') }}">login</a> to leave a review.
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4 col-xl-3">
                <div class="row g-4 fruite">
                    <div class="col-lg-12">
                        <div class="input-group w-100 mx-auto d-flex mb-4">
                            <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                        </div>
                        <div class="mb-4">
                            <h4>Categories</h4>
                            <ul class="list-unstyled fruite-categorie">
                                @foreach($categories as $category)
                                <li>
                                    <div class="d-flex justify-content-between fruite-name">
                                        <a href="{{ url('/products?category='.$category->slug) }}">
                                            <i class="fas fa-apple-alt me-2"></i>{{ $category->name }}
                                        </a>
                                        <span>({{ $category->products_count }})</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                        <h4 class="mb-4">Featured products</h4>
                        @foreach($featuredProducts as $product)
                        <div class="d-flex align-items-center justify-content-start mb-3">
                            <div class="rounded" style="width: 100px; height: 100px;">
                                <img src="{{ $product->image }}" class="img-fluid rounded" alt="{{ $product->name }}">
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-2">{{ $product->name }}</h6>
                                <div class="d-flex mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $product->average_rating)
                                        <i class="fa fa-star text-secondary"></i>
                                        @else
                                        <i class="fa fa-star text-muted"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="d-flex mb-2">
                                    <h5 class="fw-bold me-2">${{ number_format($product->price, 2) }}</h5>
                                    @if($product->compare_price)
                                    <h5 class="text-danger text-decoration-line-through">${{ number_format($product->compare_price, 2) }}</h5>
                                    @endif
                                </div>
                                <a href="{{ url('/products/'.$product->slug) }}" class="btn btn-sm border border-secondary rounded-pill px-3 text-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="col-lg-12">
                        <div class="position-relative">
                            <img src="{{ asset('img/banner-fruits.jpg') }}" class="img-fluid w-100 rounded" alt="">
                            <div class="position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%);">
                                <h3 class="text-secondary fw-bold">Fresh <br> Fruits <br> Banner</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        <h1 class="fw-bold mb-0">Related products</h1>
        <div class="vesitable">
            <div class="owl-carousel vegetable-carousel justify-content-center">
                @foreach($relatedProducts as $product)
                <div class="border border-primary rounded position-relative vesitable-item">
                    <div class="vesitable-img">
                        <img src="{{ $product->image }}" class="img-fluid w-100 rounded-top" alt="{{ $product->name }}">
                    </div>
                    <div class="text-white bg-primary px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px;">{{ $product->category->name }}</div>
                    <div class="p-4 pb-0 rounded-bottom">
                        <h4>{{ $product->name }}</h4>
                        <p>{{ Str::limit($product->short_description, 50) }}</p>
                        <div class="d-flex justify-content-between flex-lg-wrap">
                            <p class="text-dark fs-5 fw-bold">${{ number_format($product->price, 2) }}</p>
                            <a href="#" class="btn border border-secondary rounded-pill px-3 py-1 mb-4 text-primary add-to-cart" data-product-id="{{ $product->id }}">
                                <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- Single Product End -->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const productSlug = window.location.pathname.split('/').pop();
    
    // Load product details via AJAX
    function loadProductDetails() {
        $.ajax({
            url: `/api/products/${productSlug}`,
            method: 'GET',
            success: function(product) {
                const html = `
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="border rounded">
                                <img src="${product.image}" alt="${product.name}" class="img-fluid rounded">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h2 class="fw-bold mb-3">${product.name}</h2>
                            <p class="mb-3">Category: ${product.category.name}</p>
                            <div class="d-flex mb-3">
                                ${Array(5).fill().map((_, i) => 
                                    `<i class="fa fa-star ${i < product.average_rating ? 'text-secondary' : 'text-muted'}"></i>`
                                ).join('')}
                                <span class="ms-2">(${product.reviews_count} reviews)</span>
                            </div>
                            <h4 class="text-primary fw-bold mb-3">$${product.price.toFixed(2)}</h4>
                            ${product.compare_price ? 
                                `<h5 class="text-danger text-decoration-line-through mb-3">$${product.compare_price.toFixed(2)}</h5>` : ''}
                            <p class="mb-4">${product.short_description}</p>
                            
                            <div class="mt-4 d-flex align-items-center gap-2">
                                <div class="input-group quantity" style="width: 120px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" id="quantity" class="form-control form-control-sm text-center border-0" value="1">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <button class="btn border border-secondary rounded-pill px-4 py-2 text-primary" id="addToCartBtn">
                                    <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#product-detail-container').html(html);
                $('#product-description').html(`<p>${product.description}</p>`);
                
                // Initialize quantity buttons
                $('.btn-plus').click(function() {
                    let $input = $(this).parents('.input-group').find('input');
                    let val = parseInt($input.val());
                    $input.val(val + 1).change();
                });

                $('.btn-minus').click(function() {
                    let $input = $(this).parents('.input-group').find('input');
                    let val = parseInt($input.val());
                    if (val > 1) {
                        $input.val(val - 1).change();
                    }
                });
            },
            error: function() {
                $('#product-detail-container').html(`
                    <div class="alert alert-danger">
                        Failed to load product details. Please try again later.
                    </div>
                `);
            }
        });
    }
    
    // Load reviews via AJAX
    function loadReviews() {
        $.ajax({
            url: `/api/products/${productSlug}/reviews`,
            method: 'GET',
            success: function(reviews) {
                let html = '';
                if (reviews.length === 0) {
                    html = '<p class="text-muted">No reviews yet. Be the first to review!</p>';
                } else {
                    reviews.forEach(review => {
                        html += `
                            <div class="d-flex mb-4">
                                <img src="${review.user.avatar || '/img/avatar.jpg'}" class="img-fluid rounded-circle p-3" style="width: 80px; height: 80px;" alt="${review.user.name}">
                                <div class="ms-3">
                                    <p class="mb-2" style="font-size: 14px;">${new Date(review.created_at).toLocaleDateString()}</p>
                                    <div class="d-flex justify-content-between">
                                        <h5>${review.user.name}</h5>
                                        <div class="d-flex mb-3">
                                            ${Array(5).fill().map((_, i) => 
                                                `<i class="fa fa-star ${i < review.rating ? 'text-secondary' : 'text-muted'}"></i>`
                                            ).join('')}
                                        </div>
                                    </div>
                                    <p>${review.comment}</p>
                                </div>
                            </div>
                        `;
                    });
                }
                $('#reviews-section').html(html);
            }
        });
    }
    
    // Initialize star rating
    $(document).on('mouseover', '#star-rating i', function() {
        const value = $(this).data('value');
        $('#star-rating i').each(function() {
            if ($(this).data('value') <= value) {
                $(this).removeClass('far').addClass('fas');
            } else {
                $(this).removeClass('fas').addClass('far');
            }
        });
    });
    
    $(document).on('click', '#star-rating i', function() {
        const value = $(this).data('value');
        $('#rating').val(value);
    });
    
    // Submit review form
    $('#reviewForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        
        $.ajax({
            url: `/api/products/${productSlug}/reviews`,
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Review submitted successfully!');
                loadReviews();
                $('#reviewForm')[0].reset();
                $('#rating').val(0);
                $('#star-rating i').removeClass('fas').addClass('far');
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    alert('Please login to submit a review.');
                    window.location.href = '{{ route("login") }}';
                } else {
                    alert('Failed to submit review. Please try again.');
                }
            }
        });
    });
    
    // Add to cart
    $(document).on('click', '#addToCartBtn, .add-to-cart', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id') || '{{ $product->id }}';
        const quantity = $('#quantity').val() || 1;
        
        $.ajax({
            url: '/cart/add',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Product added to cart!');
                updateCartCount(response.cart_count);
            },
            error: function() {
                alert('Failed to add product to cart. Please try again.');
            }
        });
    });
    
    // Function to update cart count
    function updateCartCount(count) {
        $('.cart-count').text(count);
    }
    
    // Initialize product and reviews
    loadProductDetails();
    loadReviews();
});
</script>
@endpush