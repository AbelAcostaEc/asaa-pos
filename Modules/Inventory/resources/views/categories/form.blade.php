<form action="{{ $category ? route('inventory.categories.update', $category) : route('inventory.categories.store') }}" method="POST" id="categoryForm">
    @csrf
    @if($category) @method('PUT') @endif
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control" value="{{ $category ? $category->name : '' }}" required>
    </div>
    <div class="form-group">
        <label for="code">Code</label>
        <input type="text" name="code" class="form-control" value="{{ $category ? $category->code : '' }}">
    </div>
    <button type="submit" class="btn btn-primary">{{ $category ? 'Update' : 'Create' }}</button>
</form>