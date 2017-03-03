<div class="row margin-bottom-30">
    <div class="col-md-12">
        <form id="gameVersion-handling-form" class="memoriForm" method="POST"
              action="{{route('updateGameResourcesFiles')}}"
              enctype="multipart/form-data">
            <input name="game_flavor_id" type="hidden" value="{{$gameFlavorId}}">
            <div class="panel">
                <div class="panel-heading {{$index == 0 ? 'active' : ''}}">
                    <a class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#panelBody_{{$index}}">
                        <h3>{{$resourceCategory->description}}</h3></a>
                    <input name="resource_category_id" type="hidden" value="{{$resourceCategory->id}}">
                </div><!--.panel-heading-->
                <div id="panelBody_{{$index}}" class="panel-collapse collapse {{$index == 0 ? 'in' : ''}}">
                    <div class="panel-body">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="table-responsive">
                            <table class="table-bordered table-striped table-condensed resourcesTable">
                                <thead>
                                <tr>
                                    <th>Hint</th>
                                    <th>Display text</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($resources as $index=>$resource)
                                    <tr>
                                        <td>{{$resource->default_text}} <div class="margin-top-5"><h5>{{$resource->default_description}}</h5></div></td>
                                        <td>
                                            @if($resource->file_path != null)
                                                <div class="margin-bottom-10">
                                                    <audio controls>
                                                        <source src="{{route('resolveDataPath', ['filePath' => $resource->file_path])}}"
                                                                type="audio/mpeg">
                                                        <source src="{{route('resolveDataPath', ['filePath' => $resource->file_path])}}"
                                                                type="audio/wav">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                </div>
                                            @endif
                                            <div class="fileinput fileinput-new"
                                                 data-provides="fileinput">
                                            <span class="btn {{$resource->file_path == null ? 'btn-info' : 'btn-success'}} btn-file">
                                                @if($resource->file_path == null)
                                                    <span class="fileinput-new">Select file</span>

                                                    <span class="fileinput-exists">Change</span>
                                                @else
                                                    <span class="fileinput-new">Update file</span>

                                                    <span class="fileinput-exists">Change</span>
                                                @endif
                                                <input type="file" name="resources[{{$index}}][audio]">
                                            </span>
                                                <span class="fileinput-filename"></span>
                                                @if($resource->file_path == null)
                                                    <a href="#" class="close fileinput-exists" data-dismiss="fileinput"
                                                       style="float: none">&times;</a>
                                                @endif
                                            </div>
                                            <input name="resources[{{$index}}][id]" type="hidden"
                                                   value="{{$resource->id}}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary btn-ripple pull-right">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>