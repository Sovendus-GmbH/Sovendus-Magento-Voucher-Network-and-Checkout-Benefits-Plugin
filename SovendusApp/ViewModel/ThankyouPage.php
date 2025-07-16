<?php

namespace Sovendus\SovendusApp\ViewModel;

use Magento\Framework\App\ObjectManager;
use Sovendus\SovendusApp\Model\Config;
use Magento\Framework\View\Element\Template;


require_once __DIR__ . '/../Model/Constants.php';
require_once __DIR__ . '/../Model/Helper.php';

class ThankyouPage
{
    /**
     * @param Template $block
     * @return string
     */
    public static function get_thankyou_page_settings($block)
    {
        /** @var \Sovendus\SovendusApp\Model\Order $order_data */
        $order_data = $block->getData('view_model');

        $order_data->initializeOrderData();
        // Get language from helper function
        $language = \Sovendus\SovendusApp\Model\detectLanguage()[0];
        $encoded_settings = ObjectManager::getInstance()->get(Config::class)->getConfig();

        // Create order data structure
        $orderData = [
            'orderId' => $order_data->orderId,
            'orderValue' => [
                'netOrderValue' => $order_data->orderValue
            ],
            'orderCurrency' => $order_data->orderCurrency,
            'sessionId' => $order_data->sessionId,
            'usedCouponCodes' => $order_data->usedCouponCodes
        ];

        // Create customer data structure
        $customerData = [
            'consumerSalutation' => $order_data->consumerSalutation,
            'consumerFirstName' => $order_data->consumerFirstName,
            'consumerLastName' => $order_data->consumerLastName,
            'consumerEmail' => $order_data->consumerEmail,
            'consumerStreetWithNumber' => $order_data->consumerStreetWithNumber,
            'consumerZipcode' => $order_data->consumerZipcode,
            'consumerCity' => $order_data->consumerCity,
            'consumerCountry' => $order_data->consumerCountry,
            'consumerLanguage' => $language
        ];


        // Create the JavaScript configuration
        $jsConfig = [
            'settings' => json_decode($encoded_settings),
            'iframeContainerQuerySelector' => [
                'selector' => ".page-title-wrapper",
                'where' => "afterend"
            ],
            'integrationType' => INTEGRATION_TYPE,
            'orderData' => $orderData,
            'customerData' => $customerData
        ];

        // Create the script tag for configuration only
        return '<script type="text/javascript">
            window.sovThankyouConfig = ' . json_encode($jsConfig) . ';
        </script>';
    }
}
