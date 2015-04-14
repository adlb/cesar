<script>
	getScope('usersCtrlDiv', function(scope) {
		scope.init(
			<?php echo json_encode($obj['users']);?>,
			'<?php echo url(array('controller' => 'user', 'action' => 'delete'));?>'
		);
	});
</script>

<div id="usersCtrlDiv" ng-controller="usersCtrl">

	<input type="text" placeholder="<?php t(':SEARCH')?>" ng-model="search" /> 
	<a href="<?php echo url(array('controller' => 'user', 'view' => 'register')) ?>"><span class="glyphicon glyphicon-plus"></span></a>
	<a href="<?php echo url(array('controller' => 'user', 'view' => 'userInsert')) ?>"><span class="glyphicon glyphicon-th-list"></span></a>
	<table st-table="rowCollection" class="table table-striped">
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
			<td>{{user.email}}</td>
			<td>{{user.firstName}}</td>
			<td>{{user.lastName}}</td>
			<td>{{user.role}}</td>
			<td>
				<a href="<?php echo url(array('controller' => 'user', 'view' => 'editUser')) ?>&id={{user.id}}">edit</a>
				<a href="#" ng-click="deleteUser(user.id);"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
			</td>
		</tr>
		</tbody>
	</table>
</div>

<?php

?>