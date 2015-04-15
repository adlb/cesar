<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.11/angular.min.js"></script>

<div ng-init="
    currentLanguage=<?php echo htmlspecialchars(json_encode($obj['language']));?>;
    languages=<?php echo htmlspecialchars(json_encode($obj['languages']));?>;
    selectedLanguage=<?php echo htmlspecialchars(json_encode($obj['lg']));?>;
    prefixUrl='<?php echo htmlspecialchars(url(array('controller' => 'translationManager', 'view' => 'editText', 'key' => $obj['key'])));?>';
    langNav[currentLanguage] = true;
">
    <div class="row">
        <div class="col-md-6">From
            <ul class="nav nav-pills">
                <?php foreach($obj['languages'] as $lang) { ?>
                    <li role="presentation" ng-class="{ active: langNav.<?php echo $lang?> }">
                        <a ng-href="#"
                            ng-click="langNav[currentLanguage] = false;
                                      langNav.<?php echo $lang?> = true;
                                      currentLanguage = '<?php echo $lang?>';"><?php echo $lang?></a></li>
                <?php } ?>
            </ul>
            <div class="form-group">
                <?php foreach($obj['texts'] as $text) { ?>
                    <pre height="300px" ng-show="langNav.<?php echo $text['language']?>" readonly><?php echo htmlspecialchars($text['text']) ?></pre>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-6">To
            <form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'translationManager', 'action' => 'saveText', 'key' => $obj['key']))?>">
            <fieldset>

                <input type="hidden" id="id" name="id" value="<?php echo $obj['text']['id'] ?>" />
                <input type="hidden" id="key" name="key" value="<?php echo $obj['text']['key'] ?>" />
                <input type="hidden" id="lg" name="lg" value="<?php echo $obj['lg'] ?>" />

                <ul class="nav nav-pills">
                    <li ng-repeat="language in languages" role="presentation" ng-class="{ active: selectedLanguage == language}"><a ng-href="{{ prefixUrl }}&lg={{ language }}">{{ language }}</a></li>
                </ul>
                <textarea class="form-control" style="font-family: monospace; white-space: pre;" id="text" name="nextText" rows="<?php max(count(explode("\n", $obj['text']['nextText'])),4) ?>"><?php echo htmlspecialchars($obj['text']['nextText']) ?></textarea>
                <div class="form-group">
                    <a class="btn btn-default pull-right" href="<?php echo url(array('controller' => 'translationManager', 'view' => 'translationList')) ?>">Cancel</a>&nbsp;
                    <?php if ($obj['user']['role'] == 'Administrator') { ?>
                        <button id="actionpushed" name="actionpushed" class="btn btn-primary pull-right" value="validate">Validate</button>
                    <?php } ?>
                    <button id="actionpushed" name="actionpushed" class="btn btn-primary pull-right" value="save">Save</button>
                    <button id="actionpushed" name="actionpushed" class="btn btn-primary pull-right" value="submitforvalidatation">SubmitForValidation</button>
                </div>
            </fieldset>
            </form>
        </div>
    </div>
</div>