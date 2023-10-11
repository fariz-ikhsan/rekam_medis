<?php


namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;


class PendaftaranController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!count($request->all())) {
            $dataPasienController = Pasien::paginate(10);
            return view ('contents.master.data_pasien')->with('data', $dataPasienController);
        }else{
            if($request->ajax()){
                if($request->cek_id){
                    $count = DB::table('pasien')
                    ->where('no_rekmed', $request->cek_id)
                    ->count();

                    return response()->json(['count' => $count]);

                }else if($request->pencarian_spesifik){
                    $nama = $request->input('nama');
                    $tgl_lahir = $request->input('tgl_lahir');
                    $no_telp = $request->input('no_telp');
                    $alamat = $request->input('alamat');
                    $pekerjaan = $request->input('pekerjaan');

                    $data=DB::table('pasien')
                    ->where('nama','like','%'.$nama.'%')
                    ->where('tgl_lahir','like','%'.$tgl_lahir.'%')
                    ->where('no_telp','like','%'.$no_telp.'%')
                    ->where('alamat','like','%'.$alamat.'%')
                    ->where('pekerjaan','like','%'.$pekerjaan.'%')
                    ->paginate(10);
                    return view ('contents.search.searchmaster_pasien')->with('data', $data);

                }else if($request->page){
                    $data=Pasien::where('no_rekmed','='.$request->searchdata)->paginate(10)->withQueryString();

                    return view ('contents.search.searchmaster_pasien')->with('data', $data);
                    
                }else{
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

                    return view ('contents.search.search_pendaftaran')->with('data', $data);
                }
                
            } 
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tgllahir = date('Y-m-d', strtotime($request->tgl_lahir));
        $array = explode(";", $request->id_jdwdokter);
        $strPendaftaran = "INSERT INTO `pendaftaran` (`tgl_daftar`, `status`, `no_rekmed`, `id_jdwdokter`) VALUES";

        foreach ($array as $item) {
            $strPendaftaran .= "(CURDATE(),'Belum Periksa','".$request->no_rekmed."','".$item."')";
        }

        $strPendaftaran = str_replace(')(', '),(', $strPendaftaran);

        if($request->tambahpasien){
            DB::table('pasien')->insert([
                'no_rekmed' => $request->no_rekmed,
                'nama' => $request->nama,
                'tgl_lahir' => $tgllahir ,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
            ]);
            
        }
    
        DB::statement($strPendaftaran);
        return redirect()->route('pendaftaran.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pendaftaran  = Pendaftaran::findOrFail($id);
        $pendaftaran->update($request->all());

        return redirect()->route('datapendaftaran.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pendaftaran::destroy($id);
        return redirect()->route('datapendaftaran.index')->with('flash_message', 'Data Pendaftaran Dihapus!');
    }
}
