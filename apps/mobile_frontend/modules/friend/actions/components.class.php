<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class friendComponents extends saFriendComponents
{
  public function executeFriendListBox(saWebRequest $request)
  {
    $memberId = $this->getUser()->getMemberId();
    if ($request->hasParameter('id'))
    {
      $memberId = $request->getParameter('id');
    }
    $this->member = Doctrine::getTable('Member')->find($memberId);
    $this->row = $this->gadget->getConfig('row');
    $this->friends = $this->member->getFriends($this->row, true);
  }
}
