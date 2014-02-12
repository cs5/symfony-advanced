<?php $siteName = $sa_config['site_name'] ?>
<?php echo __('Hello! This is information from %1%.', array('%1%' => $siteName)) ?>

<?php echo __('If you register(free) of member by the following URL,%br%you can participate in %1%.', array('%1%' => $siteName, '%br%' => "\n")) ?>

<?php echo __('* Participate in %1%', array('%1%' => $siteName)) ?>

<?php echo app_url_for('mobile_frontend', sprintf('saAuthMailAddress/register?token=%s&authMode=%s', $token, $authMode), true) ?>
