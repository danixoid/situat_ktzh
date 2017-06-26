<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 22.06.17
 * Time: 15:42
 */?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{!! trans('interface.exam') !!}</div>

                    <div class="panel-body form-horizontal">

                        <div class="form-group">
                            <label class="control-label col-md-3">{!! trans('interface.position') !!}</label>
                            <div class="col-md-9 form-control-static">
                                <a href="{!! route('exam.index',['position_id' => $exam->position_id]) !!}">
                                    <span class="text-info">{!! $exam->position->orgPath !!}/{!! $exam->position->name !!}</span>
                                </a>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">{!! trans('interface.user') !!}</label>
                            <div class="col-md-9 form-control-static">{!! $exam->user->name !!}</div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">{!! trans('interface.chief') !!}</label>
                            <div class="col-md-9 form-control-static">{!! $exam->chief->name !!}</div>
                        </div>

                        @if(\AUTH::user()->id != $exam->user->id)
                        <div class="form-group">
                            <label class="control-label col-md-3">{!! trans('interface.quests') !!} ({!! $exam->count !!})</label>
                            <div class="col-md-9 form-control-static">
                                <ol class="list-group">
                                    @foreach($exam->quests as $quest)
                                        <li>
                                            <a href="{!! route('quest.show',['id' => $quest->id]) !!}">
                                                <span class="text-success">{!! $quest->shortSource !!}</span><br />
                                                <span class="text-default">{!! $quest->shortTask !!}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                        @endif

                        <div class="form-group">
                            <div class="col-md-3">&nbsp;</div>
                            @if(\AUTH::user()->hasAnyRole(['manager','admin']))
                            <div class="col-md-3">
                                <a href="{!! route('exam.edit',['id'=>$exam->id]) !!}" class="btn btn-block btn-info">{!! trans('interface.edit') !!}</a>
                            </div>

                            <div class="col-md-3">
                                <a href="#" id="deleteExam" class="delete btn btn-block btn-danger">{!! trans('interface.destroy') !!}</a>
                            </div>
                            @endif

                            @if(\AUTH::user()->id == $exam->user->id)
                                <div class="col-md-3">
                                    <a href="{!! route('ticket.index') !!}" class="delete btn btn-block btn-warning">{!! trans('interface.start') !!}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="form_delete_exam" action="{!! route('exam.destroy',['id' => $exam->id]) !!}" method="POST">
        {!! csrf_field() !!}
        {!! method_field("DELETE") !!}
    </form>

@endsection



@section('javascript')
    <script>
        $(function(){
            $("#deleteExam").click(function() {
                $('#form_delete_exam').submit();
            });

        })
    </script>
@endsection
