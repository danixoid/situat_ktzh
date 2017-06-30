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
        $this->middleware('role:admin,manager',['except' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = request('count') ?: 10;
        $position_id = request('position_id');
        $text = request('text');

        if($position_id &&  $position_id > 0)
        {
            $query = \App\Position::whereId($position_id)->first()->quests();
        }
        else
        {
            $query = \App\Quest::whereNotNull('id');
        }

        if($text) {
            $query = $query
                ->where(function($q) {
                    return $q
//                        ->where('source', 'LIKE', '%' . \request('text') . '%')
                        ->orWhere('task', 'LIKE', '%' . \request('text') . '%');
                });
        }

        $quests = $query->paginate($count);

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


        if(request()->hasFile('word_file')) {
            $file = request()->file('word_file');

            shell_exec('libreoffice --headless  -convert-to html ' . $file->path()
                . " -outdir " . sys_get_temp_dir());

            $content = file_get_contents(sys_get_temp_dir() . "/" . pathinfo(
                    $file->getClientOriginalName(), PATHINFO_FILENAME).".html");

//            $content = mb_ereg_replace("\n","", $content);
            $content = mb_ereg_replace("<!DOCTYPE.+<body[^>]+>","", $content);
            $content = mb_ereg_replace("<\/body>.+$","", $content);
            $content = mb_ereg_replace("<p((?!</p>).)+Решение\.?((?!<p).)+</p>","",$content);
//            $content = mb_ereg_replace("<p((?!</p>).)+[^а-яА-Я]+((?!<p).)+</p>","",$content);

            $arr = mb_split("<p((?!</p>).)+Ситуация((?!<p).)+</p>",$content);

            unset($arr[0]);

            $int = 1;
            foreach ($arr as $str) {
                $content .= $str;
                $data['task'] = $str;
                $quest = \App\Quest::create($data);

                if(isset($data['positions']) && is_array($data['positions'])) {

                    foreach ($data['positions'] as $position_id)
                        $quest
                            ->positions()
                            ->attach(\App\Position::find($position_id));

                }

                $int++;
            }

            return redirect()
                ->route('quest.index')
                ->with('message',trans('interface.imported_file',['count' => $int]));
        }


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

        if(isset($data['positions']) && is_array($data['positions'])) {

            foreach ($data['positions'] as $position_id)
                $quest
                    ->positions()
                    ->attach(\App\Position::find($position_id));

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
                ->json(\App\Quest::find($id));
        }

        $quest = \App\Quest::find($id);

        if(request()->has('type') && \request('type') == "minimum"){
            return view('layouts.clear',['content' => $quest->task]);
        }

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

        if(isset($data['positions']) && is_array($data['positions'])) {
            $quest
                ->positions()
                ->detach();

            foreach ($data['positions'] as $position_id)
                $quest
                    ->positions()
                    ->attach(\App\Position::find($position_id));

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
        $quest = \App\Quest::find($id);

        if(!$quest) {

            if(request()->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_deleted_quest')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_deleted_quest'));
        }

        if(count($quest->tickets) == 0) {
            $quest->delete();
        }

        return redirect()
            ->route('quest.index')
            ->with('message',trans('interface.success_deleted_quest'));

    }
}
