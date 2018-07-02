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
    public function get(string $name, $default = null)
    {
        if (isset(self::$_get[$name])) return self::$_get[$name];

        /** @var Setting $setting */
        $setting = $this->em->getRepository('SettingsBundle:Setting')->findOneBy(['name' => $name]);
        self::$_get[$name] = $setting != null ? $setting->getValue() : $default;
        $data = @unserialize(self::$_get[$name]);
        if (self::$_get[$name] === 'b:0;' || $data !== false) {
            self::$_get[$name] = $data;
        }
        return self::$_get[$name];
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function set(string $name, $value)
    {
        /** @var Setting $setting */
        $setting = $this->em->getRepository('SettingsBundle:Setting')->findOneBy(['name' => $name]);
        if (null === $setting) {
            $setting = new Setting();
            $setting->setName($name);
        }
        if(is_array($value) || is_object($value)){
            $value = serialize($value);
        }
        $setting->setValue($value);
        $this->em->persist($setting);
        $this->em->flush();

        if (isset(self::$_get[$name])) self::$_get[$name] = $value;
    }
}