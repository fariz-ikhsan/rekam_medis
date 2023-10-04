<?php


namespace App\Http\Controllers\CRUD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Poli;

class DataPoliController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!count($request->all())) {
            $dataPoliController = Poli::paginate(5);
            return view('contents.master.data_poli')->with('data', $dataPoliController);

        }else{
            if($request->ajax()){
 
                $data=Poli::where('id_poli','like','%'.$request->searchdata.'%')
                ->orwhere('nama','like','%'.$request->searchdata.'%')
                ->orwhere('jenis_poli','like','%'.$request->searchdata.'%')->paginate(5);
               
                return view ('contents.search.searchmaster_poli')->with('data', $data);
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
        Poli::create($request->all());
 
        return redirect()->route('datapoli.index')->with('success', 'Data poli berhasil ditambahkan!');
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
        $poli  = Poli::findOrFail($id);
        $poli->update($request->all());

        return redirect()->route('datapoli.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Poli::destroy($id);
        return redirect()->route('datapoli.index')->with('flash_message', 'Data Poli Dihapus!');
    }
}
