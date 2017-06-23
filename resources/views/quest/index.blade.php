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
                            <label class="col-md-10">{!! trans('interface.quests') !!}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('quest.create') !!}" >{!! trans('interface.create') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <form class="form-horizontal" id="form_quest_search" action="{!! route("quest.index") !!}">

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.org') !!}</label>
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
                                    <th>{!! trans('interface.position') !!}</th>
                                    <th>{!! trans('interface.source') !!}</th>
                                    <th>{!! trans('interface.task') !!}</th>
                                    <th>{!! trans('interface.timer') !!}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quests as $quest)
                                    <tr>
                                        <td>{!! $quest->id !!}</td>
                                        <td>
                                            <a href="{!! route('quest.index',['position_id' => $quest->position_id]) !!}">
                                                <span class="label label-info">{!! $quest->position->orgPath !!}/{!! $quest->position->name !!}</span>

                                            </a>
                                        </td>
                                        <td>{{ mb_substr(mb_ereg_replace("<[^>]+>","",$quest->source),0,50) }}...</td>
                                        <td>{{ mb_substr(mb_ereg_replace("<[^>]+>","",$quest->task),0,50) }}...</td>
                                        <td><span class="label label-warning">{{ $quest->timer }} {{ trans('interface.minutes') }}</span></td>
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

            <?php $position_id = request('position_id') ?: '0'; ?>
            /**
             *  SELECT2
             */
            $("#position").select2({
                data: [
                    {
                        id: '{!! $position_id !!}',
                        name: '{!! ($position_id > 0)
                            ? \App\Position::find(request('position_id'))->name
                            : trans('interface.no_value') !!}',
                        orgPath: '{!! $position_id > 0
                            ? \App\Position::find(request('position_id'))->orgPath
                            : trans('interface.no_value') !!}'
                    }
                ],
                ajax: {
                    url: "{!! url('/position?version='.date('YmdHis')) !!}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data,
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
                templateResult: formatPosition, // omitted for brevity, see the source of this page
                templateSelection: formatPositionSelection // omitted for brevity, see the source of this page
            });

            $('#position').on("select2:select", function(e) {
                $("#form_quest_search").submit();
//                window.location.href = '?position_id=' + e.target.value;
            });

            $('#position').on("select2:unselect", function(e) {

                $("#form_quest_search").find("#position").val("0");
                $("#form_quest_search").submit();
{{--                window.location.href = '{!! route('quest.index') !!}';--}}
            });
        });

        function formatPosition (position) {
            return "<div class='label label-info'>" + position.orgPath + "</div><div>" + position.name + "</div>";
        }

        function formatPositionSelection (position) {
            return "<label class='label label-info'>" + position.orgPath + "/" + position.name + "</label>";
        }


    </script>

@endsection