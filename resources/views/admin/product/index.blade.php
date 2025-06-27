@extends('layout.app')
@section('content')

<div class="container dashboard-card">
    <h2>Products List</h2>
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal" id="addProductBtn">
            <i class="fas fa-plus-circle"></i> Add New Product
        </button>
    </div>

    <div id="alertContainer"></div>

    <div class="table-responsive d-flex justify-content-center gap-2">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Image</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
            </tbody>
        </table>
    </div>
</div>

{{-- Product Modal (Add/Edit) --}}
@include('products.edit')

{{-- Delete Confirmation Modal --}}
@include('products.delete')

{{-- File Preview Modal --}}
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
        const productTableBody = $('#productTableBody');
        const productModal = new bootstrap.Modal($('#productModal')[0]);
        const deleteConfirmModal = new bootstrap.Modal($('#deleteConfirmModal')[0]);
        const productForm = $('#productForm');
        const productIdInput = $('#productId');
        const productNameInput = $('#productName');
        const productDescriptionInput = $('#productDescription');
        const productPriceInput = $('#productPrice');
        const productStockInput = $('#productStock');
        const productCategoryInput = $('#productCategory');
        const productBrandInput = $('#productBrand');
        const productModalLabel = $('#productModalLabel');
        const addProductBtn = $('#addProductBtn');
        const saveProductBtn = $('#saveProductBtn');
        const confirmDeleteBtn = $('#confirmDeleteBtn');
        const alertContainer = $('#alertContainer');
        const deleteProductName = $('#deleteProductName');

        let currentProductIdToDelete = null;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
            }
        });

        function showAlert(message, type = 'success') {
            const alertDiv = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            alertContainer.html('');
            alertContainer.append(alertDiv);
            setTimeout(() => {
                alertContainer.find('.alert').alert('close');
            }, 5000);
        }

        function clearForm() {
            productIdInput.val('');
            productForm[0].reset();
            productModalLabel.text('Add New Product');
        }

        function renderProducts(products) {
            productTableBody.empty();
            if (products.length === 0) {
                productTableBody.append('<tr><td colspan="9" class="text-center">No products found.</td></tr>');
                return;
            }
            $.each(products, (index, product) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${product.name}</td>
                        <td>${product.description || '-'}</td>
                        <td>${product.price}</td>
                        <td>${product.stock}</td>
                        <td>${product.category ? product.category.name : '-'}</td>
                        <td>${product.brand ? product.brand.name : '-'}</td>
                        <td>${product.file_path ? `<img src="/storage/${product.file_path}" alt="${product.name}" width="50" height="50" style="object-fit:cover;cursor:pointer" class="file-preview" data-src="/storage/${product.file_path}">` : 'No Image'}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info btn-action edit-btn" data-id="${product.id}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger btn-action delete-btn" data-id="${product.id}" data-name="${product.name}">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </td>
                    </tr>
                `;
                productTableBody.append(row);
            });
        }

        function loadCategories() {
            $.ajax({
                url: '{{ route("categories.index") }}',
                method: 'GET',
                success: function(categories) {
                    let options = '';
                    $.each(categories, (index, category) => {
                        options += `<option value="${category.id}">${category.name}</option>`;
                    });
                    productCategoryInput.html(options);
                }
            });
        }

        function loadBrands() {
            $.ajax({
                url: '{{ route("brands.index") }}',
                method: 'GET',
                success: function(brands) {
                    let options = '';
                    $.each(brands, (index, brand) => {
                        options += `<option value="${brand.id}">${brand.name}</option>`;
                    });
                    productBrandInput.html(options);
                }
            });
        }

        function fetchProducts() {
            $.ajax({
                url: '{{ route("products.index") }}',
                method: 'GET',
                success: function(data) {
                    renderProducts(data);
                },
                error: function(xhr) {
                    showAlert('Error fetching products: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText), 'danger');
                    console.error('Error fetching products:', xhr);
                }
            });
        }

        saveProductBtn.on('click', function() {
            const id = productIdInput.val();
            const name = productNameInput.val().trim();
            const description = productDescriptionInput.val().trim();
            const price = productPriceInput.val();
            const stock = productStockInput.val();
            const categoryId = productCategoryInput.val();
            const brandId = productBrandInput.val();
            const file = $('#productFile')[0].files[0];

            if (!name || !price || !stock || !categoryId || !brandId) {
                showAlert('Please fill all required fields.', 'warning');
                return;
            }

            const formData = new FormData();
            formData.append('name', name);
            formData.append('description', description);
            formData.append('price', price);
            formData.append('stock', stock);
            formData.append('category_id', categoryId);
            formData.append('brand_id', brandId);
            if (file) formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            if (id) formData.append('_method', 'PUT');

            const url = id ? `/products/${id}` : '{{ route("products.store") }}';

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    showAlert('Product ' + (id ? 'updated' : 'added') + ' successfully!', 'success');
                    productModal.hide();
                    clearForm();
                    fetchProducts();
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON?.errors?.name?.[0] || xhr.responseJSON?.message || xhr.statusText;
                    showAlert('Error saving product: ' + errorMessage, 'danger');
                    console.error('Error saving product:', xhr);
                }
            });
        });

        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            $.ajax({
                url: `/products/${id}`,
                method: 'GET',
                success: function(product) {
                    productIdInput.val(product.id);
                    productNameInput.val(product.name);
                    productDescriptionInput.val(product.description);
                    productPriceInput.val(product.price);
                    productStockInput.val(product.stock);
                    productCategoryInput.val(product.category_id);
                    productBrandInput.val(product.brand_id);
                    productModalLabel.text('Edit Product');
                    productModal.show();
                },
                error: function(xhr) {
                    showAlert('Error fetching product for edit: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText), 'danger');
                    console.error('Error fetching product for edit:', xhr);
                }
            });
        });

        $(document).on('click', '.delete-btn', function() {
            currentProductIdToDelete = $(this).data('id');
            const productNameToDelete = $(this).data('name');
            deleteProductName.text(productNameToDelete);
            deleteConfirmModal.show();
        });

        confirmDeleteBtn.on('click', function() {
            const idToDelete = currentProductIdToDelete;
            if (idToDelete) {
                $.ajax({
                    url: `/products/${idToDelete}`,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        showAlert(response.message || 'Product deleted!', 'success');
                        deleteConfirmModal.hide();
                        fetchProducts();
                        currentProductIdToDelete = null;
                    },
                    error: function(xhr) {
                        showAlert('Error deleting product: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText), 'danger');
                        console.error('Error deleting product:', xhr);
                    }
                });
            } else {
                showAlert('No product selected for deletion.', 'warning');
                deleteConfirmModal.hide();
            }
        });

        addProductBtn.on('click', function() {
            clearForm();
            loadCategories(); 
            loadBrands();
            productModalLabel.text('Add New Product');
        });

        fetchProducts();

        $(document).on('click', '.file-preview', function() {
            const src = $(this).data('src');
            $('#previewImage').attr('src', src);
            new bootstrap.Modal($('#filePreviewModal')).show();
        });
    });
</script>
@endsection
