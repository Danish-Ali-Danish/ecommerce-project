@extends('user.layouts.master')

@section('title', 'All Products - ShopNow')

@section('content')

<x-breadcrumbs :items="[
    ['label' => 'Home', 'url' => url('/')],
    ['label' => 'Products', 'url' => '']
]" />

<div class="container-fluid py-5">
    <div class="container py-5">
        <form method="GET" action="{{ route('allproducts') }}">
            <div class="row g-4">
                <!-- Filters Column -->
                <div class="col-lg-3">
                    <!-- Search Filter -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-black">Search</h5>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-black">Categories</h5>
                            <div class="list-group">
                                <a href="{{ route('allproducts') }}" class="list-group-item list-group-item-action {{ request('category') ? '' : 'active' }}">
                                    All Categories
                                </a>
                                @foreach ($categories as $category)
                                    <a href="{{ route('allproducts', array_merge(request()->except('page'), ['category' => $category->id])) }}"
                                       class="list-group-item list-group-item-action {{ request('category') == $category->id ? 'active' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-black">Brands</h5>
                            <div class="list-group">
                                <a href="{{ route('allproducts') }}" class="list-group-item list-group-item-action {{ request('brand') ? '' : 'active' }}">
                                    All Brands
                                </a>
                                @foreach ($brands as $brand)
                                    <a href="{{ route('allproducts', array_merge(request()->except('page'), ['brand' => $brand->id])) }}"
                                       class="list-group-item list-group-item-action {{ request('brand') == $brand->id ? 'active' : '' }}">
                                        {{ $brand->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-black">Price Range</h5>
                            <div class="mb-2">
                                <label for="min_price" class="form-label">Min Price</label>
                                <input type="number" name="min_price" class="form-control" value="{{ request('min_price', 0) }}">
                            </div>
                            <div class="mb-3">
                                <label for="max_price" class="form-label">Max Price</label>
                                <input type="number" name="max_price" class="form-control" value="{{ request('max_price', 10000) }}">
                            </div>
                            <button class="btn btn-outline-primary w-100" type="submit">Filter</button>
                        </div>
                    </div>
                </div>

                <!-- Products Column -->
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold">All Products</h2>
                        <div>
                            <select class="form-select" name="sort" onchange="this.form.submit()">
                                <option value="">Sort By</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-lg-9">
                    <div class="row">
                        @forelse ($products as $product)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</p>
                                        <p class="fw-bold">PKR {{ number_format($product->price) }}</p>
                                        <a href="{{ url('/product/' . $product->id) }}" class="btn btn-primary mt-auto">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center text-muted">No products found matching your filters.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
