<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestCreateRequest;
use Illuminate\Http\Request;

class QuestController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = request('page') ?: 10;
        $position_id = request('position_id');

        if($position_id &&  $position_id > 0) {
            $query = \App\Position::find(request('position_id'))
                ->quests();
        } else {
            $query = \App\Quest::whereNotNull('id');
        }

        $quests = $query
            ->where('source','LIKE','%' . \request('text') . '%')
            ->orWhere('task','LIKE','%' . \request('text') . '%')
            ->paginate($count);

        if(request()->ajax()) {
            return $quests->toJson();
        }


        return view('quest.index',['quests' => $quests]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('quest.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuestCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestCreateRequest $request)
    {
        $data = $request->all();

        $data['author_id'] = auth()->user()->id;

        $quest = \App\Quest::create($data);

        if(!$quest) {

            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_quest')
                ]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_create_quest'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_quest'),
                'quest' => $quest,
            ]);
        }

        return redirect()
            ->route('quest.show',$quest->id)
            ->with('message',trans('interface.success_create_quest'));

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
                ->json(\App\Quest::with([
                    'author',
                    'position',
                    'position.org'
                ])->find($id));
        }

        $quest = \App\Quest::find($id);
        return view('quest.show',['quest' => $quest]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quest = \App\Quest::find($id);
        return view('quest.edit',['quest' => $quest]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuestCreateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuestCreateRequest $request, $id)
    {
        $data = $request->all();

        $data['author_id'] = auth()->user()->id;

        $quest = \App\Quest::updateOrCreate(['id' => $id], $data);

        if(!$quest) {

            if($request->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_save_quest')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_save_quest'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_save_quest'),
                'quest' => $quest,
            ]);
        }

        return redirect()->route('quest.show',$id)->with('message',trans('interface.success_save_quest'));
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
