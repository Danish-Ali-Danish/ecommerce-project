@extends('admin.layout.app')

@section('content')
<div class="container dashboard-card">
    <h2>Product List</h2>

    <div id="alertContainer"></div>
    <x-admin.search-sort 
    searchId="searchProductInput" 
    sortId="sortProducts" 
    modalId="productModal" 
    addBtnId="addProductBtn" 
    addLabel="Add Product"
    placeholder="Search products..."
/>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="productTableBody"></tbody>
        </table>
    </div>
</div>

@include('admin.products.edit')
@include('admin.products.delete')

<!-- Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" class="img-fluid" alt="Product Image">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    const productModal = new bootstrap.Modal($('#productModal')[0]);
    const deleteProductModal = new bootstrap.Modal($('#deleteProductModal')[0]);
    const alertContainer = $('#alertContainer');
    const productForm = $('#productForm');
    const productIdInput = $('#productId');
    let currentProductIdToDelete = null;

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    function showAlert(message, type = 'success') {
        const html = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        alertContainer.html(html);
        setTimeout(() => $('.alert').alert('close'), 4000);
    }

    function clearForm() {
        productForm[0].reset();
        productIdInput.val('');
        $('#productModalLabel').text('Add New Product');
    }

    function renderProducts(products) {
        const tbody = $('#productTableBody');
        tbody.empty();
        if (products.length === 0) {
            tbody.append('<tr><td colspan="7" class="text-center">No products found.</td></tr>');
            return;
        }

        products.forEach((product, index) => {
            const img = product.image ? `/storage/${product.image}` : '/images/default.png';
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${product.name}</td>
                    <td>${product.category?.name ?? '—'}</td>
                    <td>${product.brand?.name ?? '—'}</td>
                    <td>$${product.price.toFixed(2)}</td>
                    <td>
                        <img src="${img}" width="50" height="50" class="file-preview" data-src="${img}" style="cursor:pointer;object-fit:cover">
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info edit-btn" data-id="${product.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${product.id}" data-name="${product.name}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    function fetchProducts() {
        const sort = $('#sortProducts').val();
        const search = $('#searchProductInput').val();
        $.get('{{ route("products.index") }}', { sort, search }, renderProducts)
         .fail(() => showAlert('Failed to fetch products', 'danger'));
    }

    $('#sortProducts, #searchProductInput').on('change keyup', fetchProducts);

    $('#addProductBtn').on('click', function () {
        clearForm();
        productModal.show();
    });

    $('#saveProductBtn').on('click', function (e) {
        e.preventDefault();
        const id = $('#productId').val();
        const formData = new FormData(productForm[0]);
        if (id) formData.append('_method', 'PUT');

        const url = id ? `admin/products/${id}` : '{{ route("products.store") }}';

        $.ajax({
            url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                showAlert(`Product ${id ? 'updated' : 'added'} successfully!`);
                productModal.hide();
                clearForm();
                fetchProducts();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let messages = '';
                    $.each(errors, (k, v) => messages += v.join('<br>') + '<br>');
                    showAlert(messages, 'danger');
                } else {
                    showAlert('Error saving product', 'danger');
                }
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        $.get(`/products/${id}`, function (product) {
            $('#productId').val(product.id);
            $('#productName').val(product.name);
            $('#productPrice').val(product.price);
            $('#productComparePrice').val(product.compare_price);
            $('#productStock').val(product.stock);
            $('#productShortDescription').val(product.short_description);
            $('#productDescription').val(product.description);
            $('#productCategory').val(product.category_id);
            $('#productBrand').val(product.brand_id);
            $('#productModalLabel').text('Edit Product');
            productModal.show();
        }).fail(() => showAlert('Failed to load product data', 'danger'));
    });

    $(document).on('click', '.delete-btn', function () {
        currentProductIdToDelete = $(this).data('id');
        $('#deleteProductName').text($(this).data('name'));
        deleteProductModal.show();
    });

    $('#confirmDeleteProductBtn').on('click', function () {
        if (!currentProductIdToDelete) return;

        $.ajax({
            url: `/products/${currentProductIdToDelete}`,
            method: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                showAlert('Product deleted successfully!');
                deleteProductModal.hide();
                fetchProducts();
                currentProductIdToDelete = null;
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Error deleting product.';
                showAlert(msg, 'danger');
            }
        });
    });

    $(document).on('click', '.file-preview', function () {
        $('#previewImage').attr('src', $(this).data('src'));
        new bootstrap.Modal($('#filePreviewModal')).show();
    });

    fetchProducts();
});
</script>
@endsection
