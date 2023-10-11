<?php


namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;


class VitalSignController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!count($request->all())) {
            $dataPendaftaran = DB::table('pendaftaran')
            ->select('pasien.no_rekmed', 'pasien.nama', 'dokter.nama as nama_dokter', 'ruangan.lokasi as ruangan')
            ->join('pasien', 'pasien.no_rekmed', '=', 'pendaftaran.no_rekmed')
            ->join('jadwal_dokter', 'jadwal_dokter.id_jdwdokter', '=', 'pendaftaran.id_jdwdokter')
            ->join('dokter', 'dokter.id_dokter', '=', 'jadwal_dokter.id_dokter')
            ->join('ruangan', 'ruangan.no_ruangan', '=', 'jadwal_dokter.no_ruangan')
            ->leftJoin('vital_sign', 'vital_sign.id_pendaftaran', '=', 'pendaftaran.id_pendaftaran')
            ->groupBy('pasien.no_rekmed')
            ->havingRaw('pasien.no_rekmed IN (SELECT no_rekmed FROM pendaftaran LEFT JOIN vital_sign ON vital_sign.id_pendaftaran = pendaftaran.id_pendaftaran WHERE vital_sign.id_vitalsign IS NULL)')
            ->paginate(5);
            
            return view ('contents.transaksi.data_vital_sign')->with('data', $dataPendaftaran);
        }else{
            
            if($request->ajax()){
                $searchData = $request->searchdata;
                $data = DB::table('pendaftaran')
                    ->select('pendaftaran.id_pendaftaran', 'pendaftaran.no_rekmed', 'pasien.nama', 'dokter.nama AS nama_dokter', 'ruangan.lokasi AS ruangan', 'vital_sign.id_vitalsign')
                    ->join('pasien', 'pasien.no_rekmed', '=', 'pendaftaran.no_rekmed')
                    ->join('jadwal_dokter', 'jadwal_dokter.id_jdwdokter', '=', 'pendaftaran.id_jdwdokter')
                    ->join('dokter', 'dokter.id_dokter', '=', 'jadwal_dokter.id_dokter')
                    ->join('ruangan', 'ruangan.no_ruangan', '=', 'jadwal_dokter.no_ruangan')
                    ->leftJoin('vital_sign', 'vital_sign.id_pendaftaran', '=', 'pendaftaran.id_pendaftaran')
                    ->where(function($data) use ($searchData) {
                        $data->where('pasien.no_rekmed', 'like', '%' . $searchData . '%')
                            ->orWhere('pasien.nama', 'like', '%' . $searchData . '%');
                    })
                    ->whereNull('vital_sign.id_vitalsign')
                    ->distinct('pendaftaran.no_rekmed')
                    ->paginate(5);
                    return view ('contents.search.search_vitalsign')->with('data', $data);
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
        $strVS = "INSERT INTO `vital_sign` (`id_vitalsign`, `berat_badan`, `tekanan_darah`, `denyut_nadi`, `spo2`, `suhu`, `respiration_rate`, `id_pendaftaran`) VALUES";

        $pdfcheck =DB::table('pendaftaran')
        ->leftJoin('vital_sign', 'vital_sign.id_pendaftaran', '=', 'pendaftaran.id_pendaftaran')
        ->whereNull('vital_sign.id_vitalsign')
        ->where('pendaftaran.no_rekmed', $request->id_pendaftaran)
        ->select('pendaftaran.id_pendaftaran')
        ->get();

        foreach ($pdfcheck as $item) {
            // Access the id_pendaftaran property of the stdClass object
            $id_pendaftaran = $item->id_pendaftaran;
        
            // Concatenate the properties to the string
            $strVS .= "('vs-" . $request->id_pendaftaran . "-" . $id_pendaftaran . "','" . $request->berat_badan . "','" . $request->tekanan_darah . "','" . $request->denyut_nadi . "','" . $request->spo2 . "','" . $request->suhu . "','" . $request->respiration_rate . "','" . $id_pendaftaran . "')";
        }

        $strVS = str_replace(')(', '),(', $strVS);
        DB::statement($strVS);
        
        return redirect()->route('vitalsign.index');
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
