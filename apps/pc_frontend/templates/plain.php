<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<title><?php echo ($sa_config['sns_title']) ? $sa_config['sns_title'] : $sa_config['sns_name'] ?></title>
<?php use_stylesheet('/cache/css/customizing.css') ?>
<?php include_stylesheets() ?>
<?php include_javascripts() ?>
</head>
<body>

<?php echo $sf_content ?>

</body>
</html>
