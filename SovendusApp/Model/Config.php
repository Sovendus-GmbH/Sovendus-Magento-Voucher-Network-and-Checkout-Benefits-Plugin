<?php

namespace Sovendus\SovendusApp\Model;

use Sovendus\SovendusApp\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;


class Config implements ConfigInterface
{
    const SETTINGS_KEY = "sovendus/sovendus_settings/general_settings/json_config";
    private $scopeConfig;
    private $configWriter;
    private $cacheTypeList;
    private $cacheFrontendPool;

    /**
     * @return void
     */

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
    }

    /**
     * @return string
     */
    public function getConfig()
    {
        return $this->scopeConfig->getValue($this::SETTINGS_KEY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param string $config
     * @return array
     */
    public function saveConfig($config)
    {
        $this->configWriter->save($this::SETTINGS_KEY, $config);
        $this->flushCache();
        return ['success' => true];
    }

    /**
     * @return void
     */
    private function flushCache()
    {
        $types = ['config', 'full_page'];
        foreach ($types as $type) {
            $this->cacheTypeList->cleanType($type);
        }
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
