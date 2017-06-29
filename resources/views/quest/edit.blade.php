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
                    <div class="panel-heading">{!! trans('interface.tickets') !!} | {!! trans('interface.edit') !!}</div>

                    <div class="panel-body">

                        <form id="form_create_quest" class="form-horizontal" action="{!! route('quest.update',['id' => $quest->id]) !!}" method="POST">
                            {!! csrf_field() !!}
                            {!! method_field("PUT")  !!}

                            <div class="form-group">
                                <label class="col-md-2 control-label">{!! trans('interface.position') !!}</label>
                                <div class="col-md-10">
                                    <select class="form-control select2-multiple" multiple name="positions[]" id="positions">
                                        @foreach($quest->positions as $position)
                                            <option value="{!! $position->id !!}" selected="selected"></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">{!! trans('interface.task') !!}</label>
                                <div class="col-md-10">
                                    <textarea id="task" rows="6" class="form-control" name="task">{!! $quest->task !!}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">{!! trans('interface.timer') !!}</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="timer" value="{!! $quest->timer !!}"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-3">
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
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>

        $(function () {

            /**
             *  SELECT2
             */
            $("#positions").select2({
                data: [
                    @foreach($quest->positions as $position)
                    {
                        id: '{!! $position->id !!}',
                        name: '{!! $position->name !!}',
                        orgPath: '{!! $position->orgPath !!}'
                    },
                    @endforeach
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
        });


        function formatPosition (position) {
            return "<div class='text-info'>" + position.orgPath + "</div><div>" + position.name + "</div>";
        }

        function formatPositionSelection (position) {
            return "<span class='text-info' title='" + position.orgPath + "'>" + position.name + "</span>";
        }


        tinymce.init({
            selector: 'textarea',  // change this value according to your HTML
            language: '{!! config('app.locale') == "kz" ? "kk" : config('app.locale')!!}',
            menubar : false,
            plugins: [
                "link image code fullscreen imageupload table"
            ],
            toolbar: "undo redo | bold italic | bullist numlist outdent indent | link image " +
                "| imageupload | code | fullscreen  | table",
            relative_urls: false,
            image_list: "{!! route('images.list') !!}",
            table_advtab: true,


        });
    </script>

@endsection

