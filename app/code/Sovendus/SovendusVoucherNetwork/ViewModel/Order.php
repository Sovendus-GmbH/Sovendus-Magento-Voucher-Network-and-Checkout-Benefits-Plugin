<?php

namespace Sovendus\SovendusVoucherNetwork\ViewModel;

use Sovendus\SovendusVoucherNetwork\Model\Config;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Order implements ArgumentInterface
{
    /**
     * @var AddressConfig
     */
    private $addressConfig;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var FilterProvider
     */
    private $filterProvider;
    /**
     * @var Config
     */
    private $config;


    public $isActive;
    public $orderId;
    public $trafficSourceNumber;
    public $trafficMediumNumber;
    public $timestamp;
    public $sessionId;
    public $orderValue;
    public $orderCurrency;
    public $usedCouponCode;
    public $consumerSalutation;
    public $consumerFirstName;
    public $consumerLastName;
    public $consumerEmail;
    public $consumerPhone;
    public $consumerStreet;
    public $consumerStreetNumber;
    public $consumerZipcode;
    public $consumerCity;
    public $consumerCountry;

    public function __construct(
        AddressConfig $addressConfig,
        ScopeConfigInterface $scopeConfig,
        FilterProvider $filterProvider,
        Config $config
    ) {
        $this->addressConfig = $addressConfig;
        $this->scopeConfig = $scopeConfig;
        $this->filterProvider = $filterProvider;
        $this->config = $config;
    }

    public function initializeSovendusData($block)
    {
        $order = $this->getorder($block);
        $consumerSData = $order->getShippingAddress()->getData();
        $consumerBData = $order->getBillingAddress()->getData();
        if (isset($consumerSData["country_id"])) {
            $this->consumerCountry = $consumerSData["country_id"];
        } else if (isset($consumerBData["country_id"])) {
            $this->consumerCountry = $consumerBData["country_id"];
        }
        list($enabled, $this->trafficSourceNumber, $this->trafficMediumNumber) = $this->config->getSovConfig($this->consumerCountry);
        $this->isActive = $enabled && $this->trafficSourceNumber && $this->trafficMediumNumber;
        if ($this->isActive) {
            $this->timestamp = time();
            $grosValue = (float) $order->getGrandTotal();
            $taxValue = (float) $order->getBaseTaxAmount();
            $shippingTax = (float) $order->getBaseShippingTaxAmount();
            $shipping = (float) $order->getBaseShippingInclTax();
            $this->orderValue = $grosValue - $taxValue - $shipping + $shippingTax;
            $this->sessionId = "$this->orderId-$this->orderValue";
            $this->orderCurrency = $order->getOrderCurrencyCode();
            $this->usedCouponCode = $order->getCouponCode();
            // TODO
            // $this->consumerSalutation = $order->getCustomerGender();
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
    }
    function getorder($block)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->orderId = $block->getOrderId();
        return $objectManager->create("Magento\Sales\Model\Order")->loadByIncrementId($this->orderId);
    }
    function splitStreetAndStreetNumber(string $street)
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