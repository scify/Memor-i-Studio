@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-6 centeredVertically">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>{!! __('messages.contact_us') !!}</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body padding-30">
                    <form id="gameVersion-handling-form" class="memoriForm" method="POST"
                          action="{{route('sendContactEmail')}}"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="inputer floating-label">
                                <div class="input-wrapper">
                                    <input type="text" name="name" class="form-control" required value="{{old('name')}}">
                                    <label for="name">{!! __('messages.your_name') !!} ({!! __('messages.required') !!})</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="inputer floating-label">
                                <div class="input-wrapper">
                                    <input type="email" name="email" class="form-control" required value="{{old('email')}}">
                                    <label for="email">{!! __('messages.your_email') !!} ({!! __('messages.required') !!})</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="inputer floating-label">
                                <div class="input-wrapper">
                                    <textarea type="text" name="subject" class="form-control" required>{{old('subject')}}</textarea>
                                    <label for="subject">{!! __('messages.subject') !!} ({!! __('messages.required') !!})</label>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-top-30">
                            <button type="submit" id="gameVersionLangSubmitBtn" class="btn btn-primary btn-ripple">
                                {!! __('messages.submit') !!}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
