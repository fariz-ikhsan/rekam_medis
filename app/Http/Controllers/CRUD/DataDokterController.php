<?php


namespace App\Http\Controllers\CRUD;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Dokter;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DataDokterController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!count($request->all())) {
            $dataDokterController =  DB::table('dokter')
                ->selectRaw('photo, id_dokter, dokter.nama, dokter.no_telp, poli.nama AS nama_poli')
                ->join('user', 'user.login_id', '=', 'dokter.login_id')
                ->join('poli', 'poli.id_poli', '=', 'dokter.id_poli')->paginate(5);
    
            return view('contents.master.data_dokter')->with('data', $dataDokterController);
        }else{
            if($request->ajax()){
                if($request->cek_id){
                    $count = DB::table('dokter')
                    ->where('id_dokter', $request->cek_id)
                    ->count();
                    return response()->json(['count' => $count]);
                }else{
                    $data = DB::table('dokter')
                    ->selectRaw('photo, id_dokter, dokter.nama, dokter.no_telp, poli.nama AS nama_poli')
                    ->join('user', 'user.login_id', '=', 'dokter.login_id')
                    ->join('poli', 'poli.id_poli', '=', 'dokter.id_poli')
                    ->where('dokter.id_dokter', 'like', '%' . $request->searchdata . '%')
                    ->orWhere('dokter.nama', 'like', '%' . $request->searchdata . '%')
                    ->orWhere('dokter.no_telp', 'like', '%' . $request->searchdata . '%')
                    ->paginate(5);
                    
                    return view('contents.search.searchmaster_dokter')->with('data', $data);
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
        if ($request->hasFile('photo')) {
            $fileName = time().$request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('images', $fileName, 'public');
            DB::table('user')->insert([
                'login_id' => $request->id_dokter,
                'password' => $request->password,
                'role' => "dokter",
                'photo' => '/storage/'.$path
            ]);
        }else{
            DB::table('user')->insert([
                'login_id' => $request->id_dokter,
                'password' => Hash::make($request->password),
                'role' => "dokter",
                'photo' => NULL
            ]);
        }
         

        DB::table('dokter')->insert([
            'id_dokter' => $request->id_dokter,
            'nama' =>  $request->nama,
            'no_telp' =>  $request->no_telp,
            'id_poli' => $request->id_poli,
            'login_id' => $request->id_dokter
        ]);
           
        DB::table('jadwal_dokter')->insert([
            'hari' => $request->hari,
            'buka_praktek' => $request->buka_prak,
            'akhir_praktek' => $request->selesai_prak,
            'id_dokter' => $request->id_dokter,
            'no_ruangan' => $request->no_ruangan
        ]);
            
        return redirect()->route('datadokter.index')->with('success', 'Data dokter berhasil ditambahkan!');
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
        $dokter  = Dokter::findOrFail($id);
        $dokter->update($request->all());

        return redirect()->route('datadokter.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   
        DB::table('jadwal_dokter')
            ->where('id_dokter', $id)
            ->delete();
        Dokter::destroy($id);
        User::destroy($id);
        return redirect()->route('datadokter.index')->with('flash_message', 'Data Dokter Dihapus!');
    }
}
