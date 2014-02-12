<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saZendMailTransportMock
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
class saZendMailTransportMock extends Zend_Mail_Transport_Abstract
{
  public $EOL = "\n";

  public function _sendMail()
  {
  }
}
