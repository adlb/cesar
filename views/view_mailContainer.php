<!DOCTYPE html>
<html lan="<?php disp($obj,'language'); ?>">
<head>
<title><?php disp($obj,'title'); ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
    <div class="container">
        <?php renderPartial($obj['innerView'], $obj); ?>
    </div>
</body>
</html>