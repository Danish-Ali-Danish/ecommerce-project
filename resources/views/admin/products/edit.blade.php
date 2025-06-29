<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="productForm" enctype="multipart/form-data">
                    <input type="hidden" id="productId">

                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="productName" placeholder="Enter product name" required>
                    </div>

                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="productDescription" rows="3" placeholder="Enter product description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Price <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="productPrice" placeholder="Enter product price" required>
                    </div>

                    <div class="mb-3">
                        <label for="productStock" class="form-label">Stock <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="productStock" placeholder="Enter stock quantity" required>
                    </div>

                    <div class="mb-3">
                        <label for="productCategory" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="productCategory" required>
                            <option value="">Select Category</option>
                            {{-- Categories will be loaded dynamically --}}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="productBrand" class="form-label">Brand <span class="text-danger">*</span></label>
                        <select class="form-select" id="productBrand" required>
                            <option value="">Select Brand</option>
                            {{-- Brands will be loaded dynamically --}}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="productFile" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="productFile" accept="image/*">
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveProductBtn">Save Product</button>
            </div>
        </div>
    </div>
</div>
