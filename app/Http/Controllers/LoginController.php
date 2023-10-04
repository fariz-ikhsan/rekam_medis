<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
public function loginAuth(Request $request)
{
    $input = $request->all();
    
    if(auth()->attempt(array('login_id' => $input['login_id'], 'password' => $input['password'])))
    {
        if (auth()->user()->role == 'admin') {
            return redirect()->route('datapasien.index');
        }
        else if (auth()->user()->role == 'pendaftaran') {
            return redirect()->route('pendaftaran.index');
        }
        else if (auth()->user()->role == 'suster') {
            return redirect()->route('vitalsign.index');
        }
        else if (auth()->user()->role == 'dokter') {

            $dokter = DB::table('dokter')
                    ->where('login_id',  $input['login_id'])
                    ->select('*')
                    ->get();
            Session::put('dokter', $dokter);
            return redirect()->route('home-spesialistik');
        }
    }
    else{
        Session::flash('error', 'user id atau password salah.');
        return redirect()->back();
    }
}

    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->forget('login_session');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/');

    }

}
