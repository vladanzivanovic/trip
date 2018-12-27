<?php


namespace CoreBundle\Extensions;


use CoreBundle\Core\Container;

class ParameterExtension extends Container
{
    public function getParameter($name, $prop = null)
    {
        if(null !== $prop) {
            $props = $this->getContainer()->getParameter($name)[$prop];

            return $this->getReferencedValue($props);
        }
        $props = $this->getContainer()->getParameter($name);

        return $this->getReferencedValue($props);
    }

    private function getReferencedValue($data)
    {
        if(false === is_array($data)) {
            return $this->getProp($data);
        }

        foreach ($data as $prop => &$value) {
            if (is_array($value)) {
                $this->getReferencedValue($value);
                continue;
            }
            $value = $this->getProp($value);
        }
        unset($value);

        return $data;
    }

    private function getProp($value)
    {
        if (false !== strpos($value, '%')) {
            $value = $this->getParameter(str_replace('%', '', $value));
        }

        return $value;
    }

}