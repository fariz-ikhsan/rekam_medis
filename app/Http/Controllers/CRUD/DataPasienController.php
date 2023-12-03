<?php

namespace App\Http\Controllers\CRUD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DataPasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        if (!count($request->all())) {
            $dataPasienController = Pasien::paginate(10);
            return view ('contents.master.data_pasien')->with('data', $dataPasienController);
        }
        else{
            if($request->ajax() ){
                    $data= DB::table('pasien')
                    ->where('no_rekmed','like','%'.$request->searchdata.'%')->paginate(10);

                    return view ('contents.search.searchmaster_pasien')->with('data', $data);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        
        Pasien::create($request->all());
 
        return redirect()->route('datapasien.index')->with('success', 'Data pasien berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $keyword)
    {
  
		// $cari = $request->all();
        $pasien =Pasien::where('no_rekmed','like','%'.$keyword.'%')
            ->orwhere('nama','like','%'.$keyword.'%')
            ->get();
        
        return view('contents.master.data_pasien',['data' => $pasien]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tgl_lahir = Carbon::createFromFormat('d M, Y', $request->tgl_lahir)->format('Y-m-d');
        

        $dataToUpdate = [
            'nama' => $request->nama,
            'tgl_lahir' => $tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'pekerjaan' => $request->pekerjaan,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ];
        
        DB::table('pasien')
            ->where('no_rekmed', '=', $id)
            ->update($dataToUpdate);
        

        return redirect()->route('datapasien.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pasien::destroy($id);
        return redirect()->route('datapasien.index')->with('flash_message', 'Data Pasien Dihapus!');  
    }
    
}
