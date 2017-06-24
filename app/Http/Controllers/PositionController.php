<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionCreateRequest;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     *  constructor.
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
        if(request()->has('q')) {
            $positions = \App\Position::where('name','LIKE', request('q'). '%')
                ->orWhereHas('org', function($query) {
                    return $query->where('name','LIKE', request('q'). '%');
                })
                ->orderBy('org_id')
                ->take(request('page'))
                ->get();

            if(request()->ajax()) {
                return $positions->toJson();
            }

        } else if(request()->has('org_id')) {
            $positions = \App\Org::find(request('org_id'))->positions;
        } else {
            $positions = \App\Position::orderBy('org_id')->get();
        }

        return view('position.index',['positions' => $positions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('position.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PositionCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionCreateRequest $request)
    {
        $data = $request->all();

        $position = \App\Position::create($data);

        if(!$position) {

            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_position')
                ]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_create_position'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_position'),
                'position' => $position,
            ]);
        }

        return redirect()
            ->route('position.show',$position->id)
            ->with('message',trans('interface.success_create_position'));

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
                ->json(\App\Position::with('org')
                    ->find($id));
        }

        $position = \App\Position::find($id);
        return view('position.show',['position' => $position]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $position = \App\Position::find($id);

        return view('position.edit',['position' => $position]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PositionCreateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PositionCreateRequest $request, $id)
    {
        $data = $request->all();

        $position = \App\Position::updateOrCreate(['id' => $id], $data);

        if(!$position) {

            if($request->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_save_position')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_save_position'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_save_position'),
                'position' => $position,
            ]);
        }

        return redirect()->route('position.show',$id)->with('message',trans('interface.success_save_position'));
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
