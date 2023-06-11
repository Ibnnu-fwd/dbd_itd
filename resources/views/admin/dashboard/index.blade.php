<x-app-layout>
    <x-breadcrumb name="dashboard" />
    <div class="xl:grid grid-cols-2 gap-x-4">
        <div class="xl:grid grid-cols-2 gap-4 md:flex md:flex-wrap">
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-users fa-2x text-primary mr-4 "></i>
                <div>
                    <a href="#">
                        <h5 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            1.560
                        </h5>
                    </a>
                    <p class="font-normal text-xs 2xl:text-sm text-gray-500">Pengguna</p>
                </div>
            </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-chart-simple fa-2x text-success mr-4"></i>
                <div>
                    <a href="#">
                        <h5 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            1.560
                        </h5>
                    </a>
                    <p class="font-normal text-xs 2xl:text-sm text-gray-500">Sampel</p>
                </div>
            </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-mosquito fa-2x text-error mr-4"></i>
                <div>
                    <a href="#">
                        <h5 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            1.560
                        </h5>
                    </a>
                    <p class="font-normal text-xs 2xl:text-sm text-gray-500">Nyamuk</p>
                </div>
            </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-worm fa-2x text-warning mr-4"></i>
                <div>
                    <a href="#">
                        <h5 class="mb-1 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            1.560
                        </h5>
                    </a>
                    <p class="font-normal text-xs 2xl:text-sm text-gray-500">Larva</p>
                </div>
            </div>
        </div>
        <x-card-container height="">
            <p class="text-xs 2xl:text-sm font-semibold">
                Statistik Sampel
            </p>
            <canvas id="line-chart"></canvas>
        </x-card-container>
    </div>

    @push('js-internal')

    @endpush
</x-app-layout>
