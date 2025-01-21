<?php

namespace Sovendus\SovendusApp\ViewModel;

use Magento\Framework\App\ObjectManager;
use Sovendus\SovendusApp\Model\Config;
use Sovendus\SovendusApp\ViewModel\Order;
use Magento\Checkout\Block\Onepage\Success;

require_once __DIR__ . '/../sovendus-plugins-commons/settings/get-settings-helper.php';
require_once __DIR__ . '/../sovendus-plugins-commons/helpers/integration-data-helpers.php';
require_once __DIR__ . '/../Constants.php';

class ThankyouPage
{

    public static function get_thankyou_page_settings(Order $order_data)
    {
        $consumerStreet = null;
        $consumerStreetNumber = null;
        if ($order_data->consumerStreetAndNumber) {
            [$consumerStreet, $consumerStreetNumber] = splitStreetAndStreetNumber($order_data->consumerStreetAndNumber);
        }
        $language = null;
        $objectManager = ObjectManager::getInstance();
        $configModel = $objectManager->get(Config::class);
        $encoded_settings = $configModel->getConfig();
        $integrationType = getIntegrationType(pluginName: PLUGIN_NAME, pluginVersion: SOVENDUS_VERSION);
        return <<<EOD
        <script>
            var sovPageConfig = {
                'settings': JSON.parse('$encoded_settings'),
                'integrationType': "$integrationType",
                "sessionId": "$order_data->sessionId",
                "iframeContainerId": "sovendus-integration-container",
                "timestamp": "$order_data->timestamp",
                "orderId": "$order_data->orderId",
                "orderValue": "$order_data->orderValue",
                "orderCurrency": "$order_data->orderCurrency",
                "usedCouponCodes": "$order_data->usedCouponCodes",
                "consumerSalutation": "$order_data->consumerSalutation",
                "consumerFirstName": "$order_data->consumerFirstName",
                "consumerLastName": "$order_data->consumerLastName",
                "consumerEmail": "$order_data->consumerEmail",
                "consumerPhone": "$order_data->consumerPhone",
                "consumerStreetNumber": "$consumerStreetNumber",
                "consumerStreet": "$consumerStreet",
                "consumerZipcode": "$order_data->consumerZipcode",
                "consumerCity": "$order_data->consumerCity",
                "consumerCountry": "$order_data->consumerCountry",
                "consumerLanguage": "$language",
            }
        </script>
        EOD;
    }
}
