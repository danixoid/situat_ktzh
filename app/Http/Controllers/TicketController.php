<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * TicketController constructor.
     */
    function __construct()
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
        if(!request()->has('exam_id')) {
            return redirect()->back()->with('warning',trans('interface.error'));
        }

        $exam = \App\Exam::find(request('exam_id'));

        return view('ticket',['tickets' => $exam->tickets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();


        if(isset($data['answer'])) {

            $ticket = \App\Ticket::find($id);

            if($ticket->quest->timer < ($ticket->finished_at - $ticket->started_at)) {
                return redirect()
                    ->to('/')
                    ->with('warning',trans('time_is_up'));
            }

            $data['finished_at'] = \Carbon\Carbon::now()->toDayDateTimeString();
        }



        $ticket = \App\Ticket::updateOrCreate(['id' => $id], $data);


        if(!$ticket) {

            if($request->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_save_ticket')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_save_ticket'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_save_ticket'),
                'ticket' => $ticket,
            ]);
        }

        return redirect()->route('ticket.show',$id)->with('message',trans('interface.success_save_ticket'));

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
