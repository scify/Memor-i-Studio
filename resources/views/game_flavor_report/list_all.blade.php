@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>GAME FLAVOR REPORTS</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body">

                    <div class="overflow-table">
                        <table class="col-md-12 table-bordered table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>Game name</th>
                                <th>Creator</th>
                                <th>Creator email</th>
                                <th>Reporter</th>
                                <th>Reporter email</th>
                                <th>Report comment</th>
                                <th>Report date</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($gameFlavorReports as $gameFlavorReport)
                                <td>{{$gameFlavorReport->gameFlavor->name}}</td>
                                <td>{{$gameFlavorReport->gameFlavor->creator->name}}</td>
                                <td>{{$gameFlavorReport->gameFlavor->creator->email}}</td>
                                @if($gameFlavorReport->user_id != null)
                                    <td>{{$gameFlavorReport->user->name}}</td>
                                    <td>{{$gameFlavorReport->user->email}}</td>
                                @else
                                    <td>{{$gameFlavorReport->user_name}}</td>
                                    <td>{{$gameFlavorReport->user_email}}</td>
                                @endif
                                <td>{{$gameFlavorReport->user_comment}}</td>
                                <td>{{$gameFlavorReport->created_at}}</td>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection