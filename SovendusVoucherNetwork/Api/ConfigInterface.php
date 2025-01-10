<?php

namespace Sovendus\SovendusApp\Api;

interface ConfigInterface
{
    /**
     * Get configuration settings
     *
     * @return mixed
     */
    public function getConfig();

    /**
     * Save configuration settings
     *
     * @param mixed $config
     * @return mixed
     */
    public function saveConfig($config);
}
