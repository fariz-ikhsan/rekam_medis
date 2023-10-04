

@include('layouts.header')
<div class="content">
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
    <h2 class="intro-y text-lg font-medium mt-5">
        Daftar Data Jadwal Praktek
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
            <a href="javascript:;" onclick="createBtnOnclick()" data-toggle="modal" data-target="#createmodal" class="button inline-block bg-theme-1 text-white">Tambah Jadwal Baru</a>
            <div class="hidden md:block mx-auto text-gray-600"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-gray-700 dark:text-gray-300">
                    <input id="cariInput" name="cari" type="text" class="input w-56 box pr-10 placeholder-theme-13" placeholder="Search..." onfocus="this.value=''">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i> 
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <script>
            var routeInitial = "datajadwal";
        </script>
        <div id="tableBase" class="intro-y col-span-12 overflow-auto lg:overflow-visible">
    <table class="table table-report -mt-2">
        <thead>
            <tr>
                <th id="photo" class="whitespace-no-wrap">Photo</th>
                <th id="jdwDokterTh" style="display: none;" class="whitespace-no-wrap">ID Jadwal</th>
                <th class="whitespace-no-wrap">ID Dokter</th>
                <th class="whitespace-no-wrap">Nama</th>
                <th class="whitespace-no-wrap">No Ruangan</th>
                <th class="whitespace-no-wrap">Hari</th>
                <th class="whitespace-no-wrap">Buka Praktek</th>
                <th class="whitespace-no-wrap">Selesai Praktek</th>
                <th class="text-center whitespace-no-wrap">OPSI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr class="intro-x">
                    <td id="tdPhoto" class="font-medium whitespace-no-wrap">
                        <div class="w-10 h-10 flex-none image-fit">
                            <img id="tbImage_{{ $index }}" src="{{ $item->photo }}" class="tooltip rounded-full">
                        </div>
                    </td>
                    <td style="display: none;" class="font-medium whitespace-no-wrap">{{ $item->id_jdwdokter }}</td>
                    <td class="font-medium whitespace-no-wrap">{{ $item->id_dokter }}</td>
                    <td class="font-medium whitespace-no-wrap">{{ $item->nama }}</td>
                    <td class="font-medium whitespace-no-wrap">{{ $item->no_ruangan }}</td>
                    <td class="font-medium whitespace-no-wrap">{{ $item->hari }}</td>
                    <td class="font-medium whitespace-no-wrap">{{ $item->buka_praktek }}</td>
                    <td class="font-medium whitespace-no-wrap">{{ $item->akhir_praktek }}</td>
                    <td>
                        <div class="flex justify-center items-center">
                            <a class="flex items-center text-theme-1 mr-3" onclick="editBtnOlick({{ $index }})" href="javascript:;" data-toggle="modal" data-target="#editmodal">
                                <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit
                            </a>
                            <a class="flex items-center text-theme-6" onclick="deleteBtnOnclick({{ $index }})" href="javascript:;" data-toggle="modal" data-target="#deletemodal">
                                <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
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
        

        // Set up the keyup event handler
        $('#cariInput').keyup(function() {
            var query = $(this).val();
            performSearch(query);
        });
    });
</script>


@include('layouts.footer')