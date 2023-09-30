@props(['height' => 'h-fit', 'id' => ''])
<div id="{{ $id }}" {!! $attributes->merge([
    'class' => 'block w-full p-6 bg-white border border-gray-200 rounded-lg shadow-sm ' . $height,
]) !!}>
    {{ $slot }}
</div>
