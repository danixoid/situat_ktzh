<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamCreateRequest;
use App\Http\Requests\ExamEditRequest;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = request('count') ?: 10;
        $exams = \App\Exam::paginate($count);
        return view('exam.index',['exams' => $exams]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('exam.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ExamCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamCreateRequest $request)
    {
        $data = $request->all();

        $exam = \App\Exam::create($data);

        if(!$exam) {

            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_exam')
                ]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_create_exam'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_exam'),
                'exam' => $exam,
            ]);
        }

        return redirect()
            ->route('exam.show',$exam->id)
            ->with('message',trans('interface.success_create_exam'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(request()->ajax()) {
            return response()
                ->json(\App\Exam::with([
                    'user',
                    'chief',
                    'position'
                ])->find($id));
        }

        $exam = \App\Exam::find($id);
        return view('exam.show',['exam' => $exam]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exam = \App\Exam::find($id);
        return view('exam.edit',['exam' => $exam]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExamEditRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExamEditRequest $request, $id)
    {
        $data = $request->all();

        $exam = \App\Exam::updateOrCreate(['id' => $id], $data);

        if(!$exam) {

            if($request->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_save_exam')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_save_exam'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_save_exam'),
                'exam' => $exam,
            ]);
        }

        return redirect()->route('exam.show',$id)->with('message',trans('interface.success_save_exam'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
