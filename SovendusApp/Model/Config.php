<?php

namespace Sovendus\SovendusApp\Model;

use Sovendus\SovendusApp\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

require_once __DIR__ . "/../sovendus-plugins-commons/settings/get-settings-helper.php";
require_once __DIR__ . "/../Constants.php";

class Config implements ConfigInterface
{
    protected $scopeConfig;
    protected $configWriter;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
    }

    public function getConfig(): string
    {
        $path = \SETTINGS_KEYS->newSettingsKey;

        error_log("[Sovendus Debug] Attempting to read from path: " . $path);

        // Try reading with store scope
        $stored_config = $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            0
        );

        error_log("[Sovendus Debug] Store scope value: " . var_export($stored_config, true));

        if (!$stored_config) {
            // Try default scope
            $stored_config = $this->scopeConfig->getValue(
                $path,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            );
            error_log("[Sovendus Debug] Default scope value: " . var_export($stored_config, true));
        }

        if ($stored_config) {
            return $stored_config;
        }

        // Generate default settings
        $settings = \Get_Settings_Helper::get_settings(
            countryCode: null,
            get_option_callback: function ($key) {
                return $this->scopeConfig->getValue($key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            },
            settings_keys: \SETTINGS_KEYS
        );

        return json_encode($settings);
    }

    public function saveConfig($config): array
    {
        $path = \SETTINGS_KEYS->newSettingsKey;
        error_log("[Sovendus Debug] Saving config to path: " . $path);
        error_log("[Sovendus Debug] Config value: " . $config);

        try {
            $this->configWriter->save(
                $path,
                $config,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                0
            );
            error_log("[Sovendus Debug] Save successful");
            return ['success' => true, "config" => $config];
        } catch (\Exception $e) {
            error_log("[Sovendus Debug] Save failed: " . $e->getMessage());
            throw $e;
        }
    }
}
