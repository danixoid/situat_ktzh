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
                        <strong>{!! $exam->user->name !!}</strong>,
                        <a href="{!! route('exam.index',['position_id' => $exam->position_id]) !!}">
                            <span class="text-primary">{!! $exam->position->orgPath !!}/{!! $exam->position->name !!}</span>
                        </a>

                    </div>

                    <div class="panel-body form-horizontal">

                        <div class="form-group">
                            <h3 class="col-md-offset-3 col-md-6 text-{!! $exam->color !!}">
                                {!! trans('interface.'.$exam->status) !!}</h3>
                        </div>

                        @if(\AUTH::user()->id != $exam->user->id || $exam->finished)
                        <div class="form-group">
                            <label class="control-label col-md-3">{!! trans('interface.quests') !!} ({!! $exam->count !!})</label>
                            <div class="col-md-6 form-control-static">

                                @foreach($exam->tickets as $ticket)
                                    <div class="well">
                                        <a href="#signing_modal" class="bmd-modalButton" data-toggle="modal"
                                           data-bmdSrc="{!! route('ticket.show',$ticket->id) !!}"
                                           data-bmdWidth="600" data-bmdHeight="550" data-target="#signing_modal">
{{--                                            <span class="text-success">{!! $ticket->quest->shortSource !!}</span><br />--}}
                                            <span class="text-default">{!! $ticket->quest->shortTask !!}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($exam->note)
                        <div class="form-group">
                            <label class="control-label col-md-3">{!! trans('interface.note') !!} {!! $exam->chief->name !!}</label>
                            <div class="col-md-6 form-control-static">
                                {!! $exam->note !!}
                            </div>
                        </div>
                        @endif

                        <div class="form-group">
                            <div class="col-md-3">&nbsp;</div>
                            @if(\AUTH::user()->hasAnyRole(['manager','admin']) && !$exam->started)
                            <div class="col-md-3">
                                <a href="{!! route('exam.edit',['id'=>$exam->id]) !!}" class="btn btn-block btn-info">{!! trans('interface.edit') !!}</a>
                            </div>

                            <div class="col-md-3">
                                <a href="#" id="deleteExam" class="delete btn btn-block btn-danger">{!! trans('interface.destroy') !!}</a>
                            </div>
                            @endif

                            @if(!$exam->finished && \AUTH::user()->id == $exam->user->id)
                            <div class="col-md-3">
                                <a href="{!! route('ticket.index',['exam_id' => $exam->id]) !!}" class="delete btn btn-block btn-warning">{!! trans('interface.start') !!}</a>
                            </div>
                            @elseif(($exam->finished && (\AUTH::user()->id == $exam->chief->id
                                || \AUTH::user()->id == $exam->user->id) && !$exam->amISigner))

                            <div class="col-md-3">

                                <a href="#signing_modal" class="btn btn-info btn-lg bmd-modalButton" data-toggle="modal"
                                   data-bmdSrc="{!! route('signing.data',$exam->id) !!}"
                                   data-bmdWidth="600" data-bmdHeight="550" data-target="#signing_modal">{!! trans('interface.eds_signing') !!}</a>
                                {{--<a href="{!! route('signing.data',$exam->id) !!}" id="signing" class="btn btn-primary"></a>--}}
                            </div>
                            @endif

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">{!! trans('interface.signers') !!}</label>
                            @foreach($exam->signs as $sign)
                                <div class="col-md-3 form-control-static">
                                    <div class="well">
                                        <strong>
                                        @if($sign->signer_id == $exam->chief_id)
                                            {!! trans('interface.chief') !!}
                                        @else
                                            {!! trans('interface.employee') !!}
                                        @endif
                                        </strong>
                                        <p>{!! $sign->signer->name !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <form id="form_delete_exam" action="{!! route('exam.destroy',['id' => $exam->id]) !!}" method="POST">
        {!! csrf_field() !!}
        {!! method_field("DELETE") !!}
    </form>


    <div class="modal fade" id="signing_modal">
        <div class="modal-dialog wide">
            <div class="modal-content bmd-modalContent">

                <div class="modal-body">

                    <div class="close-button">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="embed-responsive embed-responsive-16by9">
                        <h2 class="form-signin-heading">{!! trans('interface.xml_signing') !!}</h2>
                        <iframe class="embed-responsive-item" frameborder="0"></iframe>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('meta')
    <style>
        @media screen and (min-width: 768px) {
            .wide {
                width: 70%; /* either % (e.g. 60%) or px (400px) */
            }
        }

        /*.bmd-modalButton {*/
            /*display: block;*/
            /*margin: 15px auto;*/
            /*padding: 5px 15px;*/
        /*}*/

        .close-button {
            overflow: hidden;
        }

        .bmd-modalContent {
            box-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .bmd-modalContent .close {
            font-size: 30px;
            line-height: 30px;
            padding: 7px 4px 7px 13px;
            text-shadow: none;
            opacity: .7;
            color:#fff;
        }

        .bmd-modalContent .close span {
            display: block;
        }

        .bmd-modalContent .close:hover,
        .bmd-modalContent .close:focus {
            opacity: 1;
            outline: none;
        }

        .bmd-modalContent iframe {
            display: block;
            margin: 0 auto;
        }
    </style>
@endsection


@section('javascript')
    <script>
        $(function(){
            $("#deleteExam").click(function() {
                $('#form_delete_exam').submit();
            });

        });

        (function($) {

            $.fn.bmdIframe = function( options ) {
                var self = this;
                var settings = $.extend({
                    classBtn: '.bmd-modalButton',
                    defaultW: 640,
                    defaultH: 360
                }, options );

                $(settings.classBtn).on('click', function(e) {
                    var allowFullscreen = $(this).attr('data-bmdVideoFullscreen') || false;

                    var dataVideo = {
                        'src': $(this).attr('data-bmdSrc'),
                        'height': $(this).attr('data-bmdHeight') || settings.defaultH,
                        'width': $(this).attr('data-bmdWidth') || settings.defaultW
                    };

                    if ( allowFullscreen ) dataVideo.allowfullscreen = "";

                    // stampiamo i nostri dati nell'iframe
                    $(self).find("iframe").attr(dataVideo);
                });

                // se si chiude la modale resettiamo i dati dell'iframe per impedire ad un video di continuare a riprodursi anche quando la modale è chiusa
                this.on('hidden.bs.modal', function(){
                    $(this).find('iframe').html("").attr("src", "");
                });

                return this;
            };

        })(jQuery);




        jQuery(document).ready(function(){
            jQuery("#signing_modal").bmdIframe();
        });
    </script>
@endsection
