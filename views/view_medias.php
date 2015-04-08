<script src="js/angular.min.js"></script>

<script>
var mediaApp = angular.module('mediaApp',[]);

mediaApp.controller('mediaCtrl', ['$scope', function($scope) {
  $scope.medias = <?php echo json_encode($obj['medias']);?>;
  $scope.deleteUrl='<?php echo url(array('controller' => 'medias', 'action' => 'delete'));?>';
  $scope.uploadUrl='<?php echo url(array('controller' => 'medias', 'action' => 'upload'));?>';
  $scope.errors=[];
	$scope.deleteMedia = function(id){
		$.post($scope.deleteUrl, {'id': id}, function(data) {
			$scope.$apply(function($scope) {
				if (data.status == 'ok') {
					for(var i = 0; i < $scope.medias.length; i++) {
						var obj = $scope.medias[i];

						if(obj.id == id) {
							$scope.medias.splice(i, 1);
							i--;
						}
					}
				}
			});
		});
	};
	
	$scope.setFiles = function(element) {
		$scope.$apply(function($scope) {
			$scope.files = []
			for (var i = 0; i < element.files.length; i++) {
				$scope.files.push(element.files[i]);
			}
			$scope.progressVisible = false;
		});
    };

	$scope.uploadMedia = function(){
		var fd = new FormData();
        for (var i in $scope.files) {
            fd.append("files[]", $scope.files[i]);
        }

		$scope.errors.splice(0,$scope.errors.length);
		$.ajax({
			url : $scope.uploadUrl,
			type: "POST",
			data : fd,
			processData: false,
			contentType: false,
			success:function(data, textStatus, jqXHR){
				$scope.$apply(function($scope){
					if (data.medias) {
						data.medias.forEach(function (media) {
							$scope.medias.push(media);
						});
					}
					if (data.errors) {
						data.errors.forEach(function (error) {
							//Fixme error will be raised when mutiple same error.
							$scope.errors.push(error);
						});
					}
				});
			},
			error: function(jqXHR, textStatus, errorThrown){
				console.log(errorThrown);     
			}
		});
	};

}]);
</script>



<div ng-app="mediaApp">
	<div ng-controller="mediaCtrl">

		<form enctype="multipart/form-data" type="POST" ng-submit="uploadMedia();">
			<input id="mediaFiles" type="file" class="file" name="mediaFiles[]" multiple="true" onchange="angular.element(this).scope().setFiles(this)" />
			<button class="btn btn-default" type="submit">Upload</button>
		</form>
	
		<div ng-repeat="error in errors">
			{{ error }}
		</div>
		
		<div ng-repeat="media in medias" style="border: 1px solid black;width: 200px; height: 150px; text-align:center;display:inline-block;">
			{{ media.name }} [{{ media.id }}]<br/>
			<a ng-href="{{ media.file }}" ><img ng-src="{{ media.thumb }}" style="display:inline-block;"/></a><br/>
			<a href="#" ng-click="deleteMedia(media.id);"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
		</div>
	</div>
</div>