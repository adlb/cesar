<script>
    getScope('donationsCtrlDiv', function(scope) {
        scope.init(
            <?php echo json_encode($obj['user']['donations']);?>,
            ''
        );
    });
</script>

<div class="container">
    <legend><?php t(':PROFILE') ?></legend>

    <div class="row">
        <div class="col-md-2 text-right">
            <?php t(':NAME') ?>
        </div>
        <div class="col-md-3 text-left">
            <?php echo $obj['user']['firstName'].' '.$obj['user']['lastName'] ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 text-right">
            <?php t(':EMAIL') ?>
        </div>
        <div class="col-md-3 text-left">
            <?php echo $obj['user']['email'] ?>
        </div>
        <div class="col-md-2 text-right">
            <?php t(':PHONE') ?>
        </div>
        <div class="col-md-3 text-left">
            <?php echo $obj['user']['phone'] ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 text-right">
            <?php t(':ADDRESS') ?>
        </div>
        <div class="col-md-5 text-left">
            <?php echo $obj['user']['addressLine1'] != '' ? $obj['user']['addressLine1'].'<br />' : '' ?>
            <?php echo $obj['user']['addressLine2'] != '' ? $obj['user']['addressLine2'].'<br />' : '' ?>
            <?php echo $obj['user']['postalCode'] != '' ? $obj['user']['postalCode'].' ' : '' ?>
            <?php echo $obj['user']['city'] != '' ? $obj['user']['city'].' ' : '' ?>
            <?php echo $obj['user']['city'] != '' && $obj['user']['country'] != '' ? '- ' : '' ?>
            <?php echo $obj['user']['country'] != '' ? $obj['user']['country'].'<br />' : '' ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 text-right">
        </div>
        <div class="col-md-5 text-left">
            <a class="btn btn-default" href="<?php echo url(array(
                'controller' => 'user', 
                'view' => 'editUser', 
                'id' => $obj['user']['id'], 
                'callback' => url(array(
                        'controller' => 'user', 
                        'view' => 'profil',
                        'id' => $obj['user']['id']
                        ))
                ))?>" role="button"><?php t(":MODIFY_USER_INFO") ?></a><br/><br/>
        </div>
    </div>
    <br/>
    <br/>
    <div id="donationsCtrlDiv" class="row" ng-controller="donationsListCtrl" ng-show="count(donations)">
        <legend><?php t(':PREVIOUS_DONATIONS') ?></legend>
        <div class="col-md-12">
            <table st-table="rowCollection" class="table table-striped">
                <thead>
                <tr>
                    <th ng-click="predicate=='id' ? reverse = !reverse : predicate='id';"><?php t(':ID') ?></th>
                    <th ng-click="predicate=='dateInit' ? reverse = !reverse : predicate='dateInit';"><?php t(':DATE') ?></th>
                    <th ng-click="predicate=='amount' ? reverse = !reverse : predicate='amount';"><?php t(':AMOUNT') ?></th>
                    <th ng-click="predicate=='type' ? reverse = !reverse : predicate='type';"><?php t(':TYPE') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="donation in donations | orderBy:predicate:reverse">
                    <td>{{donation.id}}</td>
                    <td>{{donation.dateInit}}</td>
                    <td>{{donation.amount}}</td>
                    <td>{{donation.type}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>