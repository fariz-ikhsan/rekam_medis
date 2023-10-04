
@include('layouts.header')
<div class="content">
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
        <h2 class="intro-y text-lg font-medium mt-5"  style="font-size: 30px;">Kunjungan Hari ini</h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
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
            var routeInitial = "kunjunganhariini";
            var sudahPilihPasien = false;
        </script>
        <div id="tableBase" class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <div id="pdfBaseLoc"></div>
        <table class="table table-report -mt-2">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="whitespace-no-wrap">No RM</th>
                    <th class="whitespace-no-wrap">Nama</th>
                    <th class="whitespace-no-wrap">Diagnosa Utama</th>
                    <th class="whitespace-no-wrap">Komplikasi</th>
                    <th class="whitespace-no-wrap">OPSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    <tr class="intro-x">
                        <td class="font-medium whitespace-no-wrap">
                            {{ $item->no_rekmed }}
                        </td>
                        <td class="font-medium whitespace-no-wrap">
                            {{ $item->nama_psn }}
                        </td>
                        <td class="font-medium whitespace-no-wrap">
                            {{ $item->diagnosa_utama }}
                        </td>
                        <td class="font-medium whitespace-no-wrap">
                            {{ $item->komplikasi }}
                        </td>
                        <td class="font-medium whitespace-no-wrap">
                            <div>
                                <a href="{{ route('cetakpdf', ['idsps' => $item->id_spesialistik]) }}" target="_blank" class="flex items-center text-theme-1 mr-3">
                                    <i data-feather="printer" class="w-4 h-4 mr-1"></i> Cetak
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        @include('components.pagination')
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->        <!-- END: Pagination -->
    </div>
</div>
<script>
    $(document).ready(function() {
        // Define the function to handle the AJAX request
        function performSearch(query) {
            $.ajax({
                url: window.location.href,
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