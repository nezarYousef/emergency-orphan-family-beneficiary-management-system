<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;
class AuthController extends Controller { public function show(){return view('auth.login');} public function login(Request $r){$data=$r->validate(['email'=>'required|email','password'=>'required']); if(!Auth::attempt($data,$r->boolean('remember'))){return back()->withErrors(['email'=>'Invalid credentials.']);} $r->session()->regenerate(); return response('',302)->header('Location','/dashboard');} public function logout(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return response('',302)->header('Location','/login');} }
