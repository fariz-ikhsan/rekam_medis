<?php


namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use DateTime;


class SpesialistikController extends Controller

{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $datadokter = Session::get('dokter');

        $dokterdata = DB::table('user')
            ->join('dokter', 'dokter.login_id', '=', 'user.login_id')
            ->where('user.login_id',  $datadokter[0]->login_id)
            ->select('photo', 'dokter.nama')
            ->get();

                //Menghasilkan jumlah count kunjungan pasien dari bulan ini dar bulan kebelakang sebanyak 3 baris
        $chart = DB::table('pemeriksaan_spesialistik as pemsps')
                ->join('vital_sign as pvs', 'pvs.id_vitalsign', '=', 'pemsps.id_vitalsign')
                ->join('pendaftaran as pdf', 'pdf.id_pendaftaran', '=', 'pvs.id_pendaftaran')
                ->join('jadwal_dokter as jdw', 'jdw.id_jdwdokter', '=', 'pdf.id_jdwdokter')
                ->join('dokter as dkt', 'dkt.id_dokter', '=', 'jdw.id_dokter')
                ->selectRaw('DATE_FORMAT(pdf.tgl_daftar, "%Y-%m") AS bulan, COUNT(*) AS jumlah_data')
                ->where('login_id', '=', $datadokter[0]->login_id)
                ->where(function ($query) {
                    $query->where('pdf.tgl_daftar', '<=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 1 MONTH)'))
                        ->orWhereRaw('MONTH(pdf.tgl_daftar) = MONTH(CURDATE())');
                })
                ->groupBy(DB::raw('DATE_FORMAT(pdf.tgl_daftar, "%Y-%m")'))
                ->orderBy(DB::raw('DATE_FORMAT(pdf.tgl_daftar, "%Y-%m")'), 'desc')
                ->limit(3)->get();

        $antrian = DB::table('vital_sign as pvs')
                ->join('pendaftaran as pdf', 'pdf.id_pendaftaran', '=', 'pvs.id_pendaftaran')
                ->join('jadwal_dokter as jdw', 'jdw.id_jdwdokter', '=', 'pdf.id_jdwdokter')
                ->join('dokter as dkt', 'dkt.id_dokter', '=', 'jdw.id_dokter')
                ->where('dkt.login_id', '=', $datadokter[0]->login_id)
                ->where('pdf.status', '=', 'Belum Periksa')
                ->selectRaw('count(*) as hitung')
                ->get();

        $jmlKunjunganHariIni = DB::table('vital_sign AS pvs')
                ->join('pendaftaran AS pdf', 'pdf.id_pendaftaran', '=', 'pvs.id_pendaftaran')
                ->join('jadwal_dokter AS jdw', 'jdw.id_jdwdokter', '=', 'pdf.id_jdwdokter')
                ->join('dokter AS dkt', 'dkt.id_dokter', '=', 'jdw.id_dokter')
                ->where('dkt.login_id', '=',  $datadokter[0]->login_id)
                ->where('pdf.status', '=', 'Sudah Periksa')
                ->whereDate('pdf.tgl_daftar', '=', now()->toDateString())
                ->selectRaw('count(*) as hitung')
                ->get();

        $jmlKunjunganBlnIni = DB::table('vital_sign as pvs')
                ->join('pendaftaran as pdf', 'pdf.id_pendaftaran', '=', 'pvs.id_pendaftaran')
                ->join('jadwal_dokter as jdw', 'jdw.id_jdwdokter', '=', 'pdf.id_jdwdokter')
                ->join('dokter as dkt', 'dkt.id_dokter', '=', 'jdw.id_dokter')
                ->where('dkt.login_id',  $datadokter[0]->login_id)
                ->where('pdf.status', 'Sudah Periksa')
                ->whereRaw('MONTH(pdf.tgl_daftar) = MONTH(CURDATE())')
                ->selectRaw('count(*) as hitung')
                ->get();

        $jmlKunjunganKeseluruhan = DB::table('vital_sign as pvs')
                ->join('pendaftaran as pdf', 'pdf.id_pendaftaran', '=', 'pvs.id_pendaftaran')
                ->join('jadwal_dokter as jdw', 'jdw.id_jdwdokter', '=', 'pdf.id_jdwdokter')
                ->join('dokter as dkt', 'dkt.id_dokter', '=', 'jdw.id_dokter')
                ->where('dkt.login_id',  $datadokter[0]->login_id)
                ->where('pdf.status', 'Sudah Periksa')
                ->selectRaw('count(*) as hitung')
                ->get();
                
        return view ('contents.transaksi.halaman_utama_spesialistik')->with(["datadokter"=> $dokterdata,'chart' => $chart, 'antrian' => $antrian, 'jmlKunjunganHariIni' => $jmlKunjunganHariIni, 'jmlKunjunganBlnIni' => $jmlKunjunganBlnIni, 'jmlKunjunganKeseluruhan' => $jmlKunjunganKeseluruhan]);
    }
    
    public function antrianPasien(Request $request)
    {
        $datadokter = Session::get('dokter');
        $dktdata = DB::table('user')
            ->join('dokter', 'dokter.login_id', '=', 'user.login_id')
            ->where('user.login_id',  $datadokter[0]->login_id)
            ->select('photo', 'dokter.nama')
            ->get();

        $vitalsign = DB::table('vital_sign AS vs')
                    ->join('pendaftaran AS pdf', 'pdf.id_pendaftaran', '=', 'vs.id_pendaftaran')
                    ->join('pasien', 'pasien.no_rekmed', '=', 'pdf.no_rekmed')
                    ->join('jadwal_dokter AS jdw', 'jdw.id_jdwdokter', '=', 'pdf.id_jdwdokter')
                    ->join('dokter AS dkt', 'dkt.id_dokter', '=', 'jdw.id_dokter')
                    ->where('dkt.login_id', '=', $datadokter[0]->login_id)
                    ->where('pdf.status', '=', 'Belum Periksa')
                    ->select('pdf.no_rekmed', 'pasien.nama','id_vitalsign')
                    ->get();

        if (!count($request->all())) {
            $pasien = DB::table('vital_sign AS vs')
                        ->join('pendaftaran AS pdf', 'pdf.id_pendaftaran', '=', 'vs.id_pendaftaran')
                        ->join('pasien', 'pasien.no_rekmed', '=', 'pdf.no_rekmed')
                        ->join('jadwal_dokter AS jdw', 'jdw.id_jdwdokter', '=', 'pdf.id_jdwdokter')
                        ->join('dokter AS dkt', 'dkt.id_dokter', '=', 'jdw.id_dokter')
                        ->where('login_id', '=', $datadokter[0]->login_id)
                        ->where('pdf.status', '=', 'Belum Periksa')
                        ->select('pdf.no_rekmed', 'pasien.nama','id_vitalsign')->paginate(5);
                        
            return view ('contents.transaksi.antrian_pasien_spesialistik')->with(["datadokter"=> $dktdata,'data'=> $pasien]);
        }else{
            if($request->ajax()){
                $searchData = $request->searchdata;
                $data = DB::table('vital_sign AS vs')
                ->join('pendaftaran AS pdf', 'pdf.id_pendaftaran', '=', 'vs.id_pendaftaran')
                ->join('pasien', 'pasien.no_rekmed', '=', 'pdf.no_rekmed')
                ->join('jadwal_dokter AS jdw', 'jdw.id_jdwdokter', '=', 'pdf.id_jdwdokter')
                ->join('dokter AS dkt', 'dkt.id_dokter', '=', 'jdw.id_dokter')
                ->where('login_id', '=', $datadokter[0]->login_id)
                ->where('pdf.status', '=', 'Belum Periksa')
                ->where(function ($query) use ($searchData) {
                    $query->where('pdf.no_rekmed', 'LIKE', '%' . $searchData . '%')
                        ->orWhere('pasien.nama', 'LIKE', '%' . $searchData . '%');
                })
                ->select('pdf.no_rekmed', 'pasien.nama','id_vitalsign')
                ->paginate(5);

                return view("contents.search.search_antrianpasien")->with("data",$data);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function rekammedis($id, Request $request)
    {
        $datadokter = Session::get('dokter');
        $no_rm = DB::table('vital_sign as vs')
        ->join('pendaftaran as pdf', 'pdf.id_pendaftaran', '=', 'vs.id_pendaftaran')
        ->where('vs.id_vitalsign', $id)
        ->select('no_rekmed')
        ->first();

        $dktdata = DB::table('user')
            ->join('dokter', 'dokter.login_id', '=', 'user.login_id')
            ->where('user.login_id',  $datadokter[0]->login_id)
            ->select('photo', 'dokter.nama')
            ->get();

        $rm_psn = DB::table('pasien')
        ->select('no_rekmed', 'nama')
        ->selectRaw("DATE_FORMAT(CURDATE(), '%d %M %Y') AS now")
        ->where('no_rekmed', $no_rm->no_rekmed)
        ->get();

        $tglrm = DB::table('pendaftaran')
                ->select(DB::raw('min(tgl_daftar) as tgl_awal'), DB::raw('max(tgl_daftar) as tgl_akhir'))
                ->where('no_rekmed', $no_rm->no_rekmed)
                ->first();

        if (!count($request->all())) {
           
            return view ('contents.transaksi.rekam_medis')->with(["datadokter"=> $dktdata, "rm_psn" => $rm_psn, "tgl_rm"=> $tglrm]);
            
        }else{
            if($request->ajax()){
                $searchData = $request->searchdata;
                $startDate = DateTime::createFromFormat('j M, Y', $request->startdate)->format('Y-m-d');
                $endDate = DateTime::createFromFormat('j M, Y', $request->enddate)->format('Y-m-d');
                
                $data = DB::table('vital_sign AS vs')
                        ->select('vs.id_vitalsign','pdf.no_rekmed', 'pdf.tgl_daftar', 'vs.berat_badan', 'vs.tekanan_darah', 'vs.denyut_nadi', 'vs.spo2', 'vs.suhu', 'vs.respiration_rate', 'pemsps.diagnosa_utama', 'pemsps.komplikasi', 'pemsps.diagnosa_tambahan', 'pemsps.tindakan_medis')
                        ->join('pendaftaran AS pdf', 'pdf.id_pendaftaran', '=', 'vs.id_pendaftaran')
                        ->join('pemeriksaan_spesialistik AS pemsps', 'pemsps.id_vitalsign', '=', 'vs.id_vitalsign')
                        ->join('jadwal_dokter AS jdw', 'jdw.id_jdwdokter', '=', 'pdf.id_jdwdokter')
                        ->join('dokter AS dkt', 'dkt.id_dokter', '=', 'jdw.id_dokter')
                        ->where('pdf.no_rekmed', '=', $no_rm->no_rekmed)
                        ->where(function ($query) use ($searchData) {
                            $query->where('pemsps.diagnosa_utama', 'LIKE', '%' . $searchData . '%')
                                ->orWhere('pemsps.komplikasi', 'LIKE', '%' . $searchData . '%')
                                ->orWhere('dkt.nama', 'LIKE', '%' . $searchData . '%');
                        })
                        ->whereBetween('pdf.tgl_daftar', [$startDate, $endDate])
                        ->orderBy('pdf.tgl_daftar', 'desc')
                        ->get();

            $output='';
            if(count($data)>0){
                $output ='<div class="chat__chat-list overflow-y-auto scrollbar-hidden pr-1 pt-1 mt-0">';
                    
                    foreach($data as $index => $item){
                        $output .='
                        <div  onclick="detailrm(\''.$item->id_vitalsign.'\',this)" class="intro-x cursor-pointer box relative flex items-center p-5 mt-5">
                            <div class="ml-2 overflow-hidden">
                                <div class="flex items-center">
                                    <a class="font-medium">'.$item->diagnosa_utama.'</a> 
                                    <div class="text-xs text-gray-500 ml-auto" style="margin-left: 15px;">'. \Carbon\Carbon::parse($item->tgl_daftar)->format("j M, Y").'</div>
                                </div>
                                <div class="w-full truncate text-gray-600">'.$item->komplikasi.'</div>
                            </div>
                        </div>';
                    }
                        $output .='</div>';
                }
                else{
                    $output .='No results';
                }
                return $output;
            }
        }
    }
    
    public function manipulasiRekammedis(Request $request){
        if($request->ajax()){
            if($request->manipulasi_rm){
                $manipulasi_resep = $request->manipulasi_resep;
                $manipulasi_resep = str_replace("UPDATE resep_obat SET  WHERE id_resep_obat= ;", "", $manipulasi_resep);
                $manipulasi_resep = str_replace("', WHERE", "' WHERE", $manipulasi_resep);
            
                DB::table('pemeriksaan_spesialistik')
                ->where('id_spesialistik', $request->id_spesialistik)
                ->update([
                    'diagnosa_utama' => $request->diagnosa_utama,
                    'komplikasi' => $request->komplikasi,
                    'diagnosa_tambahan' => $request->diagnosa_tambahan,
                    'tindakan_medis' => $request->tindakan_medis,
                ]);

                DB::table('catatan_khusus')
                ->where('id_catkhusus', $request->id_catkhusus)
                ->update([
                    'alergi' => $request->alergi,
                    'tranfusi' => $request->tranfusi,
                    'gol_darah' => $request->gol_darah,
                    'penyakit_berat' => $request->penyakit_berat,
                    'penyakit_menular' => $request->penyakit_menular
                ]);
                DB::unprepared($manipulasi_resep);
            }
        
        }
        return "Sukses";
    }
    
    public function detailRekammedis(Request $request)
    {
        $datadokter = Session::get('dokter');
        if($request->ajax()){
            $searchData = $request->searchdata;
            $data = DB::table('vital_sign as vs')
                        ->select(
                            'dkt.id_dokter','dkt.nama as dokter_nama', 'pdf.tgl_daftar','ro.dosis', 'ro.tipe','ro.catatan',
                            'vs.berat_badan', 'vs.tekanan_darah', 'vs.denyut_nadi', 'vs.spo2', 'vs.suhu', 'vs.respiration_rate',
                            'pemsps.id_spesialistik','pemsps.diagnosa_utama', 'pemsps.komplikasi', 'pemsps.diagnosa_tambahan', 'pemsps.tindakan_medis',
                            'ro.nama_obat', 'ro.dosis', 'ro.tipe', 'ro.catatan',
                            'ck.id_catkhusus','ck.alergi', 'ck.tranfusi', 'ck.gol_darah', 'ck.penyakit_berat', 'ck.penyakit_menular',
                            'pdf.tgl_daftar'
                            )
                            ->join('pemeriksaan_spesialistik as pemsps', 'pemsps.id_vitalsign', '=', 'vs.id_vitalsign')
                            ->leftJoin('resep_obat as ro', 'ro.id_spesialistik', '=', 'pemsps.id_spesialistik')
                            ->leftJoin('catatan_khusus as ck', 'ck.id_spesialistik', '=', 'pemsps.id_spesialistik')
                            ->join('pendaftaran as pdf', 'pdf.id_pendaftaran', '=', 'vs.id_pendaftaran')
                            ->join('jadwal_dokter as jdw', 'jdw.id_jdwdokter', '=', 'pdf.id_jdwdokter')
                            ->join('dokter as dkt', 'dkt.id_dokter', '=', 'jdw.id_dokter')
                        ->where('vs.id_vitalsign', $request->idvs)
                        ->get();

            $resepObatData = DB::table('resep_obat')
                            ->join('pemeriksaan_spesialistik as pemsps', 'pemsps.id_spesialistik', '=', 'resep_obat.id_spesialistik')
                            ->select('id_resep_obat','nama_obat', 'dosis', 'tipe', 'catatan','satuan')
                            ->where('pemsps.id_spesialistik', $data->pluck("id_spesialistik")[0])
                            ->get();

            $output=''; $resepObat = '';
            if(count($data)>0){

            foreach($resepObatData as $index => $rso){
                $resepObat .='<tr class="origindiv">
                                <td style="display:none;">'.$rso->id_resep_obat.'</td>
                                <td class="border-b dark:border-dark-5">'.$rso->nama_obat.'</td>
                                <td class="border-b dark:border-dark-5">'.$rso->tipe.'</td>
                                <td class="border-b dark:border-dark-5">'.$rso->dosis.'</td>
                                <td class="border-b dark:border-dark-5">'.$rso->satuan.'</td>
                                <td class="border-b dark:border-dark-5">'.$rso->catatan.'</td>
                                <td class="editablediv" style="display:none;">
                                </td>
                            </tr>
                            <tr id="tr_editable_'.$index.'" class="editablediv" style="display:none;">
                                <td style="display:none;"><div>'.$rso->id_resep_obat.'</div></td>
                                <td class="border-b dark:border-dark-5"><div class="nama_obat" style=" border: 1px solid gray;" contenteditable>'.$rso->nama_obat.'</div></td>
                                <td class="border-b dark:border-dark-5"><div class="tipe" style=" border: 1px solid gray;" contenteditable>'.$rso->tipe.'</div></td>
                                <td class="border-b dark:border-dark-5"><div class="dosis" style=" border: 1px solid gray;" contenteditable>'.$rso->dosis.'</div></td>
                                <td class="border-b dark:border-dark-5"><div class="satuan" style=" border: 1px solid gray;" contenteditable>'.$rso->satuan.'</div></td>
                                <td class="border-b dark:border-dark-5"><div class="catatan" style=" border: 1px solid gray;" contenteditable>'.$rso->catatan.'</div></td>
                                <td class="editablediv" style="display:none;">
                                    <button onclick="this.closest(\'tr\').remove(); hapusresep_rm(\''.$rso->id_resep_obat.'\');" type="button" class="button w-15 mr-2 mb-2 flex items-center justify-center bg-theme-6 text-white"> <i data-feather="trash-2" class="w-4 h-4 mr-2"></i> Delete </button>
                                </td>
                            </tr>';
                }
               
            $output =''; 
            
            foreach($data as $index => $item){
                if($index < 1){
                    $btn ='';
                    if($datadokter[0]->id_dokter == $item->id_dokter){
                        $btn ='<div> <button type="button" onclick="ubahcatatanrm(\'tampil_ubah\')" type="button" class="button w-18 mr-1 mb-2 bg-theme-1 text-white">Ubah Catatan</button> </div>';
                    }
                    $output .='
                    <div class="intro-y text-justify leading-relaxed">
                       <div class="border-b border-gray-200 dark:border-dark-5 px-5 py-4" style="display: flex; justify-content: space-between;">
                                <div class="text-base font-medium">Dokter: '.$item->dokter_nama.'</div>
                                <div class="text-base">Tgl Periksa: '.\Carbon\Carbon::parse($item->tgl_daftar)->format("j M, Y").'</div>
                        '.$btn.'
                        </div>
                        <div class="editablediv strict_id" style="display:none;"> <input name="id_spesialistik" type="text" class="input border flex-1" style="background-color:whitesmoke; width:50%;" required value="'.$item->id_spesialistik.'"> </div>
                        <a class="block font-medium text-base mt-5">Diagnosa Utama</a>
                        <div class="origindiv"> <p class="mb-5">'.$item->diagnosa_utama.'</p> </div>
                        <div class="editablediv" style="display:none;"> <input name="diagnosa_utama" type="text" class="input border flex-1" style="background-color:whitesmoke; width:50%;" required value="'.$item->diagnosa_utama.'"> </div>
                        
                        <a class="block font-medium text-base mt-5">Komplikasi</a>
                        <div class="origindiv"> <p class="mb-5">'.$item->komplikasi.'</p> </div>
                        <div class="editablediv" style="display:none;"> <input name="komplikasi" type="text" class="input border flex-1" style="background-color:whitesmoke; width:50%;" required value="'.$item->komplikasi.'"> </div>
                        
                        <a class="block font-medium text-base mt-5">Diagnosa Tambahan</a>
                        <div class="origindiv"> <p class="mb-5">'.$item->diagnosa_tambahan.'</p> </div>
                        <div class="editablediv" style="display:none;"> <input name="diagnosa_tambahan" type="text" class="input border flex-1" style="background-color:whitesmoke; width:50%;" required value="'.$item->diagnosa_tambahan.'"> </div>
                        
                        <a class="block font-medium text-base mt-5">Tindakan Medis</a>
                        <div class="origindiv"> <p class="mb-5">'.$item->tindakan_medis.'</p> </div>
                        <div class="editablediv" style="display:none;"> <input name="tindakan_medis" type="text" class="input border flex-1" style="background-color:whitesmoke; width:50%;" required value="'.$item->tindakan_medis.'"> </div>

                        <a class="block font-medium text-base mt-5">Catatan Khusus</a>
                        <table class="table mt-5">
                            <thead>
                                <tr class="bg-gray-200 text-gray-700">
                                    <th class="whitespace-no-wrap">Riwayat</th>
                                    <th class="whitespace-no-wrap">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-b dark:border-dark-5">Alergi</td>
                                    <td class="border-b dark:border-dark-5 origindiv">'.$item->alergi.'</td>
                                    <td class="border-b dark:border-dark-5 editablediv" style="display:none;"> <input name="alergi" type="text" class="input border flex-1" style="background-color:whitesmoke; width:100%;" required value="'.$item->alergi.'"> </td>
                                    <td class="border-b dark:border-dark-5 editablediv strict_id" style="display:none;"> <input name="id_catkhusus" type="text" class="input border flex-1" style="background-color:whitesmoke; width:100%;" required value="'.$item->id_catkhusus.'"> </td>
                                    </tr>
                                <tr>
                                    <td class="border-b dark:border-dark-5">Tranfusi</td>
                                    <td class="border-b dark:border-dark-5 origindiv">'.$item->tranfusi.'</td>
                                    <td class="border-b dark:border-dark-5 editablediv" style="display:none;"> <input name="tranfusi" type="text" class="input border flex-1" style="background-color:whitesmoke; width:100%;" required value="'.$item->tranfusi.'"> </td>
                                </tr>
                                <tr>
                                    <td class="border-b dark:border-dark-5">Golongan darah</td>
                                    <td class="border-b dark:border-dark-5 origindiv">'.$item->gol_darah.'</td>
                                    <td class="border-b dark:border-dark-5 editablediv" style="display:none;"> <input name="gol_darah" type="text" class="input border flex-1" style="background-color:whitesmoke; width:100%;" required value="'.$item->gol_darah.'"> </td>
                                </tr>
                                <tr>
                                    <td class="border-b dark:border-dark-5">Penyakit berat</td>
                                    <td class="border-b dark:border-dark-5 origindiv">'.$item->penyakit_berat.'</td>
                                    <td class="border-b dark:border-dark-5 editablediv" style="display:none;"> <input name="penyakit_berat" type="text" class="input border flex-1" style="background-color:whitesmoke; width:100%;" required value="'.$item->penyakit_berat.'"> </td>
                                </tr>
                                    <td class="border-b dark:border-dark-5">Penyakit menular</td>
                                    <td class="border-b dark:border-dark-5 origindiv">'.$item->penyakit_menular.'</td>
                                    <td class="border-b dark:border-dark-5 editablediv" style="display:none;"> <input name="penyakit_menular" type="text" class="input border flex-1" style="background-color:whitesmoke; width:100%;" required value="'.$item->penyakit_menular.'"> </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <a class="block font-medium text-base mt-5">Resep Obat</a>';
                        if(count($resepObatData)){
                            $output .=' 
                                <table class="table mt-5">
                                    <thead>
                                        <tr class="bg-gray-200 text-gray-700">
                                            <th style="display:none;" class="whitespace-no-wrap">ID</th>
                                            <th class="whitespace-no-wrap">Nama Obat</th>
                                            <th class="whitespace-no-wrap">Jenis Obat</th>
                                            <th class="whitespace-no-wrap">Dosis</th>
                                            <th class="whitespace-no-wrap">Satuan</th>
                                            <th class="whitespace-no-wrap">Catatan</th>
                                            <th class="whitespace-no-wrap editablediv" style="display:none;">OPSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>'.$resepObat.'</tbody>
                                </table>';
                        }else{
                            $output .='-';
                        }
                }
            }
                
                    $output .='
                            <div class="editablediv" style="display:none;">
                                <a href="javascript:;" data-toggle="modal" data-target="#delete-modal-preview" class="button w-18 mr-1 mb-2 bg-theme-9 text-white editablediv">Simpan Perubahan Diagnosa</a>
                                <button onclick="ubahcatatanrm(\'batalkan\')" type="button" class="button w-18 mr-1 mb-2 bg-gray-200 text-gray-600 editablediv">Batalkan Perubahan</button>
                            </div>
                        </div>';
            }
            else{
                $output .='No results';
            }
            return $output;
        }
    }


    public function storePemeriksaan($id, Request $request)
    {
        $pattern = '/<div>(.*?)<\/div>/'; // Regex pattern untuk mencari dan menghapus tag <div> dan </div>
        $dt = preg_replace($pattern, '$1', $request->diagnosa_tambahan_);
        $tm = preg_replace($pattern, '$1', $request->tindakan_medis_);
      
        DB::table('pemeriksaan_spesialistik')->insert([
            'id_spesialistik' => 'sps-'.substr($id, 3),
            'diagnosa_utama' => $request->diagnosa_utama_,
            'komplikasi' =>  $request->komplikasi_,
            'diagnosa_tambahan' =>  $dt,
            'tindakan_medis' => $tm,
            'id_vitalsign' => $id
        ]);

        if($request->resep_obat_value != "TIDAK_ADA_RESEP_OBAT"){
            DB::statement($request->resep_obat_value);
        }
       
        if( $request->alergi_ || $request->tranfusi_ || $request->golongan_darah_ || $request->penyakit_berat_ || $request->penyakit_menular_ )
        {
            DB::table('catatan_khusus')->insert([
                'id_catkhusus' => null, 
                'alergi' => $request->alergi_, 
                'tranfusi' => $request->tranfusi_, 
                'gol_darah' => $request->golongan_darah_, 
                'penyakit_berat' => $request->penyakit_berat_, 
                'penyakit_menular' => $request->penyakit_menular_, 
                'id_spesialistik' => 'sps-'.substr($id, 3), 
            ]);
        }
        

        $id_pendaftaran = DB::table('vital_sign as vs')
                ->join('pendaftaran as pdf', 'pdf.id_pendaftaran', '=', 'vs.id_pendaftaran')
                ->where('vs.id_vitalsign', $id)
                ->select('pdf.id_pendaftaran')
                ->get();
           
        DB::table('pendaftaran')
            ->where('id_pendaftaran',  $id_pendaftaran[0]->id_pendaftaran)
            ->update(['status' => 'Sudah Periksa']);
      

        Session::flash('sukses', 'Data telah berhasil disimpan.');

       return "SUCCESS";
 
    }
    public function kunjunganHarini(Request $request){
        $datadokter = Session::get('dokter');
        $dktdata = DB::table('user')
        ->join('dokter', 'dokter.login_id', '=', 'user.login_id')
        ->where('user.login_id',  $datadokter[0]->login_id)
        ->select('photo', 'dokter.nama')
        ->get();

        if (!count($request->all())) {
            $data = DB::table('pemeriksaan_spesialistik as pemsps')
            ->join('vital_sign as vs', 'vs.id_vitalsign', '=', 'pemsps.id_vitalsign')
            ->join('pendaftaran as pdf', 'pdf.id_pendaftaran', '=', 'vs.id_pendaftaran')
            ->join('pasien', 'pasien.no_rekmed', '=', 'pdf.no_rekmed')
            ->join('jadwal_dokter as jdw', 'jdw.id_jdwdokter', '=', 'pdf.id_jdwdokter')
            ->join('dokter', 'dokter.id_dokter', '=', 'jdw.id_dokter')
            ->select('pemsps.id_spesialistik','pasien.no_rekmed', 'pasien.nama as nama_psn', 'pemsps.diagnosa_utama', 'pemsps.komplikasi', 'pemsps.tindakan_medis')
            ->where('dokter.id_dokter', $datadokter[0]->login_id)
            ->whereDate('pdf.tgl_daftar', now()->toDateString())
            ->paginate(5);
            
            return view ('contents.transaksi.kunjungan_sekarang_spesialistik')->with( ["data"=>$data, "datadokter"=>$dktdata]);
        }else{
            if($request->ajax()){
                $searchData = $request->searchdata;
                $data = DB::table('pemeriksaan_spesialistik as pemsps')
                ->join('vital_sign as vs', 'vs.id_vitalsign', '=', 'pemsps.id_vitalsign')
                ->join('pendaftaran as pdf', 'pdf.id_pendaftaran', '=', 'vs.id_pendaftaran')
                ->join('pasien', 'pasien.no_rekmed', '=', 'pdf.no_rekmed')
                ->join('jadwal_dokter as jdw', 'jdw.id_jdwdokter', '=', 'pdf.id_jdwdokter')
                ->join('dokter', 'dokter.id_dokter', '=', 'jdw.id_dokter')
                ->select('pemsps.id_spesialistik','pasien.no_rekmed', 'pasien.nama as nama_psn', 'pemsps.diagnosa_utama', 'pemsps.komplikasi', 'pemsps.tindakan_medis')
                ->where('dokter.id_dokter', $datadokter[0]->login_id)
                // ->whereDate('pdf.tgl_daftar', now()->toDateString())
                ->where(function ($query) use ($searchData) {
                    $query->where('pdf.no_rekmed', 'LIKE', '%' . $searchData . '%')
                        ->orWhere('pasien.nama', 'LIKE', '%' . $searchData . '%');
                })
                ->paginate(5);

                return view ('contents.search.search_kunjungan_sekarang')->with('data', $data);
            }
        }
    }
    
    public function cetak_pdf($idsps){

        $path = "/storage/images/logo_RS.jpg";

        $pemeriksaanSpesialistik = DB::table('pemeriksaan_spesialistik')
        ->where('id_spesialistik', $idsps)
        ->get();

        // Query untuk tabel catatan_khusus
        $catatanKhusus = DB::table('catatan_khusus')
            ->where('id_spesialistik', $idsps)
            ->get();

        // Query untuk tabel resep_obat
        $resepObat = DB::table('resep_obat')
            ->where('id_spesialistik', $idsps)
            ->get();

        $dktpsn = DB::table('pemeriksaan_spesialistik AS pemsps')
        ->select('pasien.no_rekmed', 'pasien.nama AS nama_psn', 'dokter.nama AS nama_dkt', 'pendaftaran.tgl_daftar')
        ->join('vital_sign AS vs', 'vs.id_vitalsign', '=', 'pemsps.id_vitalsign')
        ->join('pendaftaran', 'pendaftaran.id_pendaftaran', '=', 'vs.id_pendaftaran')
        ->join('pasien', 'pasien.no_rekmed', '=', 'pendaftaran.no_rekmed')
        ->join('jadwal_dokter AS jdw', 'jdw.id_jdwdokter', '=', 'pendaftaran.id_jdwdokter')
        ->join('dokter', 'dokter.id_dokter', '=', 'jdw.id_dokter')
        ->where('pemsps.id_spesialistik', $idsps)
        ->get(); 

        // $pdf->loadView("components.modal.cetak_rekammedis_pdf", ["pemsps"=> $pemeriksaanSpesialistik, "ck"=> $catatanKhusus, "ro"=> $resepObat, "dktpsn"=> $dktpsn]);

        // return $pdf->stream('doc.pdf');
       
        $pdf = PDF::loadView("components.modal.cetak_rekammedis_pdf", ["pemsps"=> $pemeriksaanSpesialistik, "ck"=> $catatanKhusus, "ro"=> $resepObat, "dktpsn"=> $dktpsn, "photo"=> $path]);
        return $pdf->stream($idsps.'.pdf');

        // return view("components.modal.cetak_rekammedis_pdf", ["pemsps"=> $pemeriksaanSpesialistik, "ck"=> $catatanKhusus, "ro"=> $resepObat, "dktpsn"=> $dktpsn, "photo"=> $path]);
    }

























    public function rujukan(Request $request){
        if($request->ajax()){
            $data = DB::table('jadwal_dokter as jdw')
            ->join('dokter', 'dokter.id_dokter', '=', 'jdw.id_dokter')
            ->join('user', 'user.login_id', '=', 'dokter.login_id')
            ->join('poli', 'poli.id_poli', '=', 'dokter.id_poli')
            ->join('ruangan', 'ruangan.no_ruangan', '=', 'jdw.no_ruangan')
            ->select('id_jdwdokter', 'dokter.id_dokter', 'dokter.nama', 'poli.nama as poli', 'lokasi','photo')
            ->where('jdw.hari', '=', DB::raw('CASE 
                WHEN DAYNAME(NOW()) = "Monday" THEN "senin"
                WHEN DAYNAME(NOW()) = "Tuesday" THEN "selasa"
                WHEN DAYNAME(NOW()) = "Wednesday" THEN "rabu"
                WHEN DAYNAME(NOW()) = "Thursday" THEN "kamis"
                WHEN DAYNAME(NOW()) = "Friday" THEN "jumat"
                WHEN DAYNAME(NOW()) = "Saturday" THEN "sabtu"
                WHEN DAYNAME(NOW()) = "Sunday" THEN "minggu"
                ELSE NULL
            END'))
            ->whereRaw('TIME_FORMAT(jdw.akhir_praktek, "%H:%i") >= DATE_FORMAT(NOW(), "%H:%i")')
            ->where('dokter.nama', 'LIKE', '%' . $request->searchdata . '%')
            ->get();

            return view ('contents.search.search_rujukan_dokter')->with('data', $data);
        }
    }
}