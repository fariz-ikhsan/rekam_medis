
@include('layouts.header')
<div class="content">
    @include('components.modal.select_modal')
    @include('components.modal.create_modal')
    @include('components.modal.edit_modal')
    @include('components.modal.delete_modal')
    <!-- BEGIN: Top Bar -->
    <div class="top-bar">
        <!-- BEGIN: Breadcrumb -->
        <div class="-intro-x breadcrumb mr-auto hidden sm:flex"> </div>
        <!-- END: Breadcrumb -->
        <!-- BEGIN: Account Menu -->
       @include('components.account_dropdown')
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->
    <h2 class="text-lg font-medium mr-auto" style="font-size: 30px;">
        Pendaftaran Data Pasien
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
            @can('access-pendaftaran')
                <a href="javascript:;" onclick="selectBtnOnclick('','pendaftaran-tambahpasien')" data-toggle="modal" data-target="#selectmodal" class="button inline-block bg-theme-1 text-white">Tambah Pasien Baru</a>
            @endcan
           
            <div class="hidden md:block mx-auto text-gray-600"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-gray-700 dark:text-gray-300">
                    <input id="cariInput" name="cari" type="text" class="input w-56 box pr-10 placeholder-theme-13" placeholder="Search..." onfocus="this.value=''">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                </div>
            </div>
            @can('access-pendaftaran')
                <div class="text-center">
                    <div class="dropdown inline-block" data-placement="bottom-start"> <button class="dropdown-toggle button flex items-center inline-block bg-theme-1 text-white"> Pencarian Spesifik <i data-feather="chevron-down" class="w-4 h-4 ml-2"></i> </button>
                        <div class="dropdown-box">
                            <div class="dropdown-box__content box p-5" id="pencarianSpesifik">
                                <div>
                                    <div class="text-xs">Nama</div> <input name="inptDropNama" type="text" class="input w-full border mt-2 flex-1" placeholder="Adi" />
                                </div>
                                <div class="mt-3">
                                    <div class="text-xs">Tgl Lahir</div> <input name="inptDropTglLahir" type="text" class="input w-full border mt-2 flex-1" placeholder="1999-12-30 (Tahun-Bulan-Tanggal)" />
                                </div>
                                <div class="mt-3">
                                    <div class="text-xs">No Telp</div> <input name="inptDropNoTelp" type="text" class="input w-full border mt-2 flex-1" placeholder="081284430562" />
                                </div>
                                <div class="mt-3">
                                    <div class="text-xs">Alamat</div> <input name="inptDropAlmt" type="text" class="input w-full border mt-2 flex-1" placeholder="Bekasi, Jawa Barat" />
                                </div>
                                <div class="mt-3">
                                    <div class="text-xs">Pekerjaan</div> <input name="inptDropPkrjaan" type="text" class="input w-full border mt-2 flex-1" placeholder="Pengusaha" />
                                </div>
                                <div class="flex items-center mt-3"> <button data-dismiss="dropdown" class="button w-32 justify-center block bg-gray-200 text-gray-600 dark:bg-dark-1 dark:text-gray-300 ml-auto">Tutup</button> <button class="button w-32 justify-center block bg-theme-1 text-white ml-2">Search</button> </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
        <!-- BEGIN: Data List -->
        <script>
            var routeInitial = "datapasien";
        </script>
        <div id="tableBase" class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-no-wrap">No RM</th>
                        <th class="whitespace-no-wrap">Nama</th>
                        <th class="whitespace-no-wrap">Tgl Lahir</th>
                        <th class="whitespace-no-wrap">Jenis Kelamin</th>
                        <th class="whitespace-no-wrap">No Telp</th>
                        <th class="whitespace-no-wrap">Alamat</th>
                        <th class="whitespace-no-wrap">Pekerjaan</th>
                        <th class="text-center whitespace-no-wrap">OPSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $item)
                        <tr class="intro-x">
                            <td class="font-medium whitespace-no-wrap">{{ $item->getPrimaryKey() }}</td>
                            <td class="font-medium whitespace-no-wrap">{{ $item->nama }}</td>
                            <td class="font-medium whitespace-no-wrap">{{ \Carbon\Carbon::parse($item->tgl_lahir)->format("j M, Y") }}</td>
                            <td class="font-medium whitespace-no-wrap">{{ $item->jenis_kelamin }}</td>
                            <td class="font-medium whitespace-no-wrap">{{ $item->no_telp }}</td>
                            <td class="font-medium whitespace-no-wrap">{{ $item->alamat }}</td>
                            <td class="font-medium whitespace-no-wrap">{{ $item->pekerjaan }}</td>
                            <td>
                                <div class="flex justify-center items-center">
                                    @can('access-admin')
                                        <a class="flex items-center text-theme-1 mr-3" onclick="editBtnOlick({{ $index }})" href="javascript:;" data-toggle="modal" data-target="#editmodal">
                                            <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit
                                        </a>
                                    @endcan
                                    @can('access-pendaftaran')
                                        @php
                                            $results = DB::table('rekam_medis.pendaftaran')
                                                ->where('no_rekmed', $item->no_rekmed)
                                                ->whereDate('tgl_daftar', now()->format('Y-m-d'))
                                                ->where('status', 'Belum Periksa')
                                                ->get();
                                        @endphp
                                        @if(count($results) > 0)
                                            <button class="button w-24 mr-1 mb-2 bg-theme-6 text-white">Pasien sudah terdaftar!</button>
                                        @else
                                            <a class="flex items-center text-theme-1 mr-3" onclick="selectBtnOnclick({{ $index }},'pendaftaran')" href="javascript:;" data-toggle="modal" data-target="#selectmodal">
                                                <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Pilih
                                            </a>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <script>
                        if($('table.table-report tbody tr').length < 1){
                            $('table.table-report tbody').append('<tr class="intro-x" id="trPasienKosong" style="display: none;"><td class="font-medium whitespace-no-wrap">999</td><td class="font-medium whitespace-no-wrap">fariz</td>'+
                            '<td class="font-medium whitespace-no-wrap">4 Oct, 2023</td><td class="font-medium whitespace-no-wrap">laki-laki</td>'+
                            ' <td class="font-medium whitespace-no-wrap">443</td><td class="font-medium whitespace-no-wrap">adada</td>'+
                            '<td class="font-medium whitespace-no-wrap">saas</td><td></td></tr>')
                            }else if($('table.table-report tbody tr').length > 0){
                                if($("#trPasienKosong")){
                                    $("#trPasienKosong").remove()
                                }
                            }
                    </script>
                </tbody>
            </table>
            @include('components.pagination')
        </div>
        @include('components.modal.configuration_modal')
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        
        <!-- END: Pagination -->
    </div>
</div>
<script>
    $(document).ready(function() {
        // Define the function to handle the AJAX request
        function performSearch(query) {
            $.ajax({
                url: routeInitial,
                type: "GET",
                data: { 'searchdata': query, },
                success: function(data) {
                    $("#tableBase").html(data);
                    
   
                }
            });
        }
        $(document).on('click', '#pagination-container a', function(event){
            event.preventDefault(); 
            var page = $(this).attr('href').split('page=')[1];
            $.ajax({
                url:window.location.href+"?page="+page,
                    success:function(data)
                    {
                        $('#tableBase').html(data);
                    }
                });
            });
        
            $('#pencarianSpesifik input').keyup(function() {
                var nama = $('input[name="inptDropNama"]').val();
                var tgl_lahir = $('input[name="inptDropTglLahir"]').val();
                var no_telp = $('input[name="inptDropNoTelp"]').val();
                var alamat = $('input[name="inptDropAlmt"]').val();
                var pekerjaan = $('input[name="inptDropPkrjaan"]').val();

                // Membuat objek data untuk dikirim ke server
                var searchData = {
                    "pencarian_spesifik": "ada",
                    nama: nama,
                    tgl_lahir: tgl_lahir,
                    no_telp: no_telp,
                    alamat: alamat,
                    pekerjaan: pekerjaan
                };

                // Melakukan permintaan AJAX ke controller Anda
                $.ajax({
                    url: window.location.href, // Ganti dengan URL controller Anda
                    type: 'GET', // Atau 'GET' tergantung pada kebutuhan Anda
                    data: searchData,
                    success: function(data) {
                    
                        $('#tableBase').html(data);
                    },
                    error: function(error) {
                        // Handle kesalahan jika ada
                        console.error(error);
                    }
                });
            });
        

        // Set up the keyup event handler
        $('#cariInput').keyup(function() {
            var query = $(this).val();
            performSearch(query);
        });
    });
</script>


@include('layouts.footer')