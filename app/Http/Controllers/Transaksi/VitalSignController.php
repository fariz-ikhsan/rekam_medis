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
            ->select('pendaftaran.id_pendaftaran', 'pendaftaran.no_rekmed', 'pasien.nama', 'dokter.nama AS nama_dokter', 'ruangan.lokasi AS ruangan', 'vital_sign.id_vitalsign')
            ->join('pasien', 'pasien.no_rekmed', '=', 'pendaftaran.no_rekmed')
            ->join('jadwal_dokter', 'jadwal_dokter.id_jdwdokter', '=', 'pendaftaran.id_jdwdokter')
            ->join('dokter', 'dokter.id_dokter', '=', 'jadwal_dokter.id_dokter')
            ->join('ruangan', 'ruangan.no_ruangan', '=', 'jadwal_dokter.no_ruangan')
            ->leftJoin('vital_sign', 'vital_sign.id_pendaftaran', '=', 'pendaftaran.id_pendaftaran')
            ->whereNull('vital_sign.id_vitalsign')
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
        $noRekmed = DB::table('pendaftaran')
    ->select('no_rekmed')
    ->where('id_pendaftaran', $request->id_pendaftaran)
    ->first();
        DB::table('vital_sign')->insert([
            'id_vitalsign' =>"vs-".$noRekmed->no_rekmed."-".$request->id_pendaftaran,
            'berat_badan' => $request->berat_badan,
            'tekanan_darah' => $request->tekanan_darah,
            'denyut_nadi' => $request->denyut_nadi,
            'spo2' => $request->spo2,
            'suhu' => $request->suhu,
            'respiration_rate' => $request->respiration_rate,
            'id_pendaftaran' => $request->id_pendaftaran
        ]);
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
