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
                <div class="col-md-8">
                    <div class="well">
                        <form class="form-horizontal" enctype="multipart/form-data" type="POST" ng-submit="uploadMedia();">
                            <div class="form-group">
                                <label for="mediaFiles" class="col-sm-3 control-label"><?php t(':FILE') ?></label>
                                <div class="col-sm-4">
                                    <span class="btn btn-default btn-file form-control" >
                                        <?php t(':SELECT_FILES') ?>
                                        <input id="mediaFiles" type="file" name="mediaFiles[]" multiple="true" onchange="angular.element(this).scope().setFiles(this)" />
                                    </span>
                                </div>
                                <div class="col-sm-5">
                                    <small id="FileSelectFeedback" class="form-control btn-file-caption">
                                    </small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sizeType" class="col-sm-3 control-label"><?php t(':CUSTOM') ?></label>
                                <div class="col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input id="sizeType" value="1" type="checkbox" ng-model="custom" />
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" ng-show="custom" >
                                <label for="width" class="col-sm-3 control-label"><?php t(':SIZE_CUSTOM') ?></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="width" name="width" ng-model="customWidth" />
                                        <div class="input-group-addon">x</div>
                                        <input type="text" class="form-control" placeholder="height" name="height" ng-model="customHeight" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" ng-hide="custom" >
                                <label for="size" class="col-sm-3 control-label"><?php t(':SIZE') ?></label>
                                <div class="col-sm-3">
                                    <select id="size" name="size" class="form-control" ng-model="selectedSize" ng-options="size[0] + 'x' + size[1] for size in sizes">
                                    </select>
                                </div>
                                
                            </div>
                            <div class="form-group row" >
                                <div class="col-sm-12 text-right">
                                    <button class="btn btn-default" type="submit"><?php t(':UPLOAD') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="form-group row">
                        <label for="search" class="col-sm-2 control-label"><?php t(':SEARCH')?></label>
                        <div class="col-sm-3">
                            <input type="text" placeholder="<?php t(':SEARCH')?>" ng-model="search" ng-change="saveLocal()" />
                        </div>
                        <label for="displayAsList" class="col-sm-3 control-label text-right"><?php t(':DISPLAY_AS_LIST')?></label>
                        <div class="col-sm-1">
                            <input type="checkbox" name="list" 
                                    ng-change="saveLocal()" 
                                    ng-model="displayAsList"
                                    ng-checked="displayAsList" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4" ng-show="selectedMedia">
                    <ul class="list-inline pull-right">
                        <li>
                            <a  href="";
                                ng-real-click="deleteMedia(selectedMedia.id);" 
                                ng-confirm-click="<?php t(':ARE_YOU_SURE') ?>">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a ng-href="{{ selectedMedia.file }}">
                                <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                            </a>
                        </li>
                    </ul>
                    <p>{{ selectedMedia.name }}</p>
                    <div class="text-center">
                        <a ng-href="{{ selectedMedia.file }}">
                            <img style="height: 160px; max-width: 100%;" ng-src="{{ selectedMedia.file.substr(selectedMedia.file.length - 4) == '.pdf' ? 'img/pdf.png' : selectedMedia.file }}" style="display:inline-block;"/>
                        </a>
                        <p><strong><small>id: {{ selectedMedia.id }} - taille: {{ selectedMedia.width }}x{{ selectedMedia.height }}</small></strong></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div ng-repeat="error in errors">
                        {{ error }}
                    </div>
                </div>
            </div>
            
            <div class="row" ng-hide="displayAsList == true">
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6" ng-repeat="media in mediasFiltered = (medias | filter:search | orderBy:predicate:reverse) track by media.id">
                    <div class="thumbnail galleryThumb">
                        <a ng-href="" ng-click="selectMedia(media);">
                            <img class="img-responsive" ng-src="{{ media.thumb }}" ng-alt="{{ media.file }}" />
                        </a>
                        <div class="caption">
                            <p style="overflow:hidden;text-overflow:ellipsis;height:18px"><small>{{ media.name }}</small></p>
                            <p>id:{{ media.id }} - <?php t(':SIZE') ?>:{{ media.width }}x{{ media.height }}</p>
                                <button class="btn btn-default" ng-click="selectMedia(media);"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></button>
                                <button class="btn btn-default"
                                    ng-real-click="deleteMedia(media.id);" 
                                    ng-confirm-click="<?php t(':ARE_YOU_SURE') ?>">
                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" ng-show="displayAsList == true">
                <div class="col-md-12">
                    <table st-table="rowCollection" class="table table-striped">
                        <thead>
                        <tr>
                            <th ng-click="predicate=='id' ? reverse = !reverse : predicate='id'; saveLocal();"><?php t(':ID') ?></th>
                            <th ng-click="predicate=='name' ? reverse = !reverse : predicate='name'; saveLocal();"><?php t(':NAME') ?></th>
                            <th ng-click="predicate=='width' ? reverse = !reverse : predicate='width'; saveLocal();"><?php t(':WIDTH') ?></th>
                            <th ng-click="predicate=='height' ? reverse = !reverse : predicate='height'; saveLocal();"><?php t(':HEIGHT') ?></th>
                            <th><?php t(':ACTIONS') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="media in mediasFiltered" >
                            <td scope="row" ng-click="selectMedia(media);">{{media.id}}</td>
                            <td>
                                <a class="btn-popover" 
                                    href="" ng-click="selectMedia(media);"
                                    data-container="body" data-toggle="popover" data-placement="top" data-html="true"
                                    data-trigger="hover"
                                    data-content="<img class='img-responsive' src='{{getMediaFile(media.file)}}' />"
                                    >
                                    {{media.name}}
                                </a>
                            </td>
                            <td scope="row" ng-click="selectMedia(media);">{{media.width}}</td>
                            <td scope="row" ng-click="selectMedia(media);">{{media.height}}</td>
                            <td>
                                <a 
                                    href="";
                                    ng-real-click="deleteMedia(media.id);" 
                                    ng-confirm-click="<?php t(':ARE_YOU_SURE') ?>">
                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
