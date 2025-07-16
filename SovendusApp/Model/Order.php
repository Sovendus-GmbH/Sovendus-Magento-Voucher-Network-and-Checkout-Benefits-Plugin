<?php

namespace Sovendus\SovendusApp\Model;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Checkout\Model\Session;

class Order implements ArgumentInterface
{
    /**
     * @var string|null
     */
    public $orderId;


    /**
     * @var string|null
     */
    public $sessionId;

    /**
     * @var float|null
     */
    public $orderValue;

    /**
     * @var string|null
     */
    public $orderCurrency;
    /**
     * @var array
     */
    public $usedCouponCodes;
    /**
     * @var string|null
     */
    public $consumerSalutation;
    /**
     * @var string|null
     */
    public $consumerFirstName;
    /**
     * @var string|null
     */
    public $consumerLastName;
    /**
     * @var string|null
     */
    public $consumerEmail;
    /**
     * @var string|null
     */
    public $consumerPhone;
    /**
     * @var string|null
     */
    public $consumerStreetWithNumber;
    /**
     * @var string|null
     */
    public $consumerZipcode;
    /**
     * @var string|null
     */
    public $consumerCity;
    /**
     * @var string|null
     */
    public $consumerCountry;
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @return array
     */
    public function __construct(
        Session $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
    }


    /**
     * @return void
     */
    public function initializeOrderData()
    {
        $order = $this->getorder();
        $consumerSAddress = $order->getShippingAddress();
        $consumerBAddress = $order->getBillingAddress();

        $consumerSData = ($consumerSAddress instanceof \Magento\Sales\Model\Order\Address) ? $consumerSAddress->getData() : array();
        $consumerBData = ($consumerBAddress instanceof \Magento\Sales\Model\Order\Address) ? $consumerBAddress->getData() : array();

        $this->orderId = $order->getIncrementId();

        $grosValue = (float)$order->getGrandTotal();
        $taxValue = (float)$order->getBaseTaxAmount();
        $shippingTax = (float)$order->getBaseShippingTaxAmount();
        $shipping = (float)$order->getBaseShippingInclTax();
        $this->orderValue = (float)($grosValue - $taxValue - $shipping + $shippingTax);

        $this->sessionId = "$this->orderId-$this->orderValue";
        $this->orderCurrency = $order->getOrderCurrencyCode();
        $usedCouponCode = $order->getCouponCode();
        $this->usedCouponCodes = $usedCouponCode ? [$usedCouponCode] : [];


        $this->consumerCountry = $consumerBAddress ?
            $consumerBAddress->getCountryId() : ($consumerSAddress ?
                $consumerSAddress->getCountryId() :
                ''
            );
        $gender = $order->getCustomerGender();
        $this->consumerSalutation = $this->convertGenderToSalutation($gender);
        $this->consumerFirstName = $order->getCustomerFirstName();
        $this->consumerLastName = $order->getCustomerLastName();
        if (isset($consumerBData["email"])) {
            $this->consumerEmail = $consumerBData["email"];
        } else if (isset($consumerSData["email"])) {
            $this->consumerEmail = $consumerSData["email"];
        }
        if (isset($consumerBData["telephone"])) {
            $this->consumerPhone = $consumerBData["telephone"];
        } else if (isset($consumerSData["telephone"])) {
            $this->consumerPhone = $consumerSData["telephone"];
        }
        if (isset($consumerBData["street"])) {
            $this->consumerStreetWithNumber = $consumerBData["street"];
        } else if (isset($consumerSData["street"])) {
            $this->consumerStreetWithNumber = $consumerSData["street"];
        }
        if (isset($consumerBData["postcode"])) {
            $this->consumerZipcode = $consumerBData["postcode"];
        } else if (isset($consumerSData["postcode"])) {
            $this->consumerZipcode = $consumerSData["postcode"];
        }
        if (isset($consumerBData["city"])) {
            $this->consumerCity = $consumerBData["city"];
        } else if (isset($consumerSData["city"])) {
            $this->consumerCity = $consumerSData["city"];
        }
    }

    /**
     * @return \Magento\Sales\Model\Order
     */
    function getorder()
    {
        return $this->checkoutSession->getLastRealOrder();
    }

    /**
     * Convert Magento gender code to salutation string
     * 
     * @param int|null $genderCode
     * @return string|null
     */
    private function convertGenderToSalutation($genderCode)
    {
        if ($genderCode === null) {
            return null;
        }
        switch ($genderCode) {
            case 1:
                return 'Mr.';
            case 2:
                return 'Mrs.';
            default:
                return null;
        }
    }
}
