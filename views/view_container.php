<!DOCTYPE html>
<html lan="<?php disp($obj,'language'); ?>">
<head>
<title><?php disp($obj,'title'); ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/framework.css">
<link rel="stylesheet" href="css/malteLiban.css">
<script>
var getScope = function(id, f) {
	if (typeof angular != 'undefined') {
		elm = angular.element(document.getElementById(id));
		if (typeof elm != 'undefined') {
			scope = elm.scope();
			if (typeof scope != 'undefined') {
				scope.$apply(f(scope));
				return;
			} 
		}
	}
	window.setTimeout(function() getScope(id, f), 0);
};
</script>

</head>
<body>
<div class="container" ng-app="cesarApp">
  
<br/>Header
<?php displayPartial('site', 'menu', $obj); ?>
<?php if (isset($obj['errors'])) { renderPartial('errors', $obj['errors']); } ?>
<?php displayPartial('site', 'alerts', $obj); ?>
<?php renderPartial($obj['innerView'], $obj); ?>
<br/>Footer
<br />
<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample">
  Debug information
</button>
<div class="collapse" id="collapseExample">
  <div class="well">
    <?php var_dump($obj); ?>
  </div>
</div>

</div>
</body>
<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/uicustom/jquery-ui.min.js"></script>
<script src="js/angular.min.js"></script>
<script src="js/ui-bootstrap-0.12.1.min.js"></script>
<script src="js/smart-table.min.js"></script>
<script src="js/cesar.js"></script>
</html>