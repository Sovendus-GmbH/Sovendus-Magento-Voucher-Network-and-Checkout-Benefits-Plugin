<?php

namespace Sovendus\SovendusApp\ViewModel;

use Sovendus\SovendusApp\Model\Config;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Checkout\Model\Session;

class Order implements ArgumentInterface
{
    /**
     * @var string|null
     */
    /**
     * @var string|null
     */
    public $orderId;
    /**
     * @var string|null
     */
    public $timestamp;
    /**
     * @var string|null
     */
    public $sessionId;
    /**
     * @var string|null
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
    public $consumerStreet;
    /**
     * @var string|null
     */
    public $consumerStreetNumber;
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
        $consumerSData = $order->getShippingAddress()->getData();
        $consumerBData = $order->getBillingAddress()->getData();
        $this->orderId = $order->getIncrementId();
        if (isset($consumerBData["country_id"])) {
            $this->consumerCountry = $consumerBData["country_id"];
        } else if (isset($consumerSData["country_id"])) {
            $this->consumerCountry = $consumerSData["country_id"];
        }
        $this->timestamp = time();
        $grosValue = (float) $order->getGrandTotal();
        $taxValue = (float) $order->getBaseTaxAmount();
        $shippingTax = (float) $order->getBaseShippingTaxAmount();
        $shipping = (float) $order->getBaseShippingInclTax();
        $this->orderValue = $grosValue - $taxValue - $shipping + $shippingTax;
        $this->sessionId = "$this->orderId-$this->orderValue";
        $this->orderCurrency = $order->getOrderCurrencyCode();
        $usedCouponCode = $order->getCouponCode();
        $this->usedCouponCodes = $usedCouponCode ? [$usedCouponCode] : [];
        // TODO
        $this->consumerSalutation = $order->getCustomerGender();
        $this->consumerFirstName = $order->getCustomerFirstName();
        $this->consumerLastName = $order->getCustomerLastName();
        if (isset($consumerSData["email"])) {
            $this->consumerEmail = $consumerSData["email"];
        } else if (isset($consumerBData["email"])) {
            $this->consumerEmail = $consumerBData["email"];
        }
        if (isset($consumerSData["telephone"])) {
            $this->consumerPhone = $consumerSData["telephone"];
        } else if (isset($consumerBData["telephone"])) {
            $this->consumerPhone = $consumerBData["telephone"];
        }
        if (isset($consumerSData["street"])) {
            list($this->consumerStreet, $this->consumerStreetNumber) = $this->splitStreetAndStreetNumber($consumerSData["street"]);
        } else if (isset($consumerBData["street"])) {
            list($this->consumerStreet, $this->consumerStreetNumber) = $this->splitStreetAndStreetNumber($consumerBData["street"]);
        }
        if (isset($consumerSData["postcode"])) {
            $this->consumerZipcode = $consumerSData["postcode"];
        } else if (isset($consumerBData["postcode"])) {
            $this->consumerZipcode = $consumerBData["postcode"];
        }
        if (isset($consumerSData["city"])) {
            $this->consumerCity = $consumerSData["city"];
        } else if (isset($consumerBData["city"])) {
            $this->consumerCity = $consumerBData["city"];
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
     * @param string $street
     * @return array
     */
    function splitStreetAndStreetNumber($street)
    {
        if ((strlen($street) > 0) && preg_match_all('#([0-9/ -]+ ?[a-zA-Z]?(\s|$))#', trim($street), $match)) {
            $housenr = end($match[0]);
            $consumerStreet = trim(str_replace(array($housenr, '/'), '', $street));
            $consumerStreetNumber = trim($housenr);
            return array($consumerStreet, $consumerStreetNumber);
        } else {
            return array($street, "");
        }
    }
}
