@extends('layouts.app')

@section('content')
<div class="container">


    <div class="row form-group">
        <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <?php $step = 0 ?>
                @foreach($tickets as $ticket)
                    <?php $step++ ?>
                    <li class="{!! $step == 1 ? "active" : "disabled" !!}"><a href="#step-{!! $ticket->id !!}">
                        <h4 class="list-group-item-heading">{!! trans('interface.quest_number',['num' => $step]) !!}</h4>
                        <p class="list-group-item-text">{!! trans('interface.timer') !!}:
                            {!! $ticket->quest->timer !!}
                            {!! trans('interface.minutes') !!} </p>
                        </a></li>
                @endforeach
            </ul>
        </div>
    </div>

    <?php $step = 0 ?>
    @foreach($tickets as $ticket)
    <?php $step++ ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="col-md-12 well setup-content " id="step-{!! $ticket->id !!}">

                <form class="form-horizontal" action="{!! url('') !!}" method="POST">
                    {!! csrf_field() !!}

                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-9">
                            <h3>{!! trans('interface.remaining_time',['time' => date('i:s',$ticket->quest->timer*60)]) !!}</h3>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">{!! trans('interface.source') !!}</label>
                        <div class="col-md-9">
                            <pre>{!! $ticket->quest->source !!}</pre>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">{!! trans('interface.task') !!}</label>
                        <div class="col-md-9">
                            <pre>{!! $ticket->quest->task !!}</pre>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">{!! trans('interface.answer') !!}</label>
                        <div class="col-md-9">
                            <textarea placeholder="{!! trans('interface.type_text') !!}" rows="6" class="form-control" name="answer">{!! $ticket->answer !!}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-3 form-control-static">

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-3 form-control-static">
                            <button id="activate-step-2" class="btn btn-primary btn-lg">{!! trans('interface.next') !!} >></button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection


@section('javascript')
    <script>
        $(document).ready(function() {

            var navListItems = $('ul.setup-panel li a'),
                allWells = $('.setup-content');


            allWells.hide();

            navListItems.click(function(e)
            {
                e.preventDefault();
                var $target = $($(this).attr('href')),
                    $item = $(this).closest('li');

                if (!$item.hasClass('disabled')) {
                    navListItems.closest('li').removeClass('active');
                    $item.addClass('active');
                    allWells.hide();
                    $target.show();
                }
            });

            $('ul.setup-panel li.active a').trigger('click');

            // DEMO ONLY //
            <?php $step = 0 ?>
            @foreach($tickets as $ticket)
            @if($step < $ticket->exam->count)
            $('#activate-step-{{ $step + 1 }}').on('click', function(e) {
                $('ul.setup-panel li:eq({{ $step }})').removeClass('disabled');
                $(this).remove();
                $('ul.setup-panel li:eq({{ $step }})').addClass('active');
                $('ul.setup-panel li:eq({{ $step }})').show();

                $('ul.setup-panel li:eq({{ $step - 1 }})').removeClass('active');
                $('ul.setup-panel li:eq({{ $step - 1 }})').addClass('disabled');
                $('ul.setup-panel li:eq({{ $step - 1 }}) a').addClass('disabled');

                $('ul.setup-panel li.active a').trigger('click');
            });
            <?php $step++ ?>
            @endif
            @endforeach
        });


    </script>
@endsection