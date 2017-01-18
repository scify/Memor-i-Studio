<div class="row margin-bottom-30">
    <div class="col-md-12">
        <form id="gameVersion-handling-form" class="memoriForm" method="POST"
              action="{{route('updateGameResourcesTranslations')}}"
              enctype="multipart/form-data">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h3><input name="resource_category_translation" value="{{$resourceCategory->description}}"></h3></div>
                    <input name="resource_category_id" type="hidden" value="{{$resourceCategory->id}}">
                </div><!--.panel-heading-->
                <div class="panel-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="lang_id" value="{{ $langId }}">
                    <div class="table-responsive">
                        <table class="table-bordered table-striped table-condensed resourcesTable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Display text</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($resources as $index=>$resource)
                                <tr>
                                    <td>{{$resource->name}}</td>
                                    <td>
                                        <input name="resources[{{$index}}][translation]" class="width-percent-100" value="{{$resource->default_text}}">
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
        </form>
    </div>
</div>