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
                            <div class="col-md-9">
                                <a href="{!! route('exam.index',['position_id' => $exam->position_id]) !!}">
                                    <span class="label label-info">{!! $exam->position->orgPath !!}/{!! $exam->position->name !!}</span>
                                </a>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">{!! trans('interface.user') !!}</label>
                            <div class="col-md-9">{!! $exam->user->name !!}</div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">{!! trans('interface.chief') !!}</label>
                            <div class="col-md-9">{!! $exam->chief->name !!}</div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">{!! trans('interface.ticket_count') !!}</label>
                            <div class="col-md-9">{!! $exam->count !!}</div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-3">
                                <a href="{!! route('exam.edit',['id'=>$exam->id]) !!}" class="btn btn-block btn-info">{!! trans('interface.edit') !!}</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

