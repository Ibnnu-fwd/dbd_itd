<x-app-layout>
    <x-breadcrumb name="sample.detail-sample" :data="$sample" />

    <div class="flex flex-col gap-3 md:flex-row md:justify-end mb-4">
        {{-- <x-link-button route="{{ route('admin.sample.detail-sample.export', $sample->id) }}" class="justify-center"
            color="gray" type="button" target="_blank">
            Unduh Template Import
        </x-link-button> --}}
        {{-- <x-button id="btnImport" class="justify-center bg-primary" type="button">
            Import
        </x-button> --}}
    </div>

    <div class="sm:grid grid-cols-3 gap-x-4">
        @foreach ($sample->detailSampleViruses as $detailSample)
            <x-card-container class="mb-4 md:mb-0">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-xs 2xl:text-sm">
                        {{ $detailSample->virus->name }}
                    </h3>
                    <div class="sm:flex gap-x-2">
                        <x-icon-button onclick="confirmDelete({{ $detailSample->id }})" icon="fas fa-trash-alt"
                            class="bg-red-500" />
                        <x-icon-button
                            route="{{ $detailSample->detailSampleMorphotypes->count() > 0 && $detailSample->virus_id == 1 && $detailSample->identification == 1 ? route('admin.sample.detail-sample.virus.edit', $detailSample->id) : route('admin.sample.detail-sample.virus', $detailSample->id) }}"
                            icon="fas fa-arrow-right" color="gray" />
                    </div>
                </div>

                @if ($detailSample->virus_id == 1 && $detailSample->identification == 0)
                    <h3 class="text-xs 2xl:text-sm">
                        Total Individu: {{ $detailSample->amount }}
                    </h3>
                @elseif ($detailSample->virus_id == 1 && $detailSample->identification == 1)
                    @if ($detailSample->detailSampleMorphotypes->count() > 0)
                        <div class="xl:flex items-center justify-between text-xs 2xl:text-sm mt-5">
                            <h3 class="font-semibold">
                                Total Individu
                            </h3>
                            <span class="font-semibold">
                                {{
                                    $detailSample->detailSampleMorphotypes->sum('amount')
                                }}
                            </span>
                        </div>
                        <hr class="my-3">
                        <ul class="list-inside">
                            @foreach ($detailSample->detailSampleMorphotypes as $item)
                            <li class="text-xs 2xl:text-sm mb-2 flex justify-between items-center">
                                <span class="">{{ $item->morphotype->name }}</span>
                                <span class="font-semibold">{{ $item->amount }}</span>
                            </li>
                            @endforeach
                        </ul>
                        <br>
                        <h3 class="text-xs 2xl:text-sm font-semibold mt-2">
                            Detail Serotipe
                        </h3>
                        <hr class="my-3">
                        <ul class="list-inside">
                            @foreach ($sample->detailSampleSerotypes as $item)
                            <li class="text-xs 2xl:text-sm mb-2 flex justify-between items-center">
                                <span class="">{{ $item->serotype->name }}</span>
                                <span class="">{{ $item->status == 1 ? '✓' : '✕' }}</span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-4 text-xs 2xl:text-sm text-gray-800 rounded-lg bg-gray-100 mt-4" role="alert">
                            <span class="">Data Sampling Kosong</span>
                        </div>
                    @endif
                @elseif ($detailSample->virus_id != 1 && $detailSample->identification == null)
                    <h3 class="text-xs 2xl:text-sm">
                        Total Individu: {{ $detailSample->amount }}
                    </h3>
                @endif
            </x-card-container>
        @endforeach
    </div>

    <form action="{{ route('admin.sample.detail-sample.import') }}" method="POST" hidden id="formImport"
        enctype="multipart/form-data">
        @csrf
        <input type="sample_id" name="sample_id" value="{{ $sample->id }}">
        <input type="file" name="import_file" id="import_file">
        <button type="submit" id="btnSubmit"></button>
    </form>

    @push('js-internal')
        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan data ini!. Data yang terkait dengan data ini akan ikut terhapus",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.sample.detail-sample.virus.delete', ':id') }}".replace(':id',
                                id),
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'Tutup',
                                }).then((result) => {
                                    location.reload();
                                })
                            }
                        });
                    }
                })
            }

            $(function() {
                $('#btnImport').click(function() {
                    $('#import_file').click();

                    $('#import_file').change(function() {
                        Swal.fire({
                            title: 'Konfirmasi',
                            text: 'Apakah anda yakin ingin mengimpor data ini?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Tidak',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#btnSubmit').click();
                            }
                        });
                    });
                });

                $('#formImport').on('submit', function() {
                    Swal.fire({
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showConfirmButton: false,
                        title: 'Loading',
                        html: 'Mohon menunggu sebentar',
                        didOpen: () => {
                            Swal.showLoading()
                        },
                    });
                });

                @if (Session::has('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '{{ Session::get('success') }}'
                    })
                @endif

                @if (Session::has('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: '{{ Session::get('error') }}'
                    })
                @endif
            });
        </script>
    @endpush
</x-app-layout>
