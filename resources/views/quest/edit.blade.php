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
                                <label class="col-md-3 control-label">{!! trans('interface.org') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" id="org">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.func') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" id="func">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.position') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" id="position">
                                    </select>
                                </div>
                            </div>

                            @for($i=0;$i<10;$i++)
                                <input type="hidden" name="struct[{{ $i }}][position]" value="{{ $i }}">
                                <input type="hidden" name="struct[{{ $i }}][org]" value="{{ $i }}">
                                <input type="hidden" name="struct[{{ $i }}][func]" value="{{ $i }}">
                            @endfor

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

            var data = {

                org : {
                    id: '{!! old('org_id') ?: 0 !!}',
                    name: '{!! (old('org_id'))
                                ? \App\Org::find(old('org_id'))->name
                                : trans('interface.no_value') !!}',
                },
                func : {
                    id: '{!! old('func_id') ?: 0 !!}',
                    name: '{!! (old('func_id'))
                                ? \App\Func::find(old('func_id'))->name
                                : trans('interface.no_value') !!}',
                },
                position : {
                    id: '{!! old('position_id') ?: 0 !!}',
                    name: '{!! (old('position_id') > 0)
                                ? \App\Position::find(old('position_id'))->name
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
                    allowClear: false,
                    language: '{!! config()->get('app.locale') !!}',
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                    minimumInputLength: 2,
                    templateResult: formatDetail, // omitted for brevity, see the source of this page
                    templateSelection: formatDetailSelection // omitted for brevity, see the source of this page
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

