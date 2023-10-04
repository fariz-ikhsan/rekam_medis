
<div class="modal" id="konfirmasiPemeriksaanModal">
    <div class="modal__content modal__content--lg">
        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">
            <h2 class="font-medium text-base mr-auto">Hasil Pemeriksaan</h2> <a onclick="pdf()" class="button border items-center text-gray-700 dark:border-dark-5 dark:text-gray-300 hidden sm:flex"></a>
            <div class="dropdown sm:hidden"> <a class="dropdown-toggle w-5 h-5 block" href="javascript:;"> <i data-feather="more-horizontal" class="w-5 h-5 text-gray-700 dark:text-gray-600"></i> </a>
                <div class="dropdown-box w-40">
                    <div class="dropdown-box__content box dark:bg-dark-1 p-2"> <a href="javascript:;" class="flex items-center p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md"> <i data-feather="file" class="w-4 h-4 mr-2"></i> Download Docs </a> </div>
                </div>
            </div>
        </div>
        </head>
        <div id="rekam-medis-form">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 15%; text-align: left;">
                        <img src="/storage/images/logo_RS.png"  style="width: 75px; height: 75px;">
                    </td>
                    <td style="width: 70%; text-align: center;">
                        <div class="title">
                            <span class="logo-title" style="font-size: x-large; font-weight: bold">REKAM MEDIS RAWAT JALAN</span><br>
                            <span class="logo-subtitle" style="font-size: large; font-weight: bold">RS MEDIKA BATANG HARI</span><br>
                        </div>
                    </td>
                    <td style="width: 15%; text-align: right;">

                    </td>
                </tr>
            </table>
            <hr style="border-top: 3px solid #000000;">
            <div style="text-align: center;">
                <h2></h2>
            </div>
            <div class="info-rekammedis">
                <table style="border-collapse: separate; border-spacing: 10px 16px;">
                    @foreach($rm_psn as $value)
                    <tr>                   
                        <td>No. Rekam Medis</td>
                        <td>: {{ $value->no_rekmed }}</td>
                    </tr>
                    <tr>
                        <td>Nama Pasien</td>
                        <td>: {{ $value->nama }} </td>
                    </tr>
                    @endforeach
                  
                    <tr>
                        @foreach($datadokter as $value)
                            <td>Pemeriksa</td>
                            <td>: {{ $value->nama }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($rm_psn as $value)
                            <td>Tanggal Periksa</td>
                            <td>: {{ $value->now }}</td>
                        @endforeach
                    </tr>
                </table>
            </div>
        <div id="formBaseKonfirmasi" class="p-5">
            <div class="section">
                <h2 style="font-weight: bold; font-size: x-large;">Diagnosa</h2>
                <table style="border-collapse: separate; border-spacing: 5px;">
                    <tr>
                        <td>Diagnosa Utama</td>
                        <td id="tdDU">: </td>
                    </tr>
                    <tr>
                        <td>Komplikasi</td>
                        <td id="tdKP">: </td>
                    </tr>
                    <tr>
                        <td>Diagnosa Tambahan</td>
                        <td id="tdDT">: </td>
                    </tr>
                    <tr>
                        <td>Tindakan Medis</td>
                        <td id="tdTM">: </td>
                    </tr>
                </table>
            </div>
            <div class="section" style="margin-top: 10px;">
                <h2 style="font-weight: bold; font-size: x-large;">Catatan Khusus</h2>
                <table style="border-collapse: separate; border-spacing: 5px;">
                    <tr>
                        <td>Alergi</td>
                        <td id="tdALG">: </td>
                    </tr>
                    <tr>
                        <td>Tranfusi</td>
                        <td id="tdTRF">: </td>
                    </tr>
                    <tr>
                        <td>Golongan Darah</td>
                        <td id="tdGDR">: </td>
                    </tr>
                    <tr>
                        <td>Penyakit Berat</td>
                        <td id="tdPBT">: </td>
                    </tr>
                    <tr>
                        <td>Penyakit Menular</td>
                        <td id="tdPMR">: </td>
                    </tr>
                </table>
            </div>
            <div id="resepObatConfirm" class="section" style="margin-top: 10px;">
                <h2 style="font-weight: bold; font-size: x-large;">Resep Obat</h2>

            </div>
        </div>
        </div>
           
        <div class="px-5 py-3 text-right border-t border-gray-200 dark:border-dark-5"> 
            <a data-target="#konfirmasiPemeriksaanModal" data-dismiss="modal" type="button" class="button w-20 border text-gray-700 dark:border-dark-5 dark:text-gray-300 mr-1">Batalkan</a> 
            <button onclick="lanjutSimpanRM()" type="button" class="button bg-theme-1 text-white">Lanjut Simpan</button> </div>
        <script>
            function lanjutSimpanRM(){
                
                $("#btnSubmit").click();
            }

        </script>
    </div>
</div>

