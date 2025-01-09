<?php

namespace Sovendus\VoucherNetwork\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;

class StoreManager extends AbstractHelper
{
    private $storeManager;
    private $originalStore;

    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    public function isMultiStore(): bool
    {
        return count($this->storeManager->getStores()) > 1;
    }

    public function getStores(): array
    {
        return $this->storeManager->getStores();
    }

    public function switchToStore($storeId): void
    {
        $this->originalStore = $this->storeManager->getStore()->getId();
        $this->storeManager->setCurrentStore($storeId);
    }

    public function restoreOriginalStore(): void
    {
        if ($this->originalStore !== null) {
            $this->storeManager->setCurrentStore($this->originalStore);
            $this->originalStore = null;
        }
    }
}