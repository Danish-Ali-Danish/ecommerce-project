<section class="my-5">
    <div class="container position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">Shop by Category</h2>
            <a href="{{ route('allcate') }}" class="btn btn-outline-primary btn-sm">View All Categories</a>
        </div>

        <div class="position-relative">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary me-2 d-none d-md-inline" id="categoryPrev">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <div class="flex-grow-1 overflow-auto px-2" id="categoryCarousel" style="scroll-behavior: smooth; white-space: nowrap;">
                    @foreach ($categories as $category)
                        <div class="d-inline-block me-3" style="width: 180px;">
                            <div class="card h-100 shadow-sm text-center">
                                @if ($category->file_path)
                                    <img src="{{ asset('storage/' . $category->file_path) }}"
                                         class="card-img-top img-fluid rounded"
                                         style="height: 120px; object-fit: cover; cursor: pointer;"
                                         alt="{{ $category->name }}"
                                         data-bs-toggle="modal"
                                         data-bs-target="#categoryModal"
                                         onclick="previewCategoryImage('{{ asset('storage/' . $category->file_path) }}')">
                                @else
                                    <img src="{{ asset('images/default-category.png') }}"
                                         class="card-img-top"
                                         alt="Default Category">
                                @endif
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1">{{ $category->name }}</h6>
                                    <a href="{{ route('allproducts')  }}" class="btn btn-outline-primary btn-sm">Explore</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="btn btn-outline-secondary ms-2 d-none d-md-inline" id="categoryNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>
