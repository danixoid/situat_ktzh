<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 22.06.17
 * Time: 15:42
 */?>
@extends('layouts.app')

@section('content')

    <?php $org_id = request('org_id') ?: '0'; ?>
    <?php $func_id = request('func_id') ?: '0'; ?>
    <?php $position_id = request('position_id') ?: '0'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <label class="col-md-10">{!! trans('interface.quests') !!}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('quest.create') !!}" >{!! trans('interface.create') !!}</a>
                                |
                                <a href="#modal_timer" data-toggle="modal" data-target="#modal_timer">{!! trans('interface.edit') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal_timer">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-body">
                                    <div class="close-button">
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <h2 class="form-signin-heading">{!! trans('interface.timer') !!} | {!! trans('interface.edit') !!}</h2>

                                        <form class="form"  action="{!! route('quest.update',0) !!}" method="POST">
                                        {!! csrf_field() !!}
                                        {!! method_field('PUT') !!}
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="timer" value="15">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-primary">{{ trans('interface.save') }}</button>
                                                    </span>
                                                </div><!-- /input-group -->
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class="panel-body">
                        <form class="form-horizontal" id="form_quest_search" action="{!! route("quest.index") !!}">


                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.org') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="org_id" id="org">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.func') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="func_id" id="func">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.position') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="position_id" id="position">
                                    </select>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.by_text') !!}</label>
                                <div class="col-md-9">

                                    <div class="input-group">
                                        <input type="search" name="text" class="form-control" value="{!! request('text') !!}"
                                               placeholder="{!! trans('interface.search') !!}">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-default">Найти</button>
                                        </span>
                                    </div><!-- /input-group -->
                                </div>
                            </div>

                        </form>

                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    {{--<th>{!! trans('interface.source') !!}</th>--}}
                                    <th>{!! trans('interface.task') !!}</th>
{{--                                    <th>{!! trans('interface.position') !!}</th>--}}
                                    <th>{!! trans('interface.timer') !!}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quests as $quest)
                                    <tr>
                                        <td>{!! $quest->id !!}</td>
{{--                                        <td>{{ $quest->shortSource }}...</td>--}}
                                        <td>{{ $quest->shortTask }}...</td>
                                        {{--<td>
                                            @foreach($quest->positions as $position)
                                                <a href="{!! route('quest.index',['position_id' => $position->id]) !!}">
                                                    <span title="{!! $position->orgPath !!}" class="text-info">{!! $position->name !!}</span>
                                                </a>,
                                            @endforeach--}}
                                        </td>
                                        <td><span class="text-warning">{{ $quest->timer }} {{ trans('interface.minutes') }}</span></td>
                                        <td><a href="{!! route('quest.show',['id'=>$quest->id]) !!}">{!! trans('interface.show') !!}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $quests->links() }}
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

            var data = {

                org : {
                    id: '{!! $org_id !!}',
                    name: '{!! ($org_id > 0)
                                ? \App\Org::find($org_id)->name
                                : trans('interface.no_value') !!}',
                },
                func : {
                    id: '{!! $func_id !!}',
                    name: '{!! ($func_id > 0)
                                ? \App\Func::find($func_id)->name
                                : trans('interface.no_value') !!}',
                },
                position : {
                    id: '{!! $position_id !!}',
                    name: '{!! ($position_id > 0)
                                ? \App\Position::find($position_id)->name
                                : trans('interface.no_value') !!}',
                }
            };



            $("#org,#func,#position").each(function(){
                var id = $(this).attr('id');

                $(this).select2({
                    data: [
                        data[id]
                    ],
                    ajax: {
                        url: "{!! url('/" + id + "') !!}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                count: params.page
                            };
                        },
                        processResults: function (data, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data, except to indicate that infinite
                            // scrolling can be used
                            params.page = params.page || 1;

                            return {
                                results: data.data,
                                pagination: {
                                    more: (params.page * 30) < data.length
                                }
                            };
                        },
                        cache: true
                    },
                    theme: "bootstrap",
                    placeholder: '{!! trans('interface.select_position') !!}',
                    allowClear: true,
                    language: '{!! config()->get('app.locale') !!}',
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                    minimumInputLength: 2,
                    templateResult: formatDetail, // omitted for brevity, see the source of this page
                    templateSelection: formatDetailSelection // omitted for brevity, see the source of this page
                });
            });

            $("#org,#func,#position").each(function() {
                $(this).on("select2:select", function(e) {
                    $("#form_quest_search").submit();
                });

                $(this).on("select2:unselect", function(e) {
                    $(this).val("0");
                    $("#form_quest_search").submit();
                });
            });


        });


        function formatDetail (detail) {
            return "<span class='text-warning'>" + detail.name + "</span>";
        }

        function formatDetailSelection (detail) {
            if(detail.id === '0') {
                return "<span class='text-primary'>" + detail.name + "</span>";
            } else {
                return "<span class='label label-info'>" + detail.name + "</span>";
            }
        }

    </script>

@endsection