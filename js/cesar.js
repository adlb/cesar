var cesarApp = angular.module('cesarApp',['ui.bootstrap']);

cesarApp.controller('mediaCtrl', ['$scope', function($scope) {
  $scope.medias = null;
  $scope.deleteUrl = '';
  $scope.uploadUrl = '';
  
  $scope.init = function(medias, deleteUrl, uploadUrl) {
	$scope.medias = medias;
    $scope.deleteUrl= deleteUrl;
    $scope.uploadUrl= uploadUrl;
  };
  
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

cesarApp.directive('ngConfirmClick', [
  function() {
    return {
      priority: 1,
      link: function(scope, element, attr) {
        var msg = attr.ngConfirmClick || "Are you sure?";
        element.bind('click', function(event) {
          if (window.confirm(msg)) {
            scope.$eval(attr.ngRealClick)
          }
        });
      }
    };
  }
]);

cesarApp.controller('usersCtrl', ['$scope', function($scope) {
	$scope.users=localStorage.getItem("users");
	$scope.filterRadio = '';
	$scope.errors=[];
	
	$scope.init = function(checkUrl, sendUpdateUrl) {
		$scope.checkUrl=checkUrl;
		$scope.sendUpdateUrl=sendUpdateUrl;
	}
	
	$scope.checkDatas = function() {
		localStorage.setItem("users", $scope.users);
		$scope.errors.splice(0,$scope.errors.length);
		$.ajax({
			url : $scope.checkUrl,
			type: "POST",
			data : $scope.users,
			processData: false,
			contentType: false,
			success:function(data, textStatus, jqXHR){
				$scope.$apply(function($scope){
					if (data.usersAnalysed) {
						localStorage.setItem("usersAnalysed", data.usersAnalysed);
						$scope.usersAnalysed = data.usersAnalysed;
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
	
	$scope.uploadUser = function(id) {
		loadUsers([id]);
	};
	
	$scope.uploadAll = function() {
		var userIndexes = [];
		
		for(var i=0; i<$scope.usersAnalysed.lines.length; i++) {
			userIndexes.push($scope.usersAnalysed.lines[i].index);
		}
		loadUsers(userIndexes);
	};
	
	$scope.removeUpToDate = function () {
		newText = [];
		newText.push($scope.usersAnalysed.analyzedColumns.rawLine);
		for(var i=0; i<$scope.usersAnalysed.lines.length; i++) {
			if ($scope.usersAnalysed.lines[i].status == 'UP_TO_DATE') {
				$scope.usersAnalysed.lines.splice(i,1);
				i--;
			} else {
				newText.push($scope.usersAnalysed.lines[i].rawLine);
			}
		}
		$scope.users = newText.join("\n");
		localStorage.setItem("users", $scope.users);
	};
	
	loadUsers = function (userIndexes) {
		var toBeSend = [];
		
		for(var i=0;i<userIndexes.length;i++) {
			index = userIndexes[i];
			line = $scope.usersAnalysed.lines[index];
			if (line.uploadLaunched)
				continue;
				
			if (['NEW', 'TO_UPDATE'].indexOf(line.status)==-1)
				continue;
				
			toBeSend.push(line);

			line.uploadLaunched = true;
		
			$("#loading_"+index).css('display','block');
			$("#uploadButton_"+index).css('display','none');
		}
		
		$.ajax({
			url : $scope.sendUpdateUrl,
			type: "POST",
			data : { 'lines' : toBeSend },
			success:function(data, textStatus, jqXHR){
				$scope.$apply(function($scope){
					if (data.lines) {
						for(var i=0; i<data.lines.length; i++) {
							index = data.lines[i].index;
							$("#loading_"+index).css('display','none');
							$("#uploadButton_"+index).css('display','block');
							$scope.usersAnalysed.lines[index].uploadLaunched = false;
							$scope.usersAnalysed.lines[index].status = data.lines[i].status;
						}
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
				$scope.usersAnalysed[id].uploadLaunched = false;
				$("#loading_"+id).css('display','none');
				$("#uploadButton_"+id).css('display','block');
			}
		});
	};

}]);

cesarApp.controller('usersCtrl', ['$scope', function($scope) {
  $scope.init = function(users, deleteUrl) {
	$scope.users = users;
	$scope.deleteUrl = deleteUrl;
  };
  
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

//Not angular functions
jQuery(function($) {
    var panelList = $('.draggableMenuItem');

    panelList.sortable({
        update: function() {
            var url = $(this).data('movehandler');
            var data = $(this).sortable('serialize');

            $.ajax({
                data: data,
                type: 'POST',
                url: url
            });
        }
    });
});

jQuery(document).ready(function($) {
      $(".clickableCell").click(function() {
            window.document.location = $(this).attr("href");
      });
});

$(function () {
    $('#datetimepicker').datetimepicker({
        locale: '', //<?php echo $obj['language'] ?>
        format: 'DD/MM/YYYY',
        defaultDate: "", //<?php echo date('Y-m-d') ?>",
    });
});

$(function() {
        $('#actionShow').click(function() {
          if ( $('#text').css('display') == 'none' ) {
                $('#textHTML').css('display','none');
                $('#text').css('display','inline');
          } else
              $.ajax({
                type: 'POST',
                url: '?controller=builder&action=format',
                data: $('#text').val(),
                timeout: 3000,
                success: function(data) {
                  $('#text').css("display","none");
                  $('#textHTML').html(data.formattedText);
                  $('#textHTML').css("display","inline");
                  },
                error: function() {
                  alert('La requête n\'a pas abouti'); }
              });
        });
      });