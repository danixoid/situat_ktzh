<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 28.06.17
 * Time: 10:24
 */?><!DOCTYPE html>
<html ng-app="app" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>


<div class="container" ng-controller="EDSController">

    <div class="form-horizontal">

        <h2 class="form-signin-heading">{!! trans('interface.xml_signing') !!}</h2>

        <div class="form-group" ng-if="detail.step==3">
            <label class="control-label col-md-3">{!! trans('interface.note') !!} {!! $exam->chief->name !!}</label>
            <div class="col-md-6 form-control-static">
                <textarea rows="5" class="form-control" ng-model="detail.note"
                          placeholder="{{ trans('interface.type_text') }}"></textarea>
            </div>
        </div>

        <div class="form-group" ng-if="detail.step==3 && !detail.signed">
            <div class="col-md-9 col-md-offset-3">
                <button type="submit" ng-click="putExam()" class="btn btn-success btn-lg">{{ trans('interface.next') }}</button>
            </div>
        </div>

        <!-- SIGNING -->
        @if(\AUTH::user()->id == $exam->chief->id)
        <div class="form-group" ng-if="detail.step==0 && !detail.signed">
            <label class="control-label col-md-3">{{ trans('interface.note') }}</label>
            <div class="col-md-9 form-control-static">
                @{{ detail.note }}
            </div>
        </div>
        @endif

        <div class="form-group" ng-if="detail.step==0 && !detail.signed">
            <label class="control-label col-md-3">{{ trans('interface.xml_data') }}</label>
            <div class="col-md-9">
                <textarea class="form-control" disabled rows="10">@{{ data }}</textarea>
            </div>
        </div>
        <div class="form-group" ng-if="detail.step == 0 && !detail.signed">
            <div class="col-md-9 col-md-offset-3">
                <button class="btn btn-lg btn-primary" type="button"
                    ng-click="selectSignType();">Выбрать сертификат</button>
            </div>
        </div>
        <div class="form-group" ng-if="detail.step==0 && detail.signed">
            <label class="control-label col-md-3">Подписаный XML</label>
            <div class="col-md-9">
                <input type="hidden" name="certificate" ng-value="certificate"/>
                <textarea class="form-control" disabled rows="10">@{{ certificate || data }}</textarea>
            </div>
        </div>

        <div class="form-group" ng-if="detail.step==0 && detail.signed">
            <div class="col-md-9 col-md-offset-3">
                <button class="btn btn-lg btn-success" ng-click="sendToVerifyXML();"
                        type="button">{!! trans('interface.next') !!}</button>
            </div>
        </div>

        <div class="form-group" ng-if="detail.step==4">
            <div class="col-md-9 col-md-offset-3">
                <h3>Завершено</h3>
            </div>
        </div>

        <div class="form-group" ng-if="detail.step==1">
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered small">
                    <tr>
                        <th>ИИН:</th>
                        <td>@{{ person['SERIALNUMBER'] || 'НЕ ОПРЕДЕЛЕНО' }}</td>
                    </tr>
                    <tr>
                        <th>E-mail:</th>
                        <td>@{{ person['E'] || 'НЕ ОПРЕДЕЛЕНО' }}</td>
                    </tr>
                    <tr>
                        <th>ФИО</th>
                        <td>@{{ person['CN'] || 'НЕ ОПРЕДЕЛЕНО' }} @{{ person['G'] }}</td>
                    </tr>
                    <tr id="signBINRow">
                        <th>БИН::</th>
                        <td>@{{ person['OU'] || 'НЕ ОПРЕДЕЛЕНО' }}</td>
                    </tr>
                    <tr id="signOrgNameRow">
                        <th>Наименование организации:</th>
                        <td>@{{ person['O'] || 'НЕ ОПРЕДЕЛЕНО' }}</td>
                    </tr>
                    <tr>
                        <th>Срок действия:</th>
                        <td>@{{ person['beginDate'] + " - " + person['endDate'] || 'НЕ ОПРЕДЕЛЕНО' }}</td>
                    </tr>

                </table>
                <button class="btn btn-success" type="button" ng-click="signXml(data);">Подписать</button>
                <button class="btn btn-danger" type="button" ng-click="detail.step = 0">Отмена</button>
            </div>
        </div>

        <div class="form-group" ng-if="detail.step==2">
            <label class="control-label col-md-3">{{ trans('interface.eds_password') }}:</label>
            <div class="col-md-6">
                <input type="password" class="form-control" ng-model="detail.ncaPassword" />
                <span ng-model="password_message" ng-class="password_message_class"></span>
            </div>
        </div>
        <div class="form-group" ng-if="detail.step==2">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-success" type="button" ng-click="setNCAPassword(detail.ncaPassword)">{{ trans('interface.next') }}</button>
                <button class="btn btn-danger" type="button" ng-click="detail.step = 1">Отмена</button>
            </div>
        </div>
    </div>

    <!-- Modal -->

    <div class="modal" id="response" tabindex="-1" role="dialog" aria-labelledby="signModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Ответ сервера</h4>
                </div>
                <div class="modal-body table-responsive">
                    @{{ jsonResponse }}
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js"></script>
{{--<script type="text/javascript" src="{!! asset('/js/angular.min.js') !!}"></script>--}}
<script type="text/javascript" src="{!! asset('/js/services-eds.js') !!}"></script>
<script>

    eds.controller('EDSController',function($scope,$http,NCALayer) {
        $scope.jsonResponse = "";

        $scope.detail = {
            signed : false,
            @if($exam->chief_id == \Auth::user()->id)note : "{!! $exam->note !!}",@endif
            ncaPassword : "",
            step : @if($exam->chief_id == \Auth::user()->id) 3 @else 0 @endif
        };

        $scope.loadExam = function() {
            $http({
                url: '{!! route('exam.show',$exam->id) !!}',
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).success(function (data) {
//                delete data.signs;
                $scope.data = json2xml(data,'root');
                $scope.detail.step = 0;
            });
        };

        if($scope.detail.step === 0) {
            $scope.loadExam();
        }

        $scope.putExam = function() {
            $http({
                url: '{!! route('exam.update',$exam->id) !!}',
                method: 'POST',
                data: {
                    "_method" : "PUT",
                    "note" : $scope.detail.note
                },
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).success(function (data) {
                $scope.loadExam();
            });
        };

        $scope.printPDF = function () {
            $http({
                url: './print',
                method: 'POST',
                responseType: 'arraybuffer',
                data: {
                    "url_file" : $scope.urlFile,
                    "verify_url" : "/",
                    "cms_data" : [$scope.detail.signedFile]
                },
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }

            }).success(function (data, status, headers, config) {
                var blob = new Blob([data], {type: "application/pdf"});
                var objectUrl = URL.createObjectURL(blob);
                var win = window.open(objectUrl, '_blank');
                win.focus();
            });
        };

        $scope.sendToVerifyXML = function () {
            $http({
                url: '{!! route('java.eds.restapi') !!}',
                method: 'POST',
                data: $scope.certificate,
                headers: {
                    "Content-Type": "application/xml",
                    "X-Requested-With": "XMLHttpRequest"
                }

            }).then(function(response) {
                $scope.jsonResponse = response.data;
                console.log(JSON.stringify($scope.jsonResponse));
                if($scope.jsonResponse.SignatureValue) {
                    $http({
                        url: '{!! route('sign.store') !!}',
                        method: 'POST',
                        data: {
                            "exam_id" : {!! $exam->id !!},
                            "signer_id" : {!! \Auth::user()->id !!},
                            "xml" : $scope.certificate
                        },
                        headers: {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    }).success(function (data) {
                        $scope.detail.step = 4;
                    });
                }
            });
        };

        $scope.sendToVerifyFile = function () {
            $http({
                url: './restapi',
                method: 'POST',
                data: JSON.stringify({ "url_file" : $scope.urlFile, "signed_file" : $scope.detail.signedFile  }),
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(function(response) {
                $scope.jsonResponse = response.data;
                alert(JSON.stringify($scope.jsonResponse));
            });
        };

        NCALayer.postGetDate = function  (answer,dateName) {
            $scope.person[dateName] = answer.result.split(' ')[0];
            console.log($scope.person[dateName]);
            $scope.$apply();
        };

        NCALayer.postGetSubjectDN = function  (answer) {
            NCALayer.showPerson(true);
            var arr = answer.result.split(',');
            for(var i = 0; i < arr.length; i++) {
                var keyVal = arr[i].split('=');

                if(keyVal[0] == 'SERIALNUMBER') {
                    $scope.person[keyVal[0]] = keyVal[1].replace(/^IIN/,'');

                } else if(keyVal[0] == 'OU') {
                    $scope.person[keyVal[0]] = keyVal[1].replace(/^BIN/,'');
                } else {
                    $scope.person[keyVal[0]] = keyVal[1];
                }
                $scope.$apply();
                console.log(keyVal[0] +  ' = ' + $scope.person[keyVal[0]] + ", " + keyVal[1]);
            }
        };

        NCALayer.postSignXml = function (answer) {
            $scope.certificate = answer.result;
            $scope.detail.signed = true;

            $scope.$apply();
        };

        NCALayer.postCreateCMSSignatureFromFile= function (answer) {
            $scope.detail.signedFile = answer.result;

            $scope.$apply();
        };

        NCALayer.postShowFileChooser = function (answer) {
            $scope.fileToSign = answer.result;
            $scope.$apply();
        };

        NCALayer.showPassword = function (show) {
            if (show) {
                $scope.detail.step = 2;
            } else {
                $scope.detail.step = 0;
            }
            $scope.$apply();
        };

        NCALayer.showPerson = function (show) {
            if (show) {
                $scope.detail.step = 1;
            } else {
                $scope.detail.step = 0;
            }
            $scope.$apply();
        };

        NCALayer.noConnection = function() {
            alert('Ошибка подключения к прослойке');
        };

        NCALayer.bind($scope);

    });

    var app = angular.module('app', ['edsApp']);
</script>
</body>
</html>
