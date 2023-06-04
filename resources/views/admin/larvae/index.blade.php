<x-app-layout>
    <x-breadcrumb name="larvae" />
    <x-card-container>
        <div class="flex flex-col gap-3 md:flex-row md:justify-end mb-4">
            <x-link-button route="{{ route('admin.larvae.create') }}" color="gray" type="button" class="justify-center">
                Tambah
            </x-link-button>
        </div>
        <table id="larvaeTable">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Kode</th>
                    <th rowspan="2">Kecamatan</th>
                    <th colspan="5">Demografi</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Lokasi</th>
                    <th>Permukiman</th>
                    <th>Lingkungan</th>
                    <th>Bangunan</th>
                    <th>Lantai</th>
                </tr>
            </thead>
        </table>
    </x-card-container>
    @push('js-internal')
        <script>
            $(function() {
                $('#larvaeTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('admin.larvae.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'larva_code',
                            name: 'larva_code',
                        },
                        {
                            data: 'district',
                            name: 'district',
                        },
                        {
                            data: 'location',
                            name: 'location',
                        },
                        {
                            data: 'settlement',
                            name: 'settlement',
                        },
                        {
                            data: 'environment',
                            name: 'environment',
                        },
                        {
                            data: 'building',
                            name: 'building',
                        },
                        {
                            data: 'floor',
                            name: 'floor',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                        },
                    ],
                });
            });
        </script>
    @endpush
</x-app-layout>
