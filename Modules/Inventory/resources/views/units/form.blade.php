<form action="{{ $unit ? route('inventory.units.update', $unit) : route('inventory.units.store') }}" method="POST" id="unitForm">
    @csrf
    @if($unit) @method('PUT') @endif
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control" value="{{ $unit ? $unit->name : '' }}" required>
    </div>
    <div class="form-group">
        <label for="abbreviation">Abbreviation</label>
        <input type="text" name="abbreviation" class="form-control" value="{{ $unit ? $unit->abbreviation : '' }}">
    </div>
    <button type="submit" class="btn btn-primary">{{ $unit ? 'Update' : 'Create' }}</button>
</form>