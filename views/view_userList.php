<script src="js/angular.min.js"></script>
<script src="js/smart-table.min.js"></script>
<script>
var userApp = angular.module('userApp',[]);

userApp.controller('usersCtrl', ['$scope', function($scope) {
  $scope.users = <?php echo json_encode($obj['users']);?>;
  $scope.deleteUrl='<?php echo url(array('controller' => 'user', 'action' => 'delete'));?>';
  
  $scope.deleteUser = function(id){
	$.post($scope.deleteUrl, {'id': id}, function(data) {
		$scope.$apply(function($scope) {
			if (data.status == 'ok') {
				for(var i = 0; i < $scope.users.length; i++) {
					var obj = $scope.users[i];

					if(obj.id == id) {
						$scope.users.splice(i, 1);
						i--;
					}
				}
			}
		});
	});
  };
}]);
</script>

<div ng-app="userApp">
	<div ng-controller="usersCtrl">

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
</div>
<?php

?>