<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfMessageSource_SfAdvancedCached
 *
 * @package    SfAdvanced
 * @subpackage i18n
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
 */
class sfMessageSource_SfAdvancedCached extends sfMessageSource_File
{
  protected $dataExt = '.xml.php';

  public function getCatalogueList($catalogue)
  {
    $variants = explode('_', $this->culture);
    $base = $catalogue.$this->dataSeparator;

    return array(
      $base.$variants[0].$this->dataExt, $base.$this->culture.$this->dataExt,
    );
  }

  public function save($catalogue = 'messages')
  {
    return true;
  }

  public function delete($message, $catalogue='messages')
  {
    return true;
  }

  public function update($text, $target, $comments, $catalogue = 'messages')
  {
    return true;
  }

  public function &loadData($variant)
  {
    $load = include($variant);

    return $load;
  }
}
