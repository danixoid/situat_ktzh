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
                    <div class="panel-heading">{!! trans('interface.tickets') !!} | {!! trans('interface.create') !!}</div>

                    <div class="panel-body">
                        <form id="form_create_quest" class="form-horizontal" enctype="multipart/form-data"
                              action="{!! route('quest.store') !!}" method="POST">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.position') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-multiple" multiple name="positions[]" id="positions">
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.task') !!}</label>
                                <div class="col-md-9">
                                    <textarea id="task" rows="6" class="form-control" name="task">{!! old('task') !!}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.timer') !!}</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="timer" value="{!! old('timer') ?: "15" !!}"/>
                                </div>
                            </div>

                            <div class="form-group ">
                                <div class="col-md-12 files color">
                                    <label class="col-md-3 control-label">{!! trans('interface.import') !!}</label>
                                    <div class="col-md-9">
                                        <input type="file" name="word_file" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-3">
                                    <button class="btn btn-block btn-danger" >{!! trans('interface.create') !!}</button>
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
            return "<label class='text-info'>" + position.orgPath + "</label> <span>" + position.name + "</span>";
        }

        tinymce.init({
            selector: 'textarea',  // change this value according to your HTML
            language: '{!! config('app.locale') == "kz" ? "kk" : config('app.locale')!!}',
            menubar : false,
            plugins: [
                "link image code fullscreen imageupload"
            ],
            toolbar: "undo redo | bold italic | bullist numlist outdent indent | link image | imageupload | code | fullscreen",
            relative_urls: false,
            image_list: "{!! route('images.list') !!}"

        });


    </script>

@endsection

