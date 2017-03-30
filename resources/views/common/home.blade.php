@extends('layouts.app')
@section('content')
    <div class="row margin-top-50 margin-bottom-50 memoriActionBtns">
        <div class="col-md-6">
        <div class="col-md-9 text-align-center centeredVertically">
            <a href="{{route('showGameVersionSelectionForm')}}" class="btn btn-success btn-ripple width-percent-100">
                <h1><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Create new Game</h1>
            </a>
        </div><!--.col-md-6-->
        </div>
        <div class="col-md-6">
        <div class="col-md-9 text-align-center centeredVertically">
            <a href="{{route('showAllGameFlavors')}}" class="btn btn-primary btn-ripple width-percent-100">
                <h1><i class="fa fa-gamepad" aria-hidden="true"></i> See all Games</h1>
            </a>
        </div><!--.col-md-6-->
        </div>
    </div>
    <div class="row homePageRow">
        <div class="col-md-12 centeredVertically text-align-center padding-15">
            <div class="card card-video card-player-blue">

                <div class="card-heading padding-top-20">
                    <h4>Welcome to Memor-i Studio!</h4>

                </div><!--.card-heading-->

                <div class="card-body">
                    {{--<iframe width="600" height="400"--}}
                            {{--src="https://www.youtube.com/embed/We2iD21V9WI">--}}
                    {{--</iframe>--}}
                    <ul class="list">
                        <li><i class="fa fa-check" aria-hidden="true"></i> Find free Memor-i games</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> Create your own Memor-i game without programming knowledge!</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> Learn and have fun whether you’re blind or not</li>
                    </ul>
                </div><!--.card-body-->

            </div><!--.card-->
        </div><!--.col-md-6-->
    </div>
    <div class="row homePageRow">
        <div class="col-md-12 centeredVertically text-align-center padding-15">
            <div class="card card-video card-player-blue">

                <div class="card-heading padding-top-20">
                    <h4>Καλώς ήρθες στο Memor-i Studio! </h4>
                </div><!--.card-heading-->

                <div class="card-body">
                    {{--<iframe width="600" height="400"--}}
                    {{--src="https://www.youtube.com/embed/We2iD21V9WI">--}}
                    {{--</iframe>--}}
                    <ul class="list">
                        <li><i class="fa fa-check" aria-hidden="true"></i> Βρες δωρεάν παιχνίδια Memor-i</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> Σχεδίασε το δικό σου παιχνίδι Memor-i, χωρίς γνώσεις προγραμματισμού!</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> Διασκέδαση και μάθηση για τυφλούς αλλά και βλέποντες</li>
                    </ul>
                </div><!--.card-body-->

            </div><!--.card-->
        </div><!--.col-md-6-->
    </div>
    <div class="col-md-3 margin-bottom-20">
        <a href="http://www.scify.gr/site/en/">
            <img class="col-md-5"  src="{{asset("/assets/img/scify.jpg")}}" alt="Latsis Logo Image">
        </a>
    </div>
@endsection