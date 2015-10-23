<?php

namespace ManipleCore;

class Module
{
    public function getConfig()
    {
        return require __DIR__ . '/configs/resources.config.php';
    }

    public function getAssetsBaseDir()
    {
        return 'core';
    }
}