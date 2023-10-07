<x-app-layout>
    <x-breadcrumb name="tcases.create" />
    <x-card-container class="xl:w-2/3">
        <form action="{{ route('admin.tcases.store') }}" method="POST">
            @csrf
            <div class="sm:grid grid-cols-2 gap-x-4">
                <div>
                    <p class="text-xs 2xl:text-sm font-semibold mb-6">Detail Sampling</p>
                    <x-select id="regency_id" label="Kabupaten/Kota" name="regency_id" isFit="true" required>
                        @foreach ($regencies as $regency)
                        <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                        @endforeach
                    </x-select>
                    <x-select id="district_id" label="Kecamatan" name="district_id" isFit="true" required />
                    <x-select id="village_id" label="Desa" name="village_id" isFit="true" required />
                    <p class="text-xs 2xl:text-sm" id="address"></p>
                </div>
                <div>
                    <p class="text-xs 2xl:text-sm font-semibold mb-6">Detail Kasus</p>
                    <x-input id="cases_total" name="cases_total" label="Total Kasus" type="number" required oninput="this.value = Math.abs(this.value)" />
                    <p class="text-xs">Date</p>
                    <div class="relative mt-2 mb-4">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input name="date" id="date" type="date" class="border border-gray-300 text-gray-900 text-xs 2xl:text-sm rounded-lg focus:ring-primary focus:border-primary block w-full pl-10 p-3 mb-1.5" placeholder="Pilih tanggal mulai" autocomplete="off">
                    </div>

                    <x-select id="vector_type" class="mt-6" label="Type Vector" name="vector_type" isFit="true" required>
                        <option value="Demam Berdarah">Demam Berdarah</option>
                    </x-select>
                </div>

            </div>

            <div class="text-end mt-6">
                <x-button class="bg-primary w-full md:w-auto justify-center">
                    Tambah Kasus
                </x-button>
            </div>
        </form>
    </x-card-container>

    @push('js-internal')
    <script>
        let regency;
        let district;
        let village;



        function removeDetail(id) {
            let count = $('#detailContainer').children().length;
            if (count > 1) {
                $(`#detail-${id}`).remove();
            }
        }

        $(function() {
            $("#regency_id").on("change", function() {
                regency = $(this).val();
                $("#district_id").empty();
                $("#village_id").empty();
                $("#district_id").append(
                    `<option value="" selected disabled>Pilih Kecamatan</option>`
                );
                $("#village_id").append(
                    `<option value="" selected disabled>Pilih Desa</option>`
                );
                $.ajax({
                    url: "{{ route('admin.district.list') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        regency_id: regency,
                    },
                    success: function(data) {
                        let districts = Object.values(data);
                        districts.forEach((district) => {
                            $("#district_id").append(
                                `<option value="${district.id}">${district.name}</option>`
                            );
                        });
                    },
                });
            });

            $("#district_id").on("change", function() {
                district = $(this).val();
                $("#village_id").empty();
                $("#village_id").append(
                    `<option value="" selected disabled>Pilih Desa</option>`
                );
                $.ajax({
                    url: "{{ route('admin.village.list') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        district_id: district,
                    },
                    success: function(data) {
                        let villages = Object.values(data);
                        villages.forEach((village) => {
                            $("#village_id").append(
                                `<option value="${village.id}">${village.name}</option>`
                            );
                        });
                    },
                });
            });

            $("#village_id").on("change", function() {
                village = $(this).val();
                $.ajax({
                    url: "{{ route('admin.village.show', ':id') }}".replace(
                        ":id",
                        village
                    ),
                    type: "GET",
                    success: function(data) {
                        $("#address").text('Alamat: ' + data.address);
                    },
                });
            });


            @if(Session::has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ Session::get('
                success ') }}',
            })
            @endif

            @if(Session::has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ Session::get('
                error ') }}',
            })
            @endif
        });
    </script>
    @endpush
</x-app-layout>