@foreach($cards as $card)
    <div class="col-md-6">
        @include('card.single', ['card' => $card, 'user' => \Illuminate\Support\Facades\Auth::user()])
    </div>
@endforeach
