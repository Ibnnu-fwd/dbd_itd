<x-app-layout>
    <x-breadcrumb name="sample.detail-sample.virus.edit" :data="$sample" />
    <x-card-container>
        <form action="{{ route('admin.sample.detail-sample.virus.update', $sample->id) }}" method="POST">
            @csrf
            <h3 class="mb-4 font-semibold text-gray-900 text-sm">Detail Morfotipe</h3>
            <ul class="items-center w-full text-sm font-medium text-gray-900 rounded-lg xl:flex gap-x-2">
                <li class="w-full">
                    <x-input id="morphotype_1" name="morphotype_1" label="Jumlah Morfotipe 1" type="number"
                        :value="$morphotypes[0]->amount" />
                </li>
                <li class="w-full">
                    <x-input id="morphotype_2" name="morphotype_2" label="Jumlah Morfotipe 2" type="number"
                        :value="$morphotypes[1]->amount" />
                </li>
                <li class="w-full">
                    <x-input id="morphotype_3" name="morphotype_3" label="Jumlah Morfotipe 3" type="number"
                        :value="$morphotypes[2]->amount" />
                </li>
                <li class="w-full">
                    <x-input id="morphotype_4" name="morphotype_4" label="Jumlah Morfotipe 4" type="number"
                        :value="$morphotypes[3]->amount" />
                </li>
                <li class="w-full">
                    <x-input id="morphotype_5" name="morphotype_5" label="Jumlah Morfotipe 5" type="number"
                        :value="$morphotypes[4]->amount" />
                </li>
                <li class="w-full">
                    <x-input id="morphotype_6" name="morphotype_6" label="Jumlah Morfotipe 6" type="number"
                        :value="$morphotypes[5]->amount" />
                </li>
                <li class="w-full">
                    <x-input id="morphotype_7" name="morphotype_7" label="Jumlah Morfotipe 7" type="number"
                        :value="$morphotypes[6]->amount" />
                </li>
                <li class="w-full">
                    <x-input id="unidentified" name="unidentified" label="Unidentified" type="number"
                        :value="$morphotypes[7]->amount ?? 0" />
                </li>
            </ul>

            <br>

            <h3 class="mb-4 font-semibold text-gray-900 text-sm">Detail Serotipe</h3>
            <ul class="items-center w-full text-sm font-medium text-gray-900 rounded-lg xl:flex gap-x-2">
                <li class="w-full">
                    <x-select id="denv_1" name="denv_1" label="DENV 1">
                        <option value="0" {{ $serotypes[0]->status == 0 ? 'selected' : '' }}>
                            Tidak
                        </option>
                        <option value="1" {{ $serotypes[0]->status == 1 ? 'selected' : '' }}>
                            Ya
                        </option>
                    </x-select>
                </li>
                <li class="w-full">
                    <x-select id="denv_2" name="denv_2" label="DENV 2">
                        <option value="0" {{ $serotypes[1]->status == 0 ? 'selected' : '' }}>
                            Tidak
                        </option>
                        <option value="1" {{ $serotypes[1]->status == 1 ? 'selected' : '' }}>
                            Ya
                        </option>
                    </x-select>
                </li>
                <li class="w-full">
                    <x-select id="denv_3" name="denv_3" label="DENV 3">
                        <option value="0" {{ $serotypes[2]->status == 0 ? 'selected' : '' }}>
                            Tidak
                        </option>
                        <option value="1" {{ $serotypes[2]->status == 1 ? 'selected' : '' }}>
                            Ya
                        </option>
                    </x-select>
                </li>
                <li class="w-full">
                    <x-select id="denv_4" name="denv_4" label="DENV 4">
                        <option value="0" {{ $serotypes[3]->status == 0 ? 'selected' : '' }}>
                            Tidak
                        </option>
                        <option value="1" {{ $serotypes[3]->status == 1 ? 'selected' : '' }}>
                            Ya
                        </option>
                    </x-select>
                </li>
            </ul>

            <x-button class="bg-primary" type="submit">
                {{ __('Simpan Data Sampling') }}
            </x-button>
        </form>
    </x-card-container>

    @push('js-internal')
        <script>
            @if (Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ Session::get('success') }}',
                    showConfirmButton: false,
                    timer: 1500
                });
            @elseif (Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ Session::get('error') }}',
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif
        </script>
    @endpush
</x-app-layout>
