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
                    <div class="panel-heading">{!! trans('interface.users') !!} | {!! trans('interface.edit') !!}</div>

                    <div class="panel-body">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
                                    {!! trans('interface.profile') !!}
                                </a></li>
                            <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
                                    {!! trans('interface.change_password') !!}
                                </a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="profile">
                                <br />
                                <form id="form_create_user" class="form-horizontal" action="{!!
                            route('user.update',['id' => $user->id]) !!}" method="POST">
                                    {!! csrf_field() !!}
                                    {!! method_field("PUT")  !!}

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.role') !!}</label>
                                        <div class="col-md-9">
                                            <select class="form-control select2-multiple" multiple name="role_id[]" id="role">
                                                @foreach(\App\Role::all() as $role)
                                                    <option value="{!! $role->id !!}"
                                                        {!! ($user->hasRole($role->name)) ? "selected='selected'" : ""!!}>{!! trans('interface.' . $role->name) !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.name') !!}</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="name" value="{!! $user->name !!}" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.email_address') !!}</label>
                                        <div class="col-md-9">
                                            <pre>{!! $user->email !!}</pre>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-md-3">
                                            <button class="btn btn-block btn-danger" >{!! trans('interface.update') !!}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="settings">
                                <br />
                                <form id="form_create_user" class="form-horizontal" action="{!!
                                        route('user.update',['id' => $user->id]) !!}" method="POST">
                                    {!! csrf_field() !!}
                                    {!! method_field("PUT")  !!}

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.password') !!}</label>
                                        <div class="col-md-9">
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.confirm_password') !!}</label>
                                        <div class="col-md-9">
                                            <input type="password" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-md-3">
                                            <button class="btn btn-block btn-danger" >{!! trans('interface.update') !!}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('meta')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('javascript')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/i18n/ru.js') }}"></script>
    <script>

        $(function () {

            /**
             *  SELECT2
             */
            $("#role").select2({
                theme: "bootstrap",
                placeholder: '{!! trans('interface.select_role') !!}',
                language: '{!! config()->get('app.locale') !!}',
            });
        });

    </script>

@endsection

