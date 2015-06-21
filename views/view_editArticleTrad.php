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

<section>
        <!-- BREADCRUMBS -->
		<div class="page-header">
			<div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-title"> <?php t(':traduction') ?> </h1>
                    </div>
                </div>
			</div>
		</div>
		<!-- END BREADCRUMBS -->

        <!-- PAGE CONTENT -->
		<div class="page-content" id="editTextTradCtrlDiv" ng-controller="editTextTradCtrl">
			<div class="container">
				<form class="form-horizontal" method="POST" action="{{ actionSave }}">
                <input type="hidden" id="titleKey" name="titleKey" value="{{ titleKey }}" />
                <input type="hidden" id="textKey" name="textKey" value="{{ textKey }}" />
                <input type="hidden" id="lg" name="lg" value="{{ languageTo }}" />
            
                <div class="row">
                    <div class="col-md-6">
                        <strong>From :</strong> <ul class="list-inline pull-right">
                            <li role="presentation" ng-repeat="language in languages" ng-class="{ active: (language.name == languageFrom) }">
                                <a ng-href="#"
                                    ng-click="$parent.languageFrom = language.name;">{{ language.name }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <strong>Traduction to : {{ languageTo }}</strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <pre height="300px" readonly ng-show="titleKey != ''">{{ titles[languageFrom].text }}</pre>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" style="font-family: monospace; white-space: pre;" ng-show="titleKey != ''" id="nextTitle" name="nextTitle" value="{{ titles[languageTo].nextText }}"/>
                    </div>
                </div>
                <div class="row row-eq-height">
                    <div class="col-md-6">
                        <pre id="tradFrom" readonly ng-show="textKey != ''">{{ texts[languageFrom].text }}</pre>
                    </div>
                    <div class="col-md-6">
                            <textarea id="tradTo"
                                class="form-control" 
                                style="font-family: monospace; white-space: pre;" 
                                id="nextText" 
                                name="nextText" 
                                rows="10" ng-show="textKey != ''"
                                height="100%"
                            >{{ texts[languageTo].nextText }}</textarea>
                    </div>
                </div>
                <div class="row" id="tradMargin">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-inline pull-right">
                        <?php foreach($obj['actions'] as $action) { ?>
                            <li>
                                <button type=submit
                                    id="actionPushed" 
                                    name="actionPushed" 
                                    class="btn btn-<?php echo $action['type']?> primary pull-right" 
                                    value="<?php echo $action['value']?>"
                                   ><?php echo $action['name']?></button>
                            </li>
                        <?php } ?>
                        </ul>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </section>