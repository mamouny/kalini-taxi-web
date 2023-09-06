<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
