var cesarApp = angular.module('cesarApp',['ui.bootstrap']);

cesarApp.controller('mediaCtrl', ['$scope', function($scope) {
  $scope.medias = [];
  $scope.deleteUrl = '';
  $scope.uploadUrl = '';
  $scope.sizes = [];
  $scope.selectedSize = null;
  $scope.custom = 0;
  $scope.selectedMedia = JSON.parse(localStorage.getItem("mediaCtrl.selectedMedia")) || 'undefined';
  $scope.displayAsList = $scope.$eval(localStorage.getItem("mediaCtrl.displayAsList")) || false;
  $scope.search = localStorage.getItem("mediaCtrl.search") || '';
  $scope.predicate = localStorage.getItem("mediaCtrl.predicate", $scope.predicate) || '';
  $scope.reverse = $scope.$eval(localStorage.getItem("mediaCtrl.reverse", $scope.reverse)) || false;
        
  $scope.init = function(medias, deleteUrl, uploadUrl, sizes) {
    $scope.medias = medias;
    $scope.deleteUrl= deleteUrl;
    $scope.uploadUrl= uploadUrl;
    $scope.sizes = sizes;
    $scope.selectedSize = sizes[0];
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
        fd.append("width", $scope.custom ? $scope.customWidth : $scope.selectedSize[0]);
        fd.append("height", $scope.custom ? $scope.customHeight : $scope.selectedSize[1]);

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
    
    $scope.selectMedia = function(media){
        $scope.selectedMedia = media;
        $scope.saveLocal();                     
    }
           
    $scope.saveLocal = function() { 
        localStorage.setItem("mediaCtrl.displayAsList", $scope.displayAsList);
        localStorage.setItem("mediaCtrl.selectedMedia", JSON.stringify($scope.selectedMedia));
        localStorage.setItem("mediaCtrl.search", $scope.search);
        localStorage.setItem("mediaCtrl.predicate", $scope.predicate);
        localStorage.setItem("mediaCtrl.reverse", $scope.reverse);
    };
    
    $scope.getMediaFile = function(file) {
        if (file.substring(file.length-3, file.length).toLowerCase() == 'pdf') {
            return 'img/thumb_pdf.png';
        }
        return file;
    };
}]);

cesarApp.controller('usersCtrl', ['$rootScope', '$scope', function($rootScope, $scope) {
    $scope.users=localStorage.getItem("users");
    $scope.filterRadio = '';
    $scope.usersAnalysed=false;
    
    $scope.init = function(checkUrl, sendUpdateUrl) {
        $scope.checkUrl=checkUrl;
        $scope.sendUpdateUrl=sendUpdateUrl;
    }
    
    $scope.checkDatas = function() {
        localStorage.setItem("users", $scope.users);
        $rootScope.$broadcast("clearMessages");
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
                    if (data.messages) {
                        $rootScope.$broadcast("newMessages", data.messages);
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
        //Fix me. reindexation is needed, upload does not work after remove-up-to-date
    };
    
    loadUsers = function (userIndexes) {
        $rootScope.$broadcast("clearMessages");
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
                            $scope.usersAnalysed.lines[index].statusTranslated = data.lines[i].statusTranslated;
                        }
                    }
                    if (data.messages) {
                        $rootScope.$broadcast("newMessages", data.messages);
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

cesarApp.controller('editTextTradCtrl', ['$scope', function($scope) {
    $scope.titleKey = '';
    $scope.textKey = '';
    $scope.languageFrom = '';
    $scope.languageTo = '';
    $scope.languages = {};
    $scope.titles = [];
    $scope.texts = [];
    $scope.actionSave = '';
    $scope.actionEdit = '';
    
    $scope.init=function(titleKey, textKey, languageFrom, languageTo, languages, titles, texts, actionSave, actionEdit) {
        $scope.titleKey = titleKey;
        $scope.textKey = textKey;
        $scope.languageFrom = languageFrom;
        $scope.languageTo = languageTo;
        $scope.languages = languages;
        $scope.titles = titles;
        $scope.texts = texts;
        $scope.actionSave = actionSave;
        $scope.actionEdit = actionEdit;
    }
}]);

cesarApp.controller('usersListCtrl', ['$scope', function($scope) {
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

cesarApp.controller('articleCtrl', ['$scope', function($scope) {
  $scope.image = null;
  
  $scope.init = function(type, images, imageId) {
    $scope.type = type;
    $scope.images = images;
    for(i = 0; i< images.length;i++) {
        if (images[i].id+"" == imageId+"") {
            $scope.image = images[i];
        }
    }
  };
}]);

cesarApp.controller('articlesListCtrl', ['$rootScope', '$scope', function($rootScope, $scope) {
  
    $scope.search = localStorage.getItem("articlesListCtrl.search") || '';
    $scope.predicate = localStorage.getItem("articlesListCtrl.predicate", $scope.predicate) || 'id';
    $scope.reverse = $scope.$eval(localStorage.getItem("articlesListCtrl.reverse", $scope.reverse)) || false;

    $scope.init = function(articles, languages, prefixUrl, prefixUrlArticle, deleteUrl) {
        $scope.articles = articles;
        $scope.languages = languages;
        $scope.prefixUrl = prefixUrl;
        $scope.prefixUrlArticle = prefixUrlArticle;
        $scope.deleteUrl = deleteUrl;
    };

    $scope.deleteArticle = function(id) {
        $rootScope.$broadcast("clearMessages");
        $.post($scope.deleteUrl, {'id': id}, function(data) {
            $scope.$apply(function($scope) {
                if (data.messages) {
                    $rootScope.$broadcast("newMessages", data.messages);
                }
                if (data.status == 'ok') {
                    for(var i = 0; i < $scope.articles.length; i++) {
                        var obj = $scope.articles[i];

                        if(obj.id == id) {
                            $scope.articles.splice(i, 1);
                            i--;
                        }
                    }
                }
            });
        });
    };
    
    $scope.saveLocal = function() { 
        localStorage.setItem("articlesListCtrl.search", $scope.search);
        localStorage.setItem("articlesListCtrl.predicate", $scope.predicate);
        localStorage.setItem("articlesListCtrl.reverse", $scope.reverse);
    };
}]);

cesarApp.controller('donationsListCtrl', ['$rootScope', '$scope', function($rootScope, $scope) {
  
    $scope.search = localStorage.getItem("donationCtrl.search") || '';
    $scope.predicate = localStorage.getItem("donationCtrl.predicate", $scope.predicate) || '';
    $scope.reverse = $scope.$eval(localStorage.getItem("donationCtrl.reverse", $scope.reverse)) || false;

    $scope.init = function(donations, deleteUrl, validateUrl, archiveUrl) {
        $scope.donations = donations;
        $scope.deleteUrl = deleteUrl;
        $scope.validateUrl = validateUrl;
        $scope.archiveUrl = archiveUrl;
    };

    $scope.deleteDonation = function(id) {
        $rootScope.$broadcast("clearMessages");
        $.post($scope.deleteUrl, {'id': id}, function(data) {
            $scope.$apply(function($scope) {
                if (data.messages) {
                    $rootScope.$broadcast("newMessages", data.messages);
                }
                if (data.status == 'ok') {
                    for(var i = 0; i < $scope.donations.length; i++) {
                        var obj = $scope.donations[i];

                        if(obj.id == id) {
                            $scope.donations.splice(i, 1);
                            i--;
                        }
                    }
                }
            });
        });
    };
    
    $scope.validateDonation = function(id) {
        $rootScope.$broadcast("clearMessages");
        $.post($scope.validateUrl, {'id': id}, function(data) {
            $scope.$apply(function($scope) {
                if (data.messages) {
                    $rootScope.$broadcast("newMessages", data.messages);
                }
                if (data.status == 'ok') {
                    for(var i = 0; i < $scope.donations.length; i++) {
                        var obj = $scope.donations[i];

                        if(obj.id == id) {
                            $scope.donations[i].status = 'validated';
                        }
                    }
                }
            });
        });
    };
    
    $scope.archiveDonation = function(id) {
        $rootScope.$broadcast("clearMessages");
        $.post($scope.archiveUrl, {'id': id}, function(data) {
            $scope.$apply(function($scope) {
                if (data.messages) {
                    $rootScope.$broadcast("newMessages", data.messages);
                }
                if (data.status == 'ok') {
                    for(var i = 0; i < $scope.donations.length; i++) {
                        var obj = $scope.donations[i];

                        if(obj.id == id) {
                            $scope.donations[i].status = 'archived';
                        }
                    }
                }
            });
        });
    };
    
    $scope.deleteDonation = function(id) {
        $rootScope.$broadcast("clearMessages");
        $.post($scope.deleteUrl, {'id': id}, function(data) {
            $scope.$apply(function($scope) {
                if (data.messages) {
                    $rootScope.$broadcast("newMessages", data.messages);
                }
                if (data.status == 'ok') {
                    for(var i = 0; i < $scope.donations.length; i++) {
                        var obj = $scope.donations[i];

                        if(obj.id == id) {
                            $scope.donations.splice(i, 1);
                            i--;
                        }
                    }
                }
            });
        });
    };

    $scope.saveLocal = function() { 
        localStorage.setItem("donationCtrl.search", $scope.search);
        localStorage.setItem("donationCtrl.predicate", $scope.predicate);
        localStorage.setItem("donationCtrl.reverse", $scope.reverse);
    };
    
    $scope.exportData = function () {
        var blob = new Blob([document.getElementById('exportable').innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, "Donations.xls");
    };
}]);

cesarApp.controller('menuConfigCtrl', ['$scope', function($scope) {
    $scope.activateFnmUrl = '?controller=builder&action=activateFnm';
    $scope.unactivateFnmUrl = '?controller=builder&action=unactivateFnm';
    
    $scope.activeFnm = function() {
        $.post($scope.activateFnmUrl)
            .success(function(data) {
                $scope.$apply(function($scope) {
                    if (data.status == 'ok') {
                        $scope.fnm = true;
                    }
                });
            });
    }
    
    $scope.removeFnm = function () {
        $.post($scope.unactivateFnmUrl)
            .success(function(data) {
                $scope.$apply(function($scope) {
                    if (data.status == 'ok') {
                        $scope.fnm = false;
                    }
                });
            });
    }
}]);

cesarApp.controller('donationsShortListCtrl', ['$scope', function($scope) {
    $scope.init = function(donations) {
        $scope.donations = donations;
    };
}]);

cesarApp.controller('loginFormCtrl', ['$scope', function($scope) {
  $scope.init = function(getTimesUrl) {
    $scope.getTimesUrl = getTimesUrl;
  };
  
  $scope.deleteDonation = function(id) {
    $.post($scope.getTimesUrl, {'email': email}, function(data) {
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

cesarApp.controller('messagesCtrl', ['$scope', function($scope) {
  $scope.messages = [];
  $scope.index = 0;
  
  $scope.push = function(newMessages) {
    for (var i = 0; i < newMessages.length; ++i) {
        newMessages[i].id = $scope.index++;
        $scope.messages.push(newMessages[i]);
    }  
  };
  
  $scope.remove = function(message) {
    $scope.messages.splice($scope.messages.indexOf(message), 1);
  }
  
  $scope.$on("newMessages", function (event, args) {
    $scope.push(args);
  });
  $scope.$on("clearMessages", function (event, args) {
    $scope.messages.splice(0, $scope.messages.length);
  });
}]);

cesarApp.directive('ngConfirmClick', [function() {
    return {
      priority: 1,
      link: function(scope, element, attr) {
        var msg = attr.ngConfirmClick || "Are you sure?";
        element.bind('click', function(event) {
          if (window.confirm(msg)) {
            scope.$apply(function() {scope.$eval(attr.ngRealClick);});
          }
        });
      }
    };
  }
]);

//Load Data
if (!window.cesar_q || window.cesar_q instanceof Array) {
  // Store old queue
  var oldQueue = window.cesar_q || [];

  // Create new queue
  window.cesar_q = (function () {
    var Push = function () {
      for (var i = 0; i < arguments.length; ++i) {
        var scope = angular.element(document.getElementById(arguments[i].id)).scope();
        scope.$apply(arguments[i].f(scope))
      }
    };
    return {
      push: Push
    };
  })();

  // Merge queues
  $(function() { window.cesar_q.push.apply(window.cesar_q, oldQueue) });
}

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

      var dpt1 = $('#date').datepicker({
			format: 'dd/mm/yyyy'
		}).on('changeDate', function(e) { dpt1.datepicker('hide'); });

      var dpt2 = $('#datealert').datepicker({
			format: 'dd/mm/yyyy'
		}).on('changeDate', function(e) { dpt2.datepicker('hide'); });
});

$(function() {
    $('#actionShow').click(function() {
      if ( $('#textInput').css('display') == 'none' ) {
            $('#textShow').css('display','none');
            $('#textInput').css('display','inline');
      } else
          $.ajax({
            type: 'POST',
            url: '?controller=builder&action=format',
            data: $('#textTrad').val(),
            timeout: 3000,
            success: function(data) {
              $('#textInput').css("display","none");
              $('#textHTML').html(data.formattedText);
              $('#textShow').css("display","inline");
              },
            error: function() {
              alert('La requête n\'a pas abouti'); }
          });
    });
  });

$(function () {
  $('[data-toggle="popover"]').popover()
})

cesarApp.directive('bsPopover', function() {
    return function(scope, element, attrs) {
        element.find("a[data-toggle=popover]").popover();
    };
});

cesarApp.directive("deferredCloak", function () {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {       
            attrs.$set("deferredCloak", undefined);
            element.removeClass("deferred-cloak");
        }
    };
});

$(document).on('change', '.btn-file :file', function() {
    var input = $(this);
    var numFiles = input.get(0).files ? input.get(0).files.length : 1;
    var labels = [];
    if (numFiles == 1) {
        labels.push(input.val().replace(/\\/g, '/').replace(/.*\//, ''));
    } else {
        for (var i = 0; i < input.get(0).files.length; i++) {
            labels.push(input.get(0).files[i].name.replace(/\\/g, '/').replace(/.*\//, ''));
        }
    }
    input.trigger('fileselect', [numFiles, labels]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, labels) {
        if (labels.length == 1)
            $('#FileSelectFeedback').text(labels[0]);
        else
            $('#FileSelectFeedback').text(labels.length + " files");
    });
});

$('#modal-alerts').modal({show: showModal});