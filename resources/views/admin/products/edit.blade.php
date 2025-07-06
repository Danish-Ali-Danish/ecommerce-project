<!-- resources/views/admin/products/edit.blade.php -->

@includeWhen(isset($categories) && isset($brands), 'admin.products._form')
