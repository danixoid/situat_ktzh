<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamCreateRequest;
use App\Http\Requests\ExamEditRequest;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ExamController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager',['except' => ['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $count = request('count') ?: 10;
        $org_id = request('org_id');
        $func_id = request('func_id');
        $position_id = request('position_id');
        $user_id = request('user_id');
        $chief_id = request('chief_id');
        $startedDate = request('date_started');
        $finishedDate = request('date_finished');

        $query = \App\Exam::whereNotNull('id');

        if($org_id &&  $org_id > 0) {
            $query = $query->where('org_id',$org_id);
        }

        if($func_id &&  $func_id > 0) {
            $query = $query->where('func_id',$func_id);
        }

        if($position_id &&  $position_id > 0) {
            $query = $query->where('position_id',$position_id);
        }

        if($user_id && $user_id > 0) {
            $query = $query->where('user_id',$user_id);
        }

        if($chief_id && $chief_id > 0) {
            $query = $query->where('chief_id',$chief_id);
        }

        if($startedDate) {
            $query = $query->whereHas('tickets',function($q) use ($startedDate)
            {
                return $q->where('started_at','>',$startedDate);
            });
        }

        if($finishedDate) {
            $query = $query->whereHas('tickets',function($q) use ($finishedDate)
            {
                return $q->where('finished_at','<',$finishedDate);
            });
        }

        $exams = $query->orderBy('created_at','desc')->paginate($count);

        if(request()->ajax()) {
            return $exams->toJson();
        }

        return view('exam.index',['exams' => $exams->appends(Input::except('page'))]);
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

        $quests = \App\Quest::whereHas('orgs',function($q) use ($data) {
            return $q->whereOrgId($data['org_id']);
            })
            ->whereHas('funcs',function($q) use ($data) {
                return $q->whereFuncId($data['func_id']);
            })
            ->whereHas('positions',function($q) use ($data) {
                return $q->wherePositionId($data['position_id']);
            })
            ->inRandomOrder()
            ->take($data['count'])
            ->get();

        if(count($quests) < $data['count'])
        {
            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_exam')
                ]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_create_exam'));
        }

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

        // ЗАПИСЬ ЗАДАНИЙ ДЛЯ ЭКЗАМЕНА
        foreach($quests as $quest)
        {
            \App\Ticket::create([
                'exam_id' => $exam->id,
                'quest_id' => $quest->id
            ]);
        }

        if($request->ajax())
        {
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
                    'position',
                    'tickets.quest'
                ])->find($id));
        }

        $exam = \App\Exam::find($id);

        if(\request()->has('type') && \request('type') == 'pdf')
        {
            $pdf = \PDF::loadView('pdf.exam', ['exam' => $exam]);
            return $pdf->stream('exam'. $exam->id . 'pdf');
        }

        return view('exam.show',[ 'exam' => $exam ]);
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
        $exam = \App\Exam::find($id);

        if(!$exam) {

            if(request()->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_deleted_exam')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_deleted_exam'));
        }

        $exam->delete();

        return redirect()
            ->route('exam.index')
            ->with('message',trans('interface.success_deleted_exam'));

    }
}
