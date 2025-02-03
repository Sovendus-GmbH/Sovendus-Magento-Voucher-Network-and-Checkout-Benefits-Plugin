<?php

namespace Sovendus\SovendusApp;

require_once __DIR__ . '/sovendus-plugins-commons/settings/get-settings-helper.php';

define('PLUGIN_NAME',  'magento');
define('SOVENDUS_VERSION', '2.0.0-beta-2');

define('SETTINGS_KEYS', new \SettingsKeys(
    1,
    true,
    "sovendus/sovendus_settings/general_settings/json_config",
    "sovendusvouchernetwork/{country}_settings/{country}_enable",
    "sovendusvouchernetwork/{country}_settings/{country}_traffic_source_number",
    "sovendusvouchernetwork/{country}_settings/{country}_traffic_medium_number",
    "sovendusvouchernetwork/{country}_{lang}_settings/{country}_{lang}_enable",
    "sovendusvouchernetwork/{country}_{lang}_settings/{country}_{lang}_traffic_source_number",
    "sovendusvouchernetwork/{country}_{lang}_settings/{country}_{lang}_traffic_medium_number"
));
