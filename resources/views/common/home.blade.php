@extends('layouts.app')
@section('content')
    <div class="row margin-top-50 margin-bottom-50 memoriActionBtns">
        <div class="col-md-6 text-align-center">
            <a href="{{route('showGameVersionSelectionForm')}}" class="btn btn-success btn-ripple ">
                <h1>Create new Game</h1>
            </a>
        </div><!--.col-md-6-->
        <div class="col-md-6 text-align-center">
            <a href="{{route('showAllGameFlavors')}}" class="btn btn-primary btn-ripple ">
                <h1>See all Games</h1>
            </a>
        </div><!--.col-md-6-->
    </div>
        <div class="row">
        <div class="col-md-12 centeredVertically text-align-center padding-15">
            <div class="card card-video card-player-blue">

                <div class="card-heading padding-top-20">
                    <h4>How to create a game?</h4>
                    <p>Think of a game story, create cards and customize your game!</p>

                </div><!--.card-heading-->

                <div class="card-body">
                    <iframe width="600" height="400"
                            src="https://www.youtube.com/embed/We2iD21V9WI">
                    </iframe>
                </div><!--.card-body-->

            </div><!--.card-->
        </div><!--.col-md-6-->
    </div>
@endsection