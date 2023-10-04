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

        if (!count($request->all())) {
            
      
            return view ('contents.transaksi.rekam_medis')->with(["datadokter"=> $dktdata, "rm_psn" => $rm_psn]);
                  // $rm = DB::table('vital_sign AS vs')
            //     ->select('vs.id_vitalsign','pdf.no_rekmed', 'pdf.tgl_daftar', 'vs.berat_badan', 'vs.tekanan_darah', 'vs.denyut_nadi', 'vs.spo2', 'vs.suhu', 'vs.respiration_rate', 'pemsps.diagnosa_utama', 'pemsps.komplikasi', 'pemsps.diagnosa_tambahan', 'pemsps.tindakan_medis')
            //     ->join('pendaftaran AS pdf', 'pdf.id_pendaftaran', '=', 'vs.id_pendaftaran')
            //     ->join('pemeriksaan_spesialistik AS pemsps', 'pemsps.id_vitalsign', '=', 'vs.id_vitalsign')
            //     ->where('pdf.no_rekmed', '=', $no_rm->no_rekmed)
            //     ->get();

            // return view ('contents.transaksi.rekam_medis')->with(["datadokter"=> $dktdata,'data', $rm]);


        }else{
            if($request->ajax()){
                $searchData = $request->searchdata;
                $startDate = DateTime::createFromFormat('j M, Y', $request->startdate)->format('Y-m-d');
                $endDate = DateTime::createFromFormat('j M, Y', $request->enddate)->format('Y-m-d');
                
                $data = DB::table('vital_sign AS vs')
                        ->select('vs.id_vitalsign','pdf.no_rekmed', 'pdf.tgl_daftar', 'vs.berat_badan', 'vs.tekanan_darah', 'vs.denyut_nadi', 'vs.spo2', 'vs.suhu', 'vs.respiration_rate', 'pemsps.diagnosa_utama', 'pemsps.komplikasi', 'pemsps.diagnosa_tambahan', 'pemsps.tindakan_medis')
                        ->join('pendaftaran AS pdf', 'pdf.id_pendaftaran', '=', 'vs.id_pendaftaran')
                        ->join('pemeriksaan_spesialistik AS pemsps', 'pemsps.id_vitalsign', '=', 'vs.id_vitalsign')
                        ->where('pdf.no_rekmed', '=', $no_rm->no_rekmed)
                        ->where(function ($query) use ($searchData) {
                            $query->where('pemsps.diagnosa_utama', 'LIKE', '%' . $searchData . '%')
                                ->orWhere('pemsps.komplikasi', 'LIKE', '%' . $searchData . '%');
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
    
    
    public function detailRekammedis(Request $request)
    {
        if($request->ajax()){
            $searchData = $request->searchdata;
            $data = DB::table('vital_sign as vs')
                        ->select(
                            'dkt.nama as dokter_nama', 'pdf.tgl_daftar','ro.dosis', 'ro.tipe','ro.catatan',
                            'vs.berat_badan', 'vs.tekanan_darah', 'vs.denyut_nadi', 'vs.spo2', 'vs.suhu', 'vs.respiration_rate',
                            'pemsps.id_spesialistik','pemsps.diagnosa_utama', 'pemsps.komplikasi', 'pemsps.diagnosa_tambahan', 'pemsps.tindakan_medis',
                            'ro.nama_obat', 'ro.dosis', 'ro.tipe', 'ro.catatan',
                            'ck.alergi', 'ck.tranfusi', 'ck.gol_darah', 'ck.penyakit_berat', 'ck.penyakit_menular'
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
                            ->select('nama_obat', 'dosis', 'tipe', 'catatan','satuan')
                            ->where('pemsps.id_spesialistik', $data->pluck("id_spesialistik")[0])
                            ->get();

            $output=''; $resepObat = '';
            if(count($data)>0){

            foreach($resepObatData as $rso){
                $resepObat .='<tr>
                                <td class="border-b dark:border-dark-5">'.$rso->nama_obat.'</td>
                                <td class="border-b dark:border-dark-5">'.$rso->tipe.'</td>
                                <td class="border-b dark:border-dark-5">'.$rso->dosis.'</td>
                                <td class="border-b dark:border-dark-5">'.$rso->satuan.'</td>
                                <td class="border-b dark:border-dark-5">'.$rso->catatan.'</td>
                            </tr>';
                }
              
            $output ='<div class="intro-y text-justify leading-relaxed">';
            
            foreach($data as $index => $item){
                if($index < 1){
                    $output .='
                        <a class="block font-medium text-base mt-5">Diagnosa Utama</a>
                        <p class="mb-5">'.$item->diagnosa_utama.'</p>
                        <a class="block font-medium text-base mt-5">Komplikasi</a>
                        <p class="mb-5">'.$item->komplikasi.'</p>
                        <a class="block font-medium text-base mt-5">Diagnosa Tambahan</a>
                        <p class="mb-5">'.$item->diagnosa_tambahan.'</p>
                        <a class="block font-medium text-base mt-5">Tindakan Medis</a>
                        <p class="mb-5">'.$item->tindakan_medis.'</p>

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
                                    <td class="border-b dark:border-dark-5">'.$item->alergi.'</td>
                                </tr>
                                <tr>
                                    <td class="border-b dark:border-dark-5">Tranfusi</td>
                                    <td class="border-b dark:border-dark-5">'.$item->tranfusi.'</td>
                                </tr>
                                <tr>
                                    <td class="border-b dark:border-dark-5">Golongan darah</td>
                                    <td class="border-b dark:border-dark-5">'.$item->gol_darah.'</td>
                                </tr>
                                <tr>
                                    <td class="border-b dark:border-dark-5">Penyakit berat</td>
                                    <td class="border-b dark:border-dark-5">'.$item->penyakit_berat.'</td>
                                </tr>
                                    <td class="border-b dark:border-dark-5">Penyakit menular</td>
                                    <td class="border-b dark:border-dark-5">'.$item->penyakit_menular.'</td>
                                </tr>
                
                            </tbody>
                        </table>
                        <a class="block font-medium text-base mt-5">Resep Obat</a>';
                        if(count($resepObatData)){
                            $output .=' 
                                <table class="table mt-5">
                                    <thead>
                                        <tr class="bg-gray-200 text-gray-700">
                                            <th class="whitespace-no-wrap">Nama Obat</th>
                                            <th class="whitespace-no-wrap">Jenis Obat</th>
                                            <th class="whitespace-no-wrap">Dosis</th>
                                            <th class="whitespace-no-wrap">Satuan</th>
                                            <th class="whitespace-no-wrap">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>'.$resepObat.'</tbody>
                                </table>';
                        }else{
                            $output .='-';
                        }
                }
            }
                
                    $output .='</div>';
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
        $dt = preg_replace($pattern, '$1', $request->diagnosa_tambahan);
        $tm = preg_replace($pattern, '$1', $request->tindakan_medis);

        DB::table('pemeriksaan_spesialistik')->insert([
            'id_spesialistik' => 'sps-'.substr($id, 3),
            'diagnosa_utama' => $request->diagnosa_utama,
            'komplikasi' =>  $request->komplikasi,
            'diagnosa_tambahan' =>  $dt,
            'tindakan_medis' => $tm,
            'id_vitalsign' => $id
        ]);

        if($request->resep_obat_value != "TIDAK_ADA_RESEP_OBAT"){
            DB::statement($request->resep_obat_value);
        }
       
        if( $request->alergi || $request->tranfusi || $request->golongan_darah || $request->penyakit_berat|| $request->penyakit_menular )
        {
            DB::table('catatan_khusus')->insert([
                'id_catkhusus' => null, 
                'alergi' => $request->alergi, 
                'tranfusi' => $request->tranfusi, 
                'gol_darah' => $request->golongan_darah, 
                'penyakit_berat' => $request->penyakit_berat, 
                'penyakit_menular' => $request->penyakit_menular, 
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
        return $pdf->stream('doc.pdf');

       
        // return view("components.modal.cetak_rekammedis_pdf", ["pemsps"=> $pemeriksaanSpesialistik, "ck"=> $catatanKhusus, "ro"=> $resepObat, "dktpsn"=> $dktpsn, "photo"=> $path]);


    }
    
}