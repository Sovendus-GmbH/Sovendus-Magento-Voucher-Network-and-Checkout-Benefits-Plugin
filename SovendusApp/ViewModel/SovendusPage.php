<?php

namespace Sovendus\SovendusApp\ViewModel;

use Magento\Framework\App\ObjectManager;
use Sovendus\SovendusApp\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

require_once __DIR__ . '/../Model/Constants.php';

class SovendusPage
{
    /**
     * @return string
     */
    public static function get_sovendus_page_settings()
    {
        $objectManager = ObjectManager::getInstance();
        $scopeConfig = $objectManager->get(ScopeConfigInterface::class);
        $locale = $scopeConfig->getValue('general/locale/code', ScopeInterface::SCOPE_STORE);
        $localeParts = explode('_', $locale);
        $language = strtoupper($localeParts[0]); // e.g., 'EN'
        $country = $localeParts[1];   // e.g., 'US'

        $configModel = $objectManager->get(Config::class);
        $encoded_settings = $configModel->getConfig();
        $integrationType = INTEGRATION_TYPE;
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
