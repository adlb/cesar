<script>
    var cesar_q = cesar_q || [];
    cesar_q.push({
        id: 'usersCtrlDiv', 
        f: function(scope) {
        scope.init(
            '<?php echo url(array('controller' => 'user', 'action' => 'check'));?>',
            '<?php echo url(array('controller' => 'user', 'action' => 'update'));?>'
        );}
    });
</script>


    
    <div class="page-container" id="usersCtrlDiv" ng-controller="usersCtrl">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab">
                            <a data-toggle="collapse" href="#Help">
                                <h4 class="panel-title">
                                    <strong><?php echo t(':HELP') ?></strong> <i class="fa fa-question pull-right"></i>
                                </h4>
                            </a>
                        </div>
                        <div id="Help" class="panel-collapse collapse" role="tabpanel">
                            <div class="panel-body">
                                <article>
                                    <?php
                                        displayPartial('site', 'fixedArticle', array('titleKey' => 'file:Help_UserInsert', 'renderType' => 'raw'));
                                    ?>
                                <article>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form class="form-horizontal" method="POST" action="#">
              <fieldset>
                <div ng-show="usersAnalysed">
                    <input class="pull-right" type="text" placeholder="<?php t(':SEARCH')?>" ng-model="search" />
                    <div class="btn-group">
                      <label class="btn btn-default" ng-model="filterRadio" btn-radio=""> <?php t(':ALL') ?> </label>
                      <label class="btn btn-default" ng-model="filterRadio" btn-radio="{status:'ERROR'}"> <?php t(':ALL_ERRORS') ?> </label>
                      <label class="btn btn-default" ng-model="filterRadio" btn-radio="{status:'NEW'}"> <?php t(':NEW') ?> </label>
                      <label class="btn btn-default" ng-model="filterRadio" btn-radio="{status:'TO_UPDATE'}"> <?php t(':UPDATE') ?> </label>
                    </div>
                    <table st-table="rowCollection" class="table table-striped">
                        <thead>
                            <tr>
                                <th ng-click="predicate=='index' ? reverse = !reverse : predicate='index';"><?php t(':INDEX') ?></th>
                                <th ng-click="predicate=='email' ? reverse = !reverse : predicate='email';"><?php t(':EMAIL') ?></th>
                                <th ng-click="predicate=='lastName' ? reverse = !reverse : predicate='lastName';"><?php t(':LASTNAME') ?></th>
                                <th ng-click="predicate=='rawLine' ? reverse = !reverse : predicate='rawLine';"><?php t(':RAWLINE') ?></th>
                                <th ng-click="predicate=='status' ? reverse = !reverse : predicate='status';"><?php t(':STATUS') ?></th>
                                <th><?php t(':ACTIONS') ?>/<?php t(':STATUS') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="line in usersAnalysed.lines | filter:search | filter:filterRadio | orderBy:predicate:reverse">
                                <td>{{line.index}}</td>
                                <td>{{line.email}}</td>
                                <td>{{line.lastName}}</td>
                                <td>{{line.rawLine | limitTo:20}}</td>
                                <td>{{line.statusTranslated}}</td>
                                <td>
                                    <span id="loading_{{line.index}}" class="glyphicon glyphicon-refresh" aria-hidden="true" style="display:none"></span>
                                    <div id="uploadButton_{{line.index}}">
                                        <i ng-show="['NEW', 'TO_UPDATE'].indexOf(line.status)!=-1" ng-click="uploadUser(line.index);" class="fa fa-arrow-up " aria-hidden="true" role="button" style="cursor: hand; cursor: pointer;"></i>
                                        <i ng-show="['UP_TO_DATE'].indexOf(line.status)!=-1" class="fa fa-check " aria-hidden="true"></i>
                                        <i ng-show="['ERROR_FIELDS_ARE_MISSING', 'ERROR_INVALID_EMAIL', 'ERROR_EMAIL_TWICE'].indexOf(line.status)!=-1" class="fa fa-thumbs-down " aria-hidden="true"></i>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                      <div class="col-md-12">
                        <ul class="list-inline pull-right">
                            <li>
                                <a class="btn btn-default" ng-click="usersAnalysed=false"><?php t(':BACK') ?></a>
                            </li>
                            <li>
                                <button id="buttonRemove" type="button" class="btn btn-primary" ng-click="removeUpToDate()"><?php t(':REMOVE_UP_TO_DATE') ?></button>
                            </li>
                            <li>
                                <button id="buttonInsert" type="button" class="btn btn-primary" ng-real-click="uploadAll()" ng-confirm-click="<?php t(':ARE_YOU_SURE') ?>"><?php t(':UPDATE_ALL') ?></button>
                            </li>
                        </ul>
                      </div>
                    </div>
                </div>

                <!-- Textarea -->
                <div ng-hide="usersAnalysed">
                    <div class="form-group">
                        <!--<label class="col-md-4 control-label" for="text">Text</label>-->
                        <div class="col-md-12">
                            <textarea class="form-control" id="users" name="users" rows="10" ng-model="users"></textarea>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="form-group">
                        <div class="col-md-12">
                            <ul class="list-inline pull-right">
                                <li>
                                    <a class="btn btn-default" href="<?php echo url(array('controller' => 'site', 'view' => '')) ?>"><?php t(':CANCEL') ?></a>
                                </li>
                                <li>
                                    <button id="buttonCheck" type="button" class="btn btn-primary" ng-click="checkDatas();"><?php t(':NEXT') ?></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
              </fieldset>
            </form>
        </div>
    </div>