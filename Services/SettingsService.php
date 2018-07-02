<?php

namespace Pkshetlie\SettingsBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Pkshetlie\SettingsBundle\Entity\Setting;

class SettingsService
{
    /**
     * @var mixed[]
     */
    protected static $_get = [];

    /** @var EntityManager */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed|null|string
     */
    public function get(string $name, mixed $default = null)
    {
        if (isset(self::$_get[$name])) return self::$_get[$name];

        /** @var Setting $setting */
        $setting = $this->em->getRepository('SettingsBundle:Setting')->findOneBy(['name' => $name]);
        self::$_get[$name] = $setting != null ? $setting->getValue() : $default;

        return self::$_get[$name];
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, mixed $value)
    {
        /** @var Setting $setting */
        $setting = $this->em->getRepository('SettingsBundle:Setting')->findOneBy(['name' => $name]);
        if (null === $setting) {
            $setting = new Setting();
            $setting->setName($name);
        }
        $setting->setValue($value);
        $this->em->persist($setting);
        $this->em->flush();

        if (isset(self::$_get[$name])) self::$_get[$name] = $value;
    }
}