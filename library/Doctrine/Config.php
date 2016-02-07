<?php

namespace ManipleCore\Doctrine;

class Config
{
    protected $_paths;

    public function addPath($path)
    {
        $this->_paths[] = $path;
        return $this;
    }

    public function getPaths()
    {
        return $this->_paths;
    }
}
