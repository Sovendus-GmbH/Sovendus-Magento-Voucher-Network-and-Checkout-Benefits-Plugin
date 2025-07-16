<?php

namespace Sovendus\SovendusApp\Api;

interface ConfigInterface
{
    /**
     * Get configuration settings
     *
     * @return string
     */
    public function getConfig();

    /**
     * Save configuration settings
     *
     * @param string $config
     * @return array
     */
    public function saveConfig($config);
}
