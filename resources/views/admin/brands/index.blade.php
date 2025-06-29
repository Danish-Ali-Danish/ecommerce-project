@extends('layout.app')
@section('content')
<div class="container dashboard-card">
    <h2>Brand List</h2>
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#brandModal" id="addBrandBtn">
            <i class="fas fa-plus-circle"></i> Add Brand
        </button>
    </div>

    <div id="alertContainer"></div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="brandTableBody">
                <!-- AJAX will populate here -->
            </tbody>
        </table>
    </div>
</div>

{{-- Brand Modal (Add/Edit) --}}
@include('brands.edit') {{-- you will create a file brands/edit.blade.php --}}
{{-- Delete Modal --}}
@include('brands.delete') {{-- you will create a file brands/delete.blade.php --}}
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

    let currentBrandIdToDelete = null;

    // CSRF setup
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
            tbody.append('<tr><td colspan="4" class="text-center">No brands found.</td></tr>');
        } else {
            $.each(brands, function(index, brand) {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${brand.name}</td>
                        <td>${brand.category.name}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info edit-btn" data-id="${brand.id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${brand.id}" data-name="${brand.name}">Delete</button>
                        </td>
                    </tr>`;
                tbody.append(row);
            });
        }
    }

    function fetchBrands() {
        $.get('{{ route("brands.index") }}', function(data) {
            renderBrands(data);
        }).fail(function(xhr) {
            showAlert('Failed to load brands.', 'danger');
        });
    }

    // Save (Add/Update)
    saveBrandBtn.on('click', function(e) {
        e.preventDefault();
        const id = brandIdInput.val();
        const name = brandNameInput.val().trim();
        const category_id = brandCategoryInput.val();

        if (!name || !category_id) {
            showAlert('All fields are required.', 'warning');
            return;
        }

        const formData = {
            name: name,
            category_id: category_id,
            _token: '{{ csrf_token() }}'
        };

        if (id) formData._method = 'PUT';

        $.ajax({
            url: id ? `/brands/${id}` : '{{ route("brands.store") }}',
            method: 'POST',
            data: formData,
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

    // Initial load
    fetchBrands();
});
</script>
@endsection
