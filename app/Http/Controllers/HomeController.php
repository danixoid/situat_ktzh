<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
//        request()->user()->authorizeRoles(['employee']);
        return view('home');
    }

    /**
     * QUEST
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ticket()
    {
//        request()->user()->authorizeRoles(['employee']);
        return view('ticket');
    }

    /*
    public function someAdminStuff(Request $request)
    {
      $request->user()->authorizeRoles('manager');
      return view(‘some.view’);
    }
    */
}
