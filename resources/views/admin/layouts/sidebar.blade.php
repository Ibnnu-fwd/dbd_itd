<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700 md:w-[15%] lg:block lg:w-[15%] w-1/2"
    aria-label="Sidebar">
    <div class="h-full pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="font-medium">
            <x-sidebar-item title="Dashboard" icon="fas fa-tachometer-alt" route="{{ route('admin.dashboard') }}"
                active="{{ request()->routeIs('admin.dashboard') }}" />

            <!-- Master Location -->
            <x-sidebar-dropdown title="Master Lokasi" icon="fas fa-box-archive" toggle="master-location"
                active="{{ request()->routeIs('admin.province.*') || request()->routeIs('admin.regency.*') || request()->routeIs('admin.district.*') || request()->routeIs('admin.village.*') }}">
                <x-sidebar-item title="Provinsi" route="{{ route('admin.province.index') }}"
                    active="{{ request()->routeIs('admin.province.*') }}" />
                <x-sidebar-item title="Kabupaten / Kota" route="{{ route('admin.regency.index') }}"
                    active="{{ request()->routeIs('admin.regency.*') }}" />
                <x-sidebar-item title="Kecamatan" route="{{ route('admin.district.index') }}"
                    active="{{ request()->routeIs('admin.district.*') }}" />
                <x-sidebar-item title="Desa" route="{{ route('admin.village.index') }}"
                    active="{{ request()->routeIs('admin.village.*') }}" />
            </x-sidebar-dropdown>

            <!-- Master Demography -->
            <x-sidebar-dropdown title="Master Demografi" icon="fas fa-box-archive" toggle="master-type"
                active="{{ request()->routeIs('admin.tpa-type.*') || request()->routeIs('admin.floor-type.*') || request()->routeIs('admin.environment-type.*') || request()->routeIs('admin.location-type.*') || request()->routeIs('admin.settlement-type.*') || request()->routeIs('admin.building-type.*') }}">
                <x-sidebar-item title="Jenis TPA" route="{{ route('admin.tpa-type.index') }}"
                    active="{{ request()->routeIs('admin.tpa-type.*') }}" />
                <x-sidebar-item title="Jenis Lantai" route="{{ route('admin.floor-type.index') }}"
                    active="{{ request()->routeIs('admin.floor-type.*') }}" />
                <x-sidebar-item title="Jenis Lingkungan" route="{{ route('admin.environment-type.index') }}"
                    active="{{ request()->routeIs('admin.environment-type.*') }}" />
                <x-sidebar-item title="Jenis Lokasi" route="{{ route('admin.location-type.index') }}"
                    active="{{ request()->routeIs('admin.location-type.*') }}" />
                <x-sidebar-item title="Jenis Pemukiman" route="{{ route('admin.settlement-type.index') }}"
                    active="{{ request()->routeIs('admin.settlement-type.*') }}" />
                <x-sidebar-item title="Jenis Bangunan" route="{{ route('admin.building-type.index') }}"
                    active="{{ request()->routeIs('admin.building-type.*') }}" />
            </x-sidebar-dropdown>

            <!-- Master Virus -->
            <x-sidebar-dropdown title="Master Sampel" icon="fas fa-box-archive" toggle="master-virus"
                active="{{ request()->routeIs('admin.serotype.*') || request()->routeIs('admin.virus.*') || request()->routeIs('admin.morphotype.*') || request()->routeIs('admin.sample-method.*') }}">
                <x-sidebar-item title="Virus" route="{{ route('admin.virus.index') }}"
                    active="{{ request()->routeIs('admin.virus.*') }}" />
                <x-sidebar-item title="Serotipe" route="{{ route('admin.serotype.index') }}"
                    active="{{ request()->routeIs('admin.serotype.*') }}" />
                <x-sidebar-item title="Morfotipe" route="{{ route('admin.morphotype.index') }}"
                    active="{{ request()->routeIs('admin.morphotype.*') }}" />
                <x-sidebar-item title="Metode Sampling" route="{{ route('admin.sample-method.index') }}"
                    active="{{ request()->routeIs('admin.sample-method.*') }}" />
            </x-sidebar-dropdown>

            <hr class="my-2 border-gray-200 dark:border-gray-700 mx-2" />

            <!-- Master Sample -->
            <x-sidebar-dropdown title="Sampel" icon="fas fa-mosquito" toggle="master-sample"
                active="{{ request()->routeIs('admin.sample.*') || request()->routeIs('admin.variable-agent.*') }}">
                <x-sidebar-item title="Nyamuk" route="{{ route('admin.sample.index') }}"
                    active="{{ request()->routeIs('admin.sample.*') }}" />
                <x-sidebar-item title="Agen Variabel" route="{{ route('admin.variable-agent.index') }}"
                    active="{{ request()->routeIs('admin.variable-agent.*') }}" />
            </x-sidebar-dropdown>
        </ul>
    </div>
</aside>
