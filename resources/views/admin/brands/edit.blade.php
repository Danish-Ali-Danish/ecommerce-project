<div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="brandForm">
      @csrf
      <input type="hidden" id="brandId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="brandModalLabel">Add New Brand</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="brandName" class="form-label">Brand Name</label>
            <input type="text" id="brandName" class="form-control" name="name" required>
          </div>
          <div class="mb-3">
            <label for="brandCategory" class="form-label">Select Category</label>
            <select id="brandCategory" name="category_id" class="form-control" required>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" id="saveBrandBtn">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>
