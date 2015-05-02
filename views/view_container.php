<!DOCTYPE html>
<html lan="<?php disp($obj,'language'); ?>">
<head>
<title><?php disp($obj,'title'); ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/v4.0.0/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<link rel="stylesheet" href="css/framework.css">
<link rel="stylesheet" href="css/malteLiban.css">
<script src="js/jquery-1.11.2.min.js"></script>
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
    window.setTimeout(function() {getScope(id, f);}, 0);
};
</script>

</head>
<body>
<div class="container" ng-app="cesarApp" ng-cloak>

    <br/>Header
    <?php displayPartial('site', 'menu', null); ?>
    <?php renderPartial('messages', $obj['messages']); ?>
    <?php renderPartial($obj['innerView'], $obj); ?>
    <br />
    <?php displayPartial('site', 'footer', $obj); ?>
    <br />
</div>
</body>
<script src="js/bootstrap.min.js"></script>
<script src="js/uicustom/jquery-ui.min.js"></script>
<script src="js/angular.min.js"></script>
<script src="js/ui-bootstrap-0.12.1.min.js"></script>
<script src="js/smart-table.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/v4.0.0/src/js/bootstrap-datetimepicker.js"></script>
<script src="js/cesar.js"></script>
<?php echo $obj['analytics'];?>
</html>