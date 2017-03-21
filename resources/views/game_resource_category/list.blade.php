@extends('layouts.app')
@section('content')
    <div class="row instructionsContainer">
        @if($interface_lang_id == 1)
            <div class="col-md-6 centeredVertically text-align-center padding-15">
                <div class="card card-video card-player-blue">

                    <div class="card-heading padding-top-20">
                        <h5>Οδηγίες </h5>
                    </div>

                    <div class="card-body">
                        Εδώ μπορείς να αντικαταστήσεις όλα τα ηχητικά που ακούγονται στο παιχνίδι (ή όσα θα ήθελες!). Φτιάξε τη δική σου ιστορία, τις δικές σου οδηγίες για το παιχνίδι - οδηγό και ηχογράφησε τη δική σου φωνή. Δες την αναλυτική περιγραφή για κάθε ηχητικό ξεχωριστά. Άκου πως είναι τώρα το παιχνίδι, για να πάρεις ιδέες και να βοηθηθείς!
                        * Για το ‘Σενάριο’ και τα ‘Αστεία στοιχεία’ μπορείς να πάρεις ιδέες από τα αντίστοιχα που είχαμε φτιάξει για το παιχνίδι ‘Η Κιβωτός του Νώε’. Χρησιμοποίησε το χιούμορ και την φαντασία σου!
                        ** Τα ηχητικά πρέπει να είναι σε μορφή .wav or .mp3 αρχείου και μέγεθος μέχρι 3Mb.
                    </div><!--.card-body-->

                </div>
            </div>
        @else
            <div class="col-md-6 centeredVertically text-align-center padding-15">
                <div class="card card-video card-player-blue">
                    <div class="card-heading padding-top-20">
                        <h5>Instuctions</h5>

                    </div>
                    <div class="card-body">
                        Here you can replace all the sounds heard in the game (or only the ones you want!). Create your own story, your instructions for the tutorial and record your own voice. There are detailed descriptions for each audio. Listen to how the game’s audios are now, in order to be helped and get ideas!
                        * For the ‘Storyline’ and the ‘Funny elements’ get ideas from the ones we made for the Memor-i game 'Noah's Ark'. Use your sense of humor and your imagination!
                        ** The audios must be .wav or .mp3 file and have a size up to 3Mb.
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group accordion padding-left-30">
                @foreach($resourceCategories as $index => $resourceCategory)
                    @include('game_resource.list', ['resources' => $resourceCategory->resources, 'index' => $index])
                @endforeach
            </div>
        </div>
    </div>
@endsection