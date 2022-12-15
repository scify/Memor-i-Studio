@extends('layouts.app')

@section('content')
    <div class="container py-5" id="user-management-page">
        <!-- most popular tag section -->
        <div class="row mb-3">
            <div class="col text-left">
                <h1>Platform Statistics</h1>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col">
                <h2 class="mb-3">General Statistics</h2>
                <ul class="list-group">
                    @foreach($viewModel->generalPlatformStatistics as $name => $stat)
                    <li class="list-group-item">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-4 col-md-5 col-sm-6">
                                    {{ $name }}:
                                </div>
                                <div class="col-lg-8 col-md-7 col-sm-6">
                                    <span class="badge bg-primary">{{ $stat }}</span>
                                </div>
                            </div>
                        </div>

                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col">
                <h2 class="mb-3">Statistics per Game Language</h2>
                <ul class="list-group">
                    @foreach($viewModel->gameFlavorsPerLanguageStatistics as $gameFlavorsPerLanguageStatist)
                        <li class="list-group-item">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-4 col-md-5 col-sm-6">
                                        {{ $gameFlavorsPerLanguageStatist->name }}:
                                    </div>
                                    <div class="col-lg-8 col-md-7 col-sm-6">
                                        <span class="badge bg-primary">{{ $gameFlavorsPerLanguageStatist->num }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col">
                <h2 class="mb-3">Top Game Creators</h2>
                @foreach($viewModel->gameFlavorsPerUserStatistics as $gameFlavorsPerUserStats)
                    <li class="list-group-item">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-4 col-md-5 col-sm-6">
                                    {{ $gameFlavorsPerUserStats->user_name }}:
                                </div>
                                <div class="col-lg-8 col-md-7 col-sm-6">
                                    <span class="badge bg-primary">{{ $gameFlavorsPerUserStats->game_flavors_num }}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </div>
        </div>
    </div>
@endsection
