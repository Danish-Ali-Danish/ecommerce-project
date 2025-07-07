@extends('admin.layout.app')

@section('content')
<div class="container dashboard-card ">
    <h2>Categories List</h2>

    <x-admin.search-sort 
        searchId="searchCategoryInput" 
        sortId="sortCategories" 
        placeholder="Search Categories by name..." 
        modalId="categoryModal" 
        addBtnId="addCategoryBtn" 
        addLabel="Add Category" 
    />

    <div id="alertContainer"></div>

    <div class="table-responsive d-flex justify-content-center gap-2">
        <table class="table table-striped table-hover">
            <thead class="table-dark ">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>File</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="categoryTableBody"></tbody>
        </table>
    </div>
</div>

@include('admin.categories.edit')

<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">File Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" class="img-fluid" alt="File Preview">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    const categoryTableBody = $('#categoryTableBody');
    const categoryModal = new bootstrap.Modal($('#categoryModal')[0]);
    const categoryForm = $('#categoryForm');
    const categoryIdInput = $('#categoryId');
    const categoryNameInput = $('#categoryName');
    const categoryModalLabel = $('#categoryModalLabel');
    const addCategoryBtn = $('#addCategoryBtn');
    const saveCategoryBtn = $('#saveCategoryBtn');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
        }
    });

    $('#sortCategories').on('change', fetchCategories);
    $('#searchCategoryInput').on('keyup', fetchCategories);

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
        categoryIdInput.val('');
        categoryForm[0].reset();
        categoryModalLabel.text('Add New Category');
    }

    function renderCategories(categories) {
        categoryTableBody.empty();
        if (categories.length === 0) {
            categoryTableBody.append('<tr><td colspan="4" class="text-center">No categories found.</td></tr>');
            return;
        }
        $.each(categories, (index, category) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${category.name}</td>
                    <td>${category.file_path ? `<img src="/storage/${category.file_path}" alt="${category.name}" width="50" height="50" style="object-fit:cover;cursor:pointer" class="file-preview" data-src="/storage/${category.file_path}">` : 'No File'}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info btn-action edit-btn" data-id="${category.id}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger btn-action delete-btn" data-id="${category.id}" data-name="${category.name}">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
            categoryTableBody.append(row);
        });
    }

    function fetchCategories() {
        const sort = $('#sortCategories').val();
        const search = $('#searchCategoryInput').val();

        $.ajax({
            url: '{{ route("categories.index") }}',
            method: 'GET',
            data: { sort, search },
            success: function (data) {
                renderCategories(data);
            },
            error: function () {
                showAlert('Error fetching categories.', 'error');
            }
        });
    }

    saveCategoryBtn.on('click', function () {
        const id = categoryIdInput.val();
        const name = categoryNameInput.val().trim();
        const file = $('#categoryFile')[0].files[0];

        if (!name) {
            showAlert('Category name is required.', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('name', name);
        if (file) formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        if (id) formData.append('_method', 'PUT');

        const url = id ? `/categories/${id}` : '{{ route("categories.store") }}';

        $.ajax({
            url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                showAlert('Category ' + (id ? 'updated' : 'added') + ' successfully!', 'success');
                categoryModal.hide();
                clearForm();
                fetchCategories();
            },
            error: function (xhr) {
                const errorMessage = xhr.responseJSON?.errors?.name?.[0] || xhr.responseJSON?.message || xhr.statusText;
                showAlert('Error saving category: ' + errorMessage, 'error');
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: `/categories/${id}`,
            method: 'GET',
            success: function (category) {
                categoryIdInput.val(category.id);
                categoryNameInput.val(category.name);
                categoryModalLabel.text('Edit Category');
                categoryModal.show();
            },
            error: function () {
                showAlert('Error fetching category for edit.', 'error');
            }
        });
    });

    $(document).on('click', '.delete-btn', function () {
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
                    url: `/categories/${id}`,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        showAlert('Category deleted successfully!', 'success');
                        fetchCategories();
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON?.message || 'Failed to delete category.';
                        showAlert(msg, 'error');
                    }
                });
            }
        });
    });

    addCategoryBtn.on('click', function () {
        clearForm();
        categoryModalLabel.text('Add New Category');
        categoryModal.show();
    });

    fetchCategories();
});

$(document).on('click', '.file-preview', function () {
    const src = $(this).data('src');
    $('#previewImage').attr('src', src);
    new bootstrap.Modal($('#filePreviewModal')).show();
});
</script>
@endsection