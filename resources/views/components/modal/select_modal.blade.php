<div class="modal" id="selectmodal">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">
            <h2 class="font-medium text-base mr-auto">Data Pasien yang diperiksa</h2>
            <script>
                 var currentURL = window.location.href;
                var urlParts = currentURL.split("/");
                var lastPart = urlParts[urlParts.length - 1];
                if(lastPart == "pendaftaran"){
                    $(".modal__content--lg > div:nth-child(1) > h2:nth-child(1)").text("Data Pasien yang Didaftarkan")
                }else if(lastPart == "vitalsign"){
                    $(".modal__content--lg > div:nth-child(1) > h2:nth-child(1)").text("Data Pemerikasaan Vital Sign")
                }
                
            </script>
            <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-6 text-white" style="display: none"> <i data-feather="alert-octagon" class="w-6 h-6 mr-2"></i> <p></p> </div>
            <div class="dropdown sm:hidden"> <a class="dropdown-toggle w-5 h-5 block" href="javascript:;"> <i data-feather="more-horizontal" class="w-5 h-5 text-gray-700 dark:text-gray-600"></i> </a></div>
        </div>
        <form id="selectForm" action="" method="POST">
            <div class="accordion">
                <div class="accordion__pane active border border-gray-200 dark:border-dark-5 p-4"> <a id="accordion_1" href="javascript:;" class="accordion__pane__toggle font-medium block">Data Pasien</a>
                    <div class="accordion__pane__content mt-3 text-gray-700 dark:text-gray-600 leading-relaxed">
                        <div class="p-5 grid grid-cols-12 gap-4 row-gap-3"></div>
                    </div>
                </div>
                <div class="accordion__pane border border-gray-200 dark:border-dark-5 p-4 mt-3"> <a  id="accordion_2" href="javascript:;" class="accordion__pane__toggle font-medium block"></a>
                    <div class="accordion__pane__content mt-3 text-gray-700 dark:text-gray-600 leading-relaxed">
                        <div>

                        </div>
                        <div class="intro-y flex flex-col-reverse sm:flex-row items-center">
                            <div class="w-full sm:w-auto relative mr-auto mt-3 sm:mt-0">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 ml-3 left-0 z-10 text-gray-700 dark:text-gray-300" data-feather="search"></i> 
                                <input id="cariDokter" type="text" class="input w-full sm:w-64 box px-10 text-gray-700 dark:text-gray-300 placeholder-theme-13" placeholder="Cari Dokter">
                            </div>
                            <div class="w-full sm:w-auto flex">
                                <div class="dropdown">
                                    <div class="dropdown-box w-40">
                                        <div class="dropdown-box__content box dark:bg-dark-1 p-2">
                                            <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="file" class="w-4 h-4 mr-2"></i> Share Files </a>
                                            <a href="" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="settings" class="w-4 h-4 mr-2"></i> Settings </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BEGIN: Directory & Files -->
                        <div id="selectContentBase" class="intro-y grid grid-cols-12 gap-3 sm:gap-6 mt-5">
                        </div>
                    </div>
                </div>
            </div>
            @csrf
            <div id="divExecuteSelect" class="px-5 py-3 text-right border-t border-gray-200 dark:border-dark-5">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 dark:border-dark-5 dark:text-gray-300 mr-1">Batalkan</button>
                <button type="button" class="button mr-5 mb-2 inline-flex items-center justify-center bg-theme-10 text-white">
                    <span style="margin-right: 5px;">Simpan</span>
                </button>
            </div>
        </form>  
    </div>
</div>

<script>
    $(document).ready(function() {
        @if (Gate::allows("access-pendaftaran"))
            var thisRoute = 'pendaftaran';
            $('.rounded-md.flex.items-center.px-5.py-4.mb-2.bg-theme-6.text-white > p').text('Harap pilih dokter!');
            $('#accordion_2').text('Pilih Dokter');
            performSearch("");

            // Set up the keyup event handler
            $('#cariDokter').keyup(function() {
                var query = $(this).val();
                performSearch(query);
            });

        @elseif (Gate::allows('access-suster'))
            var thisRoute = 'vitalsign';
            $('.rounded-md.flex.items-center.px-5.py-4.mb-2.bg-theme-6.text-white > p').text('Harap pasien!');
            $('#accordion_2').text('Input pemeriksaan vital sign');
            $('.w-full.sm\\:w-auto.relative.mr-auto.mt-3.sm\\:mt-0').hide();
            $('h2.font-medium.text-base.mr-auto').text('Form pemeriksaan vital sign');

            var labels = ['Berat badan', 'Tekanan darah', 'Denyut nadi', 'Spo2', 'Suhu', 'Respiration rate'];
            var inputNames = ['berat_badan', 'tekanan_darah', 'denyut_nadi', 'spo2', 'suhu', 'respiration_rate'];
            var satuan = ['kg','mmHg','x/mnt','%','°C','x/mnt']

            for (var i = 0; i < labels.length; i++) {
                var label = $('<label>').text(labels[i]).attr('for', inputNames[i]);

                var inputWrapper = $('<div>').addClass('relative mt-2');
                if(i == 0 || i == 1){
                    var input = $('<input>').attr({ type: 'text', class: 'input pr-12 w-full border col-span-4', name: inputNames[i], required : true});
                }else{
                    var input = $('<input>').attr({ type: 'text', class: 'input pr-12 w-full border col-span-4', name: inputNames[i]});
                }
               
                var addon = $('<div>').css('width', '3.3rem').addClass('absolute top-0 right-0 rounded-r w-10 h-full flex items-center justify-center bg-gray-100 dark:bg-dark-1 dark:border-dark-4 border text-gray-600').text(satuan[i]);

                input.appendTo(inputWrapper);
                addon.appendTo(inputWrapper);

                var wrapper = $('<div class="col-span-12 sm:col-span-6">')
                    .append(label)
                    .append(inputWrapper);

                wrapper.appendTo('#selectContentBase');

                input.on('input', function () {
                var value = $(this).val();

                // Check if the input value contains non-numeric characters
                if (/[^0-9,]/.test(value)) {
                    alert('Harap input data berupa angka');
                    // Clear the input value or handle it as needed
                    $(this).val('');
                }
            });
            }
        @endif
       
        // Define the function to handle the AJAX request
        function performSearch(query) {
            $.ajax({
                url: thisRoute,
                type: "GET",
                data: { 'searchdata': query},
                success: function(data) {      // success: function(response)
                    // var output = response.output;
                    // var arrayImg = response.arrayImg;

                    $('#selectContentBase').html(data);
                    // var jmlImg = $('#dktImageDivPdf img').length;
   
                    // for (var i = 0; i < jmlImg; i++) {
                    //     $("#tbImage_"+i).attr("src", arrayImg[i])
                    // }
                }
            });
        }
    });
</script>
