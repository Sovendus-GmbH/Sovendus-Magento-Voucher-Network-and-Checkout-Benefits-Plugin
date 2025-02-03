<?php

namespace Sovendus\SovendusApp\ViewModel;

use Magento\Framework\App\ObjectManager;
use Sovendus\SovendusApp\Model\Config;

require_once __DIR__ . '/../sovendus-plugins-commons/settings/get-settings-helper.php';
require_once __DIR__ . '/../sovendus-plugins-commons/helpers/integration-data-helpers.php';
require_once __DIR__ . '/../Constants.php';
require_once __DIR__ . '/helper.php';

class SovendusPage
{
    /**
     * @return string
     */
    public static function get_sovendus_page_settings()
    {
        list($language, $country) = detectLanguage();
        $objectManager = ObjectManager::getInstance();
        $configModel = $objectManager->get(Config::class);
        $encoded_settings = $configModel->getConfig();
        $integrationType = getIntegrationType(\PLUGIN_NAME,  \SOVENDUS_VERSION);
        return <<<EOD
            <script>
                var sovPageConfig = {
                    'settings': JSON.parse('$encoded_settings'),
                    'integrationType': "$integrationType",
                    'country': "$country",
                    'language': "$language",
                };
            </script>
EOD;
    }
}
