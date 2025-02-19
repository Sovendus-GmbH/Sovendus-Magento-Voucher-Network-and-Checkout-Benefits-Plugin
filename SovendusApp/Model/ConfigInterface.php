<?php

namespace Sovendus\SovendusApp\Model;

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
