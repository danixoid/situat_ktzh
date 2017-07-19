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
                    <div class="panel-heading">{!! trans('interface.exam') !!} | {!! trans('interface.create') !!}</div>

                    <div class="panel-body">


                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">{{ trans('interface.create') }}</a></li>
                            <li role="presentation"><a href="#import" aria-controls="import" role="tab" data-toggle="tab">{{ trans('interface.import') }}</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">

                            <!-- TAB 1 -->
                            <div role="tabpanel" class="tab-pane active" id="home">

                                <form id="form_create_exam" class="form-horizontal" action="{!! route('exam.store') !!}" method="POST">
                                    {!! csrf_field() !!}

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

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.ticket_count') !!}</label>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" name="count" value="{!! old('count') ?: "2" !!}"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-md-3">
                                            <button class="btn btn-block btn-danger" >{!! trans('interface.create') !!}</button>
                                        </div>
                                    </div>
                                </form>
                                <table id="quests_table" class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>{{ trans('interface.task') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <!-- TAB 2 -->
                            <div role="tabpanel" class="tab-pane" id="import">
                                <form id="form_create_exam" class="form-horizontal"  enctype="multipart/form-data"
                                      action="{!! route('exam.store') !!}" method="POST">
                                    {!! csrf_field() !!}

                                    <div class="form-group">
                                        <div class="col-md-9 col-md-offset-3">
                                            <input type="file" name="file" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.ticket_count') !!}</label>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" name="count" value="{!! old('count') ?: "2" !!}"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-md-3">
                                            <button class="btn btn-block btn-danger" >{!! trans('interface.import') !!}</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>


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
                },
                user : {
                    id: '{!! old('user_id') ?: 0 !!}',
                    name: '{!! (old('user_id'))
                                ? \App\User::find(old('user_id'))->name
                                : trans('interface.no_value') !!}',
                },
                chief : {
                    id: '{!! old('chief_id') ?: 0 !!}',
                    name: '{!! (old('chief_id'))
                                ? \App\User::find(old('chief_id'))->name
                                : trans('interface.no_value') !!}',
                }
            };

            $("#org,#func,#position,#user,#chief").each(function(){
                var id = $(this).attr('id');

                userId = id === 'chief' ? 'user' : id;

                $(this).select2({
                    data: [
                        data[id]
                    ],
                    ajax: {
                        url: "{!! url('/" + (id === "chief" ? "user" : id) + "') !!}",
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


            $("#org,#func,#position").each(function() {
                $(this).on("select2:select", function(e) {

                    $.ajax({
                        url: "{!! route('quest.index') !!}",
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            'org_id' : $("#org").val() > 0 ? $("#org").val() : null,
                            'func_id' : $("#func").val() > 0 ? $("#func").val() : null,
                            'position_id' : $("#position").val() > 0 ? $("#position").val() : null
                        },
                        success: function(result) {
                            var data = result.quests.data;
                            console.log(JSON.stringify(result));
                            $('#quests_table tbody').html('');
                            for(var i = 0; i < data.length; i++) {
                                $('#quests_table tbody').append(
                                    '<tr>' +
                                    '<td>' + (i+1) + '</td>' +
                                    '<td>' + data[i].task + '</td>' +
                                    '</tr>'
                                )
                            }
                        }
                    });
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

