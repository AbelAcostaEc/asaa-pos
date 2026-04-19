@props(['name', 'label' => null, 'options' => []])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
        </label>
    @endif
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        x-init="$nextTick(() => {
            const validOptions = Array.from($el.options).filter(option => option.value !== '');
            if (($el.value === '' || $el.value == null) && validOptions.length === 1) {
                $el.value = validOptions[0].value;
                $el.dispatchEvent(new Event('change', { bubbles: true }));
            }
        })"
        {{ $attributes->merge(['class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white']) }}
    >
        @foreach($options as $value => $label)
            <option value="{{ $value }}">
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>
