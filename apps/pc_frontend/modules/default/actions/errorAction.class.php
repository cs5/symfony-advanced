<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * default actions.
 *
 * @package    SfAdvanced
 * @subpackage default
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class errorAction extends sfAction
{
 /**
  * Executes error action
  *
  * @param opWebRequest $request A request object
  */
  public function execute($request)
  {
    if ($request->isSmartphone())
    {
      $this->setLayout('smtLayoutSns');
      $this->setTemplate('smtError');
    }
  }
}
