<div class="inline-flex gap-2">
    <button class="btn btn-sm btn-square rounded rounded-md !important">
        <i class="fas fa-edit fa-xs 2xl:fa-sm"></i>
    </button>
    <button onclick="btnDelete('{{ $data->id }}', '{{ $data->name }}')"
        class="btn btn-sm btn-square btn-error rounded rounded-md !important">
        <i class="fas fa-trash fa-xs 2xl:fa-sm"></i>
    </button>
</div>
