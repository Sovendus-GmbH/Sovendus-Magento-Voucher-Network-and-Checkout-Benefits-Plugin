<?php

namespace Sovendus\VoucherNetwork\Helper;

use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class AdminNotices extends AbstractHelper
{
    private $messageManager;

    public function __construct(
        Context $context,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->messageManager = $messageManager;
    }

    public function addInstallNotice(): void
    {
        $this->messageManager->addError(
            __('Sovendus requires an active Magento installation. Please ensure Magento is properly installed and configured.')
        );
    }

    public function addMultistoreNotice(): void
    {
        $this->messageManager->addError(
            __('Sovendus requires proper configuration for all stores. Please configure Sovendus settings for all store views.')
        );
    }
}