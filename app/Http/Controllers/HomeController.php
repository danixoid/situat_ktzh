<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
//        request()->user()->authorizeRoles(['employee']);
        $exams = \App\Exam::where('user_id',Auth::user()->id)
//            ->whereHas('tickets',function($q){
//                return $q->whereNull('finished_at');
//            })
            ->paginate(10);
        return view('home',['exams' => $exams]);
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
