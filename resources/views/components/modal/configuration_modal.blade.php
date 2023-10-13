<script>
    var colArray = []; // Initialize an empty Column array
    var tdCount = $('table.table-report thead th').length;
    var thElements = $('table.table-report thead th');
    var hari = '<option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option><option value="Kamis">Kamis</option><option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option><option value="Minggu">Minggu</option>';
    @php
        $dataArray = json_decode($data, true);
        if (!empty($dataArray)) {
            $keys = array_keys($dataArray[0]);

        } else {
            $keys=["NULL"];
        }

    @endphp
    
    @foreach ($keys as $key)
        colArray.push('{{ $key }}'); // Push each key into the Column array
    @endforeach
    
    if(colArray == "NULL"){
            if(window.location.href.indexOf("datapasien") !== -1){  colArray = ["no_rekmed","nama","tgl_lahir","jenis_kelamin","no_telp","alamat","pekerjaan"]
                
            }else if(window.location.href.indexOf("datadokter") !== -1){ colArray =  ["id_dokter","nama","no_telp","id_poli","login_id"]
            
            }else if(window.location.href.indexOf("datajadwal") !== -1){ colArray = ["id_jdwdokter","hari","buka_praktek","akhir_praktek","id_dokter","no_ruangan"]
            
            }else if(window.location.href.indexOf("datapoli") !== -1){ colArray = ["id_poli","nama","jenis_poli"]
            
            }else if(window.location.href.indexOf("datasuster") !== -1){ colArray = ["id_suster","nama","login_id"]
                
            }else if(window.location.href.indexOf("datastaffpendaftaran") !== -1){  colArray = ["id_karyawan","nama","jenis","login_id"]
            
            }
       }


    function selectBtnOnclick(index, role){

        var tdElements = $('table.table-report tbody tr.intro-x:eq(' + index + ') td:not(:last-child)'); // Select the specific row by index and get its td elements

        if(colArray == "NULL"){
            if(window.location.href.indexOf("pendaftaran") !== -1){  
                colArray = ["no_rekmed","nama","tgl_lahir","jenis_kelamin","no_telp","alamat","pekerjaan"]
            }
       }
        var dynamicElements = $('.p-5.grid.grid-cols-12.gap-4.row-gap-3'); // Assuming this is where you want to append the dynamic input elements
        dynamicElements.empty();

        var firstTdValue = encodeURIComponent(tdElements.first().text().trim().replace(/\s+/g, ' '));
        
        var formElement = $("#selectForm");
        var formattedValue = String(firstTdValue).padStart(firstTdValue.toString().length, '0');


        tdElements.each(function(i) {
            var tdValue = $(this).text().trim().replace(/\s+/g, ' '); // Get the text content of the current td element
            var thText = $('table.table-report thead th').eq($(this).index()).text(); // Get the corresponding th text from the thead
            
            if(role == "pendaftaran" || role == "suster"){
                var dynamicElement = $('<div class="col-span-12 sm:col-span-6">')
                    .append('<label>' + thText + '</label>')
                    .append('<input name="'+colArray[i]+'" type="text" class="input w-full border mt-2 flex-1"  value="' + tdValue + '" disabled>');
            }
            else if(role == "pendaftaran-tambahpasien"){
                if(i == 0){
                    var dynamicElement = $('<div class="col-span-12 sm:col-span-6">')
                    .append('<label>' + thText + '</label>')
                    .append(' <input onkeyup="cekId(this)" name="no_rekmed" type="text" class="input w-full border mt-2 flex-1" required>');
                }
                else if(i == 2){
                    var dynamicElement = $('<div class="col-span-12 sm:col-span-6">')
                    .append('<label>' + thText + '</label>')
                    .append('<div class="relative w-56" style="margin-top: 6px;"><div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600 dark:bg-dark-1 dark:border-dark-4">'+
                        '<i data-feather="calendar" class="w-4 h-4"></i> </div> <input name='+colArray[i]+' type="text" class="datepicker input pl-12 border" data-single-mode="true"></div>');
                }
                else if(i == 3){
                    var dynamicElement = $('<div class="col-span-12 sm:col-span-6">')
                    .append('<label>' + thText + '</label>')
                    .append('<div style="margin-top: 7px;"> <select class="tail-select w-full" name="jenis_kelamin">'+
                                '<option value="laki-laki">Laki-laki</option>'+
                                '<option value="perempuan">perempuan</option>'+
                            '</select></div>');            
                }
                else{
                    var dynamicElement = $('<div class="col-span-12 sm:col-span-6">')
                    .append('<label>' + thText + '</label>')
                    .append('<input name="'+colArray[i]+'" type="text" class="input w-full border mt-2 flex-1" required>');
                }

            }

            dynamicElements.append(dynamicElement);
        });

        if(role == "pendaftaran" ){
            formElement.attr('action', "pendaftaran");
            $("#selectForm").append(
                $("<input>")
                    .attr({
                        "type": "hidden",
                        "name": "no_rekmed",
                        "value": formattedValue
                    })
            );
        }else if(role == "pendaftaran-tambahpasien"){
            // $('#divExecuteSelect button:eq(1)').removeAttr("id");
            // $('#divExecuteSelect button:eq(1)').attr('type', "submit");
            formElement.attr('action', "pendaftaran");
            
            $("#selectForm").append(
                $("<input>")
                    .attr({
                        "type": "hidden",
                        "name": "tambahpasien",
                        "value": "tambah_baru_pasien"
                    })
            );
            
            $("#selectForm").append(
                $("<input>")
                    .attr({
                        "id" : "idjdwdokter_pasienbaru",
                        "type": "hidden",
                        "name": "id_jdwdokter"
                    })
            );

        
        }else if(role == "suster"){
            $('#divExecuteSelect button:eq(1)').attr('type', "submit");
            formElement.attr('action', "vitalsign");
            $(".col-span-12:first").hide()
            $("#selectForm").append(
                $("<input>")
                    .attr({
                        "type": "hidden",
                        "name": "id_pendaftaran",
                        "value": formattedValue
                    })
            );
        }
        else{
            formElement.attr('action', routeInitial + "/" + formattedValue);
        }
        $.getScript("{{ asset('dist/js/app.js')}}", function() {});
    }
   
    @php
        // Check the current URL
        $currentURL = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        
        if ($currentURL == '/datadokter') {
            $mysqli = mysqli_connect("localhost", "root", "", "rekam_medis");
            $poli = mysqli_query($mysqli, "SELECT id_poli, nama FROM poli");
            $idpoli = [];
            $nama = [];
            while ($row = mysqli_fetch_assoc($poli)) {
                // Add each row to the results array
                $idpoli[] = $row["id_poli"];
                $nama[] = $row["nama"];
            }

            $ruangan = mysqli_query($mysqli, "SELECT * FROM ruangan");
            while ($row = mysqli_fetch_assoc($ruangan)) {
                $no_ruangan[] = $row["no_ruangan"];
                $lokasi[] = $row["lokasi"];
            }
        }
        else if ($currentURL == '/datajadwal'){
            $mysqli = mysqli_connect("localhost", "root", "", "rekam_medis");
            $dokter = mysqli_query($mysqli, "SELECT id_dokter, nama FROM dokter");
            $id_dokter = [];
            $namadokter = [];
            while ($row = mysqli_fetch_assoc($dokter)) {
                // Add each row to the results array
                $id_dokter[] = $row["id_dokter"];
                $namadokter[] = $row["nama"];
            }

            $ruangan = mysqli_query($mysqli, "SELECT * FROM ruangan");
            while ($row = mysqli_fetch_assoc($ruangan)) {
                $no_ruangan[] = $row["no_ruangan"];
                $lokasi[] = $row["lokasi"];
            }
        }
    @endphp
  


    @if(!empty($idpoli))
        var id_poli = @json($idpoli);
        var userName = @json($nama);
        var poli = "";
        for (var y = 0; y < id_poli.length; y++) {
                poli += '<option value="' + id_poli[y] + '">' + userName[y] + '</option>';
        }
    @endif

    @if(!empty($no_ruangan))
        var no_ruangan = @json($no_ruangan);
        var lokasi = @json($lokasi);
        var ruanganDkt = "";
         for (var y = 0; y < no_ruangan.length; y++) {
            ruanganDkt += '<option value="' + no_ruangan[y] + '">' + lokasi[y] + '</option>';
        }
    @endif

    @if(!empty($id_dokter))
        var id_dokter = @json($id_dokter);
        var namadokter = @json($namadokter);
        var dataDokter = "";

         for (var u = 0; u < id_dokter.length; u++) {
            dataDokter += '<option value="' + id_dokter[u] + '">' + namadokter[u] + '</option>';
        }
    @endif


    function createBtnOnclick(){
        var dynamicElements = $('.p-5.grid.grid-cols-12.gap-4.row-gap-3');
        dynamicElements.empty();
           
        // Get the text content of each <th> element
        $('.table thead th:not(:last-child):not(#photo):not(#photo):not(#jdwDokterTh)').each(function(i) {
            var labelText = $(this).text();

            if(window.location.href.indexOf("datajadwal") !== -1 && ( labelText == "ID Dokter" || labelText == "Nama")){
                $('#simpanCreateFormBtn').attr('type', 'submit');
                if ( labelText == "ID Dokter"){
                    var dynamicElement = $('<div class="col-span-12 sm:col-span-6">').append('<label>Pilih Dokter</label>');
                        labelText = "Pilih Dokter";
                }else{
                    var dynamicElement = $('<div class="col-span-12 sm:col-span-6">').append('<label>Pilih Ruangan</label>');
                        labelText = "Pilih Ruangan";
                }
            }else if(labelText != "No Ruangan"){
                var dynamicElement = $('<div class="col-span-12 sm:col-span-6">').append('<label>' + labelText + '</label>');
            }
           
            
            if(labelText == "Poli"){
                dynamicElement.append('<div style="margin-top: 7px;"> <select class="tail-select w-full" name="'+colArray[i]+'">'+poli+'</select></div>');             
                $.getScript("{{ asset('dist/js/app.js')}}", function() {});  
            } 
            else if (window.location.href.indexOf("datajadwal") !== -1){
                if(labelText == "Pilih Dokter"){
                    dynamicElement.append('<select class="tail-select w-full" name="id_dokter">'+dataDokter+'</select>');
                }
                else if(labelText == "Pilih Ruangan"){
                    dynamicElement.append('<select class="tail-select w-full" name="no_ruangan">'+ruanganDkt+'</select>');
                }
                else if (labelText == "Hari"){
                    dynamicElement.append('<select class="tail-select w-full" name="hari">'+hari+'</select>');
                    $.getScript("{{ asset('dist/js/app.js')}}", function() {}); 
                }else if(labelText != "No Ruangan"){
                    dynamicElement.append('<input name="'+colArray[i-2]+'" type="text" class="input w-full border mt-2 flex-1">');
                }
            }
            else if (window.location.href.indexOf("datapoli") !== -1){
                if(labelText == "Jenis Poli"){
                    dynamicElement.append('<select class="tail-select w-full" name="jenis_poli"><option value="Umum">Umum</option><option value="Spesialis">Spesialis</option></select>');
                    $.getScript("{{ asset('dist/js/app.js')}}", function() {}); 
                }else{
                    dynamicElement.append('<input name="'+colArray[i]+'" type="text" class="input w-full border mt-2 flex-1">');
                }
            }else{
                dynamicElement.append('<input name="'+colArray[i]+'" type="text" class="input w-full border mt-2 flex-1">');
            }

            if(window.location.href.indexOf("datadokter") !== -1 && labelText == "ID Dokter"){
                dynamicElements.append('<div class="col-span-12 sm:col-span-6"> <label>Photo</label> <input name="photo" type="file" class="input w-full border mt-2 flex-1"></div>');
            }


            dynamicElements.append(dynamicElement);
            
            if(window.location.href.indexOf("datadokter") !== -1  || window.location.href.indexOf("datasuster") !== -1 || window.location.href.indexOf("datastaffpendaftaran") !== -1  ){
                if(labelText == "ID Dokter" || labelText == "ID Suster" || labelText == "ID Staff Pendaftaran"){
                    dynamicElements.append('<div class="col-span-12 sm:col-span-6"> <label>Password</label> <input name="password" type="password" class="input w-full border mt-2 flex-1"></div>')
                    $('#createForm').find('input[name="id_dokter"]').attr('onkeyup','cekId(this)');
                    $('#createForm').find('input[name="id_karyawan"]').attr('onkeyup','cekId(this)');
                    $('#createForm').find('input[name="id_suster"]').attr('onkeyup','cekId(this)');
                }
                else if(labelText == "Poli"){
                    dynamicElements.append('<div class="col-span-12 sm:col-span-6 w-full" style="margin-top: 10px;"></div><div class="col-span-12 sm:col-span-6 w-full" style="margin-top: 10px;"></div>');

                    dynamicElements.append('<div class="col-span-12 sm:col-span-6"> <label>Hari</label> <select class="tail-select w-full" name="hari">'+hari+'</select></div>');
                    dynamicElements.append('<div class="col-span-12 sm:col-span-6"> <label>No Ruangan</label> <select class="tail-select w-full" name="no_ruangan">'+ruanganDkt+'</select></div></div>');
                    dynamicElements.append('<div class="col-span-12 sm:col-span-6"> <label>Jam Buka Praktek</label><input name="buka_prak" type="text" class="input w-full border mt-2 flex-1"></div>');
                    dynamicElements.append('<div class="col-span-12 sm:col-span-6"> <label>Jam Selesai Praktek</label><input name="selesai_prak" type="text" class="input w-full border mt-2 flex-1"></div>');
                }
            }
            
        });
        $('#createForm').find('input[name="id_poli"]').attr('onkeyup','cekId(this)');
    }

    function cekId(element){
        $.ajax({
            url: window.location.href,
            type: "GET",
            data: { 'cek_id': element.value },
            success: function(response) {
                var count = response.count;
               if(count == 0){
                    element.className= "input w-full border mt-2 flex-1"
                    $('#simpanCreateFormBtn').attr('type', 'submit');
                    $('#selectForm').attr('onsubmit', 'return true;');
                    $('#divExecuteSelect button:eq(1)').attr('type', "submit");
               }else if(count > 0){
                    element.className="input w-full border border-theme-6 mt-2"
                    alert("ID atau No Rekam Medis Sudah Ada")
                    $('#simpanCreateFormBtn').attr('type', 'button');
                    $('#selectForm').attr('onsubmit', 'return false;');
                    $('#divExecuteSelect button:eq(1)').attr('type', "button");
               }
            }
        });
    }
   
    function deleteBtnOnclick(index){
        var tdElements = $('table.table-report tbody tr.intro-x:eq(' + index + ') td:not(:last-child):not(#tdPhoto)'); // Select the specific row by index and get its td elements
        var firstTdValue = encodeURIComponent(tdElements.first().text().trim().replace(/\s+/g, ' '));

        var formElement = $("#deleteForm");
        var formattedValue = String(firstTdValue).padStart(firstTdValue.toString().length, '0');
        formElement.attr('action', routeInitial + "/" + formattedValue);
    }

    function editBtnOlick(index) {
        var tdElements = $('table.table-report tbody tr.intro-x:eq(' + index + ') td:not(:last-child):not(#tdPhoto)'); // Select the specific row by index and get its td elements
        
        var dynamicElements = $('.p-5.grid.grid-cols-12.gap-4.row-gap-3'); // Assuming this is where you want to append the dynamic input elements
        dynamicElements.empty();
        
        var firstTdValue = encodeURIComponent(tdElements.first().text().trim().replace(/\s+/g, ' '));

        var formElement = $("#editForm");
        var formattedValue = String(firstTdValue).padStart(firstTdValue.toString().length, '0');
        formElement.attr('action', routeInitial + "/" + formattedValue);
        
        tdElements.each(function(i) {
            var tdValue = $(this).text().trim().replace(/\s+/g, ' '); // Get the text content of the current td element
            var thText = $('table.table-report thead th').eq($(this).index()).text(); // Get the corresponding th text from the thead
            
            var dynamicElement = $('<div class="col-span-12 sm:col-span-6">').append('<label>' + thText + '</label>')
                
            if(thText == "Poli"){
                dynamicElement.append('<div style="margin-top: 7px;"> <select class="tail-select w-full" name="'+colArray[i]+'">'+poli+'</select></div>');
                $.getScript("{{ asset('dist/js/app.js')}}", function() {});  
            }
            else if(window.location.href.indexOf("datajadwal") !== -1){
                if(thText == "ID Jadwal"){
                    dynamicElement.append('<input name="id_jdwdokter" type="text" class="input w-full border mt-2 flex-1" value="' + tdValue + '">');
                }else if(thText == "ID Dokter"){
                    dynamicElement.append('<input name="id_dokter" type="text" class="input w-full border mt-2 flex-1" value="' + tdValue + '">');
                }
                else if(thText == "Nama"){
                    dynamicElement.append('<input name="nama_dokter" type="text" class="input w-full border mt-2 flex-1" value="' + tdValue + '">');
                }else if(thText == "No Ruangan"){
                    dynamicElement.append('<select id="no_ruangan_jdw" class="tail-select w-full" name="no_ruangan">'+ruanganDkt+'</select>');
                    $.getScript("{{ asset('dist/js/app.js')}}", function() {}); 
                }
                else{
                    dynamicElement.append('<input name="'+colArray[i-3]+'" type="text" class="input w-full border mt-2 flex-1" value="' + tdValue + '">');
                }
            }else if (window.location.href.indexOf("datapoli") !== -1){
                if(thText == "Jenis Poli"){
                    dynamicElement.append('<select class="tail-select w-full" name="jenis_poli"><option value="Umum">Umum</option><option value="Spesialis">Spesialis</option></select>');
                    $.getScript("{{ asset('dist/js/app.js')}}", function() {}); 
                }else{
                    dynamicElement.append('<input name="'+colArray[i]+'" type="text" class="input w-full border mt-2 flex-1" value="'+tdValue+'">');
                }
            }
            else{
                dynamicElement.append('<input name="'+colArray[i]+'" type="text" class="input w-full border mt-2 flex-1" value="' + tdValue + '">');
            }

            dynamicElements.append(dynamicElement);

            if(window.location.href.indexOf("datadokter") !== -1  || window.location.href.indexOf("datasuster") !== -1 ||  window.location.href.indexOf("datastaffpendaftaran") !== -1  ){
                if(thText == "ID Dokter" || thText == "ID Suster" || thText == "ID Staff Pendaftaran"){
                    dynamicElements.append('<div class="col-span-12 sm:col-span-6"> <label>Password</label> <input name="password" type="password" class="input w-full border mt-2 flex-1"></div>')
                }
            }
        });
    }


    function initializeModal(modalId) {
        var modal = document.getElementById(modalId);
        var cancelButton = modal.querySelector('.button[data-dismiss="modal"]');

        function openModal() {
            modal.style.display = 'block';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                event.stopPropagation();
            }
        });

        cancelButton.addEventListener('click', function () {
            $("#selectContentBase > div > div").each(function() {
                $(this).css("background-color", "");
            });
            closeModal();
        });

        return {
            open: openModal,
            close: closeModal
        };
    }


    var editModal = initializeModal("editmodal");
    var createModal = initializeModal("createmodal");
    var selecttModal = initializeModal("selectmodal");

    function openModal() {
        editModal.open();
        createModal.open();
    }

    function closeModal() {
        editModal.close();

        createModal.close();
    }
</script>