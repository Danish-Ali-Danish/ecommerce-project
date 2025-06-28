@extends('user.layouts.master')

@section('title', 'Home - ShopNow')

@section('content')

<!-- Hero Section with Animation -->
<section class="hero-section position-relative overflow-hidden">
    <div class="container py-7 py-lg-8 position-relative z-index-1">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4 text-white animate__animated animate__fadeInDown">Welcome to ShopNow</h1>
                <p class="lead text-white-80 mb-5 animate__animated animate__fadeIn animate__delay-1s">Discover the best deals on top-rated products with free shipping on orders over $50</p>
                <div class="d-flex justify-content-center gap-3 animate__animated animate__fadeIn animate__delay-2s">
                    <a href="{{ route('products') }}" class="btn btn-light btn-lg px-4 py-3 rounded-pill fw-medium shadow-sm">
                        Shop Now <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="#featured-products" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill fw-medium">
                        Featured Products
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
        <div class="shape-3"></div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="section-header mb-5 text-center">
            <h2 class="fw-bold mb-3">Shop By Categories</h2>
            <p class="text-muted">Browse through our wide range of product categories</p>
        </div>
        <div class="row g-4" id="categories-section">
            <!-- Loading skeleton -->
            <div class="col-12">
                <div class="row">
                    @for($i=0; $i<6; $i++)
                    <div class="col-6 col-md-3 col-lg-2 mb-4">
                        <div class="card h-100 border-0 placeholder-glow">
                            <div class="card-body p-3 text-center">
                                <div class="mb-3 placeholder" style="height: 80px; width: 80px; margin: 0 auto;"></div>
                                <h6 class="card-title placeholder mb-0" style="width: 80%"></h6>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Brands Section -->
<section class="py-5">
    <div class="container">
        <div class="section-header mb-5 text-center">
            <h2 class="fw-bold mb-3">Popular Brands</h2>
            <p class="text-muted">Shop from your favorite brands</p>
        </div>
        <div class="row g-4" id="brands-section">
            <!-- Loading skeleton -->
            <div class="col-12">
                <div class="row">
                    @for($i=0; $i<6; $i++)
                    <div class="col-6 col-md-3 col-lg-2 mb-4">
                        <div class="card h-100 border-0 placeholder-glow">
                            <div class="card-body p-3 text-center">
                                <div class="mb-3 placeholder" style="height: 80px; width: 80px; margin: 0 auto;"></div>
                                <h6 class="card-title placeholder mb-0" style="width: 80%"></h6>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-light" id="featured-products-section">
    <div class="container">
        <div class="section-header mb-5 text-center">
            <h2 class="fw-bold mb-3">Featured Products</h2>
            <p class="text-muted">Handpicked products just for you</p>
        </div>
        <div class="row g-4" id="featured-products">
            <!-- Loading skeleton -->
            @for($i=0; $i<3; $i++)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm placeholder-glow">
                    <div class="placeholder" style="height: 250px;"></div>
                    <div class="card-body">
                        <h5 class="card-title placeholder" style="width: 80%"></h5>
                        <p class="card-text placeholder mb-2" style="width: 60%"></p>
                        <div class="d-flex justify-content-between">
                            <div class="placeholder" style="width: 40%; height: 38px;"></div>
                            <div class="placeholder" style="width: 38px; height: 38px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('products') }}" class="btn btn-primary btn-lg px-5 py-3 rounded-pill">
                View All Products <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="fw-bold mb-4">Subscribe to Our Newsletter</h2>
                <p class="mb-5">Get the latest updates on new products and upcoming sales</p>
                <form id="newsletter-form" class="row g-3 justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="email" class="form-control form-control-lg" placeholder="Your email address" required>
                            <button class="btn btn-dark btn-lg px-4" type="submit">Subscribe</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection





@push('scripts')
<script>
    $(document).ready(function () {
        // Load categories with skeleton loader
        loadCategories();
        
        // Load brands with skeleton loader
        loadBrands();
        
        // Load featured products with skeleton loader
        loadFeaturedProducts();
        
        // Newsletter form submission
        $('#newsletter-form').submit(function(e) {
            e.preventDefault();
            const email = $(this).find('input[type="email"]').val();
            
            $.ajax({
                url: '/api/newsletter/subscribe',
                method: 'POST',
                data: { email: email },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#newsletter-form button').prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm me-2" role="status"></span> Subscribing...');
                },
                success: function(res) {
                    showToast('success', 'Subscribed successfully! Thank you.');
                    $('#newsletter-form')[0].reset();
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Subscription failed. Please try again.';
                    showToast('error', message);
                },
                complete: function() {
                    $('#newsletter-form button').prop('disabled', false).text('Subscribe');
                }
            });
        });
    });
    
    function loadCategories() {
        $.ajax({
            url: '/api/categories',
            method: 'GET',
            beforeSend: function() {
                $('#categories-section').html(`
                    <div class="col-12">
                        <div class="row">
                            ${Array(6).fill().map(() => `
                                <div class="col-6 col-md-3 col-lg-2 mb-4">
                                    <div class="card h-100 border-0 placeholder-glow">
                                        <div class="card-body p-3 text-center">
                                            <div class="mb-3 placeholder" style="height: 80px; width: 80px; margin: 0 auto;"></div>
                                            <h6 class="card-title placeholder mb-0" style="width: 80%"></h6>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `);
            },
            success: function (response) {
                if (response.categories?.length > 0) {
                    let html = response.categories.map(category => `
                        <div class="col-6 col-md-3 col-lg-2 mb-4">
                            <a href="/products?category=${category.id}" class="text-decoration-none">
                                <div class="card h-100 shadow-sm border-0 text-center hover-scale">
                                    <div class="card-body p-3">
                                        <div class="mb-3 mx-auto" style="height: 80px; width: 80px; display: flex; align-items: center; justify-content: center;">
                                            <img src="${category.image || 'https://via.placeholder.com/80'}" 
                                                 alt="${category.name}" 
                                                 class="img-fluid rounded-circle" 
                                                 style="max-height: 100%; max-width: 100%; object-fit: cover;">
                                        </div>
                                        <h6 class="card-title text-dark mb-0">${category.name}</h6>
                                        <small class="text-muted">${category.product_count || 0} items</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    `).join('');
                    
                    $('#categories-section').html(html);
                } else {
                    $('#categories-section').html(`
                        <div class="col-12 text-center py-5">
                            <div class="alert alert-info">
                                No categories found. Please check back later.
                            </div>
                        </div>
                    `);
                }
            },
            error: function (xhr) {
                console.error('Error loading categories:', xhr.responseText);
                $('#categories-section').html(`
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-danger">
                            Failed to load categories. Please try again later.
                        </div>
                    </div>
                `);
            }
        });
    }
    
    function loadBrands() {
        $.ajax({
            url: '/api/brands',
            method: 'GET',
            beforeSend: function() {
                $('#brands-section').html(`
                    <div class="col-12">
                        <div class="row">
                            ${Array(6).fill().map(() => `
                                <div class="col-6 col-md-3 col-lg-2 mb-4">
                                    <div class="card h-100 border-0 placeholder-glow">
                                        <div class="card-body p-3 text-center">
                                            <div class="mb-3 placeholder" style="height: 80px; width: 80px; margin: 0 auto;"></div>
                                            <h6 class="card-title placeholder mb-0" style="width: 80%"></h6>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `);
            },
            success: function (response) {
                if (response.brands?.length > 0) {
                    let html = response.brands.map(brand => `
                        <div class="col-6 col-md-3 col-lg-2 mb-4">
                            <a href="/products?brand=${brand.id}" class="text-decoration-none">
                                <div class="card h-100 shadow-sm border-0 text-center hover-scale">
                                    <div class="card-body p-3">
                                        <div class="mb-3 mx-auto" style="height: 80px; width: 80px; display: flex; align-items: center; justify-content: center;">
                                            <img src="${brand.logo || brand.image || 'https://via.placeholder.com/80'}" 
                                                 alt="${brand.name}" 
                                                 class="img-fluid" 
                                                 style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                        </div>
                                        <h6 class="card-title text-dark mb-0">${brand.name}</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    `).join('');
                    
                    $('#brands-section').html(html);
                } else {
                    $('#brands-section').html(`
                        <div class="col-12 text-center py-5">
                            <div class="alert alert-info">
                                No brands found. Please check back later.
                            </div>
                        </div>
                    `);
                }
            },
            error: function (xhr) {
                console.error('Error loading brands:', xhr.responseText);
                $('#brands-section').html(`
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-danger">
                            Failed to load brands. Please try again later.
                        </div>
                    </div>
                `);
            }
        });
    }
    
    function loadFeaturedProducts() {
        $.ajax({
            url: '/api/featured-products',
            method: 'GET',
            beforeSend: function() {
                $('#featured-products').html(`
                    ${Array(3).fill().map(() => `
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm placeholder-glow">
                                <div class="placeholder" style="height: 250px;"></div>
                                <div class="card-body">
                                    <h5 class="card-title placeholder" style="width: 80%"></h5>
                                    <p class="card-text placeholder mb-2" style="width: 60%"></p>
                                    <div class="d-flex justify-content-between">
                                        <div class="placeholder" style="width: 40%; height: 38px;"></div>
                                        <div class="placeholder" style="width: 38px; height: 38px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                `);
            },
            success: function (response) {
                if (response.products?.length > 0) {
                    let html = response.products.map(product => `
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm product-card">
                                <div class="position-relative">
                                    <img src="${product.image}" 
                                         class="card-img-top" 
                                         alt="${product.name}" 
                                         style="height: 250px; object-fit: cover;">
                                    ${product.discount ? `
                                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                            ${product.discount}% OFF
                                        </span>
                                    ` : ''}
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text text-muted mb-2">${product.category?.name || ''}</p>
                                    <div class="d-flex align-items-center mb-2">
                                        ${product.rating ? `
                                            <div class="me-2">
                                                ${Array(5).fill().map((_, i) => `
                                                    <i class="fas fa-star ${i < Math.round(product.rating) ? 'text-warning' : 'text-secondary'}"></i>
                                                `).join('')}
                                                <small class="text-muted ms-1">(${product.review_count || 0})</small>
                                            </div>
                                        ` : ''}
                                    </div>
                                    <div class="mt-auto">
                                        <div class="d-flex align-items-center mb-2">
                                            <p class="fw-bold mb-0 me-2">PKR ${product.discounted_price || product.price}</p>
                                            ${product.discounted_price ? `
                                                <p class="text-muted text-decoration-line-through mb-0">PKR ${product.price}</p>
                                            ` : ''}
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <a href="/product/${product.id}" class="btn btn-primary flex-grow-1 me-2">
                                                View Details
                                            </a>
                                            <button class="btn btn-outline-danger wishlist-btn ${product.is_in_wishlist ? 'active' : ''}" 
                                                    data-id="${product.id}">
                                                <i class="fas ${product.is_in_wishlist ? 'fa-heart' : 'fa-heart'}"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    
                    $('#featured-products').html(html);
                } else {
                    $('#featured-products').html(`
                        <div class="col-12 text-center py-5">
                            <div class="alert alert-info">
                                No featured products found. Please check back later.
                            </div>
                        </div>
                    `);
                }
            },
            error: function (xhr) {
                console.error('Error loading featured products:', xhr.responseText);
                $('#featured-products').html(`
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-danger">
                            Failed to load featured products. ${xhr.responseJSON?.message || ''}
                        </div>
                    </div>
                `);
            }
        });
    }
    
    // Wishlist toggle handler
    $(document).on('click', '.wishlist-btn', function () {
        const $btn = $(this);
        const productId = $btn.data('id');
        
        $.ajax({
            url: '/api/wishlist/toggle',
            method: 'POST',
            data: { product_id: productId },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $btn.prop('disabled', true);
            },
            success: function (res) {
                $btn.toggleClass('active');
                $btn.find('i').toggleClass('fa-heart fa-heart');
                showToast('success', res.message || 'Wishlist updated!');
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Failed to update wishlist.';
                showToast('error', message);
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    });
    
    // Toast notification function
    function showToast(type, message) {
        const toast = $(`
            <div class="toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);
        
        $('body').append(toast);
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
        
        toast.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }
</script>
@endpush