<form action="{{ $product ? route('inventory.products.update', $product) : route('inventory.products.store') }}" method="POST" id="productForm">
    @csrf
    @if($product) @method('PUT') @endif
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control" value="{{ $product ? $product->name : '' }}" required>
    </div>
    <div class="form-group">
        <label for="code">Code</label>
        <input type="text" name="code" class="form-control" value="{{ $product ? $product->code : '' }}" required>
    </div>
    <div class="form-group">
        <label for="category_id">Category</label>
        <select name="category_id" class="form-control" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $product && $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="unit_id">Unit</label>
        <select name="unit_id" class="form-control" required>
            <option value="">Select Unit</option>
            @foreach($units as $unit)
            <option value="{{ $unit->id }}" {{ $product && $product->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="price">Price</label>
        <input type="number" step="0.01" name="price" class="form-control" value="{{ $product ? $product->price : '' }}" required>
    </div>
    <div class="form-group">
        <label for="image">Image URL</label>
        <input type="url" name="image" class="form-control" value="{{ $product ? $product->image : '' }}">
    </div>
    <div class="form-group">
        <label for="stock">Stock</label>
        <input type="number" step="0.01" name="stock" class="form-control" value="{{ $product ? $product->stock : '' }}">
    </div>
    <button type="submit" class="btn btn-primary">{{ $product ? 'Update' : 'Create' }}</button>
</form>