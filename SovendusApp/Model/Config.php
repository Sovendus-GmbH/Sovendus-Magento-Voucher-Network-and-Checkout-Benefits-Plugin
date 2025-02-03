<?php

namespace Sovendus\SovendusApp\Model;

use Sovendus\SovendusApp\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

require_once __DIR__ . "/../sovendus-plugins-commons/settings/get-settings-helper.php";
require_once __DIR__ . "/../sovendus-plugins-commons/settings/app-settings.php";
require_once __DIR__ . "/../Constants.php";

class Config implements ConfigInterface
{
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
        $settings = \Get_Settings_Helper::get_settings(
            null,
            function ($key) {
                return $this->scopeConfig->getValue($key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            },
            \SETTINGS_KEYS
        );
        // TODO handle custom hooks
        $settings->voucherNetwork->iframeContainerId = ".page.messages";

        return json_encode($settings);
    }

    /**
     * @param string $config
     * @return array
     */
    public function saveConfig($config)
    {
        $decodedConfig = json_decode($config, true);
        $validated_settings = \Sovendus_App_Settings::fromJson($decodedConfig);

        $this->configWriter->save(\SETTINGS_KEYS->newSettingsKey, json_encode($validated_settings));
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
