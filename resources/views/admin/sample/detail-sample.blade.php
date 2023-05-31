<x-app-layout>
    <x-breadcrumb name="sample.detail-sample" :data="$sample" />
    <div class="sm:grid grid-cols-3 gap-x-4">
        @foreach ($sample->detailSampleViruses as $detailSample)
            <x-card-container>
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-xs 2xl:text-sm">
                        {{ $detailSample->virus->name }}
                    </h3>
                    <div class="sm:flex gap-x-2">
                        <x-icon-button onclick="confirmDelete({{ $detailSample->id }})" icon="fas fa-trash-alt"
                            class="bg-red-500" />
                        <x-icon-button route="{{ route('admin.sample.detail-sample.virus', $detailSample->id) }}"
                            icon="fas fa-arrow-right" color="gray" />
                    </div>
                </div>

                <!-- Detail Sample Morphotyp List -->
                @if ($detailSample->detailSampleMorphotypes->count() > 0)
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xs 2xl:text-sm">Jumlah Morfotipe</span>
                        <span class=text-xs
                            2xl:text-sm">{{ $detailSample->detailSampleMorphotypes->sum('amount') }}</span>
                    </div>
                    <ul class="list-inside text-xs 2xl:text-sm mt-4">
                        @foreach ($detailSample->detailSampleMorphotypes as $detailSampleMorphotype)
                            <li class="list-inside mt-2 sm:flex justify-between items-center">
                                {{ $detailSampleMorphotype->morphotype->name }}
                                <!-- badge -->
                                <span class="text-xs 2xl:text-sm">{{ $detailSampleMorphotype->amount }}</span>
                                <!-- list detail serotype -->
                            </li>
                            @foreach ($detailSampleMorphotype->detailSampleSerotypes as $detailSampleSerotype)
                                <li class="list-inside mt-2 sm:flex justify-between items-center">
                                    {{ $detailSampleSerotype->serotype->name }}
                                    <!-- badge -->
                                    <span class="text-xs 2xl:text-sm">{{ $detailSampleSerotype->amount }}</span>
                                </li>
                            @endforeach
                            <!-- border -->
                            @if (!$loop->last)
                                <hr class="my-2">
                            @endif
                        @endforeach
                    </ul>
                @else
                    <div class="p-4 text-xs 2xl:text-sm text-gray-800 rounded-lg bg-gray-100 mt-4" role="alert">
                        <i class="fas fa-info-circle mr-2 fa-lg text-gray-800"></i> Tidak ada data
                    </div>
                @endif
            </x-card-container>
        @endforeach
    </div>

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
        </script>
    @endpush
</x-app-layout>
