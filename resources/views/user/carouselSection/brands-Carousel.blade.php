<section class="my-5">
    <div class="container position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">Shop by Brand</h2>
            <a href="{{ route('allbrands') }}" class="btn btn-outline-primary btn-sm">View All Brands</a>
        </div>

        <div class="position-relative">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary me-2 d-none d-md-inline" id="brandPrev">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <div class="flex-grow-1 overflow-auto px-2" id="brandCarousel" style="scroll-behavior: smooth; white-space: nowrap; overflow-y: hidden;">
                    @foreach ($brands as $brand)
                        <div class="d-inline-block me-3" style="width: 180px;">
                            <div class="card h-100 shadow-sm text-center">
                                @if ($brand->file_path)
                                    <img src="{{ asset('storage/' . $brand->file_path) }}"
                                         class="card-img-top img-fluid rounded"
                                         style="height: 120px; object-fit: cover; cursor: pointer;"
                                         alt="{{ $brand->name }}"
                                         data-bs-toggle="modal"
                                         data-bs-target="#brandModal"
                                         onclick="previewBrandImage('{{ asset('storage/' . $brand->file_path) }}')">
                                @else
                                    <img src="{{ asset('images/default-brand.png') }}"
                                         class="card-img-top"
                                         alt="Default Brand">
                                @endif
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1">{{ $brand->name }}</h6>
                                    <a href="{{ route('allproducts') }}" class="btn btn-outline-primary btn-sm">Explore</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="btn btn-outline-secondary ms-2 d-none d-md-inline" id="brandNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>