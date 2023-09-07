<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PagesController extends Controller
{

    public function login(){
        return view('auth.login');
    }

    public function dashboard()
    {
        $driversCount = app('firebase.firestore')->database()->collection('drivers')->documents()->size();
        $usersCount   = app('firebase.firestore')->database()->collection('users')->documents()->size();
        $coursesCount = app('firebase.firestore')->database()->collection('courses')->documents()->size();
        $clientsCount = app('firebase.firestore')->database()->collection('clients')->documents()->size();

        return view('admin.dashboard', compact('driversCount', 'usersCount', 'coursesCount', 'clientsCount'));
    }

    public function changeLanguage(Request $request)
    {
        App::setLocale($request->lang);
        session()->put('locale', $request->lang);

        return redirect()->back();
    }
}
