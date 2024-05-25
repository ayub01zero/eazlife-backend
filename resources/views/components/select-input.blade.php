@props(['disabled' => false, 'options' => [], 'selectedOptions' => []])

@php
    $attributes = $attributes->class(['w-full bg-gray-700 text-white border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm']);
@endphp

<select {{ $attributes }} {{ $disabled ? 'disabled' : '' }}
    @if (isset($attributes['multiple']) && $attributes['multiple']) onmousedown="toggleOption(event)" @endif>
    @foreach ($options as $id => $option)
        @php
            $isSelected = in_array($id, $selectedOptions);
        @endphp
        <option class="select-multiple-option py-1 px-3 -ml-3 -mr-3 {{ $isSelected ? 'bg-primary-500 text-white' : '' }}"
            value="{{ $id }}" {{ $isSelected ? 'selected' : '' }}>
            {{ __($option) }}
        </option>
    @endforeach
</select>

@if (isset($attributes['multiple']) && $attributes['multiple'])
    <script>
        function toggleOption(event) {
            if (!/Mobi/.test(navigator.userAgent)) {
                event.preventDefault();

                const option = event.target;
                if (option.tagName === 'OPTION') {
                    option.selected = !option.selected;
                    if (option.selected) {
                        option.classList.add('bg-primary-500');
                        option.classList.add('text-white');
                    } else {
                        option.classList.remove('bg-primary-500');
                        option.classList.remove('text-white');
                    }
                }
                var el = event.target;

                var scrollTop = el.parentNode.scrollTop;
                setTimeout(() => el.parentNode.scrollTo(0, scrollTop), 0);
            }
        }
    </script>
@endif
