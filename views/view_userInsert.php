<script>
    getScope('usersCtrlDiv', function(scope) {
        scope.init(
            '<?php echo url(array('controller' => 'user', 'action' => 'check'));?>',
            '<?php echo url(array('controller' => 'user', 'action' => 'update'));?>'
        );
    });
</script>

<div id="usersCtrlDiv" ng-controller="usersCtrl">
    <?php renderPartial('errorsAngular', $obj); ?>

    <form class="form-horizontal" method="POST" action="#">
      <fieldset>
        <p>Enter a list of user. One user per line.</p>
        <p>First line must contains colmun names separated by ';' ('email' and 'lastname' at least).</p>
        <div ng-show="usersAnalysed">
        <input type="text" placeholder="<?php t(':SEARCH')?>" ng-model="search" />
        <div class="btn-group">
          <label class="btn btn-default" ng-model="filterRadio" btn-radio=""> All </label>
          <label class="btn btn-default" ng-model="filterRadio" btn-radio="{status:'EMAIL'}"> All errors </label>
          <label class="btn btn-default" ng-model="filterRadio" btn-radio="{status:'NEW'}"> New </label>
          <label class="btn btn-default" ng-model="filterRadio" btn-radio="{status:'UPDATE'}"> Update </label>
        </div>
        {{ filterRadio }}
        <table st-table="rowCollection" class="table table-striped">
            <thead>
            <tr>
                <th ng-click="predicate=='index' ? reverse = !reverse : predicate='index';"><?php t(':INDEX') ?></th>
                <th ng-click="predicate=='email' ? reverse = !reverse : predicate='email';"><?php t(':EMAIL') ?></th>
                <th ng-click="predicate=='lastName' ? reverse = !reverse : predicate='lastName';"><?php t(':LASTNAME') ?></th>
                <th ng-click="predicate=='rawLine' ? reverse = !reverse : predicate='rawLine';"><?php t(':RAWLINE') ?></th>
                <th ng-click="predicate=='status' ? reverse = !reverse : predicate='status';"><?php t(':STATUS') ?></th>
                <th><?php t(':ACTIONS') ?></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="line in usersAnalysed.lines | filter:search | filter:filterRadio | orderBy:predicate:reverse">
                <td>{{line.index}}</td>
                <td>{{line.email}}</td>
                <td>{{line.lastName}}</td>
                <td>{{line.rawLine | limitTo:20}}</td>
                <td>{{line.status}}</td>
                <td>
                    <span id="loading_{{line.index}}" class="glyphicon glyphicon-refresh" aria-hidden="true" style="display:none"></span>
                    <span ng-show="['NEW', 'TO_UPDATE'].indexOf(line.status)!=-1" id="uploadButton_{{line.index}}" ng-click="uploadUser(line.index);" class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="form-group">
          <div class="col-md-4 pull-right">
            <a class="btn btn-default" ng-real-click="usersAnalysed=false;" ng-confirm-click="AYS ?">Back</a>
            <button id="buttonRemove" type="button" class="btn btn-primary" ng-click="removeUpToDate()">Remove Up to date</button>
            <button id="buttonInsert" type="button" class="btn btn-primary" ng-click="uploadAll()" ng-confirm-click="AYS ?">Update All</button>
          </div>
        </div>
        </div>

        <!-- Textarea -->
        <div ng-hide="usersAnalysed">
        <div class="form-group">
          <!--<label class="col-md-4 control-label" for="text">Text</label>-->
          <div class="col-md-12">
            <textarea class="form-control" id="users" name="users" rows="10" ng-model="users" onKeyUp="$('#buttonInsert').attr('disabled', true);" onChange="$('#buttonInsert').attr('disabled', true);"></textarea>
          </div>
        </div>

        <!-- Button -->
        <div class="form-group">
          <div class="col-md-2 pull-right">
            <a class="btn btn-default" href="<?php echo url(array('controller' => 'site', 'view' => '')) ?>">Cancel</a>
            <button id="buttonCheck" type="button" class="btn btn-primary" ng-click="checkDatas();">Validate</button>
          </div>
        </div>
        </div>
      </fieldset>
    </form>
</div>