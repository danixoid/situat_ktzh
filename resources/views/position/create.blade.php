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
                    <div class="panel-heading">{!! trans('interface.positions') !!} | {!! trans('interface.create') !!}</div>

                    <div class="panel-body">

                        <form id="form_create_position" class="form-horizontal" action="{!! route('position.store') !!}" method="POST">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.org') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="org_id" id="position">
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.position') !!}</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="name" value="">
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
    <script>

        $(function () {

            /**
             *  SELECT2
             */
            $("#position").select2({
                data: [
                    {
                        id: 0,
                        orgPath: '{!! trans('interface.no_value') !!}'
                    }
                ],
                ajax: {
                    url: "{!! url('/org') !!}",
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
                allowClear: false,
                language: '{!! config()->get('app.locale') !!}',
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 2,
                templateResult: formatPosition, // omitted for brevity, see the source of this page
                templateSelection: formatPositionSelection // omitted for brevity, see the source of this page
            });
        });


        function formatPosition (org) {
            return "<div class='label label-info'>" + org.orgPath + "</div>";
        }

        function formatPositionSelection (org) {
            return "<label class='label label-info'>" + org.orgPath + "</label>";
        }

    </script>

@endsection

