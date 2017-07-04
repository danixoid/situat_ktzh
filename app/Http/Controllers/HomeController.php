<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

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
        $exams = \App\Exam::where(function($q) {
            if(Auth::user()->hasAnyRole(['admin','manager'])) {
                return $q;
            }
            return $q
                ->where('user_id', Auth::user()->id)
                ->orWhere('chief_id', Auth::user()->id);
            })
            ->paginate(10);
        return view('home',['exams' => $exams->appends(Input::except('page'))]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function signingData($id)
    {
//      $request->user()->authorizeRoles('manager');
        $exam = \App\Exam::where('id',$id)
            ->where(function($q) {
                return $q
                    ->where('user_id',Auth::user()->id)
                    ->orWhere('chief_id',Auth::user()->id);
            })
            ->with(['tickets','tickets.quest'])
            ->first();

        if($exam) {
            return view("signing",['exam' => $exam ]);
        }

        abort(403);
    }

    public function signedXml($id)
    {
        return response(\App\Sign::find($id)->xml,200,[
            'Content-Type' => 'application/xml'
        ]);
    }


    public function imageUpload(Request $request) {

        $file = $request->file('imagefile');

        $extension = $file->getClientOriginalExtension();
        $preview = $file->getFilename();
        $filename = $preview . '.' . $extension;
        Storage::disk('local')->put($filename, File::get($file));

        return view('upload._image-upload', compact('filename'));
    }

    public function getImage($filename) {
        return file_get_contents(storage_path('app/' . $filename));
    }


    public function getImages() {
        $files = File::allFiles(storage_path('app/'));

        $arr = [];

        foreach ($files as $file)
        {
            if(is_file($file))
            {
                array_push($arr, [
                    'title' => 'Изображение ' . $file->getFilename(),
                    'value' => route('uploaded.image',$file->getFilename())
                ]);
            }
        }

        return $arr;
    }

    public function __test() {
        $exam = \App\Exam::find(1);
        return view('pdf.exam',['exam' => $exam]);
    }
}
