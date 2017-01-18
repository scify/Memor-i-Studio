<div class="row margin-bottom-30">
    <div class="col-md-12">
        <form id="gameVersion-handling-form" class="memoriForm" method="POST"
              action="{{route('updateGameResourcesTranslations')}}"
              enctype="multipart/form-data">
            <div class="panel">
                <div class="panel-heading {{$index == 0 ? 'active' : ''}}">
                    <a class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#panelBody_{{$index}}"><h3>{{$resourceCategory->description}}</h3></a>
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
                                        <td>{{$resource->default_text}}</td>
                                        <td>
                                            {{$resource->file_path}}
                                            <input name="resources[{{$index}}][id]" type="hidden" value="{{$resource->id}}">
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