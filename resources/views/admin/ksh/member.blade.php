<x-app-layout>
    <x-breadcrumb name="ksh.member" />
    <x-card-container>
        <div class="flex flex-col gap-3 md:flex-row md:justify-end mb-4">
            <x-link-button route="{{ route('admin.ksh.member.create') }}" color="gray" type="button" class="justify-center">
                Tambah
            </x-link-button>
        </div>
        <table id="memberTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Gender</th>
                    <th>No. Telefon</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung Sejak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </x-card-container>

    @push('js-internal')
        <script></script>
    @endpush
</x-app-layout>
