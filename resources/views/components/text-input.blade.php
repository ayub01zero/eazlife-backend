@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'w-full bg-gray-700 text-white border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm',
]) !!}>
