@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{!! trans('interface.situation') !!}</div>

                <div class="panel-body">
                    <form class="form-horizontal" action="{!! url('') !!}" method="POST">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="col-md-3 control-label">{!! trans('interface.source') !!}</label>
                            <div class="col-md-9">
                                <pre>some source</pre>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{!! trans('interface.task') !!}</label>
                            <div class="col-md-9">
                                <pre>some task</pre>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{!! trans('interface.answer') !!}</label>
                            <div class="col-md-9">
                                <textarea rows="6" class="form-control" name="answer">some answer</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{!! trans('interface.answer') !!}</label>
                            <div class="col-md-9">
                                <button class="btn btn-block btn-info" name="">{!! trans('interface.next') !!}</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-offset-3 col-md-9">{!! trans('interface.timer') !!}</label>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
