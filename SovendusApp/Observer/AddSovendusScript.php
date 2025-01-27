<?php

namespace Sovendus\SovendusApp\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;

class AddSovendusScript implements ObserverInterface
{
    protected $layout;

    public function __construct(LayoutInterface $layout)
    {
        $this->layout = $layout;
    }

    public function execute(Observer $observer)
    {
        $layout = $observer->getEvent()->getLayout();
        $block = $layout->createBlock(\Magento\Framework\View\Element\Template::class);
        $block->setTemplate('Sovendus_SovendusApp::sovendus_script.phtml');
        $container = $layout->getBlock('before.body.end');
        if ($container) {
            $container->append($block);
        } else {
            // Log an error or handle the case where the container is not found
            error_log('before.body.end block not found in the layout');
        }
    }
}