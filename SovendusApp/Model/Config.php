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
        $settings = \Get_Settings_Helper::get_settings(
            countryCode: null,
            get_option_callback: function ($key) {
                $value = $this->scopeConfig->getValue($key);
                error_log("[Sovendus Debug] Fetching config key: $key, value: " . json_encode($value));
                return $value;
            },
            settings_keys: SETTINGS_KEYS
        );
        return json_encode($settings);
    }

    public function saveConfig($config): array
    {
        // TODO: Validate config
        error_log("[Sovendus Debug] Saving config: " . json_encode($config));
        $this->configWriter->save(SETTINGS_KEYS->newSettingsKey, $config);
        return ['success' => true];
    }
}
