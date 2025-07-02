@extends('admin.layout.app')
@section('content')
<div class="container dashboard-card">
    <h2>Brand List</h2>
    <!-- <div class="d-flex justify-content-end mb-3">
     
    </div> -->

    <div id="alertContainer"></div>
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="">
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

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#brandModal" id="addBrandBtn">
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
            <tbody id="brandTableBody">
                <!-- Populated via JS -->
            </tbody>
        </table>
    </div>
</div>

@include('admin.brands.edit')
@include('admin.brands.delete')

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
<script>
$(document).ready(function() {
    const brandModal = new bootstrap.Modal($('#brandModal')[0]);
    const deleteBrandModal = new bootstrap.Modal($('#deleteBrandModal')[0]);

    const brandForm = $('#brandForm');
    const brandIdInput = $('#brandId');
    const brandNameInput = $('#brandName');
    const brandCategoryInput = $('#brandCategory');
    const brandModalLabel = $('#brandModalLabel');
    const saveBrandBtn = $('#saveBrandBtn');
    const addBrandBtn = $('#addBrandBtn');
    const confirmDeleteBtn = $('#confirmDeleteBrandBtn');
    const alertContainer = $('#alertContainer');
    const deleteBrandName = $('#deleteBrandName');
    $('#sortBrands').on('change', fetchBrands);
    
    let currentBrandIdToDelete = null;

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    function showAlert(message, type = 'success') {
        const alertDiv = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        alertContainer.html(alertDiv);
        setTimeout(() => $('.alert').alert('close'), 4000);
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
        } else {
            $.each(brands, function(index, brand) {
                const imageUrl = brand.file_path ? `/storage/${brand.file_path}` : '/images/default.png';
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${brand.name}</td>
                        <td>${brand.category.name}</td>
                        <td>${brand.file_path ? `<img src="${imageUrl}" alt="${brand.name}" width="50" height="50" style="object-fit:cover;cursor:pointer" class="file-preview" data-src="${imageUrl}">` : 'No Image'}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info edit-btn" data-id="${brand.id}"><i class="fas fa-edit"></i> Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${brand.id}" data-name="${brand.name}"><i class="fas fa-trash-alt"></i> Delete</button>
                        </td>
                    </tr>`;
                tbody.append(row);
            });
        }
    }

    function fetchBrands() {
    const sortOption = $('#sortBrands').val(); // Get selected sort option

    $.get('{{ route("brands.index") }}', { sort: sortOption }, function(data) {
        renderBrands(data);
    }).fail(function(xhr) {
        showAlert('Failed to load brands.', 'danger');
    });
    }$('#searchInput').on('keyup', function() {
        let search = $(this).val();
        $.ajax({
            url: "{{ route('brands.index') }}",
            method: "GET",
            data: { search: search },
            success: function(data) {
                renderBrands(data);
            },
            error: function() {
                alert('Search failed!');
            }
        });
    });



    saveBrandBtn.on('click', function(e) {
        e.preventDefault();
        const id = brandIdInput.val();
        const name = brandNameInput.val().trim();
        const category_id = brandCategoryInput.val();
        const file = $('#brandFile')[0].files[0];

        if (!name || !category_id) {
            showAlert('All fields are required.', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('name', name);
        formData.append('category_id', category_id);
        formData.append('_token', '{{ csrf_token() }}');
        if (file) formData.append('file', file);
        if (id) formData.append('_method', 'PUT');

        $.ajax({
            url: id ? `/brands/${id}` : '{{ route("brands.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showAlert('Brand ' + (id ? 'updated' : 'added') + ' successfully!');
                brandModal.hide();
                clearForm();
                fetchBrands();
            },
            error: function(xhr) {
                showAlert('Failed to save brand.', 'danger');
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
        }).fail(function() {
            showAlert('Failed to fetch brand.', 'danger');
        });
    });

    $(document).on('click', '.delete-btn', function() {
        currentBrandIdToDelete = $(this).data('id');
        deleteBrandName.text($(this).data('name'));
        deleteBrandModal.show();
    });

    confirmDeleteBtn.on('click', function() {
        if (currentBrandIdToDelete) {
            $.ajax({
                url: `/brands/${currentBrandIdToDelete}`,
                method: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    showAlert('Brand deleted successfully!');
                    deleteBrandModal.hide();
                    fetchBrands();
                },
                error: function() {
                    showAlert('Failed to delete brand.', 'danger');
                }
            });
        }
    });

    addBrandBtn.on('click', function() {
        clearForm();
    });

    $(document).on('click', '.file-preview', function() {
        const src = $(this).data('src');
        $('#previewImage').attr('src', src);
        new bootstrap.Modal($('#filePreviewModal')).show();
    });

    fetchBrands();
});
</script>
@endsection