<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */
class BannerUseImageTable extends Doctrine_Table
{
  public function retrieveByBannerAndImageId($bannerId, $bannerImageId)
  {
    return $this->createQuery()
      ->where('banner_id = ?', $bannerId)
      ->addWhere('banner_image_id = ?', $bannerImageId)
      ->fetchOne();
  }
}
