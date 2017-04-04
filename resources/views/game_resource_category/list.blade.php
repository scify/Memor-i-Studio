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
                        <h4>Instructions</h4>

                    </div>
                    <div class="card-body">
                        Here you can replace all the sounds heard in the game (or only the ones you want!). Create your
                        own story, your instructions for the tutorial and record your own voice. There are detailed
                        descriptions for each audio. Listen to how the game’s audios are now, in order to be helped and
                        get ideas!
                        * For the ‘Storyline’ and the ‘Funny elements’ get ideas from the ones we made for the Memor-i
                        game 'Noah's Ark'. Use your sense of humor and your imagination!
                        ** The audios must be .wav or .mp3 file and have a size up to 3Mb.
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="">
        <div class="col-md-6 padding-left-30">
            <form id="gameFlavor-handling-form" class="memoriForm" method="POST"
                  action="{{route('getResourcesForGameFlavor', ['id' => $gameFlavor->id])}}"
                  enctype="multipart/form-data">
                <input type="hidden" name="game_version_id" value="{{$gameFlavor->game_version_id}}">
                <div class="panelContainer">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title"><h4>Choose language</h4></div>
                        </div><!--.panel-heading-->
                        <div class="panel-body">
                            <div class="col-md-3">Interface Language</div><!--.col-md-3-->
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