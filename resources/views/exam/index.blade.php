<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 22.06.17
 * Time: 15:42
 */?>
@extends('layouts.app')

@section('content')

    <?php $position_id = request('position_id') ?: '0'; ?>
    <?php $user_id = request('user_id') ?: '0'; ?>
    <?php $chief_id = request('chief_id') ?: '0'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <label class="col-md-10">{!! trans('interface.exams') !!}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('exam.create') !!}" >{!! trans('interface.create') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <form class="form-horizontal" id="form_quest_search" action="{!! route("exam.index") !!}">

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.position') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="position_id" id="position">
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.user') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="user_id" id="user">
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.chief') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="chief_id" id="chief">
                                    </select>
                                </div>
                            </div>

                        </form>

                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>{!! trans('interface.position') !!}</th>
                                    <th>{!! trans('interface.user') !!}</th>
                                    <th>{!! trans('interface.chief') !!}</th>
                                    <th>{!! trans('interface.quest_count') !!}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($exams as $exam)
                                    <tr>
                                        <td>{!! $exam->id !!}</td>
                                        <td>
                                            <a href="{!! route('exam.index',['position_id' => $exam->position_id]) !!}">
                                                <span class="text-info">{!! $exam->position->orgPath !!}/{!! $exam->position->name !!}</span>

                                            </a>
                                        </td>
                                        <td>{{ $exam->user->name }}</td>
                                        <td>{{ $exam->chief->name }}</td>
                                        <td>{{ $exam->count }}</td>
                                        <td><a href="{!! route('exam.show',['id'=>$exam->id]) !!}">{!! trans('interface.show') !!}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $exams->links() }}
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
            $("#position").select2({
                data: [
                    {
                        id: '{!! $position_id !!}',
                        name: '{!! ($position_id > 0)
                            ? \App\Position::find($position_id)->name
                            : trans('interface.no_value') !!}',
                        orgPath: '{!! $position_id > 0
                            ? \App\Position::find($position_id)->orgPath
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

            $("#user").select2({
                data: [
                    {
                        id: '{!! $user_id !!}',
                        name: '{!! ($user_id > 0)
                            ? \App\User::find($user_id)->name
                            : trans('interface.no_value') !!}'
                    }
                ],
                ajax: {
                    url: "{!! url('/user') !!}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
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
                placeholder: '{!! trans('interface.select_user') !!}',
                allowClear: true,
                language: '{!! config()->get('app.locale') !!}',
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 2,
                templateResult: formatUser, // omitted for brevity, see the source of this page
                templateSelection: formatUserSelection, // omitted for brevity, see the source of this page
            });

            $("#chief").select2({
                data: [
                    {
                        id: '{!! $chief_id !!}',
                        name: '{!! ($chief_id > 0)
                            ? \App\User::find($chief_id)->name
                            : trans('interface.no_value') !!}'
                    }
                ],
                ajax: {
                    url: "{!! url('/user') !!}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
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
                placeholder: '{!! trans('interface.select_user') !!}',
                allowClear: true,
                language: '{!! config()->get('app.locale') !!}',
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 2,
                templateResult: formatUser, // omitted for brevity, see the source of this page
                templateSelection: formatUserSelection, // omitted for brevity, see the source of this page
            });

            $('#position,#user,#chief').on("select2:select", function(e) {
                $("#form_quest_search").submit();
            });

            $('#position,#user,#chief').on("select2:unselect", function(e) {
                $(this).val("0");
                $("#form_quest_search").submit();
            });
        });

        function formatPosition (position) {
            return "<div class='text-info'>" + position.orgPath + "</div><div>" + position.name + "</div>";
        }

        function formatPositionSelection (position) {
            return "<label class='text-info'>" + position.orgPath + "/" + position.name + "</label>";
        }

        function formatUser (user) {
            return "<div class='text-info'>" + user.name + "</div>";
        }

        function formatUserSelection (user) {
            return "<div class='text-info'>" + user.name + "</div>";
        }


    </script>

@endsection