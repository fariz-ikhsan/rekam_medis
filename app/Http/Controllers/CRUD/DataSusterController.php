<?php


namespace App\Http\Controllers\CRUD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suster;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DataSusterController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!count($request->all())) 
        {
            $dataSusterController = Suster::paginate(5);
            return view('contents.master.data_suster')->with('data', $dataSusterController);
        }
        else{
            if($request->ajax()){
                if($request->cek_id){
                    $count = DB::table('suster')
                    ->where('id_suster', $request->cek_id)
                    ->count();
                    return response()->json(['count' => $count]);
                }else{
                    $data=Suster::where('id_suster','like','%'.$request->searchdata.'%')
                    ->orwhere('nama','like','%'.$request->searchdata.'%')->paginate(5);
                    
                    return view ('contents.search.searchmaster_suster')->with('data', $data);
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
        DB::table('user')->insert([
            'login_id' => $request->id_suster,
            'password' => Hash::make($request->password),
            'role' => "suster",
            'photo' => NULL
        ]);

        DB::table('suster')->insert([
            'id_suster' => $request->id_suster,
            'nama' => $request->nama,
            'login_id' => $request->id_suster
        ]);
        
        
        
        return redirect()->route('datasuster.index')->with('success', 'Data suster berhasil ditambahkan!');
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
        $suster  = Suster::findOrFail($id);
        $suster->update($request->all());

        return redirect()->route('datasuster.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Suster::destroy($id);
        User::destroy($id);
        return redirect()->route('datasuster.index')->with('flash_message', 'Data Suster Dihapus!');
    }
}
