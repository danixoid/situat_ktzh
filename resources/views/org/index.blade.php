<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 22.06.17
 * Time: 15:42
 */?>
@extends('layouts.app')

@section('content')

    <style>

        .tree li {
            list-style-type:none;
            margin:0;
            padding:10px 5px 0 5px;
            position:relative
        }
        .tree li::before, .tree li::after {
            content:'';
            left:-20px;
            position:absolute;
            right:auto
        }
        .tree li::before {
            border-left:1px solid #999;
            bottom:50px;
            height:100%;
            top:0;
            width:1px
        }
        .tree li::after {
            border-top:1px solid #999;
            height:20px;
            top:25px;
            width:25px
        }
        .tree li span:not(.glyphicon) {
            -moz-border-radius:5px;
            -webkit-border-radius:5px;
            border-radius:5px;
            display:inline-block;
            padding:4px 9px;
            text-decoration:none
        }
        .tree li.parent_li>span:not(.glyphicon) {
            cursor:pointer
        }
        .tree>ul>li::before, .tree>ul>li::after {
            /*border:0*/
        }
        .tree li:last-child::before {
            height:25px
        }
        .tree li.parent_li>span:not(.glyphicon):hover, .tree li.parent_li>span:not(.glyphicon):hover+ul li span:not(.glyphicon) {
            background:#eee;
            border:1px solid #999;
            padding:3px 8px;
            color:#000
        }
    </style>
    <?php
    function recursive($org_id) {?>
            <ul>

                <?php foreach (\App\Position::whereOrgId($org_id)->get() as $position):?>
                <li class="parent_li">
                    <a href="{!! route('position.show',['id' => $position->id]) !!}">
                        <span class="label label-default">{!! $position->name !!}</span>
                    </a>
                </li>
                <?php endforeach;?>

                <?php foreach(\App\Org::whereOrgId($org_id)->get() as $org):?>
                <li class="parent_li">
                    <a href="{!! route('org.index',['id' => $org->id]) !!}">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                    <a href="{!! route('org.show',['id' => $org->id]) !!}">
                        <span class="label label-success">{{--{!! $org->id !!}) --}}{!! mb_strtoupper($org->name) !!}</span>
                    </a>
                    <small>{!! trans('interface.positions') !!}: {!! $org->positions->count() !!}</small>
                    {{--[<a href="{!! route('org.show',['id' => $org->id]) !!}">{!! trans('interface.show') !!}</a>]

                    [<a href="{!! route('org.create',['org_id' => $org->id]) !!}">{!! trans('interface.create') !!}</a>]
                    [<a href="{!! route('org.edit',['id' => $org->id]) !!}">{!! trans('interface.edit') !!}</a>]
                    [<a href="#" class="delete" action="{!! route('org.destroy',['id' => $org->id]) !!}">{!! trans('interface.destroy') !!}</a>]
                    --}}

                    {!! recursive($org->id) !!}
                </li>
                <?php endforeach; ?>

            </ul>

        <?php
    }

    $org = \App\Org::find(\request('id'));
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('interface.orgs') }}</div>

                    <div class="panel-body tree">
                        @if(\request()->has('id'))
                        <a href="{!! route('org.index',['id' => $org->org_id]) !!}">
                            <span class="label label-primary">{!! $org->name !!}</span>
                        </a>
                        @else
                        <a href="{!! route('org.index') !!}">
                            <span class="label label-primary">{!! trans('interface.root') !!}</span>
                        </a>
                        @endif
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


