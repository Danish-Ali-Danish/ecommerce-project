@extends('user.layouts.master')

@section('title', 'Products - ShopNow')

@section('content')
<x-breadcrumbs :items="[
    ['label' => 'Home', 'url' => url('/')],
    ['label' => 'Products', 'url' => '']
]" />

<!-- Products Section -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-4">
            <!-- Filters Column -->
            <div class="col-lg-3">
                <!-- Search Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-black">Search</h5>
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
                            <button class="btn btn-primary" id="searchBtn"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-black">Categories</h5>
                        <div id="categoryFilter">
                            <!-- Categories will be loaded via AJAX -->
                        </div>
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-black">Price Range</h5>
                        <div class="range-slider">
                            <input type="range" class="form-range" id="priceMin" min="0" max="10000" value="0">
                            <input type="range" class="form-range" id="priceMax" min="0" max="10000" value="10000">
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-black">PKR <span id="priceMinValue">0</span></span>
                                <span class="text-black">PKR <span id="priceMaxValue">10000</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Column -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">All Products</h2>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Sort By
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                            <li><a class="dropdown-item sort-option" href="#" data-sort="default">Default</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="price_asc">Price: Low to High</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="price_desc">Price: High to Low</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="name_asc">Name: A-Z</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="name_desc">Name: Z-A</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="row" id="product-list">
                    <!-- Products will be loaded via AJAX -->
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-12">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center" id="pagination">
                                <!-- Pagination will be loaded via AJAX -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Current page and filters
    let currentPage = 1;
    let currentFilters = {
        category: '',
        search: '',
        minPrice: 0,
        maxPrice: 10000,
        sort: 'default'
    };

    // Load products
    function loadProducts(page = 1) {
        currentPage = page;
        
        $.ajax({
            url: '/api/products',
            method: 'GET',
            data: {
                page: page,
                category_id: currentFilters.category,
                search: currentFilters.search,
                min_price: currentFilters.minPrice,
                max_price: currentFilters.maxPrice,
                sort: currentFilters.sort
            },
            success: function(response) {
                renderProducts(response.data);
                renderPagination(response);
            },
            error: function() {
                $('#product-list').html('<div class="col-12 text-center text-danger">Failed to load products. Please try again.</div>');
            }
        });
    }

    // Render products
    function renderProducts(products) {
        let html = '';
        
        if (products.length > 0) {
            products.forEach(product => {
                html += `
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="${product.image}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text text-muted mb-2">${product.category_name || 'Uncategorized'}</p>
                                <div class="mt-auto">
                                    <p class="fw-bold mb-1">PKR ${product.price.toLocaleString()}</p>
                                    <div class="d-flex justify-content-between">
                                        <a href="/product/${product.id}" class="btn btn-primary flex-grow-1 me-2">View Details</a>
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
            html = '<div class="col-12 text-center text-muted">No products found matching your criteria.</div>';
        }
        
        $('#product-list').html(html);
    }

    // Render pagination
    function renderPagination(response) {
        let html = '';
        
        if (response.last_page > 1) {
            // Previous button
            html += `<li class="page-item ${response.current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${response.current_page - 1}">Previous</a>
                    </li>`;
            
            // Page numbers
            for (let i = 1; i <= response.last_page; i++) {
                html += `<li class="page-item ${response.current_page === i ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>`;
            }
            
            // Next button
            html += `<li class="page-item ${response.current_page === response.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${response.current_page + 1}">Next</a>
                    </li>`;
        }
        
        $('#pagination').html(html);
    }

    // Load categories
    function loadCategories() {
        $.ajax({
            url: '/api/categories',
            method: 'GET',
            success: function(categories) {
                let html = '<div class="list-group">';
                html += '<a href="#" class="list-group-item list-group-item-action active category-filter" data-id="">All Categories</a>';
                
                categories.forEach(category => {
                    html += `<a href="#" class="list-group-item list-group-item-action category-filter" data-id="${category.id}">${category.name}</a>`;
                });
                
                html += '</div>';
                $('#categoryFilter').html(html);
            }
        });
    }

    // Initialize
    loadCategories();
    loadProducts();

    // Event listeners
    $(document).on('click', '.category-filter', function(e) {
        e.preventDefault();
        $('.category-filter').removeClass('active');
        $(this).addClass('active');
        currentFilters.category = $(this).data('id');
        loadProducts(1);
    });

    $('#searchBtn').click(function() {
        currentFilters.search = $('#searchInput').val();
        loadProducts(1);
    });

    $('#searchInput').keypress(function(e) {
        if (e.which === 13) {
            currentFilters.search = $(this).val();
            loadProducts(1);
        }
    });

    $(document).on('click', '.sort-option', function(e) {
        e.preventDefault();
        currentFilters.sort = $(this).data('sort');
        loadProducts(1);
    });

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadProducts(page);
    });

    $('#priceMin').on('input', function() {
        currentFilters.minPrice = $(this).val();
        $('#priceMinValue').text($(this).val());
        loadProducts(1);
    });

    $('#priceMax').on('input', function() {
        currentFilters.maxPrice = $(this).val();
        $('#priceMaxValue').text($(this).val());
        loadProducts(1);
    });

    // Wishlist functionality
    $(document).on('click', '.wishlist-btn', function() {
        const productId = $(this).data('id');
        $.ajax({
            url: '/api/wishlist/toggle',
            method: 'POST',
            data: { product_id: productId },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(res) {
                showToast(res.message || 'Wishlist updated!');
            },
            error: function() {
                showToast('Failed to update wishlist.');
            }
        });
    });
});

function showToast(message) {
    // Implement your toast notification here
    alert(message); // Simple alert for demonstration
}
</script>
@endpush