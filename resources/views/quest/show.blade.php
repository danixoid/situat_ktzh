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
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{!! trans('interface.quest') !!}</div>

                    <div class="panel-body form-horizontal">

                        <div class="form-group">
                            <label class="control-label col-md-2">{!! trans('interface.position') !!}</label>
                            <div class="col-md-10 form-control-static">
                                @foreach($quest->positions as $position)
                                    <a href="{!! route('quest.index',['position_id' => $position->id]) !!}">
                                        <span class="text-info" title="{!! $position->orgPath !!}">{!! $position->name !!}</span>,
                                    </a>
                            @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">{!! trans('interface.task') !!}</label>
                            <div class="col-md-10 form-control-static">
                                <iframe id="iframe" src="{!! route('quest.show',
                                    ['id'=>$quest->id,'type'=>'minimum']) !!}" onload="resizeIframe(this)"
                                        style="width:100%; background: #FFFFFF;"></iframe>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">{!! trans('interface.timer') !!}</label>
                            <div class="col-md-10 form-control-static">{!! $quest->timer !!} {!! trans('interface.minutes') !!}</div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-3">
                                <a href="{!! route('quest.edit',['id'=>$quest->id]) !!}" class="btn btn-block btn-info">{!! trans('interface.edit') !!}</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-9">
                                <small>
                                    {!! ($quest->created_at == $quest->updated_at)
                                    ? trans('interface.author') : trans('interface.editor') !!}: {!! $quest->author->name !!}

                                    {!! ($quest->created_at != $quest->updated_at)
                                    ? "<span class=\"text-success\">" . trans('interface.edited') . " " . $quest->updated_at . "</span>"
                                    : ""!!}
                                </small>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        function resizeIframe(obj) {
            obj.style.height = (obj.contentWindow.document.body.scrollHeight + 20) + 'px';
        }
    </script>
@endsection
