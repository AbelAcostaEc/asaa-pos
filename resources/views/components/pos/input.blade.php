@props(['disabled' => false, 'error' => null, 'label' => null])

<div>
    @if($label)
        <label {{ $attributes->only('id')->merge(['class' => 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1']) }}>
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-primary focus:ring-primary shadow-sm transition-all duration-200 ' . ($error ? 'border-danger focus:border-danger focus:ring-danger' : 'border-gray-300 dark:border-gray-700 focus:border-primary focus:ring-primary')]) !!}>
        
        @if($error)
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="h-5 w-5 text-danger" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif
    </div>

    @if($error)
        <p class="mt-1 text-sm text-danger">{{ $error }}</p>
    @endif
</div>
