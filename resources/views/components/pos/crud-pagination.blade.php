@props(['paginator', 'class' => 'mt-6'])

@if ($paginator->hasPages())
    <div class="{{ $class }}">
        {{ $paginator->links() }}
    </div>
@endif
