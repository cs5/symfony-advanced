<p>
<?php echo link_to(__('Privacy policy'), '@privacy_policy', array('target' => '_blank')); ?> 
<?php echo link_to(__('Terms of service'), '@user_agreement', array('target' => '_blank')); ?> 
<?php $siteConfigSettings = sfConfig::get('sfadvanced_site_config'); ?>
<?php if (saToolkit::isSecurePage()) : ?>
<?php echo Doctrine::getTable('SiteConfig')->get('footer_after', $siteConfigSettings['footer_after']['Default']); ?>
<?php else: ?>
<?php echo Doctrine::getTable('SiteConfig')->get('footer_before', $siteConfigSettings['footer_before']['Default']); ?>
<?php endif; ?>
</p>
