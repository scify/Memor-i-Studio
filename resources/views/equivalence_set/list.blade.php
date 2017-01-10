@extends('common.layout')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-3">
                <button id="newEquivalenceSetBtn" class="btn btn-default btn-ripple" data-toggle="modal"
                        data-target="#cardOptions">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
            </div>
            @foreach($equivalenceSets as $equivalenceSet)
                @include('equivalence_set.single', ['equivalenceSet' => $equivalenceSet])
            @endforeach
        </div>
    </div>
    @include('equivalence_set.modals')
@endsection