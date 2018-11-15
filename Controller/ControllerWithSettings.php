<?php
/**
 * Created by PhpStorm.
 * User: p.pobelle
 * Date: 15/11/2018
 * Time: 14:10
 */

namespace Pkshetlie\SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class ControllerWithSettings extends Controller
{
    private $setting_service = null;

    /**
     * @param string $name name of the setting
     * @param mixed $default default value to return
     * @return mixed|null|string
     */
    public function getSetting($name, $default = null)
    {
        if(null === $this->setting_service){
            $this->setting_service = $this->get('pkshetlie.settings');
        }
        return $this->setting_service->get($name, $default);
    }

}