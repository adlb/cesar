<script>
    var cesar_q = cesar_q || [];
    cesar_q.push({
        id: 'usersCtrlDiv',
        f: function(scope) {
        scope.init(
            <?php echo json_encode($obj['users']);?>,
            '<?php echo url(array('controller' => 'user', 'action' => 'delete'));?>'
        );
    }});
</script>

    <div class="page-header">
        <div class="container">
            <h1 class="page-title pull-left"> <?php t(':USER_MANAGEMENT') ?> </h1>
        </div>
    </div>

    <div class="page-container" id="usersCtrlDiv" ng-controller="usersListCtrl">
        <div class="container">
            <table class="table table-hover table-bordered table-condensed table-striped">
                <caption>
                    <?php t(':USERS_LIST') ?>
                    <ul class="list-inline pull-right">
                        <li>
                            <input type="text" placeholder="<?php t(':SEARCH')?>" ng-model="search" />
                        </li>
                        <li>
                            <a href="<?php echo url(array('controller' => 'user', 'view' => 'register')) ?>"><span class="glyphicon glyphicon-plus"></span></a>
                        </li>
                        <li>
                            <a href="<?php echo url(array('controller' => 'user', 'view' => 'userInsert')) ?>"><span class="glyphicon glyphicon-th-list"></span></a>
                        </li>
                        <li>
                            <a class="btn-popover" 
                                href="<?php echo url(array('controller' => 'user', 'view' => 'export', 'respType' => 'xls')) ?>" 
                                data-container="body" data-toggle="popover" data-placement="top"
                                data-content="Export excel file."
                            ><span class="glyphicon glyphicon-download-alt"></span></a>
                        </li>
                    </ul>
                </caption>

                <thead>
                <tr>
                    <th ng-click="predicate=='email' ? reverse = !reverse : predicate='email';"><?php t(':EMAIL') ?></th>
                    <th ng-click="predicate=='firstName' ? reverse = !reverse : predicate='firstName';"><?php t(':FIRSTNAME') ?></th>
                    <th ng-click="predicate=='lastName' ? reverse = !reverse : predicate='lastName';"><?php t(':LASTNAME') ?></th>
                    <th ng-click="predicate=='role' ? reverse = !reverse : predicate='role';"><?php t(':ROLE') ?></th>
                    <th><?php t(':ACTIONS') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="user in users | filter:search | orderBy:predicate:reverse">
                    <td><a ng-href="<?php echo url(array('controller' => 'user', 'view' => 'profil')) ?>&id={{user.id}}">{{user.email}}</a></td>
                    <td>{{user.firstName}}</td>
                    <td>{{user.lastName}}</td>
                    <td>{{user.role}}</td>
                    <td>
                        <a href="<?php echo url(array('controller' => 'user', 
                                                      'view' => 'profil',
                                                      )) ?>&id={{user.id}}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        <a href="<?php echo url(array('controller' => 'user', 
                                                      'view' => 'editUser',
                                                      'callback' => url(array(
                                                        'controller' => 'user',
                                                        'view' => 'userList'
                                                      )))) ?>&id={{user.id}}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        <a href="#" ng-click="deleteUser(user.id);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>