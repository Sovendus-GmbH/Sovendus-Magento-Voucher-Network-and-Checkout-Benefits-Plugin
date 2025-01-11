<?php

namespace Sovendus\SovendusApp\Model;

use Sovendus\SovendusApp\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

require_once __DIR__ . "/../sovendus-plugins-commons/settings/get-settings-helper.php";


class Config implements ConfigInterface
{
    protected $settings_keys;
    protected $scopeConfig;
    protected $configWriter;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter
    ) {
        $this->settings_keys = new \SettingsKeys(
            active_value: 1,
            uses_lower_case: false,
            newSettingsKey: "sovendus_settings",
            active: "{countryCode}_sovendus_activated",
            trafficSourceNumber: "{countryCode}_sovendus_trafficSourceNumber",
            trafficMediumNumber: "{countryCode}_sovendus_trafficMediumNumber",
            multiLangCountryActive: "{lang}_{countryCode}_sovendus_activated",
            multiLangCountryTrafficSourceNumber: "{lang}_{countryCode}_sovendus_trafficSourceNumber",
            multiLangCountryTrafficMediumNumber: "{lang}_{countryCode}_sovendus_trafficMediumNumber",
        );
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
            settings_keys: $this->settings_keys
        );
        return json_encode($settings);
    }

    public function saveConfig($config): array
    {
        error_log("[Sovendus Debug] Saving config: " . json_encode($config));
        $this->configWriter->save($this->settings_keys->newSettingsKey, $config);
        return ['success' => true];
    }
}
