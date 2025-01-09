<?php

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

require_once plugin_dir_path(file: __FILE__) . '../sovendus-plugins-commons/settings/app-settings.php';
require_once plugin_dir_path(file: __FILE__) . '../sovendus-plugins-commons/settings/sovendus-countries.php';

class Magento_Sovendus_Helper
{

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfig($path, $scopeCode = null, $scopeType = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue($path, $scopeType, $scopeCode);
    }

    public function get_settings(string|null $countryCode): Sovendus_App_Settings
    {
        $settingsJson = $this->getConfig("sovendusvouchernetwork/{$countryCode}_settings");
        if ($settingsJson) {
            return Sovendus_App_Settings::fromJson($settingsJson);
        } else {
            $anyCountryEnabled = true; // TODO
            $settings = new Sovendus_App_Settings(
                voucherNetwork: new VoucherNetwork(
                    anyCountryEnabled: $anyCountryEnabled,
                ),
                optimize: new Optimize(
                    useGlobalId: true,
                    globalId: null,
                    globalEnabled: false,
                    countrySpecificIds: [],
                ),
                checkoutProducts: false,
                version: Versions::TWO,
            );
            $countries = $countryCode
                ? [$countryCode => LANGUAGES_BY_COUNTRIES[$countryCode]]
                : LANGUAGES_BY_COUNTRIES;
            foreach ($countries as $countryKey => $countryData) {
                $countriesLanguages = array_keys($countryData);
                $settings->voucherNetwork->addCountry(
                    countryCode: CountryCodes::from($countryKey),
                    country: new VoucherNetworkCountry(
                        languages: count(LANGUAGES_BY_COUNTRIES[$countryKey]) > 1
                        ? self::get_multilang_country_settings(countryCode: $countryKey, langs: $countriesLanguages)
                        : self::get_country_settings(countryCode: $countryKey, lang: $countriesLanguages[0])
                    )
                );
            }
            return $settings;
        }
    }

    private function get_country_settings($countryCode, $lang)
    {
        $sovendusActive = $this->getConfig("{$countryCode}_enable");
        $trafficSourceNumber = (int) $this->getConfig("{$countryCode}_traffic_source_number");
        $trafficMediumNumber = (int) $this->getConfig("{$countryCode}_traffic_medium_number");
        return [
            $lang => new VoucherNetworkLanguage(
                isEnabled: $sovendusActive === "yes" && $trafficSourceNumber && $trafficMediumNumber ? true : false,
                trafficSourceNumber: $trafficSourceNumber,
                trafficMediumNumber: $trafficMediumNumber,
            )
        ];
    }

    private function get_multilang_country_settings($countryCode, $langs)
    {
        $languageSettings = [];
        foreach ($langs as $lang) {
            $languageSettings[$lang] = new VoucherNetworkLanguage(
                isEnabled: $this->getConfig("{$countryCode}_{$lang}_enable"),
                trafficSourceNumber: (int) $this->getConfig("{$countryCode}_{$lang}_traffic_source_number"),
                trafficMediumNumber: (int) $this->getConfig("{$countryCode}_{$lang}_traffic_medium_number"),
            );
        }
        return $languageSettings;
    }
}