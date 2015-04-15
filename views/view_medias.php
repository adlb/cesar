<script>
    getScope('mediaCtrlDiv', function(scope) {
        scope.init(
                <?php echo json_encode($obj['medias']);?>,
                '<?php echo url(array('controller' => 'medias', 'action' => 'delete'));?>',
                '<?php echo url(array('controller' => 'medias', 'action' => 'upload'));?>'
            );
    });
</script>

<div>
    <div id="mediaCtrlDiv" ng-controller="mediaCtrl">
        <form enctype="multipart/form-data" type="POST" ng-submit="uploadMedia();">
            <input id="mediaFiles" type="file" class="file" name="mediaFiles[]" multiple="true" onchange="angular.element(this).scope().setFiles(this)" />
            <button class="btn btn-default" type="submit">Upload</button>
        </form>

        <div ng-repeat="error in errors">
            {{ error }}
        </div>

        <div ng-repeat="media in medias" style="border: 1px solid black;width: 200px; height: 150px; text-align:center;display:inline-block;">
            {{ media.name }} [{{ media.id }}]<br/>
            <a ng-href="{{ media.file }}" ><img ng-src="{{ media.thumb }}" style="display:inline-block;"/></a><br/>
            <a href="#" ng-click="deleteMedia(media.id);"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
        </div>
    </div>
</div>
