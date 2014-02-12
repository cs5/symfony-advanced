<?php

/**
 * DesignBannerImageForm
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
 */
class DesignBannerImageForm extends sfForm
{
  protected $key = 'footer_';

  public function configure()
  {
    $type = $this->getOption('type');
    $this->key .= $type;
    $siteConfig = Doctrine::getTable('SnsConfig')->findByName($this->key);

    $this->setWidgets(array(
      $type => new sfWidgetFormTextarea(),
    ));
    $this->setDefault($type, $siteConfig->getValue());
    $this->widgetSchema->setNameFormat('design_footer[%s]');

    $this->setValidators(array(
      'before' => new sfValidatorPass(),
      'after' => new sfValidatorPass(),
    ));
  }

  public function save()
  {
    $type = $this->getOption('type');
    $values = $this->getValues();

    $siteConfig = Doctrine::getTable('SnsConfig')->findByName($this->key);
    if (!$siteConfig)
    {
      $siteConfig = new SnsConfig();
      $siteConfig->setName($this->Key);
    }
    $siteConfig->setValue($values[$type]);
    $siteConfig->save();
  }
}
