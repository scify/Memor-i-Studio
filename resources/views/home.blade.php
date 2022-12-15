@extends('layouts.app')
@section('content')
    <div class="row margin-top-50 margin-bottom-50 memoriActionBtns">
        <div class="col-md-5 col-md-offset-1 text-align-center">
            <a href="{{route('showGameVersionSelectionForm')}}"
               class="btn btn-success btn-ripple width-percent-100">
                <h2 class="btn-title"><i class="fa fa-lightbulb-o"
                                         aria-hidden="true"></i> {!! __('messages.create_new_game') !!}</h2>
            </a>
        </div>
        <div class="col-md-5 text-align-center">
            <a href="{{route('showAllGameFlavors')}}" class="btn btn-primary btn-ripple width-percent-100">
                <h2 class="btn-title"><i class="fa fa-gamepad"
                                         aria-hidden="true"></i> {!! __('messages.see_all_games') !!}</h2>
            </a>
        </div>
    </div>
    <div class="row margin-top-50">
        <div class="col-md-12 centeredVertically text-align-center padding-15">
            <div class="card card-video card-player-blue">

                <div class="card-heading padding-top-20">
                    <h2>{!! __('messages.welcome_to') !!} Memor-i Studio!</h2>

                </div><!--.card-heading-->
                <div class="card-body">
                    <ul class="list">
                        <li><h4><i class="fa fa-check" aria-hidden="true"></i> {!! __('messages.intro_text_1') !!}</h4>
                        </li>
                        <li><h4><i class="fa fa-check" aria-hidden="true"></i> {!! __('messages.intro_text_2') !!}</h4>
                        </li>
                        <li><h4><i class="fa fa-check" aria-hidden="true"></i> {!! __('messages.intro_text_3') !!}</h4>
                        </li>
                    </ul>
                </div><!--.card-body-->

            </div><!--.card-->
        </div><!--.col-md-6-->
        <div class="col-md-3 col-centered text-center">
            <a href="https://www.scify.gr/site/en/">
                <img width="90px" height="108px" loading="lazy" src="{{asset("/assets/img/scify_logo_108.png")}}"
                     alt="Logo Image">
            </a>
        </div>
    </div>
@endsection
