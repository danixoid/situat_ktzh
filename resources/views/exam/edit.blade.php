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
                    <div class="panel-heading">{!! trans('interface.tickets') !!} | {!! trans('interface.edit') !!}</div>

                    <div class="panel-body">

                        <form id="form_create_exam" class="form-horizontal" action="{!! route('exam.update',['id' => $exam->id]) !!}" method="POST">
                            {!! csrf_field() !!}
                            {!! method_field("PUT")  !!}

                            <div class="form-group">
                                <label class="control-label col-md-3">{!! trans('interface.user') !!}</label>
                                <div class="col-md-9">{!! $exam->user->name !!}</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.position') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="position_id" id="position">
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

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.exams') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-multiple" multiple name="quest_id" id="quest">
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.ticket_count') !!}</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="count" value="{!! $exam->count !!}"/>
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
                        id: '{!! $exam->position->id !!}',
                        name: '{!! $exam->position->name !!}',
                        orgPath: '{!! $exam->position->orgPath !!}'
                    }
                ],
                ajax: {
                    url: "{!! url('/position') !!}",
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

            $("#chief").select2({
                data: [
                    {
                        id: 0,
                        name: '{!! trans('interface.no_value') !!}'
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

            $("#quest").select2({
                data: [
                    {
                        id: 0,
                        shortSource: '{!! trans('interface.no_value') !!}',
                        shortTask: '{!! trans('interface.no_value') !!}'
                    }
                ],
                ajax: {
                    url: "{!! url('/quest') !!}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            text: params.term, // search term
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
                minimumSelectionLength: 2,
                maximumSelectionLength: 2,
                formatSelectionTooBig: function (limit) {

                    // Callback

                    return 'Too many selected items';
                }
                templateResult: formatQuest, // omitted for brevity, see the source of this page
                templateSelection: formatQuestSelection, // omitted for brevity, see the source of this page
            });
        });


        function formatPosition (position) {
            return "<div class='label label-info'>" + position.orgPath + "</div><div>" + position.name + "</div>";
        }

        function formatPositionSelection (position) {
            return "<label class='label label-info'>" + position.orgPath + "</label> <span>" + position.name + "</span>";
        }

        function formatUser (user) {
            return "<div class='label label-info'>" + user.name + "</div>";
        }

        function formatUserSelection (user) {
            return "<div class='label label-info'>" + user.name + "</div>";
        }

        function formatQuest (exam) {
            return "<div><span class='label label-info'>{!! trans('interface.source') !!}:" +
                exam.shortSource + "</span> <span class='label label-success'>{!! trans('interface.task') !!}: " +
                exam.shortTask + "</span></div>";
        }

        function formatQuestSelection (exam) {
            return " <span class='label label-info'>" + exam.shortSource + "</span> " +
                " <span class='label label-primary'>" + exam.shortTask + "</span>";
        }

    </script>

@endsection

