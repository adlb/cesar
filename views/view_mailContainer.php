<!DOCTYPE html>
<html lan="<?php disp($obj,'language'); ?>">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"> 
<meta charset="UTF-8">
<title><?php disp($obj,'title'); ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
    <div class="container" style="font-family: Open Sans,sans-serif;">
        <style>p{margin:0;}
        </style>
        <?php renderPartial($obj['innerView'], $obj); ?>
    </div>
</body>
</html>