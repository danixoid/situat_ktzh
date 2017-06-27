@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{!! trans('interface.tickets') !!}</div>

                <div class="panel-body">

                    @if(count($exams) > 0)
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>{{ trans('interface.position') }}</th>
                                <th>{{ trans('interface.user') }}</th>
                                <th>{{ trans('interface.quests') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($exams as $exam)
                                    <td><span class="text-info">{!! $exam->position->orgPath !!}/{!! $exam->position->name !!}</span></td>
                                    <td>{!! $exam->user->name !!}</td>
                                    <td>{!! $exam->count !!}</td>
                                    <td>
                                        <a href="{!! route('exam.show',['id' => $exam->id]) !!}">
                                            {!! trans('interface.show') !!}
                                        </a>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                    {!! $exams->links() !!}
                    @else
                        <h3>{!! trans('interface.not_found') !!}</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
