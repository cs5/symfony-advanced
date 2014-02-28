<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * SnsConfig form.
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class SnsConfigForm extends BaseForm
{
  public function configure()
  {
    $siteConfig = sfConfig::get('sfadvanced_site_config');
    $category = sfConfig::get('sfadvanced_site_category');
    if (empty($category[$this->getOption('category')]))
    {
      return false;
    }

    foreach ($category[$this->getOption('category')] as $configName)
    {
      if (empty($siteConfig[$configName]))
      {
        continue;
      }

      $this->setWidget($configName, saFormItemGenerator::generateWidget($siteConfig[$configName]));
      $this->setValidator($configName, saFormItemGenerator::generateValidator($siteConfig[$configName]));
      $this->widgetSchema->setLabel($configName, $siteConfig[$configName]['Caption']);
      if (isset($siteConfig[$configName]['Help']))
      {
        $this->widgetSchema->setHelp($configName, $siteConfig[$configName]['Help']);
      }

      $value = saConfig::get($configName);
      if ($value instanceof sfOutputEscaperArrayDecorator)
      {
        $value = $value->getRawValue();
      }

      $this->setDefault($configName, $value);
    }

    $this->widgetSchema->setNameFormat('site_config[%s]');
  }

  public function save()
  {
    $config = sfConfig::get('sfadvanced_site_config');
    foreach ($this->getValues() as $key => $value)
    {
      $siteConfig = Doctrine::getTable('SnsConfig')->retrieveByName($key);
      if (!$siteConfig)
      {
        $siteConfig = new SnsConfig();
        $siteConfig->setName($key);
      }
      $siteConfig->setValue($value);
      $siteConfig->save();
    }
  }
}
