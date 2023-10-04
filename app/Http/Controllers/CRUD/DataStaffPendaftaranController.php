<?php


namespace App\Http\Controllers\CRUD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Staff_Pendaftaran;

class DataStaffPendaftaranController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!count($request->all())) {
            $dataStaff_PendaftaranController = DB::table('karyawan_non_nakes')
            ->where('jenis', 'staff pendaftaran')
            ->paginate(5);
            return view('contents.master.data_staff_pendaftaran')->with('data', $dataStaff_PendaftaranController);
        }
        else{
            if($request->ajax()){
                if($request->cek_id){
                    $count = DB::table('karyawan_non_nakes')
                    ->where('id_karyawan', $request->cek_id)
                    ->count();
                    return response()->json(['count' => $count]);
                }else{
                    $data=DB::table('karyawan_non_nakes')
                    ->where('jenis', 'staff pendaftaran')
                    ->where('id_karyawan','like','%'.$request->searchdata.'%')
                    ->orwhere('nama','like','%'.$request->searchdata.'%')->paginate(5);
                   
                    return view ('contents.search.searchmaster_staffpendaftaran')->with('data', $data);
                }
               
            }
        }
       
    }

    public function store(Request $request)
    {
        DB::table('user')->insert([
            'login_id' => $request->input('id_karyawan'),
            'password' => Hash::make($request->password),
            'role' => "dokter",
            'photo' => NULL
        ]);

         DB::table('karyawan_non_nakes')->insert([
            'id_karyawan' =>  $request->input('id_karyawan'),
            'nama' =>  $request->input('nama'),
            'jenis' => 'staff pendaftaran',
            'login_id' => $request->input('id_karyawan')
        ]);
    
        return redirect()->route('datastaffpendaftaran.index')->with('success', 'Data staff pendaftaran berhasil ditambahkan!');
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
        $staff_pendaftaran  = Staff_Pendaftaran::findOrFail($id);
        $staff_pendaftaran->update($request->all());

        return redirect()->route('datastaffpendaftaran.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Staff_Pendaftaran::destroy($id);
        return redirect()->route('datastaffpendaftaran.index')->with('flash_message', 'Data Staff_Pendaftaran Dihapus!');
    }
}
