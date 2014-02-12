<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfValidatorMobileEmail validates mobile emails.
 *
 * @package    SfAdvanced
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfValidatorMobileEmail extends sfValidatorEmail
{
  /**
   * @see sfValidatorRegex
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $filter = create_function('$value', 'return preg_quote($value, \'/\');');
    $str = join('|', array_filter(opToolkit::getMobileMailAddressDomains(), $filter));

    $this->setOption('pattern', '/^([^@\s]+)@('.$str.')$/i');
    $this->setOption('max_length', 320);
  }
}
