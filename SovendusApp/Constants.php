<?php

namespace Sovendus\SovendusApp;

require_once __DIR__ . '/sovendus-plugins-commons/settings/get-settings-helper.php';

define(constant_name: 'PLUGIN_NAME', value: 'magento');
define('SOVENDUS_VERSION', '2.0.0-beta-2');

define(constant_name: 'SETTINGS_KEYS', value: new \SettingsKeys(
    active_value: 1,
    uses_lower_case: true,
    newSettingsKey: "sovendus/sovendus_settings/general_settings/json_config",
    active: "sovendusvouchernetwork/{country}_settings/{country}_enable",
    trafficSourceNumber: "sovendusvouchernetwork/{country}_settings/{country}_traffic_source_number",
    trafficMediumNumber: "sovendusvouchernetwork/{country}_settings/{country}_traffic_medium_number",
    multiLangCountryActive: "sovendusvouchernetwork/{country}_{lang}_settings/{country}_{lang}_enable",
    multiLangCountryTrafficSourceNumber: "sovendusvouchernetwork/{country}_{lang}_settings/{country}_{lang}_traffic_source_number",
    multiLangCountryTrafficMediumNumber: "sovendusvouchernetwork/{country}_{lang}_settings/{country}_{lang}_traffic_medium_number",
));
