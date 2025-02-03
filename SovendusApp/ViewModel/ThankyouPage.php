<?php

namespace Sovendus\SovendusApp\ViewModel;

use Magento\Framework\App\ObjectManager;
use Sovendus\SovendusApp\Model\Config;
use Sovendus\SovendusApp\ViewModel\Order;
use Magento\Framework\View\Element\Template;


require_once __DIR__ . '/../sovendus-plugins-commons/settings/get-settings-helper.php';
require_once __DIR__ . '/../sovendus-plugins-commons/helpers/integration-data-helpers.php';
require_once __DIR__ . '/../Constants.php';
require_once __DIR__ . '/helper.php';

class ThankyouPage
{
    /**
     * @param Template $block
     * @return string
     */
    public static function get_thankyou_page_settings($block)
    {

        /** @var $order_data Order */
        $order_data = $block->getData('view_model');
        if (!$order_data instanceof Order) {
            $order_data = ObjectManager::getInstance()->create(Order::class);
        }
        $order_data->initializeOrderData();
        $language = detectLanguage()[0];
        $objectManager = ObjectManager::getInstance();
        $configModel = $objectManager->get(Config::class);
        $encoded_settings = $configModel->getConfig();
        $integrationType = getIntegrationType(\PLUGIN_NAME, \SOVENDUS_VERSION);
        $encoded_coupons = json_encode($order_data->usedCouponCodes);
        return <<<EOD
            <script>
                var sovThankyouConfig = {
                    'settings': JSON.parse('$encoded_settings'),
                    'integrationType': "$integrationType",
                    "sessionId": "$order_data->sessionId",
                    "iframeContainerId": "sovendus-integration-container",
                    "timestamp": "$order_data->timestamp",
                    "orderId": "$order_data->orderId",
                    "orderValue": "$order_data->orderValue",
                    "orderCurrency": "$order_data->orderCurrency",
                    "usedCouponCodes": JSON.parse('$encoded_coupons'),
                    "consumerSalutation": "$order_data->consumerSalutation",
                    "consumerFirstName": "$order_data->consumerFirstName",
                    "consumerLastName": "$order_data->consumerLastName",
                    "consumerEmail": "$order_data->consumerEmail",
                    "consumerStreet": "$order_data->consumerStreet",
                    "consumerStreetNumber": "$order_data->consumerStreetNumber",
                    "consumerZipcode": "$order_data->consumerZipcode",
                    "consumerCity": "$order_data->consumerCity",
                    "consumerCountry": "$order_data->consumerCountry",
                    "consumerLanguage": "$language",
                };
            </script>
EOD;
    }
}
