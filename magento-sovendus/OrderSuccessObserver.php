<?php
namespace Sovendus\VoucherNetwork\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class OrderSuccessObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $this->registry->register('current_order', $order);
        return $this;
    }
}