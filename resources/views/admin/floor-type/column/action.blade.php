<div class="inline-flex gap-2">
    <x-link-button route="{{ route('admin.floor-type.edit', $data->id) }}" color="gray">
        Ubah
    </x-link-button>
    <x-link-button onclick="btnDelete('{{ $data->id }}', '{{ $data->name }}')" color="red">
        Hapus
    </x-link-button>
</div>
