@extends('layout.app')
@section('content')

{{-- The main content area. The app-main class in app.blade.php handles the margin and padding. --}}
{{-- Using 'container' for Bootstrap's responsive fixed-width behavior, but overall layout handled by app-main --}}
<div class="container dashboard-card ">
    <h2>Categories List</h2>
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal" id="addCategoryBtn">
            <i class="fas fa-plus-circle"></i> Add New Category
        </button>
    </div>

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
            <tbody id="categoryTableBody">
                </tbody>
        </table>
    </div>
</div>
{{-- Category Modal (Add/Edit) --}}
@include('categories.edit')
{{-- Delete Confirmation Modal --}}
@include('categories.delete')


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

@section('scripts') {{-- Using @section('scripts') for page-specific JS --}}
    <script>
        $(document).ready(function() {
            const categoryTableBody = $('#categoryTableBody');
            // Initialize Bootstrap Modals using the JavaScript API for proper control
            const categoryModal = new bootstrap.Modal($('#categoryModal')[0]);
            const deleteConfirmModal = new bootstrap.Modal($('#deleteConfirmModal')[0]);
            const categoryForm = $('#categoryForm');
            const categoryIdInput = $('#categoryId');
            const categoryNameInput = $('#categoryName');
            const categoryModalLabel = $('#categoryModalLabel');
            const addCategoryBtn = $('#addCategoryBtn');
            const saveCategoryBtn = $('#saveCategoryBtn');
            const confirmDeleteBtn = $('#confirmDeleteBtn');
            const alertContainer = $('#alertContainer');
            const deleteCategoryName = $('#deleteCategoryName');

            let currentCategoryIdToDelete = null;

            // Set up CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
                }
            });

            // --- Utility Functions ---
            function showAlert(message, type = 'success') {
                const alertDiv = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                alertContainer.html(''); // Clear previous alerts
                alertContainer.append(alertDiv);
                setTimeout(() => {
                    alertContainer.find('.alert').alert('close');
                }, 5000); // Auto-close after 5 seconds
            }

            function clearForm() {
                categoryIdInput.val('');
                categoryForm[0].reset(); // Reset native form element
                categoryModalLabel.text('Add New Category');
            }

            function renderCategories(categories) {
                categoryTableBody.empty(); // Clear existing rows
                if (categories.length === 0) {
                    categoryTableBody.append('<tr><td colspan="4" class="text-center">No categories found.</td></tr>');
                    return;
                }
                $.each(categories, (index, category) => {
                    const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${category.name}</td>
                            <td>${category.file_path ? `<img src="/storage/${category.file_path}" alt="${category.name}" width="50" height="50" style="object-fit:cover;cursor:pointer" class="file-preview" data-src="/storage/${category.file_path}">` : 'No File'}
                            </td>

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

            // --- CRUD Operations (AJAX Calls) ---

            // Fetch Categories
            function fetchCategories() {
                    $.ajax
            ({
                        url: '{{ route("categories.index") }}',
                        method: 'GET',
                        success: function(data) {
                            renderCategories(data);
                        },
                        error: function(xhr) {
                            showAlert('Error fetching categories: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText), 'danger');
                            console.error('Error fetching categories:', xhr);
                        }
            });
}

            // Save Category (Add or Update)
            saveCategoryBtn.on('click', function() {
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
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            showAlert('Category ' + (id ? 'updated' : 'added') + ' successfully!', 'success');
            categoryModal.hide();
            clearForm();
            fetchCategories();
        },
        error: function(xhr) {
            const errorMessage = xhr.responseJSON?.errors?.name?.[0] || xhr.responseJSON?.message || xhr.statusText;
            showAlert('Error saving category: ' + errorMessage, 'danger');
            console.error('Error saving category:', xhr);
        }
    });
});

            // Edit Category: Event delegation for dynamically added elements
           // Edit Category
$(document).on('click', '.edit-btn', function() {
    const id = $(this).data('id');
    $.ajax({
        url: `/categories/${id}`,
        method: 'GET',
        success: function(category) {
            categoryIdInput.val(category.id);
            categoryNameInput.val(category.name);
            categoryModalLabel.text('Edit Category');
            categoryModal.show();
        },
        error: function(xhr) {
            showAlert('Error fetching category for edit: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText), 'danger');
            console.error('Error fetching category for edit:', xhr);
        }
    });
});

            // Prepare Delete Confirmation Modal: Event delegation
            $(document).on('click', '.delete-btn', function() {
                currentCategoryIdToDelete = $(this).data('id');
                const categoryNameToDelete = $(this).data('name');
                deleteCategoryName.text(categoryNameToDelete); // Display category name in the confirmation modal
                deleteConfirmModal.show(); // Show the delete confirmation modal
            });

            // Delete Category
            confirmDeleteBtn.on('click', function() {
    const idToDelete = currentCategoryIdToDelete;
    if (idToDelete) {
        $.ajax({
            url: `/categories/${idToDelete}`,
            method: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                showAlert(response.message || 'Category deleted!', 'success');
                deleteConfirmModal.hide();
                fetchCategories();
                currentCategoryIdToDelete = null;
            },
            error: function(xhr) {
                showAlert('Error deleting category: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText), 'danger');
                console.error('Error deleting category:', xhr);
            }
        });
    } else {
        showAlert('No category selected for deletion.', 'warning');
        deleteConfirmModal.hide();
    }
});
            // Event Listener for Add Category button (clears form and resets title)
            addCategoryBtn.on('click', function() {
                clearForm();
                categoryModalLabel.text('Add New Category'); // Ensure title is correct for new
            });

            // Initial load of categories when the page is ready
            fetchCategories();
        });

        $(document).on('click', '.file-preview', function()     
        {
            const src = $(this).data('src');
            $('#previewImage').attr('src', src);
            new bootstrap.Modal($('#filePreviewModal')).show();
        });

    </script>
@endsection