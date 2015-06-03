<script>
    getScope('mediaCtrlDiv', function(scope) {
        scope.init(
                <?php echo json_encode($obj['medias']);?>,
                '<?php echo url(array('controller' => 'medias', 'action' => 'delete'));?>',
                '<?php echo url(array('controller' => 'medias', 'action' => 'upload'));?>',
                <?php echo json_encode($obj['sizes']);?>
            );
    });
</script>

<div>
    <div id="mediaCtrlDiv" ng-controller="mediaCtrl">
        <form enctype="multipart/form-data" type="POST" ng-submit="uploadMedia();">
            <input id="mediaFiles" type="file" class="file" name="mediaFiles[]" multiple="true" onchange="angular.element(this).scope().setFiles(this)" />
            <input id="sizeType" value="1" type="checkbox" ng-model="custom" />
            <select id="size" name="size" ng-hide="custom" ng-model="selectedSize" ng-options="size[0] + 'x' + size[1] for size in sizes">
            </select>
            <div ng-show="custom">
                <input type="text" name="width" ng-model="customWidth" /> x <input type="text" name="height" ng-model="customHeight" />
            </div>
            <button class="btn btn-default" type="submit">Upload</button>
        </form>

        <div ng-repeat="error in errors">
            {{ error }}
        </div>

        <div ng-repeat="media in medias" style="border: 1px solid black;width: 200px; height: 150px; text-align:center;display:inline-block;">
            {{ media.name }} [{{ media.id }}]<br/>
            <a ng-href="{{ media.file }}" ><img ng-src="{{ media.thumb }}" style="display:inline-block;"/></a><br/>
            {{ media.width }} x {{ media.height }}
            <a href="#" ng-click="deleteMedia(media.id);"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
        </div>
    </div>
</div>
