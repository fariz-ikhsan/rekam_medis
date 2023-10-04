<div id="contentPemeriksaan" style="display: none">
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto" style="font-size: 30px;">
            Pemeriksaan
        </h2>
    </div>
    @include('components.modal.konfirmasi_pemeriksaan_modal')
    <!-- BEGIN: Form Layout -->
    <div class="intro-y box py-10 sm:py-20 mt-5">
        <div class="wizard flex flex-col lg:flex-row justify-center px-5 sm:px-20">
            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                <button id="btnPemeriksaanDiagnosa" onclick="diagnosaSession()" class="w-10 h-10 rounded-full button text-white bg-theme-1">1</button>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Diagnosa</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                <button id="btnPemeriksaanCatatanKhusus" onclick="catatanKhususSession()" class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200 dark:bg-dark-1">2</button>
                <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-gray-700 dark:text-gray-600">Catatan Khusus</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                <button id="btnPemeriksaanResepObat" onclick="resepObatSession()" class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200 dark:bg-dark-1">3</button>
                <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-gray-700 dark:text-gray-600">Resep Obat</div>
            </div>
            <div class="wizard__line hidden lg:block w-2/3 bg-gray-200 dark:bg-dark-1 absolute mt-5"></div>
        </div>
        <form action="" method="POST" id="formRequestor">
            @csrf
            @method('POST')
            <div id="diagnosaSessionForm" class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
                <div  class="font-medium text-base">Form Diagnosa</div>
                <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Diagnosa Utama</div>
                        <input name="diagnosa_utama" type="text" class="input w-full border flex-1" style="background-color:whitesmoke;" required>
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Komplikasi</div>
                        <input name="komplikasi" type="text" class="input w-full border flex-1" style="background-color:whitesmoke;" required>
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Diagnosa Tambahan</div>
                        <div id="txtEditor_diagnosaTambahan"  style="height: 100px;"></div>
                        <input id="diagnosa_tambahan_value" type="hidden" name="diagnosa_tambahan">
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Tindakan Medis</div>
                        <div id="txtEditor_tindakanMedis" style="height: 100px;"></div>
                        <input id="tindakan_medis_value" type="hidden" name="tindakan_medis">
                    </div>
                    <div class="intro-y col-span-12 flex items-center justify-center sm:justify-end mt-5">
                        <button type="button" onclick="catatanKhususSession()" class="button w-24 justify-center block bg-theme-1 text-white ml-2">Lanjut</button>
                    </div>
                </div>
            </div>
            
            <div id="catatanKhususSessionForm" class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
                <div  class="font-medium text-base">Form Catatan Khsusus</div>
                <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Alergi</div>
                        <input name="alergi" type="text" class="input w-full border flex-1" style="background-color:whitesmoke;">
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Tranfusi</div>
                        <input name="tranfusi" type="text" class="input w-full border flex-1" style="background-color:whitesmoke;">
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Golongan Darah</div>
                        <input name="golongan_darah" type="text" class="input w-full border flex-1" style="background-color:whitesmoke;">
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Penyakit Berat</div>
                        <input name="penyakit_berat" type="text" class="input w-full border flex-1" style="background-color:whitesmoke;">
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <div class="mb-2">Penyakit Menular</div>
                        <input name="penyakit_menular" type="text" class="input w-full border flex-1" style="background-color:whitesmoke;">
                    </div>
                    <div class="intro-y col-span-12 flex items-center justify-center sm:justify-end mt-5">
                        <button type="button" onclick="diagnosaSession()" class="button w-24 justify-center block bg-gray-200 text-gray-600 dark:bg-dark-1 dark:text-gray-300">Kembali</button>
                        <button type="button" onclick="resepObatSession()" class="button w-24 justify-center block bg-theme-1 text-white ml-2">Lanjut</button>
                    </div>
                </div>
            </div>
            <div id="resepObatSessionForm" class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
                <div  class="font-medium text-base">Form Resep Obat</div>
                <div class="overflow-x-auto">
                    <div class="sm:grid grid-cols-2 gap-2">
                        <div class="relative mt-2">
                            <div class="pl-3">
                                <input id="inptResepObatValue" name="resep_obat_value" type="text" style="display: none">
                                <div class="mb-2">Nama Obat</div>
                                <input id="inptNamaObat" type="text" class="input w-full border col-span-4" placeholder="Nama Obat">
                            </div>
                        </div>
                        <div class="relative mt-2">
                            <div style="display: flex">
                                <div class="pl-3">
                                    <div class="mb-2">Dosis</div>
                                    <input id="inptDosis" type="text" class="input w-full border col-span-4" placeholder="Dosis">
                                </div>
                                <div class="pl-3" style="width: 20%">
                                    <div class="mb-2">Satuan</div>
                                    <input id="inptSatuan" type="text" class="input w-full border col-span-4" placeholder="Satuan">
                                </div>
                            </div>
                                
                        </div>
                        <div class="relative mt-2">
                            <div class="pl-3">
                                <div class="mb-2">Tipe Obat</div>
                                <select id="selectObat" class="tail-select w-full">
                                    <option value="Obat Oral">Obat Oral</option>
                                    <option value="Obat Topikal">Obat Topikal</option>
                                    <option value="Obat Injeksi">Obat Injeksi</option>
                                </select>
                                <div class="intro-y col-span-12 flex sm:justify-start mt-5" style="z-index: 1;">
                                    <button onclick="tambahResep()" type="button" class="button w-24 inline-block mr-1 mb-2 bg-theme-1 text-white">Buat Resep</button>
                                </div>
                            </div>
                        </div>
                        <div class="relative mt-2">
                            <div class="pl-3">
                                <div class="mb-2">Catatan</div>
                                <input id="inptCatatan" type="text" class="input w-full border col-span-4" placeholder="Catatan">
                            </div>
                        </div>
                    </div>
                    <table id="tblResp" class="table mt-5">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700">
                                <th class="whitespace-no-wrap">Nama Obat</th>
                                <th class="whitespace-no-wrap">Jenis Obat</th>
                                <th class="whitespace-no-wrap">Dosis</th>
                                <th class="whitespace-no-wrap">Satuan</th>
                                <th class="whitespace-no-wrap">Catatan</th>
                                <th class="whitespace-no-wrap">OPSI</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyResepObat">

                        </tbody>
                    </table>
                    <script>
                        function hapusBaris(tr) {
                            tr.remove(); // Menghapus elemen <tr> dari dokumen
                        }
                         
                        function tambahResep(){
                            var markup = '<tr>'+
                                            '<td class="border-b dark:border-dark-5"><div contenteditable>'+$("#inptNamaObat").val()+'</div></td>'+
                                            '<td class="border-b dark:border-dark-5"><div contenteditable>'+$("#selectObat").val()+'</div></td>'+
                                            '<td class="border-b dark:border-dark-5"><div contenteditable>'+$("#inptDosis").val()+'</div></td>'+
                                            '<td class="border-b dark:border-dark-5"><div contenteditable>'+$("#inptSatuan").val()+'</div></td>'+
                                            '<td class="border-b dark:border-dark-5"><div contenteditable>'+$("#inptCatatan").val()+'</div></td>'+
                                            '<td class="border-b dark:border-dark-5"><button type="button" onclick="hapusBaris(this.parentNode.parentNode)" class="button w-15 inline-block mr-1 mb-2 bg-theme-6 text-white">Hapus</button></td>'+
                                        '</tr>';
                            if($("#tblRespForm")){
                                $("#tblRespForm").remove()
                            }
                            
                            $("#tbodyResepObat").append(markup);
                            $("#inptNamaObat").val("")
                            $("#inptDosis").val("")
                            $("#inptCatatan").val("")
                        }
                    </script>
                </div>
                <div class="intro-y col-span-12 flex sm:justify-end mt-5">
                    <a onclick="catatanKhususSession()" type="button" class="button w-24 mr-1 mb-2  bg-gray-200 text-gray-600">Kembali</a>
                    <a href="javascript:;"  id="btnSubmitExecutor" data-toggle="modal" data-target="#konfirmasiPemeriksaanModal" class="button w-24 mr-1 mb-2 bg-theme-9 text-white">Simpan</a>
                    
                </div>
            </div>
            <button id="btnSubmit" type="submit"></button>
        </form>
    </div>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
         $(document).ready(function(){
            var segments =  window.location.href.split('/');
            var lastSegment = segments[segments.length - 1];

            $("#btnSubmitExecutor").click(function() {
                var diagnosaUtamaInput = $('input[name="diagnosa_utama"]').val();
                var komplikasiInput = $('input[name="komplikasi"]').val();
                var diagnosaTambahanInput = $("#txtEditor_diagnosaTambahan .ql-editor").text()
                var tindakanMedisInput = $("#txtEditor_tindakanMedis .ql-editor").text()
                var alergiInput = $('input[name="alergi"]').val();
                var tranfusiInput = $('input[name="tranfusi"]').val();
                var golonganDarahInput = $('input[name="golongan_darah"]').val();
                var penyakitBeratInput = $('input[name="penyakit_berat"]').val();
                var penyakitMenularInput = $('input[name="penyakit_menular"]').val();

                $("#tdDU").text(": "+diagnosaUtamaInput);
                $("#tdKP").text(": "+komplikasiInput);
                $("#tdDT").text(": "+diagnosaTambahanInput);
                $("#tdTM").text(": "+tindakanMedisInput);
                $("#tdALG").text(": "+alergiInput);
                $("#tdTRF").text(": "+tranfusiInput);
                $("#tdGDR").text(": "+golonganDarahInput);
                $("#tdPBT").text(": "+penyakitBeratInput);
                $("#tdPMR").text(": "+penyakitMenularInput);

                if($("#tblRespForm")){
                    $("#tblRespForm").remove()
                }

                var query = "";
                if ($("#tblResp #tbodyResepObat tr").length > 0) {

                    $("#resepObatConfirm").append($("#tblResp").clone().attr("id","tblRespForm"))

                    $('#tblRespForm thead th:eq(5)').remove(); 
                    $('#tblRespForm tbody tr').each(function () {
                        $(this).find('td:eq(5)').remove(); 
                    });
    
                    $("#tblResp #tbodyResepObat tr").each(function() {
                    var medicineName = $(this).find("td:eq(0) div").text();
                    var medicineType = $(this).find("td:eq(1) div").text();
                    var medicineDose = $(this).find("td:eq(2) div").text();
                    var medicineParam = $(this).find("td:eq(3) div").text();
                    var medicineDesc = $(this).find("td:eq(4) div").text();
                    
                        query += '(NULL, "'+medicineName+'", '+medicineDose+',"'+medicineParam+'", "'+medicineType+'", "'+medicineDesc+'", "'+lastSegment.replace('vs', 'sps')+'")';
                        query = query.replace(')(', '),(')
                    });
                    
                    $("#inptResepObatValue").val('INSERT INTO `resep_obat` (`id_resep_obat`, `nama_obat`, `dosis`, `satuan`, `tipe`, `catatan`, `id_spesialistik`) VALUES'+query)
                }else{
                    $("#inptResepObatValue").val("TIDAK_ADA_RESEP_OBAT");
                }
                
            });

            $('#formRequestor').submit(function(event) {
                event.preventDefault(); 
                var formData = $('#formRequestor').serialize();
                $.ajax({
                    type: 'POST', 
                    url: window.location.href, 
                    data: formData, 
                    success: function(response) {
                        window.open("http://127.0.0.1:8000/spesialistik/cetakpdf/"+lastSegment.replace('vs', 'sps'), '_blank');
                        setTimeout(function() {
                            window.open("http://127.0.0.1:8000/spesialistik/")
                        }, 5000);
                        
                    },
                    error: function(error) {

                        console.error(error);
                    }
                });
            });
        });
        

        //TEXT EDITOR
        var quill1 = new Quill('#txtEditor_diagnosaTambahan', {
                theme: 'snow'
            });

            var quill2 = new Quill('#txtEditor_tindakanMedis', {
                theme: 'snow'
            });
            var form = document.querySelector('#formRequestor');
            form.onsubmit = function() {
                // Ambil konten dari setiap editor dan simpan dalam input yang sesuai
                document.querySelector('input[name=diagnosa_tambahan]').value = quill1.root.innerHTML;
                document.querySelector('input[name=tindakan_medis]').value = quill2.root.innerHTML;
            };
             
        $("#btnPemeriksaanDiagnosa").click()
       
        function diagnosaSession(){
            $("#btnSubmit").css("display","none")

            $("#diagnosaSessionForm").css("display","")
            $("#catatanKhususSessionForm").css("display","none")
            $("#resepObatSessionForm").css("display","none")
        
            $("#btnPemeriksaanDiagnosa").attr("class","w-10 h-10 rounded-full button text-white bg-theme-1")
            $("#btnPemeriksaanCatatanKhusus").attr("class","w-10 h-10 rounded-full button text-gray-600 bg-gray-200 dark:bg-dark-1")
            $("#btnPemeriksaanResepObat").attr("class","w-10 h-10 rounded-full button text-gray-600 bg-gray-200 dark:bg-dark-1")
        }
        
        function catatanKhususSession(){
            var val_du = $('input[name="diagnosa_utama"]').val().length
            var val_kp = $('input[name="komplikasi"]').val().length
            $("#btnSubmit").css("display","none")

            if(val_du != 0 && val_kp != 0 ){
                $("#catatanKhususSessionForm").css("display","")
                $("#diagnosaSessionForm").css("display","none")
                $("#resepObatSessionForm").css("display","none")

                $("#btnPemeriksaanCatatanKhusus").attr("class","w-10 h-10 rounded-full button text-white bg-theme-1")
                $("#btnPemeriksaanDiagnosa").attr("class","w-10 h-10 rounded-full button text-gray-600 bg-gray-200 dark:bg-dark-1") 
                $("#btnPemeriksaanResepObat").attr("class","w-10 h-10 rounded-full button text-gray-600 bg-gray-200 dark:bg-dark-1")    
            }else{
                $("#btnSubmit").click()
            }   
        }

        function resepObatSession(){
            var val_du = $('input[name="diagnosa_utama"]').val().length
            var val_kp = $('input[name="komplikasi"]').val().length
            $("#btnSubmit").css("display","")

            if(val_du != 0 && val_kp != 0 ){

                $("#resepObatSessionForm").css("display","")
                $("#diagnosaSessionForm").css("display","none")
                $("#catatanKhususSessionForm").css("display","none")
            
                $("#btnPemeriksaanResepObat").attr("class","w-10 h-10 rounded-full button text-white bg-theme-1")
                $("#btnPemeriksaanDiagnosa").attr("class","w-10 h-10 rounded-full button text-gray-600 bg-gray-200 dark:bg-dark-1")
                $("#btnPemeriksaanCatatanKhusus").attr("class","w-10 h-10 rounded-full button text-gray-600 bg-gray-200 dark:bg-dark-1")   
            }else{
                $("#btnSubmit").click()
            }
             
            
        }
</script>
</div>