<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 22.06.17
 * Time: 15:42
 */?>
@extends('layouts.app')

@section('content')


    <?php
    function recursive($org_id) {?>
            <ul style="list-style: disc;">

            <?php foreach(\App\Org::whereOrgId($org_id)->get() as $org):?>
                <li>
                    <a href="{!! route('org.show',['id' => $org->id]) !!}" class="btn btn-link">
                        {{--{!! $org->id !!}) --}}{!! $org->name !!}
                    </a>
                    <small>{!! trans('interface.positions') !!}: {!! $org->positions->count() !!}</small>
                    {{--[<a href="{!! route('org.show',['id' => $org->id]) !!}">{!! trans('interface.show') !!}</a>]

                    [<a href="{!! route('org.create',['org_id' => $org->id]) !!}">{!! trans('interface.create') !!}</a>]
                    [<a href="{!! route('org.edit',['id' => $org->id]) !!}">{!! trans('interface.edit') !!}</a>]
                    [<a href="#" class="delete" action="{!! route('org.destroy',['id' => $org->id]) !!}">{!! trans('interface.destroy') !!}</a>]
                    --}}<br />
                    <?php foreach ($org->positions as $position):?>
                        <a href="{!! route('position.show',['id' => $position->id]) !!}">
                            <span class="label label-success">{!! $position->id !!}
                           {!! $position->name !!}</span></a>
                    <?php endforeach;?>
                    {!! recursive($org->id) !!}
                </li>
            <?php endforeach; ?>
            </ul>

        <?php
    }?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('interface.orgs') }}</div>

                    <div class="panel-body">
                        {!! recursive(request('id')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="form_delete_org" action="" method="POST">
        {!! csrf_field() !!}
        {!! method_field("DELETE") !!}
    </form>
@endsection


@section('javascript')
    <script>
        $(function(){
            $(".delete").each(function() {
                $(this).click(function() {
                    $('#form_delete_org').attr('action', $(this).attr('action'));
                    $('#form_delete_org').submit();
                });
            });


        })
    </script>
@endsection


