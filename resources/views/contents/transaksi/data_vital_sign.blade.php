
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
        Daftar Data Kunjungan Pasien
    </h2>
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
            var routeInitial = "vitalsign";
        </script>
        <div id="tableBase" class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-no-wrap" style="display:none;">id_pendaftaran</th>
                        <th class="whitespace-no-wrap">No RM</th>
                        <th class="whitespace-no-wrap">Nama</th>
                        <th class="whitespace-no-wrap">Dokter</th>
                        <th class="whitespace-no-wrap">Ruangan</th>
                        <th class="text-center whitespace-no-wrap">OPSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $item)
                        <tr class="intro-x">
                            <td id="idpendaftaran" style="display:none;">
                                {{ $item->id_pendaftaran }}
                            </td>
                            <td class="font-medium whitespace-no-wrap">
                                {{ $item->no_rekmed }}
                            </td>
                            <td class="font-medium whitespace-no-wrap">
                                {{ $item->nama }}
                            </td>
                            <td class="font-medium whitespace-no-wrap">
                                {{ $item->nama_dokter }}
                            </td>
                            <td class="font-medium whitespace-no-wrap">
                                {{ $item->ruangan }}
                            </td>
                            <td>
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center text-theme-1 mr-3" onclick="selectBtnOnclick({{ $index }}, 'suster')" href="javascript:;" data-toggle="modal" data-target="#selectmodal">
                                        <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Pilih
                                    </a>
                                </div>
                                <script>
                                    $("#executeSelectBtnOnclick").on("click", function () {
                                        var berat_badan = $("input[name='berat_badan']").val();
                                        var tekanan_darah = $("input[name='tekanan_darah']").val();

                                        $("#selectContentBase input:gt(1)").each(function () {
                                            if ($(this).val() === "") {
                                                // Fill it with a default value
                                                $(this).val("-");
                                            }
                                        });
                                        if (berat_badan === "" || tekanan_darah === "") {
                                            if (berat_badan === "") {
                                                alert("Berat badan wajib diisi!");
                                            } else if (tekanan_darah === "") {
                                                alert("Tekanan darah wajib diisi!");
                                            }
                                        } else {
                                            $("#selectForm").submit();
                                        }
                                    });
                                </script>
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