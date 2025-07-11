@extends('user.layouts.master')

@section('title', 'Home - ShopNow')

@section('content')

<!-- Hero Section -->
<section class="hero-section position-relative text-white bg-dark overflow-hidden">
    <div class="container py-7 py-lg-8 position-relative z-1 text-center">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Welcome to ShopNow</h1>
                <p class="lead mb-5 animate__animated animate__fadeIn animate__delay-1s">
                    Discover top-rated products with free shipping on orders over $50
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3 animate__animated animate__fadeIn animate__delay-2s">
                    <a href="{{ route('allproducts') }}" class="btn btn-light btn-lg px-4 py-2 rounded-pill shadow">
                        Shop Now <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('allproducts') }}" class="btn btn-outline-light btn-lg px-4 py-2 rounded-pill">
                        Featured Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Carousel -->
<section class="my-5">
    <div class="container">
        
        <!-- Heading and View All Button -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0">Shop by Category</h2>
            <a href="{{ route('allcate') }}" class="btn btn-outline-primary btn-sm">View All Categories</a>
        </div>

        <!-- Carousel Scroll Row -->
        <div class="overflow-auto d-flex flex-nowrap px-2" id="categoryCarousel" style="scroll-behavior: smooth;">
            @foreach ($categories as $category)
                <div class="card me-3 text-center shadow-sm flex-shrink-0" style="width: 160px;">
                    <img src="{{ $category->file_path ? asset('storage/' . $category->file_path) : asset('images/default-category.png') }}"
                         class="card-img-top img-fluid rounded" style="height: 100px; object-fit: cover;" alt="{{ $category->name }}">
                    <div class="card-body p-2">
                        <h6 class="card-title mb-1">{{ $category->name }}</h6>
                        <a href="{{ route('allproducts') }}" class="btn btn-outline-primary btn-sm w-100">Explore</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Arrows in New Row (Always Visible) -->
        <div class="d-flex justify-content-center mt-3 gap-3">
            <button class="btn btn-outline-primary" id="categoryPrev">
                <i class="fas fa-chevron-left me-1"></i> Previous
            </button>
            <button class="btn btn-outline-primary" id="categoryNext">
                Next <i class="fas fa-chevron-right ms-1"></i>
            </button>
        </div>
    </div>
</section>
<!-- Brands Carousel -->
<section class="my-5">
    <div class="container">
        <!-- Heading + View All -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0">Shop by Brand</h2>
            <a href="{{ route('allbrands') }}" class="btn btn-outline-primary btn-sm">View All Brands</a>
        </div>

        <!-- Carousel Scroll Row -->
        <div class="overflow-auto d-flex flex-nowrap px-2" id="brandCarousel" style="scroll-behavior: smooth;">
            @foreach ($brands as $brand)
                <div class="card me-3 text-center shadow-sm flex-shrink-0" style="width: 160px;">
                    <img src="{{ $brand->file_path ? asset('storage/' . $brand->file_path) : asset('images/default-brand.png') }}"
                         class="card-img-top img-fluid rounded" style="height: 100px; object-fit: cover;" alt="{{ $brand->name }}">
                    <div class="card-body p-2">
                        <h6 class="card-title mb-1">{{ $brand->name }}</h6>
                        <a href="{{ route('allproducts') }}" class="btn btn-outline-primary btn-sm w-100">Explore</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Arrow Buttons Row -->
        <div class="d-flex justify-content-center mt-3 gap-3">
            <button class="btn btn-outline-primary" id="brandPrev">
                <i class="fas fa-chevron-left me-1"></i> Previous
            </button>
            <button class="btn btn-outline-primary" id="brandNext">
                Next <i class="fas fa-chevron-right ms-1"></i>
            </button>
        </div>
    </div>
</section>

<!-- Latest Products -->
<section class="my-5">
    <div class="container">
        <h4 class="fw-bold mb-4">Latest Products</h4>
        <div class="row g-3">
            @foreach ($products as $product)
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="overflow-hidden">
                            <img src="{{ asset('storage/' . ($product->image ?? 'images/default-product.png')) }}"
                                 class="card-img-top img-fluid rounded product-hover"
                                 style="height: 120px; object-fit: cover; transition: transform 0.3s;">
                        </div>
                        <div class="card-body p-2">
                            <h6 class="card-title mb-1">{{ Str::limit($product->name, 20) }}</h6>
                            <p class="text-muted small mb-1">PKR {{ number_format($product->price) }}</p>
                            <a href="{{ url('/product/' . $product->id) }}" class="btn btn-outline-primary btn-sm w-100">View</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Subscribe to Our Newsletter</h2>
        <p class="mb-4">Get the latest updates on new products and upcoming sales</p>
        <form id="newsletter-form" class="row g-2 justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="input-group input-group-lg">
                    <input type="email" class="form-control" placeholder="Your email address" required>
                    <button class="btn btn-dark px-4" type="submit">Subscribe</button>
                </div>
            </div>
        </form>
    </div>
</section>
<!-- Professional Dynamic Home Page: Blade Version -->

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            @foreach($features as $feature)
                <div class="col-md-3 col-6 mb-4">
                    <i class="{{ $feature['icon'] }} fa-2x text-primary mb-2"></i>
                    <h6 class="fw-bold">{{ $feature['title'] }}</h6>
                    <p class="text-muted small">{{ $feature['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Promotional Banner -->
@if($promo)
<section class="py-5 bg-dark text-white text-center">
    <div class="container">
        <h2 class="fw-bold mb-3">{{ $promo['title'] }}</h2>
        <p class="mb-4">{{ $promo['subtitle'] }}</p>
        <a href="{{ $promo['url'] }}" class="btn btn-outline-light btn-lg px-5 py-2">{{ $promo['button_text'] }}</a>
    </div>
</section>
@endif

<!-- Testimonials -->
@if(count($testimonials))
<section class="py-5 bg-light">
    <div class="container">
        <h4 class="text-center fw-bold mb-5">What Our Customers Say</h4>
        <div class="row">
            @foreach($testimonials as $testimonial)
                <div class="col-md-4 mb-4">
                    <div class="bg-white p-4 shadow-sm rounded h-100">
                        <p class="text-muted">"{{ $testimonial['message'] }}"</p>
                        <div class="d-flex align-items-center mt-3">
                            <img src="{{ $testimonial['avatar'] }}" class="rounded-circle me-2" alt="User Avatar">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $testimonial['name'] }}</h6>
                                <small class="text-muted">{{ $testimonial['location'] }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Blog Preview -->
@if(count($blogs))
<section class="py-5">
    <div class="container">
        <h4 class="fw-bold mb-4 text-center">Latest from Our Blog</h4>
        <div class="row g-4">
            @foreach($blogs as $blog)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('storage/' . $blog->image) }}" class="card-img-top" alt="Blog Image">
                        <div class="card-body">
                            <h6 class="card-title fw-bold">{{ Str::limit($blog->title, 50) }}</h6>
                            <p class="text-muted small">{{ Str::limit($blog->excerpt, 80) }}</p>
                            <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('styles')
<style>
    .product-hover:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@push('scripts')
<script>
    const scrollCarousel = (id, dir) => {
        document.getElementById(id).scrollBy({
            left: dir * 200,
            behavior: 'smooth'
        });
    };

    document.getElementById('categoryNext')?.addEventListener('click', () => scrollCarousel('categoryCarousel', 1));
    document.getElementById('categoryPrev')?.addEventListener('click', () => scrollCarousel('categoryCarousel', -1));
    document.getElementById('brandNext')?.addEventListener('click', () => scrollCarousel('brandCarousel', 1));
    document.getElementById('brandPrev')?.addEventListener('click', () => scrollCarousel('brandCarousel', -1));
</script>
@endpush
