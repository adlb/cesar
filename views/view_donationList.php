<script type="text/javascript">
    var cesar_q = cesar_q || [];
    cesar_q.push({
        id: 'donationsCtrlDiv', 
        f: function(scope) {
            scope.init(
                <?php echo json_encode($obj['donations'], JSON_NUMERIC_CHECK);?>,
                '<?php echo url(array('controller' => 'donation', 'action' => 'delete'));?>',
                '<?php echo url(array('controller' => 'donation', 'action' => 'validate'));?>',
                '<?php echo url(array('controller' => 'donation', 'action' => 'archive'));?>'
            );}
    });
</script>

    <div class="page-header">
        <div class="container">
            <h1 class="page-title pull-left"> <?php t(':DONATION_MANAGEMENT') ?> </h1>
        </div>
    </div>

    <div class="page-container" id="donationsCtrlDiv" ng-controller="donationsListCtrl" ng-cloak>
        <div class="container" id="exportable">
            <table class="table table-hover table-bordered table-condensed table-striped">
                <caption>
                    <?php t(':DONATIONS_LIST') ?>
                    <ul class="list-inline pull-right">
                        <li>
                            <?php if ($obj['isFiltered']) { ?>
                                <a href=<?php echo url(array('controller' => 'donation', 'view' => 'donationList', 'isFiltered' => false)) ?>><?php t(':DISPLAY_ALL') ?></a>
                            <?php } else { ?>
                                <a href=<?php echo url(array('controller' => 'donation', 'view' => 'donationList', 'isFiltered' => true)) ?>><?php t(':HIDE_ARCHIVED') ?></a>
                            <?php } ?>
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
                                href="<?php echo url(array('controller' => 'donation', 'view' => 'export', 'isFiltered' => $obj['isFiltered'], 'respType' => 'xls')) ?>"
                                data-container="body" data-toggle="popover" data-placement="top"
                                data-content="Export excel file."
                            ><span class="glyphicon glyphicon-download-alt"></span></a>
                        </li>
                    </ul>
                </caption>
                <thead>
                    <tr>
                        <th ng-click="predicate=='id' ? reverse = !reverse : predicate='id'; saveLocal();"><?php t(':ID') ?></th>
                        <th ng-click="predicate=='dateInit' ? reverse = !reverse : predicate='dateInit'; saveLocal();"><?php t(':DATE') ?></th>
                        <th ng-click="predicate=='email' ? reverse = !reverse : predicate='email'; saveLocal();"><?php t(':EMAIL') ?></th>
                        <th ng-click="predicate=='firstName' ? reverse = !reverse : predicate='firstName'; saveLocal();"><?php t(':FIRSTNAME') ?></th>
                        <th ng-click="predicate=='lastName' ? reverse = !reverse : predicate='lastName'; saveLocal();"><?php t(':LASTNAME') ?></th>
                        <th ng-click="predicate=='amount' ? reverse = !reverse : predicate='amount'; saveLocal();"><?php t(':AMOUNT') ?></th>
                        <th ng-click="predicate=='type' ? reverse = !reverse : predicate='type'; saveLocal();"><?php t(':TYPE') ?></th>
                        <th ng-click="predicate=='status' ? reverse = !reverse : predicate='status'; saveLocal();"><?php t(':STATUS') ?></th>
                        <th class="text-center"><?php t(':ACTIONS') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="donation in donations | filter:search | orderBy:predicate:reverse">
                        <td>{{donation.id}}</td>
                        <td>{{donation.dateInit}}</td>
                        <td>{{donation.email}}</td>
                        <td>{{donation.firstName}}</td>
                        <td>{{donation.lastName}}</td>
                        <td class="text-right">{{donation.amount | number:2}}</td>
                        <td>{{donation.type}}</td>
                        <td>{{donation.status}}</td>
                        <td class="text-center">
                            <ul class="list-inline">
                                <li>
                                    <a href="<?php echo url(array('controller' => 'donation', 'view' => 'edit', 'callback' => $obj['callback'])) ?>&id={{donation.id}}">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
                                </li>
                                <li>
                                    <a 
                                        href="#";
                                        ng-real-click="validateDonation(donation.id);" 
                                        ng-confirm-click="<?php t(':ARE_YOU_SURE') ?>">
                                        <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
                                    </a>
                                </li>
                                <li>
                                    <a 
                                        href="#"
                                        class="btn-popover"
                                        ng-real-click="archiveDonation(donation.id);" 
                                        ng-confirm-click="<?php t(':ARE_YOU_SURE') ?>">
                                        <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
                                    </a>
                                </li>
                                <li>
                                    <a 
                                        href="#";
                                        ng-real-click="deleteDonation(donation.id);" 
                                        ng-confirm-click="<?php t(':ARE_YOU_SURE') ?>">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr/>
        </div>
    </div>