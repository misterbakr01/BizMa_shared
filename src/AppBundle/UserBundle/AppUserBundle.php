<?php
/**
 * Created by PhpStorm.
 * User: Bakr
 * Date: 4/3/2016
 * Time: 4:01 PM
 */
namespace App\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}