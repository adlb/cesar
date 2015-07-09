<script>
    var cesar_q = cesar_q || [];
    cesar_q.push({
        id: 'mediaCtrlDiv',
        f: function(scope) {
        scope.init(
                <?php echo json_encode($obj['medias'],JSON_NUMERIC_CHECK);?>,
                '<?php echo url(array('controller' => 'medias', 'action' => 'delete'));?>',
                '<?php echo url(array('controller' => 'medias', 'action' => 'upload'));?>',
                <?php echo json_encode($obj['sizes']);?>
            );
    }});
</script>

    <div class="page-header">
        <div class="container">
            <h1 class="page-title pull-left"> <?php t(':MEDIA_MANAGEMENT') ?> </h1>
        </div>
    </div>

    <div class="page-container" id="mediaCtrlDiv" ng-controller="mediaCtrl" ng-cloak>
        <div class="container">    
            <div class="row">
                <div class="col-lg-8">
                    <div class="well">
                        <form enctype="multipart/form-data" type="POST" ng-submit="uploadMedia();">
                            <div class="form-group row">
                                <label for="files" class="col-lg-3 control-label">File</label>
                                <div class="col-lg-5">
                                    <input id="mediaFiles" type="file" name="mediaFiles[]" multiple="true" onchange="angular.element(this).scope().setFiles(this)" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="size" class="col-lg-3 control-label">Size</label>
                                <div class="col-lg-5">
                                    <input id="sizeType" value="1" type="checkbox" ng-model="custom" />
                                    <select id="size" name="size" ng-hide="custom" ng-model="selectedSize" ng-options="size[0] + 'x' + size[1] for size in sizes">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" ng-show="custom" >
                                <label for="width" class="col-lg-3 control-label">SizeCustom</label>
                                <div class="col-lg-2">
                                    <input type="text" name="width" ng-model="customWidth" />
                                </div> 
                                <label for="height" class="col-lg-1 control-label">x</label>
                                <div class="col-lg-2">
                                    <input type="text" name="height" ng-model="customHeight" />
                                </div>
                            </div>
                            <div class="form-group row" >
                                <label for="button" class="col-lg-3 control-label"></label>
                                <div class="col-lg-2">
                                    <button class="btn btn-default" type="submit">Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4" ng-show="selectedMedia">
                    <p>{{ selectedMedia.name }}</p>
                    <a ng-href="{{ selectedMedia.file }}" ><img ng-src="{{ selectedMedia.thumb }}" style="display:inline-block;"/></a><br/>
                    <p>id:{{ selectedMedia.id }} - size:{{ selectedMedia.width }}x{{ selectedMedia.height }}</p>
                    <button ng-click="deleteMedia(selectedMedia.id);"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></Button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div ng-repeat="error in errors">
                        {{ error }}
                    </div>
                </div>
            </div>
            {{ displayAsList }}
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-group row">
                        <label for="search" class="col-lg-3 control-label"><?php t(':SEARCH')?></label>
                        <div class="col-lg-3">
                            <input type="text" placeholder="<?php t(':SEARCH')?>" ng-model="search" />
                        </div>
                        <label for="displayAsList" class="col-lg-3 control-label"><?php t(':DISPLAY_AS_LIST')?></label>
                        <div class="col-lg-1">
                            <input type="checkbox" name="list" ng-model="displayAsList" ng-change="saveDisplayAsList()" ng-checked="displayAsList"/>
                        </div> 
                    </div>
                </div>
            </div>
            
            <div class="row" ng-hide="displayAsList">
                <div class="col-sm-3 col-md-2" ng-repeat="media in mediasFiltered = (medias | filter:search | orderBy:predicate:reverse) track by media.id">
                    <div class="thumbnail galleryThumb">
                        <a ng-href="{{ media.file }}" >
                            <img class="img-responsive" ng-src="{{ media.thumb }}" ng-alt="{{ media.file }}" />
                        </a>
                        <div class="caption">
                            <p style="overflow:hidden;text-overflow:ellipsis;height:18px"><small>{{ media.name }}</small></p>
                            <p>id:{{ media.id }} - size:{{ media.width }}x{{ media.height }}</p>
                                <button class="btn btn-default" ng-click="selectMedia(media);"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></button>
                                <button class="btn btn-default" ng-click="deleteMedia(media.id);"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" ng-show="displayAsList">
                <table st-table="rowCollection" class="table table-striped">
                    <thead>
                    <tr>
                        <th ng-click="predicate=='id' ? reverse = !reverse : predicate='id';"><?php t(':ID') ?></th>
                        <th ng-click="predicate=='name' ? reverse = !reverse : predicate='name';"><?php t(':NAME') ?></th>
                        <th ng-click="predicate=='width' ? reverse = !reverse : predicate='width';"><?php t(':WIDTH') ?></th>
                        <th ng-click="predicate=='height' ? reverse = !reverse : predicate='height';"><?php t(':HEIGHT') ?></th>
                        <th><?php t(':ACTIONS') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="media in mediasFiltered" >
                        <td scope="row" ng-click="selectMedia(media);">{{media.id}}</td>
                        <td><a ng-href="{{ media.file }}">{{media.name}}</a></td>
                        <td scope="row" ng-click="selectMedia(media);">{{media.width}}</td>
                        <td scope="row" ng-click="selectMedia(media);">{{media.height}}</td>
                        <td>
                            <a href="#" ng-click="deleteMedia(media.id);"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>