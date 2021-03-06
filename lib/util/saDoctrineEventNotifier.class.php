<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saDoctrineEventNotifier
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Eitarow Fukamachi <fukamachi@tejimaya.com>
 */
class saDoctrineEventNotifier extends Doctrine_Record_Listener
{
  protected static function notify($when, $action, $doctrineEvent)
  {
    if (!sfContext::hasInstance())
    {
      return null;
    }

    $dispatcher = sfContext::getInstance()->getEventDispatcher();
    $dispatcher->notify(new saDoctrineEvent($doctrineEvent, $when, $action));
  }

  public function preSave(Doctrine_Event $event)
  {
    self::notify('pre', 'save', $event);
  }

  public function postSave(Doctrine_Event $event)
  {
    self::notify('post', 'save', $event);
  }

  public function preUpdate(Doctrine_Event $event)
  {
    self::notify('pre', 'update', $event);
  }

  public function postUpdate(Doctrine_Event $event)
  {
    self::notify('post', 'update', $event);
  }

  public function preInsert(Doctrine_Event $event)
  {
    self::notify('pre', 'insert', $event);
  }

  public function postInsert(Doctrine_Event $event)
  {
    self::notify('post', 'insert', $event);
  }

  public function preDelete(Doctrine_Event $event)
  {
    self::notify('pre', 'delete', $event);
  }

  public function postDelete(Doctrine_Event $event)
  {
    self::notify('post', 'delete', $event);
  }

  public function preValidate(Doctrine_Event $event)
  {
    self::notify('pre', 'validate', $event);
  }

  public function postValidate(Doctrine_Event $event)
  {
    self::notify('post', 'validate', $event);
  }
}
