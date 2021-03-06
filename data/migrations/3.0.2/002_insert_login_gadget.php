<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class insertLoginGadget extends saMigration
{
  public function up()
  {
    $gadget = new Gadget();
    $gadget->setType('loginTop');
    $gadget->setName('loginForm');
    $gadget->setSortOrder(10);
    $gadget->save();
  }

  public function down()
  {
    $gadgets = array_merge(
      (array)GadgetPeer::retrieveLoginTopGadgets(),
      (array)GadgetPeer::retrieveLoginSideMenuGadgets(),
      (array)GadgetPeer::retrieveLoginContentsGadgets(),
      (array)GadgetPeer::retrieveLoginBottomGadgets()
    );

    foreach ($gadgets as $gadget)
    {
      $gadget->delete();
    }
  }
}
