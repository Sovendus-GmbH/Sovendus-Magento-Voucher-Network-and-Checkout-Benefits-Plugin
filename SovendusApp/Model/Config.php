<?php

namespace Sovendus\SovendusApp\Model;

use Sovendus\SovendusApp\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

require_once "../sovendus-plugins-commons/settings/get-settings-helper.php";

class Config implements ConfigInterface
{
    protected $settings_keys = new \SettingsKeys(
        uses_lower_case: false,
        newSettingsKey: "sovendus_settings",
        active: "{countryCode}_sovendus_activated",
        trafficSourceNumber: "{countryCode}_sovendus_trafficSourceNumber",
        trafficMediumNumber: "{countryCode}_sovendus_trafficMediumNumber",
        multiLangCountryActive: "{lang}_{countryCode}_sovendus_activated",
        multiLangCountryTrafficSourceNumber: "{lang}_{countryCode}_sovendus_trafficSourceNumber",
        multiLangCountryTrafficMediumNumber: "{lang}_{countryCode}_sovendus_trafficMediumNumber",
    );
    protected $scopeConfig;
    protected $configWriter;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
    }

    public function getConfig()
    {
        return [
            'some_config' => $this->scopeConfig->getValue('sovendus/general/some_config')
        ];
    }

    public function saveConfig($config)
    {
        $this->configWriter->save('sovendus/general/some_config', $config['some_config']);
        return ['success' => true];
    }
}
