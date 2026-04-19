@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Unit</h1>
    <form action="{{ route('inventory.units.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="abbreviation">Abbreviation</label>
            <input type="text" name="abbreviation" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection