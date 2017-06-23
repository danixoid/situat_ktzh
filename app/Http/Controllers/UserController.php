<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     *  constructor.
     */
    public function __construct()
    {
        $this->middleware('auth',['except' => ['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = request('count') ?: 10;

        if(request()->has('q')) {
            $users = \App\User::where('name','LIKE', request('q'). '%')
//                ->take(request('page'))
                ->with('parent')
                ->paginate($count);

            if(request()->ajax()) {
                return $users->toJson();
            }

        } else {
            $users = \App\User::paginate($count);
        }

        return view('user.index',['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $data = $request->all();

        $user = \App\User::create($data);

        if(!$user) {

            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_user')
                ]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_create_user'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_user'),
                'user' => $user,
            ]);
        }

        return redirect()
            ->route('user.show',$user->id)
            ->with('message',trans('interface.success_create_user'));

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
                ->json(\App\User::find($id));
        }

        $user = \App\User::find($id);
        return view('user.show',['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = \App\User::find($id);

        return view('user.edit',['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserEditRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserEditRequest $request, $id)
    {
        $data = $request->all();

//        dd($data);

        $user = \App\User::updateOrCreate(['id' => $id], $data);

        if(isset($data['role_id']) && is_array($data['role_id'])) {
            foreach ($data['role_id'] as $role_id)
            $user
                ->roles()
                ->attach(\App\Role::find($role_id));
        }

        if(!$user) {

            if($request->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_save_user')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_save_user'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_save_user'),
                'user' => $user,
            ]);
        }

        return redirect()->route('user.show',$id)->with('message',trans('interface.success_save_user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = \App\User::find($id);

        if(!$user) {

            if(request()->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_deleted_user')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_deleted_user'));
        }

        foreach($user->positions as $position): $position->delete(); endforeach;

        $user->delete();

        return redirect()->back()->with('message',trans('interface.success_deleted_user'));
    }
}
