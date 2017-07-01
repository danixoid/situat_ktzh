<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 01.07.17
 * Time: 21:42
 */?>
@extends('layouts.pdf')

@section('content')
    <style>
        body {
            /*font-family: sans-serif;*/
            font-size: 10pt;
        }
    </style>
    <div>
        <h3 style="clear:both; text-align: center;">{{ trans('interface.exam') }} № {{ $exam->id }}</h3>
        <div>
            <div style="float:right;">
                <p><strong>{{ trans('interface.started_date') }}</strong>:</p>
                <p>{{ date('d.m.Y',strtotime($exam->finishedDate)) }}г.</p>
            </div>
            <div>
                <p><strong>{{ trans('interface.employee') }}</strong>: {{ $exam->user->name }}</p>
                <p><strong>{{ trans('interface.chief') }}</strong>: {{ $exam->chief->name }}</p>
            </div>

            <div style="clear:both"></div>
        </div>

        @foreach($exam->tickets as $ticket)

            <fieldset>
            <p><strong>{!! trans('interface.quest_number',['num' => $ticket->quest->id]) !!}</strong></p>
            <p>{!! $ticket->quest->task !!}</p>
            <p><strong>{{ trans('interface.answer') }}</strong></p>
            <p>{{ $ticket->answer }}</p>
            </fieldset>
        @endforeach
        <br />
        <div><strong>{{ trans('interface.note') }}</strong>: {{ $exam->note }}</div>
        <br />
        @if(count($exam->signs) > 0)
            <div><strong>{!! trans('interface.signers') !!}</strong>:</div>
            <br />
            <div>
                @foreach($exam->signs as $sign)
                    <?php
                    $root = simplexml_load_string($sign->xml);
                    $errors = libxml_get_errors();
                    $root->registerXPathNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
                    $pub = ("-----BEGIN CERTIFICATE-----".
                        $root->xpath('//ds:Signature/ds:KeyInfo/ds:X509Data/ds:X509Certificate')[0]->__toString()
                        ."-----END CERTIFICATE-----");
                    $pub_key = openssl_x509_parse(openssl_x509_read($pub));
                    ?>
                    <div style="border: 1px solid #555555; margin-right: 20px; padding:10px 20px;float: left;">
                        <strong>
                            @if($sign->signer_id == $exam->chief_id)
                                {!! trans('interface.chief') !!}
                            @else
                                {!! trans('interface.employee') !!}
                            @endif
                        </strong>
                        <p>{!! $sign->signer->name !!}</p>
                        <p>{!! trans('interface.signer') !!}: {!! $pub_key['subject']['CN'] !!}</p>
                        <p>{!! trans('interface.iin') !!}:
                            {!! mb_ereg_replace("^(I|B)IN","",$pub_key['subject']['serialNumber']) !!}</p>
                        <p>{!! trans('interface.sign') !!}:</p>
                        <img src="data:image/png;base64,
                            {!! base64_encode(\QrCode::format('png')
                                ->size(150)
                                ->generate(route('exam.show',$root->xpath('//ds:Signature/ds:SignatureValue')[0])))  !!}"/>
                    </div>
                @endforeach
{{--

                <div style="clear:both;">

                    <img src="data:image/png;base64,
                            {!! base64_encode(\QrCode::format('png')
                                ->size(150)
                                ->generate(route('exam.show',$root->xpath('//ds:Signature/ds:SignatureValue')[0])))  !!}"/>
                </div>
--}}

            </div>
        @endif
    </div>
@endsection