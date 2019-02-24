<?php
/**
 * Created by PhpStorm.
 * User: Hemraj Solanki
 * Date: 2/19/2019
 * Time: 4:00 PM
 */
namespace App\Imports;

use App\Model\ShareInfo;

class ShareImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function contextValue()
    {
      $context = stream_context_create(
          array(
              'http' => array(
                  'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
                  'timeout' => 10000
              ),
          )
      );
      return $context;
    }
}
