<x-app-layout>
    <x-breadcrumb name="variable-agent.show" :data="$regency" />
    <x-card-container>
        <table id="variableAgentTable" class="w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kecamatan</th>
                    <th>Lokasi</th>
                    <th>Jumlah</th>
                    <th>Tipe</th>
                    {{-- <th>Detail</th> --}}
                </tr>
            </thead>
        </table>
    </x-card-container>

    @push('js-internal')
        <script>
            $(function() {
                $('#variableAgentTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: "{{ route('admin.variable-agent.show', $id) }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'district',
                            name: 'district'
                        },
                        {
                            data: 'location',
                            name: 'location'
                        },
                        {
                            data: 'count',
                            name: 'count'
                        },
                        {
                            data: 'type',
                            name: 'type'
                        },
                        // {
                        //     data: 'action',
                        //     name: 'action'
                        // }
                    ]
                });
            });
        </script>
    @endpush
</x-app-layout>
