<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class SiteConfig extends BaseSiteConfig
{
  protected $siteConfigSettings = array();

  public function construct()
  {
    $this->siteConfigSettings = sfConfig::get('sfadvanced_site_config');
    return parent::construct();
  }

  public function getConfig()
  {
    $name = $this->getName();
    if ($name && isset($this->siteConfigSettings[$name]))
    {
      return $this->siteConfigSettings[$name];
    }

    return false;
  }

  public function getValue()
  {
    $value = $this->_get('value');

    if ($this->isMultipleSelect())
    {
      $value = unserialize($value);
    }

    return $value;
  }

  public function setValue($value)
  {
    if ($this->isMultipleSelect())
    {
      $value = serialize($value);
    }

    $this->_set('value', $value);
  }

  protected function isMultipleSelect()
  {
    $config = $this->getConfig();

    return ('checkbox' === $config['FormType']);
  }
}
