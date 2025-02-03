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
    public $orderId;

    /**
     * @var int|null
     */
    public $timestamp;

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
        $consumerSAddress = $order->getShippingAddress();
        $consumerBAddress = $order->getBillingAddress();

        $consumerSData = $consumerSAddress ? $consumerSAddress->__toArray() : array();
        $consumerBData = $consumerBAddress ? $consumerBAddress->__toArray() : array();

        $this->orderId = $order->getIncrementId();

        $this->timestamp = (string)time();
        $grosValue = (float)$order->getGrandTotal();
        $taxValue = (float)$order->getBaseTaxAmount();
        $shippingTax = (float)$order->getBaseShippingTaxAmount();
        $shipping = (float)$order->getBaseShippingInclTax();
        $this->orderValue = (string)($grosValue - $taxValue - $shipping + $shippingTax);

        $this->sessionId = "$this->orderId-$this->orderValue";
        $this->orderCurrency = $order->getOrderCurrencyCode();
        $usedCouponCode = $order->getCouponCode();
        $this->usedCouponCodes = $usedCouponCode ? [$usedCouponCode] : [];
        $gender = $order->getCustomerGender();
        $this->consumerSalutation = $this->convertGenderToSalutation($gender);
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
     * Convert Magento gender code to salutation string
     * 
     * @param int|null $genderCode
     * @return string|null
     */
    private function convertGenderToSalutation(?int $genderCode): ?string
    {
        if ($genderCode === null) {
            return null;
        }

        return match ($genderCode) {
            1 => 'Mr.',
            2 => 'Mrs.',
            default => null
        };
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
