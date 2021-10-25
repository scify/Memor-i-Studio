@extends('layouts.app')
@section('content')
    <div class="row instructionsContainer">
        @if($interface_lang_id == 1)
            <div class="col-md-6 centeredVertically text-align-center padding-15">
                <div class="card card-video card-player-blue">

                    <div class="card-heading padding-top-20">
                        <h4>Οδηγίες</h4>
                    </div>

                    <div class="card-body">
                        Εδώ μπορείς να αντικαταστήσεις όλα τα ηχητικά που ακούγονται στο παιχνίδι (ή όσα θα ήθελες!).
                        Φτιάξε τη δική σου ιστορία, τις δικές σου οδηγίες για το παιχνίδι - οδηγό και ηχογράφησε τη δική
                        σου φωνή. Δες την αναλυτική περιγραφή για κάθε ηχητικό ξεχωριστά. Άκου πως είναι τώρα το
                        παιχνίδι, για να πάρεις ιδέες και να βοηθηθείς!
                        * Για το ‘Σενάριο’ και τα ‘Αστεία στοιχεία’ μπορείς να πάρεις ιδέες από τα αντίστοιχα που είχαμε
                        φτιάξει για το παιχνίδι ‘Η Κιβωτός του Νώε’. Χρησιμοποίησε το χιούμορ και την φαντασία σου!
                        ** Τα ηχητικά πρέπει να είναι σε μορφή .wav or .mp3 αρχείου και μέγεθος μέχρι 3Mb.
                    </div><!--.card-body-->

                </div>
            </div>
        @else
            <div class="col-md-6 centeredVertically text-align-center padding-15">
                <div class="card card-video card-player-blue">
                    <div class="card-heading padding-top-20">
                        <h4>{!! __('messages.instructions_title') !!}</h4>
                    </div>
                    <div class="card-body">
                        {!! __('messages.instructions_message') !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="">
        <div class="col-md-6 padding-left-30">
            <form id="gameFlavor-handling-form" class="memoriForm" method="GET"
                  action="{{route('getResourcesForGameFlavor', ['id' => $gameFlavor->id])}}"
                  enctype="multipart/form-data">
                <div class="panelContainer">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title"><h4>{!! __('messages.choose_language') !!}</h4></div>
                        </div><!--.panel-heading-->
                        <div class="panel-body">
                            <div class="col-md-3">{!! __('messages.interface_language') !!}</div><!--.col-md-3-->
                            <div class="col-md-9">
                                <select class="form-control selecter" name="interface_lang_id" onchange="this.form.submit()">
                                    @foreach($interfaceLanguages as $interfaceLanguage)
                                        <option value="{{$interfaceLanguage->id}}" {{ $interface_lang_id == $interfaceLanguage->id ? 'selected' : ''}}>{{$interfaceLanguage->name}}</option>
                                    @endforeach
                                </select>
                            </div><!--.col-md-9-->
                        </div><!--.row-->
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="panel-group accordion padding-left-30">
        @foreach($resourceCategories as $index => $resourceCategory)
            @include('game_resource.list', ['resources' => $resourceCategory->resources, 'index' => $index])
        @endforeach
    </div>

@endsection
