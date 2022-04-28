@extends('layouts.app')
@section('content')

    <div class="row aboutPageRow">
        <div class="col-md-8 col-md-offset-2 col-sm-11 col-sm-offset-0">
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
                        <li><i class="fa fa-check" aria-hidden="true"></i> {!! __('messages.about_text_2') !!}</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> {!! __('messages.about_text_3') !!}</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> {!! __('messages.about_text_4') !!}</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> {!! __('messages.about_text_5') !!}</li>
                    </ul>
                    <b>{!! __('messages.who_can_use_memori_studio_title') !!}</b>
                    <br>
                    {!! __('messages.who_can_use_memori_studio_message') !!}
                    <br><br>
                    <iframe width="560" height="315" src="{{ __('messages.tutorial_video_link') }}"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-11 col-sm-offset-0">
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
        <div class="col-md-8 col-md-offset-2 col-sm-11 col-sm-offset-0">
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

    <div class="row margin-top-20" id="memori-game">
        <div class="col-md-8 col-md-offset-2 col-sm-11 col-sm-offset-0">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>Memor-i</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body padding-30 disclaimerText">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>{!! __('messages.sponsors_crowdfunding_title') !!}</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td>Elina Makri</td>
                                        <td>Akrotiri Women's Group</td>
                                        <td>Ioannis Pantazis</td>
                                        <td>Μανώλης Χλουβεράκης</td>
                                    </tr>
                                    <tr>
                                        <td>Irina Arnold</td>
                                        <td>Efthymia Bogea</td>
                                        <td>Alfa Beta Roto S.A.</td>
                                        <td>Krystallo Michou</td>
                                    </tr>
                                    <tr>
                                        <td>Ioannis Pantazis</td>
                                        <td>Nikos Aletras</td>
                                        <td>Ioannis Askitoglou</td>
                                        <td>Bill Kapralos</td>
                                    </tr>
                                    <tr>
                                        <td>Christoforos Korakas</td>
                                        <td>Nikki Johnston</td>
                                        <td>Γεώργιος Βλάχος</td>
                                        <td>Aggeliki Xona</td>
                                    </tr>
                                    <tr>
                                        <td>Dion SPILIOPOULOS</td>
                                        <td>Meropi Paneli</td>
                                        <td>Athina Spiliopoulou</td>
                                        <td>Olympia Papayannakopoulou</td>
                                    </tr>
                                    <tr>
                                        <td>Orestis Popotas</td>
                                        <td>Efstratios Andronikos</td>
                                        <td>Orestis Telelis</td>
                                        <td>Maria Terlixidou</td>
                                    </tr>
                                    <tr>
                                        <td>Marousa Tsakogianni</td>
                                        <td>Dimitrios Stratakis</td>
                                        <td>Monica Pistoli</td>
                                        <td>Αθανάσιος Καραλής</td>
                                    </tr>
                                    <tr>
                                        <td>George Bogeas</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row margin-top-20">
                            <div class="col-md-12">
                                <h4>{!! __('messages.sponsors_norwegian') !!}</h4>
                            </div>
                        </div>
                        <div class="row margin-top-20">
                            <div class="col-md-12">
                                <h4>{!! __('messages.sponsors_pvp') !!}</h4>
                            </div>
                        </div>
                        <div class="row margin-top-20">
                            <div class="col-md-12">
                                <h4>{!! __('messages.sponsors_multilingual') !!}</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="conainer-fluid">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img loading="lazy" class="width-percent-100" src="{{ asset('assets/img/EULogo.jpg') }}"
                                                 alt="European Union image">
                                        </div>
                                        <div class="col-md-8 text-left">
                                            This project has received funding from the European Union's Horizon 2020
                                            research and
                                            innovation programme under grant agreement No. 857159.
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img loading="lazy" class="width-percent-100" src="{{ asset('assets/img/shapes.png') }}"
                                                 alt="Shapes project image">
                                        </div>
                                        <div class="col-md-8 text-left">
                                            Assisting Living and Learning (ALL) Institute, Maynooth University, Co.
                                            Kildare, Ireland
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
