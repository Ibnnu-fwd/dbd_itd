<x-app-layout>
    <x-breadcrumb name="sample.detail-sample.virus" :data="$sample" />
    <x-card-container>
        @if ($sample->virus_id == 1 && $sample->identification == 0)
            <div class="xl:grid grid-cols-3 gap-x-4 items-center">
                <x-select id="identification" name="identification" label="Status Identifikasi">
                    <option value="0" {{ $sample->identification == 0 ? 'selected' : '' }}>
                        Tidak
                    </option>
                    <option value="1" {{ $sample->identification == 1 ? 'selected' : '' }}>
                        Ya
                    </option>
                </x-select>
                <x-input id="amount" name="amount" label="Jumlah Individu" type="number" :value="$sample->amount" />
                <div class="mt-2">
                    <x-button class="bg-primary" type="submit" id="btnUpdate">
                        {{ __('Ubah Total Individu') }}
                    </x-button>
                </div>
            </div>
        @elseif ($sample->virus_id == 1 && $sample->identification == 1)
            <form action="{{ route('admin.sample.detail-sample.virus.store', $sample->id) }}" method="POST">
                @csrf
                <h3 class="mb-4 font-semibold text-gray-900 text-xs 2xl:text-sm">Detail Morfotipe</h3>
                <ul class="items-center w-full text-sm font-medium text-gray-900 rounded-lg xl:flex gap-x-2">
                    <li class="w-full">
                        <x-input id="morphotype_1" name="morphotype_1" label="Jumlah Morfotipe 1" type="number"
                            value="0" />
                    </li>
                    <li class="w-full">
                        <x-input id="morphotype_2" name="morphotype_2" label="Jumlah Morfotipe 2" type="number"
                            value="0" />
                    </li>
                    <li class="w-full">
                        <x-input id="morphotype_3" name="morphotype_3" label="Jumlah Morfotipe 3" type="number"
                            value="0" />
                    </li>
                    <li class="w-full">
                        <x-input id="morphotype_4" name="morphotype_4" label="Jumlah Morfotipe 4" type="number"
                            value="0" />
                    </li>
                    <li class="w-full">
                        <x-input id="morphotype_5" name="morphotype_5" label="Jumlah Morfotipe 5" type="number"
                            value="0" />
                    </li>
                    <li class="w-full">
                        <x-input id="morphotype_6" name="morphotype_6" label="Jumlah Morfotipe 6" type="number"
                            value="0" />
                    </li>
                    <li class="w-full">
                        <x-input id="morphotype_7" name="morphotype_7" label="Jumlah Morfotipe 7" type="number"
                            value="0" />
                    </li>
                </ul>

                <br>

                <h3 class="mb-4 font-semibold text-gray-900 text-xs 2xl:text-sm">Detail Serotipe</h3>
                <ul class="items-center w-full text-sm font-medium text-gray-900 rounded-lg xl:flex gap-x-2">
                    <li class="w-full">
                        <x-select id="denv_1" name="denv_1" label="DENV 1">
                            <option value="0">
                                Tidak
                            </option>
                            <option value="1">
                                Ya
                            </option>
                        </x-select>
                    </li>
                    <li class="w-full">
                        <x-select id="denv_2" name="denv_2" label="DENV 2">
                            <option value="0">
                                Tidak
                            </option>
                            <option value="1">
                                Ya
                            </option>
                        </x-select>
                    </li>
                    <li class="w-full">
                        <x-select id="denv_3" name="denv_3" label="DENV 3">
                            <option value="0">
                                Tidak
                            </option>
                            <option value="1">
                                Ya
                            </option>
                        </x-select>
                    </li>
                    <li class="w-full">
                        <x-select id="denv_4" name="denv_4" label="DENV 4">
                            <option value="0">
                                Tidak
                            </option>
                            <option value="1">
                                Ya
                            </option>
                        </x-select>
                    </li>
                </ul>

                <x-button class="bg-primary" type="submit">
                    {{ __('Simpan Data Sampling') }}
                </x-button>
            </form>
        @else
            <div class="xl:grid grid-cols-3 gap-x-4 items-center">
                <x-input id="amount" name="amount" label="Jumlah Individu" type="number" :value="$sample->amount" />
                <div class="mt-2">
                    <x-button class="bg-primary" type="submit" id="btnUpdate">
                        {{ __('Ubah Total Individu') }}
                    </x-button>
                </div>
            </div>
        @endif
    </x-card-container>

    @push('js-internal')
        <script>
            $(function() {
                $('#identification').change(function() {
                    let value = $(this).val();
                    if (value == 1) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Apakah anda yakin ingin mengubah status identifikasi menjadi "Ya" ? Jika iya, maka status identifikasi tidak dapat diubah kembali.',
                            showCancelButton: true,
                            confirmButtonText: `Ya`,
                            cancelButtonText: `Tidak`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('admin.sample.detail-sample.virus.update-identification', ':id') }}"
                                        .replace(':id', "{{ $sample->id }}"),
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        identification: value
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: 'Status identifikasi berhasil diubah.',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.reload();
                                            }
                                        });
                                    }
                                });
                            }
                        })
                    }
                });

                $('#btnUpdate').click(function(e) {
                    e.preventDefault();
                    let amount = $('#amount').val();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.sample.detail-sample.virus.update-single-amount', ':id') }}"
                            .replace(':id', "{{ $sample->id }}"),
                        data: {
                            _token: "{{ csrf_token() }}",
                            amount: amount
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Total individu berhasil diubah.',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
