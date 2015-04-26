<script>
    getScope('editTextTradCtrlDiv', function(scope) {
        scope.init(
            '<?php echo $obj['titleKey'];?>',
            '<?php echo $obj['textKey'];?>',
            <?php echo json_encode($obj['languageFrom']);?>,
            <?php echo json_encode($obj['languageTo']);?>,
            <?php echo json_encode($obj['languages']);?>,
            <?php echo json_encode($obj['titles']);?>,
            <?php echo json_encode($obj['texts']);?>,
            '<?php echo url(array('controller' => 'translationManager', 'action' => 'saveText'));?>',
            '<?php echo url(array('controller' => 'translationManager', 'action' => 'editArticleTrad'));?>'
        );
    });
</script>

<div id="editTextTradCtrlDiv" ng-controller="editTextTradCtrl">
    <div class="row">
        <div class="col-md-6">From
            <ul class="nav nav-pills">
                <li role="presentation" ng-repeat="language in languages" ng-class="{ active: (language.name == languageFrom) }">
                    <a ng-href="#"
                        ng-click="$parent.languageFrom = language.name;">{{ language.name }}</a>
                </li>
            </ul>
            <div class="form-group" >
                <pre height="300px" readonly ng-show="titleKey != ''">{{ titles[languageFrom].text }}</pre>
                <pre height="300px" readonly ng-show="textKey != ''">{{ texts[languageFrom].text }}</pre>
            </div>
        </div>
        <div class="col-md-6">
            Traduction to : {{ languageTo }}
            <form class="form-horizontal" method="POST" action="{{ actionSave }}">
            <fieldset>
                <input type="hidden" id="titleKey" name="titleKey" value="{{ titleKey }}" />
                <input type="hidden" id="textKey" name="textKey" value="{{ textKey }}" />
                <input type="hidden" id="lg" name="lg" value="{{ languageTo }}" />
                
                <input type="text" class="form-control" style="font-family: monospace; white-space: pre;" ng-show="titleKey != ''" id="nextTitle" name="nextTitle" value="{{ titles[languageTo].nextText }}"/>
                <textarea class="form-control" style="font-family: monospace; white-space: pre;" id="nextText" name="nextText" rows="10" ng-show="textKey != ''">{{ texts[languageTo].nextText }}</textarea>
                <div class="form-group">
                    <?php foreach($obj['actions'] as $action) { ?>
                        <button type=submit
                                id="actionPushed" 
                                name="actionPushed" 
                                class="btn btn-<?php echo $action['type']?> primary pull-right" 
                                value="<?php echo $action['value']?>"
                                ><?php echo $action['name']?></button>
                    <?php } ?>
                </div>
            </fieldset>
            </form>
        </div>
    </div>
</div>