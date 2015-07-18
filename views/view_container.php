<!DOCTYPE html>
<head>
	<title><?php echo $obj['title'] ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Responsive Multipurpose Bootstrap Theme">
	<meta name="author" content="The Develovers">

	<!-- CSS -->
	<link href="templates/Repute/theme/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="templates/Repute/theme/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="templates/Repute/theme/assets/css/main.css" rel="stylesheet" type="text/css">
	<link href="css/framework.css" rel="stylesheet" type="text/css">

	<!-- IE 9 Fallback-->
	<!--[if IE 9]>
		<link href="templates/Repute/theme/assets/css/ie.css" rel="stylesheet">
	<![endif]-->

	<!-- GOOGLE FONTS -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400italic,400,600,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,300italic,400italic,700,400,300' rel='stylesheet' type='text/css'>

	<!-- FAVICONS -->
	<link rel="shortcut icon" href="templates/Repute/theme/assets/ico/favicon.png?v=2">
</head>

<body>
	<!-- WRAPPER -->
	<div class="wrapper" ng-app="cesarApp">
		<!-- NAVBAR -->
		<nav class="navbar navbar-default" role="navigation">
			<div class="container">
				<?php displayPartial('site', 'menu', null); ?>
			</div>
		</nav>
		<!-- END NAVBAR -->

		<?php renderPartial('messages', $obj['messages']); ?>
    
        <?php renderPartial($obj['innerView'], $obj); ?>
    </div>
	
    <?php displayPartial('site', 'footer', $obj); ?>
    <!-- END WRAPPER --><!-- JAVASCRIPTS -->
	<script src="templates/Repute/theme/assets/js/jquery-2.1.1.min.js"></script>
	<script src="templates/Repute/theme/assets/js/bootstrap.min.js"></script>
	<script src="templates/Repute/theme/assets/js/plugins/slick/slick.min.js"></script>
	<script src="templates/Repute/theme/assets/js/plugins/stellar/jquery.stellar.min.js"></script>
	<script src="templates/Repute/theme/assets/js/plugins/jquery-easypiechart/jquery.easypiechart.min.js"></script>
	<script src="templates/Repute/theme/assets/js/plugins/autohidingnavbar/jquery.bootstrap-autohidingnavbar.min.js"></script>
    <script src="templates/Repute/theme/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
	<script src="templates/Repute/theme/assets/js/repute-scripts.js"></script>
    <script src="js/uicustom/jquery-ui.min.js"></script>
    <script src="js/angular.min.js"></script>
    <script src="js/ui-bootstrap-0.12.1.min.js"></script>
    <script src="js/smart-table.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/v4.0.0/src/js/bootstrap-datetimepicker.js"></script>
    <script src="js/cesar.js"></script>
</body>
</html>
