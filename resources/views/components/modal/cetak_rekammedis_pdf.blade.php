


<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <style>
            .styled-table {
                border-collapse: collapse;
                margin: 25px 0;
                font-size: 0.9em;
                font-family: sans-serif;
                width: 100%;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            }

            .styled-table thead tr {
                background-color: #009879;
                color: #ffffff;
                text-align: left;
            }

            .styled-table th,
            .styled-table td {
                padding: 12px 15px;
                text-align: left;
            }

            .styled-table tbody tr {
                border-bottom: 1px solid #dddddd;
            }

            .styled-table tbody tr:nth-of-type(even) {
                background-color: #f3f3f3;
            }

            .styled-table tbody tr:last-of-type {
                border-bottom: 2px solid #009879;
            }

            .styled-table tbody tr.active-row {
                font-weight: bold;
                color: #000000;
            }

            .rectangle {
                text-align: center;
                position: relative;
                height: 125px;
                border: 1px solid #000;
            }
            .ttd-wrapper {
                position: absolute;
                bottom: 1;
                width: 100%;
                text-align: center;
            }
            #ttd {
                display: inline-block;
                background-color: #fff;
                padding: 5px 10px;
            }

    

        </style>
    </head>
    <body>

<div id="rekam-medis-form">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 15%; text-align: left;">
                <img src="{{public_path('/images/logo_RS.png')}}" style="width: 120px; height: 120px;">
            </td>
            <td style="width: 70%; text-align: center;">
                <div class="title">
                    <span class="logo-title" style="font-size: x-large; font-weight: bold">REKAM MEDIS RAWAT JALAN</span><br>
                    <span class="logo-subtitle" style="font-size: large; font-weight: bold">RS MEDIKA BATANG HARI</span><br>
                    <span class="logo-subtitle" style="font-size: large; "> Jl. Gajah Mada, Teratai, Kec. Muara Bulian, Kabupaten Batang Hari, Jambi 36613</span><br>
                   
                </div>
            </td>
            <td style="width: 15%; text-align: right;"></td>
        </tr>
    </table>
    <hr style="border-top: 3px solid #000000;">

    <div class="info-rekammedis" style="margin-top: 2px">
        <table style="border-collapse: separate; border-spacing: 10px 16px;">
        @foreach($dktpsn as $value)
            <tr>                   
                <td>No. Rekam Medis</td>
                <td>: {{ $value->no_rekmed }}</td>
            </tr>
            <tr>
                <td>Nama Pasien</td>
                <td>: {{ $value->nama_psn }} </td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td>: {{ $value->tgl_lahir }}</td>
         @endforeach
            </tr>
        </table>
    </div>
    <div id="formBaseKonfirmasi" class="p-5">
        <div class="section">
            <div style="font-weight: bold; font-size: x-large; margin-top: 20px;">Diagnosa</div>
            <table style="border-collapse: separate; border-spacing: 10px; 5px">
            @foreach($pemsps as $value)
                <tr>
                    <td>Diagnosa Utama</td>
                    <td>: {{ $value->diagnosa_utama }}</td>
                </tr>
                <tr>
                    <td>Komplikasi</td>
                    <td>: {{ $value->komplikasi }}</td>
                </tr>
                <tr>
                    <td>Diagnosa Tambahan</td>
                    <td>: {{ strip_tags($value->diagnosa_tambahan )}}</td>
                </tr>
                <tr>
                    <td>Tindakan Medis</td>
                    <td>: {{ strip_tags($value->tindakan_medis) }}</td>
                </tr>
            @endforeach
            </table>
        </div>
        <div class="section" style="margin-top: 20px;">
            <div style="font-weight: bold; font-size: x-large;">Catatan Khusus</div>
            <table style="border-collapse: separate; border-spacing: 10px; 5px">
            @foreach($ck as $value)
            @if(isset($value->alergi))
                <tr>
                    <td>Alergi</td>
                    <td>: {{ $value->alergi }}</td>
                </tr>
            @endif
            @if(isset($value->tranfusi))
                <tr>
                    <td>Tranfusi</td>
                    <td>: {{ $value->tranfusi }}</td>
                </tr>
            @endif
            @if(isset($value->golongan_darah))
                <tr>
                    <td>Golongan Darah</td>
                    <td>: {{ $value->golongan_darah }}</td>
                </tr>
            @endif
            @if(isset($value->penyakit_berat))
                <tr>
                    <td>Penyakit Berat</td>
                    <td>: {{ $value->penyakit_berat }}</td>
                </tr>
            @endif
            @if(isset($value->penyakit_menular))
                <tr>
                    <td>Penyakit Menular</td>
                    <td>: {{ $value->penyakit_menular }}</td>
                </tr>
            @endif
            @endforeach
            </table>
        </div>
        <div id="resepObatConfirm" class="section" style="margin-top: 10px;">
            <h2 style="font-weight: bold; font-size: x-large;">Resep Obat</h2>
        @if(count($ro) > 0 )
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th>Jenis Obat</th>
                        <th>Dosis</th>
                        <th>Satuan</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ro as $value)
                    <tr class="active-row">
                        <td>{{ $value->nama_obat }} </td>
                        <td>{{ $value->tipe }} </td>
                        <td>{{ $value->dosis }} </td>
                        <td>{{ $value->satuan }} </td>
                        <td>{{ $value->catatan }} </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nama Dokter Pemeriksa</th>
                <th>Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dktpsn as $value)
            <tr>
                <td style="width: 450px">
                    <div class="rectangle">{{$value->nama_dkt}}</div>
                </td>
                <td style="width: 250px">
                    <div class="rectangle">
                        <div class="ttd-wrapper">
                            <div id="ttd">{{ \Carbon\Carbon::parse($value->tgl_daftar)->isoFormat('D MMMM YYYY') }}</div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
        
</div>
    </body>
</html>

           
      