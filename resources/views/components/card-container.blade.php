<div {!! $attributes->merge([
    'class' => 'block w-full p-6 bg-white border border-gray-200 rounded-lg shadow-sm mb-4 h-fit',
]) !!}>
    {{ $slot }}
</div>
