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
                            <label class="col-md-10">{!! trans('interface.tickets') !!}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('position.create') !!}" >{!! trans('interface.create') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-md-3 control-label">{!! trans('interface.org') !!}</label>
                            <div class="col-md-9">
                                <select class="form-control select2-single" name="org_id" id="org">
                                </select>
                            </div>
                        </div>

                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>{!! trans('interface.org') !!}</th>
                                    <th>{!! trans('interface.position') !!}</th>
                                    {{--<th>{!! trans('interface.edit') !!}</th>--}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($positions as $position)
                                    <tr>
                                        <td>{!! $position->id !!}</td>
                                        <td><a href="{!! route('position.index',['org_id' => $position->org_id]) !!}">
                                                <span class="text-info">{!! $position->orgPath !!}</span></a></td>
                                        <td><a href="{!! route('position.show',['id' => $position->id]) !!}">{!! $position->name !!}</a></td>
{{--                                        <td><a href="{!! route('position.edit',['id'=>$position->id]) !!}">{!! trans('interface.edit') !!}</a></td>--}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! $positions->links() !!}
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
            $("#org").select2({
                data: [
                    {
                        id: '{!! request('org_id') ?: '0' !!}',
                        orgPath: '{!! request()->has('org_id')
                            ? \App\Org::find(request('org_id'))->orgPath
                            : trans('interface.no_value') !!}'
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
                allowClear: true,
                language: '{!! config()->get('app.locale') !!}',
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 2,
                templateResult: formatPosition, // omitted for brevity, see the source of this page
                templateSelection: formatPositionSelection // omitted for brevity, see the source of this page
            });

            $('#org').on("select2:select", function(e) {
                window.location.href = '?org_id=' + e.target.value;
            });

            $('#org').on("select2:unselecting", function(e) {
                window.location.href = '{!! route('position.index') !!}';
            });
        });


        function formatPosition (org) {
            return "<div class='text-info'>" + org.orgPath + "</div>";
        }

        function formatPositionSelection (org) {
            return "<label class='text-info'>" + org.orgPath + "</label>";
        }

    </script>

@endsection