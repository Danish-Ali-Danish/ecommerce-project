@extends('admin.layout.app')

@section('content')
<div class="container dashboard-card">
    <h2>Brand List</h2>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <input type="text" id="searchInput" class="form-control" placeholder="Search brand by name...">
        </div>
        <div>
            <label for="sortBrands" class="form-label me-2 fw-bold">Sort by:</label>
            <select id="sortBrands" class="form-select w-auto d-inline-block">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="az">Name A–Z</option>
                <option value="za">Name Z–A</option>
            </select>
        </div>
        <button class="btn btn-primary" id="addBrandBtn">
            <i class="fas fa-plus-circle"></i> Add Brand
        </button>
    </div>

    <div class="table-responsive d-flex justify-content-center gap-2">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="brandTableBody"></tbody>
        </table>
    </div>
</div>

{{-- ✅ Add/Edit Brand Modal --}}
<div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="brandModalLabel">Add New Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="brandForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="brandId" name="id">

                    <div class="mb-3">
                        <label for="brandName" class="form-label">Brand Name</label>
                        <input type="text" class="form-control" id="brandName" name="name" placeholder="Enter brand name">
                    </div>

                    <div class="mb-3">
                        <label for="brandCategory" class="form-label">Category</label>
                        <select id="brandCategory" name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="brandFile" class="form-label">Brand Image</label>
                        <input type="file" class="form-control" id="brandFile" name="file" accept="image/*">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveBrandBtn">Save</button>
            </div>
        </div>
    </div>
</div>

{{-- ✅ File Preview Modal --}}
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" class="img-fluid" alt="Image Preview">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    const brandModal = new bootstrap.Modal($('#brandModal')[0]);
    const brandForm = $('#brandForm');
    const brandIdInput = $('#brandId');
    const brandNameInput = $('#brandName');
    const brandCategoryInput = $('#brandCategory');
    const brandModalLabel = $('#brandModalLabel');
    const saveBrandBtn = $('#saveBrandBtn');
    const addBrandBtn = $('#addBrandBtn');
    let currentBrandIdToDelete = null;

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    function showAlert(message, type = 'success') {
        Swal.fire({
            icon: type,
            title: type.charAt(0).toUpperCase() + type.slice(1),
            html: message,
            timer: 4000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
        });
    }

    function clearForm() {
        brandForm[0].reset();
        brandIdInput.val('');
        brandModalLabel.text('Add New Brand');
    }

    function renderBrands(brands) {
        const tbody = $('#brandTableBody');
        tbody.empty();
        if (brands.length === 0) {
            tbody.append('<tr><td colspan="5" class="text-center">No brands found.</td></tr>');
            return;
        }

        $.each(brands, function(index, brand) {
            const imageUrl = brand.file_path ? `/storage/${brand.file_path}` : '/images/default.png';
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${brand.name}</td>
                    <td>${brand.category.name}</td>
                    <td>${brand.file_path ? `<img src="${imageUrl}" width="50" height="50" style="object-fit:cover;cursor:pointer" class="file-preview" data-src="${imageUrl}">` : 'No Image'}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info edit-btn" data-id="${brand.id}"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${brand.id}" data-name="${brand.name}"><i class="fas fa-trash-alt"></i> Delete</button>
                    </td>
                </tr>`;
            tbody.append(row);
        });
    }

    function fetchBrands() {
        const sort = $('#sortBrands').val();
        $.get('{{ route("brands.index") }}', { sort }, function(data) {
            renderBrands(data);
        }).fail(() => showAlert('Failed to load brands.', 'error'));
    }

    $('#searchInput').on('keyup', function() {
        const search = $(this).val();
        $.get('{{ route("brands.index") }}', { search }, function(data) {
            renderBrands(data);
        }).fail(() => showAlert('Search failed.', 'error'));
    });

    saveBrandBtn.on('click', function(e) {
        e.preventDefault();
        const id = brandIdInput.val();
        const name = brandNameInput.val().trim();
        const category_id = brandCategoryInput.val();
        const file = $('#brandFile')[0]?.files[0];

        if (!name || !category_id) {
            showAlert('All fields are required.', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('name', name);
        formData.append('category_id', category_id);
        if (file) formData.append('file', file);
        if (id) formData.append('_method', 'PUT');

        $.ajax({
            url: id ? `/brands/${id}` : '{{ route("brands.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                showAlert(`Brand ${id ? 'updated' : 'added'} successfully!`, 'success');
                brandModal.hide();
                clearForm();
                fetchBrands();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorHtml = '';
                    $.each(errors, function(key, messages) {
                        errorHtml += `<div>${messages.join('<br>')}</div>`;
                    });
                    showAlert(errorHtml, 'error');
                } else {
                    showAlert('Failed to save brand.', 'error');
                }
            }
        });
    });

    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.get(`/brands/${id}`, function(brand) {
            brandIdInput.val(brand.id);
            brandNameInput.val(brand.name);
            brandCategoryInput.val(brand.category_id);
            brandModalLabel.text('Edit Brand');
            brandModal.show();
        }).fail(() => showAlert('Failed to fetch brand.', 'error'));
    });

    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: `Delete "${name}"?`,
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/brands/${id}`,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        showAlert('Brand deleted successfully!', 'success');
                        fetchBrands();
                    },
                    error: function() {
                        showAlert('Failed to delete brand.', 'error');
                    }
                });
            }
        });
    });

    addBrandBtn.on('click', function () {
        clearForm();
        brandModal.show(); // ✅ Manual modal open
    });

    $(document).on('click', '.file-preview', function () {
        $('#previewImage').attr('src', $(this).data('src'));
        new bootstrap.Modal($('#filePreviewModal')).show();
    });

    $('#sortBrands').on('change', fetchBrands);

    fetchBrands();
});
</script>
@endsection
