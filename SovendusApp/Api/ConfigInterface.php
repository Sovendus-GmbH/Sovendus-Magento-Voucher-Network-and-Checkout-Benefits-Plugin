<?php

namespace Sovendus\SovendusApp\Api;

interface ConfigInterface
{
    /**
     * Get configuration settings
     *
     * @return array
     */
    public function getConfig(): string;

    /**
     * Save configuration settings
     *
     * @param string $config
     * @return array
     */
    public function saveConfig($config);
}
