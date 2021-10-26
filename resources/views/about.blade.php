@extends('layouts.app')
@section('content')

    <div class="row aboutPageRow">
        <div class="col-md-6 mx-auto">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>{!! __('messages.details') !!}</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body padding-30">
                    <h4>{!! __('messages.info_about') !!} Memor-i Studio</h4>
                    <b>{!! __('messages.what_is_memori_studio') !!}</b>
                    <br>
                    {!! __('messages.pre') !!}<i>Memor-i Studio</i> {!! __('messages.about_text_1') !!}
                    <br>
                    <ul class="list margin-bottom-20 margin-top-20">
                        <li><i class="fa fa-check" aria-hidden="true"></i> {!! __('messages.about_text_1') !!}</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> {!! __('messages.about_text_2') !!}</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> {!! __('messages.about_text_3') !!}</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> {!! __('messages.about_text_4') !!}</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> {!! __('messages.about_text_5') !!}</li>
                    </ul>
                    <b>{!! __('messages.who_can_use_memori_studio_title') !!}</b>
                    <br>
                    {!! __('messages.who_can_use_memori_studio_message') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 centeredVertically">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>{!! __('messages.about') !!}</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body padding-30">
                    {!! __('messages.about_text_7') !!}
                    <div class="row margin-top-30">
                        <div class="col-md-6 text-align-center">
                            <a href="https://www.scify.gr/site/en/">
                                <img loading="lazy" class="col-md-5 centeredVertically"
                                     src="{{asset("/assets/img/scify.jpg")}}" alt="Latsis Logo Image">
                            </a>
                        </div>
                        <div class="col-md-6 text-align-center">
                            <a href="https://www.latsis-foundation.org/eng">
                                <img loading="lazy" class="col-md-6 margin-top-20 centeredVertically"
                                     src="{{asset("/assets/img/latsis_logo.jpg")}}" alt="Latsis Logo Image">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row margin-top-20">
        <div class="col-md-6 centeredVertically">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>{!! __('messages.disclaimer_title') !!}</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body padding-30 disclaimerText">
                    {!! __('messages.disclaimer_message') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
