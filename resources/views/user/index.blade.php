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
                    <div class="panel-heading">
                        <div class="row">
                            <label class="col-md-10">{!! trans('interface.users') !!}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('user.create') !!}" >{!! trans('interface.create') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th>№</th>
                                <th>{!! trans('interface.name') !!}</th>
                                <th>{!! trans('interface.email_address') !!}</th>
                                <th>{!! trans('interface.roles') !!}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{!! $user->id !!}</td>
                                    <td><a href="{!! route('user.show',['id' => $user->id]) !!}">{!! $user->name !!}</a></td>
                                    <td><span class="label label-info">{!! $user->email !!}</span></td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="label label-info">{!!
                                                trans('interface.' . $role->name) !!}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

