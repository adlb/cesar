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
        button{
            background-color: #123a13;
            border-color: #3c6699;
            border-radius: 2px;
            transition-delay: 0s;
            transition-duration: 0.2s;
            transition-property: all;
            transition-timing-function: ease-in-out;
            color: #fff;
            -moz-border-colors: none;
            -moz-user-select: none;
            background-image: none;
            border-style: solid;
            border-width: 1px;
            border-image-outset: 0 0 0 0;
            border-image-repeat: stretch stretch;
            border-image-slice: 100% 100% 100% 100%;
            border-image-source: none;
            border-image-width: 1 1 1 1;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: normal;
            line-height: 1.42857;
            margin-bottom: 0;
            padding-bottom: 6px 12px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }</style>
        <?php renderPartial($obj['innerView'], $obj); ?>
    </div>
</body>
</html>