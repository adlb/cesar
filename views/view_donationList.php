<script>
    var cesar_q = cesar_q || [];
    cesar_q.push({
        id: 'donationsCtrlDiv', 
        f: function(scope) {
        scope.init(
            <?php echo json_encode($obj['donations']);?>,
            '<?php echo url(array('controller' => 'donation', 'action' => 'delete'));?>'
        );
    });
</script>

    <div class="page-header">
        <div class="container">
            <h1 class="page-title pull-left"> <?php t(':MODIFY_DONATION') ?> </h1>
        </div>
    </div>

    <div class="page-container" id="donationsCtrlDiv" ng-controller="donationsListCtrl">
        <div class="container">
            <table class="table table-hover table-bordered table-condensed table-striped">
                <caption>
                    <?php t(':LIST_USERS') ?>
                    <ul class="list-inline pull-right">
                        <li>
                            <input type="text" placeholder="<?php t(':SEARCH')?>" ng-model="search" />
                        </li>
                        <li>
                            <a class="btn-popover"
                                href="<?php echo url(array('controller' => 'donation', 'view' => 'create')) ?>"
                                data-container="body" data-toggle="popover" data-placement="top"
                                data-content="Create a new donation."
                                >
                                <span class="glyphicon glyphicon-plus"></span>
                            </a>
                        </li>
                        <li>
                            <a class="btn-popover" 
                                href="<?php echo url(array('controller' => 'donation', 'view' => 'export')) ?>"
                                data-container="body" data-toggle="popover" data-placement="top"
                                data-content="Export excel file."
                            ><span class="glyphicon glyphicon-download-alt"></span></a>
                        </li>
                    </ul>
                </caption>
                <thead>
                    <tr>
                        <th ng-click="predicate=='id' ? reverse = !reverse : predicate='id';"><?php t(':ID') ?></th>
                        <th ng-click="predicate=='dateInit' ? reverse = !reverse : predicate='dateInit';"><?php t(':DATE') ?></th>
                        <th ng-click="predicate=='firstName' ? reverse = !reverse : predicate='firstName';"><?php t(':FIRSTNAME') ?></th>
                        <th ng-click="predicate=='lastName' ? reverse = !reverse : predicate='lastName';"><?php t(':LASTNAME') ?></th>
                        <th ng-click="predicate=='amount' ? reverse = !reverse : predicate='amount';"><?php t(':AMOUNT') ?></th>
                        <th ng-click="predicate=='type' ? reverse = !reverse : predicate='type';"><?php t(':TYPE') ?></th>
                        <th ng-click="predicate=='status' ? reverse = !reverse : predicate='status';"><?php t(':STATUS') ?></th>
                        <th><?php t(':ACTIONS') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="donation in donations | filter:search | orderBy:predicate:reverse">
                        <td>{{donation.id}}</td>
                        <td>{{donation.dateInit}}</td>
                        <td>{{donation.firstName}}</td>
                        <td>{{donation.lastName}}</td>
                        <td>{{donation.amount}}</td>
                        <td>{{donation.type}}</td>
                        <td>{{donation.status}}</td>
                        <td>
                            <a href="<?php echo url(array('controller' => 'donation', 'view' => 'edit')) ?>&id={{donation.id}}">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </a>
                            <a href="#" ng-click="deleteDonation(donation.id);">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>