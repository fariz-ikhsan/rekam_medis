<?php


namespace App\Http\Controllers\CRUD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dokter;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DataJadwalController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!count($request->all())) {
            $dataJadwalController =DB::table('jadwal_dokter as jdw')
                    ->join('dokter as dkt', 'dkt.id_dokter', '=', 'jdw.id_dokter')
                    ->join('user', 'user.login_id', '=', 'dkt.login_id')
                    ->join('ruangan', 'ruangan.no_ruangan', '=', 'jdw.no_ruangan')
                    ->select('jdw.id_jdwdokter','dkt.id_dokter', 'dkt.nama', 'jdw.hari', 'jdw.buka_praktek', 'jdw.akhir_praktek', 'jdw.no_ruangan','photo','jdw.no_ruangan')
                    ->paginate(5);
            return view('contents.master.data_jadwal')->with('data', $dataJadwalController);
        }else{
            if($request->ajax()){

                $data= DB::table('jadwal_dokter as jdw')
                    ->join('dokter as dkt', 'dkt.id_dokter', '=', 'jdw.id_dokter')
                    ->join('user', 'user.login_id', '=', 'dkt.login_id')
                    ->select('jdw.id_jdwdokter','dkt.id_dokter', 'dkt.nama', 'jdw.hari', 'jdw.buka_praktek', 'jdw.akhir_praktek', 'jdw.no_ruangan','photo')
                    ->where('dkt.id_dokter','like','%'.$request->searchdata.'%')
                    ->orwhere('dkt.nama','like','%'.$request->searchdata.'%')
                    ->paginate(5);
            
                    return view('contents.search.search_jadwal')->with('data', $data);
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
        DB::table('jadwal_dokter')->insert([
            'hari' => $request->hari,
            'buka_praktek' => $request->buka_praktek,
            'akhir_praktek' => $request->akhir_praktek,
            'id_dokter' => $request->id_dokter,
            'no_ruangan' => $request->no_ruangan
        ]);
            
        return redirect()->route('datajadwal.index')->with('success', 'Data dokter berhasil ditambahkan!');
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
        DB::table('jadwal_dokter')
            ->where('id_jdwdokter', $id)
            ->update([
                'hari' => $request->hari,
                'buka_praktek' => $request->buka_praktek,
                'akhir_praktek' => $request->akhir_praktek,
                'no_ruangan' => $request->no_ruangan
            ]);

        return redirect()->route('datajadwal.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   
        DB::table('jadwal_dokter')
            ->where('id_jdwdokter', $id)
            ->delete();
        return redirect()->route('datajadwal.index')->with('flash_message', 'Data Dokter Dihapus!');
    }
}
