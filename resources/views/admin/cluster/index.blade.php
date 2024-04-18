<x-app-layout>
    <x-breadcrumb name="cluster.index" />

    @isset($failures)
        <div id="alert-2" class="text-sm flex p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium">
                    Periksa kembali file yang diupload, terdapat beberapa data yang tidak sesuai dengan format yang
                </span>
                <ul class="mt-1.5 ml-4 list-disc list-inside">
                    @foreach ($failures as $failure)
                        <li>
                            <span class="text-red-600 dark:text-red-400">Baris {{ $failure->row() }}</span> -
                            {{ $failure->errors()[0] }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <button type="button"
                class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                data-dismiss-target="#alert-2" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endisset

    <x-card-container>
        <div class="text-end mb-3">
            <x-link-button route="#" color="gray" type="button" id="btnImport" class="justify-center">
                Import
            </x-link-button>
        </div>

        <table id="clusterSample">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Provinsi</th>
                    <th>Kecamatan</th>
                    <th>Desa</th>
                    <th>Jenis Lokasi</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Aedes Aegypti</th>
                    <th>Aedes Albopictus</th>
                    <th>Culex</th>
                    <th>Morfotipe 1</th>
                    <th>Morfotipe 2</th>
                    <th>Morfotipe 3</th>
                    <th>Morfotipe 4</th>
                    <th>Morfotipe 5</th>
                    <th>Morfotipe 6</th>
                    <th>Morfotipe 7</th>
                    <th>DENV 1</th>
                    <th>DENV 2</th>
                    <th>DENV 3</th>
                    <th>DENV 4</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </x-card-container>

    <form action="{{ route('admin.cluster.import') }}" method="POST" hidden id="formImport"
        enctype="multipart/form-data">
        @csrf
        <input type="file" name="import_file" id="import_file">
        <button type="submit" id="btnSubmit"></button>
    </form>

    @push('js-internal')
        <script>
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

                $('#clusterSample').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    responsive: true,
                    ajax: '{{ route('admin.cluster.index') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'province',
                            name: 'province'
                        },
                        {
                            data: 'district',
                            name: 'district'
                        },
                        {
                            data: 'village',
                            name: 'village'
                        },
                        {
                            data: 'location_type',
                            name: 'location_type'
                        },
                        {
                            data: 'latitude',
                            name: 'latitude'
                        },
                        {
                            data: 'longitude',
                            name: 'longitude'
                        },
                        {
                            data: 'aedes_aegypti',
                            name: 'aedes_aegypti'
                        },
                        {
                            data: 'aedes_albopictus',
                            name: 'aedes_albopictus'
                        },
                        {
                            data: 'culex',
                            name: 'culex'
                        },
                        {
                            data: 'morphotype_1',
                            name: 'morphotype_1'
                        },
                        {
                            data: 'morphotype_2',
                            name: 'morphotype_2'
                        },
                        {
                            data: 'morphotype_3',
                            name: 'morphotype_3'
                        },
                        {
                            data: 'morphotype_4',
                            name: 'morphotype_4'
                        },
                        {
                            data: 'morphotype_5',
                            name: 'morphotype_5'
                        },
                        {
                            data: 'morphotype_6',
                            name: 'morphotype_6'
                        },
                        {
                            data: 'morphotype_7',
                            name: 'morphotype_7'
                        },
                        {
                            data: 'denv_1',
                            name: 'denv_1'
                        },
                        {
                            data: 'denv_2',
                            name: 'denv_2'
                        },
                        {
                            data: 'denv_3',
                            name: 'denv_3'
                        },
                        {
                            data: 'denv_4',
                            name: 'denv_4'
                        },
                    ],
                });
            });

            $('#formImport').on('submit', function() {
                Swal.fire({
                    title: 'Mohon tunggu',
                    text: 'Sedang memproses data',
                    allowOutsideClick: false,
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
        </script>
    @endpush
</x-app-layout>
