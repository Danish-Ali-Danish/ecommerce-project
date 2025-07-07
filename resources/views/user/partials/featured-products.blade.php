<!-- Featured Products -->
<section class="py-5 bg-light" id="featured-products-section">
    <div class="container">
        <div class="section-header mb-5 text-center">
            <h2 class="fw-bold mb-3">Featured Products</h2>
            <p class="text-muted">Handpicked products just for you</p>
        </div>
        <div class="row g-4" id="featured-products">
            <!-- Skeleton loading -->
            @for($i = 0; $i < 3; $i++)
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
            <a href="{{ route('allproducts') }}" class="btn btn-primary btn-lg px-5 py-3 rounded-pill">
                View All Products <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
