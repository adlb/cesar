<?php
include('config.php');

$utest = true;
echo "STARTING UTEST FOR PARAMETER DAL<BR/>";
include('models/parameter_dal.php');
echo "STARTING UTEST FOR ARTICLE DAL<BR/>";
include('models/article_dal.php');
echo "STARTING UTEST FOR TRANSLATOR<BR/>";
include('helpers/translator.php');

?>