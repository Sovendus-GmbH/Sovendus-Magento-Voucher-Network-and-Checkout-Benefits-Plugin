<?php
namespace Sovendus\VoucherNetwork\Block\Checkout;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order;
use Sovendus\VoucherNetwork\Helper\Settings;

class ThankYou extends Template
{
    private $settings;
    private $order;

    public function __construct(
        Template\Context $context,
        Settings $settings,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->settings = $settings;
    }

    public function setOrder(Order $order)
    {
        $this->order = $order;
        return $this;
    }

    public function getThankYouHtml()
    {
        $country = $this->order->getBillingAddress()->getCountryId();
        $settings = $this->settings->getSettings($country);

        return sovendus_thankyou_page(
            settings: $settings,
            pluginName: 'Magento2',
            pluginVersion: '1.0.0',
            sessionId: $this->order->getQuoteId(),
            timestamp: strtotime($this->order->getCreatedAt()),
            orderId: $this->order->getIncrementId(),
            orderValue: $this->order->getSubtotal() - $this->order->getShippingAmount() - $this->order->getTaxAmount() + $this->order->getShippingTaxAmount(),
            orderCurrency: $this->order->getOrderCurrencyCode(),
            usedCouponCodes: $this->order->getCouponCode() ? [$this->order->getCouponCode()] : [],
            consumerFirstName: $this->order->getBillingAddress()->getFirstname(),
            consumerLastName: $this->order->getBillingAddress()->getLastname(),
            consumerEmail: $this->order->getCustomerEmail(),
            consumerStreetAndNumber: $this->order->getBillingAddress()->getStreet()[0],
            consumerStreetNumber: null,
            consumerStreet: null,
            consumerZipcode: $this->order->getBillingAddress()->getPostcode(),
            consumerCity: $this->order->getBillingAddress()->getCity(),
            consumerCountry: $country,
            consumerLanguage: $this->_localeResolver->getLocale(),
            consumerPhone: $this->order->getBillingAddress()->getTelephone(),
        );
    }
}