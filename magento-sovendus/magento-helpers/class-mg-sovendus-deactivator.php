<?php

namespace Sovendus\VoucherNetwork\Model;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class Deactivator
{
    private $storeManager;
    private $configWriter;
    private $originalStore;

    public function __construct(
        StoreManagerInterface $storeManager,
        WriterInterface $configWriter
    ) {
        $this->storeManager = $storeManager;
        $this->configWriter = $configWriter;
    }

    public function deactivate(): void
    {
        // Check if multi-store
        if (count($this->storeManager->getStores()) > 1) {
            // Handle each store
            foreach ($this->storeManager->getStores() as $store) {
                $this->switchToStore($store->getId());
                $this->deactivateForCurrentStore();
            }
            $this->restoreOriginalStore();
        } else {
            // Single store
            $this->deactivateForCurrentStore();
        }
    }

    private function switchToStore($storeId): void
    {
        $this->originalStore = $this->storeManager->getStore()->getId();
        $this->storeManager->setCurrentStore($storeId);
    }

    private function restoreOriginalStore(): void
    {
        if ($this->originalStore !== null) {
            $this->storeManager->setCurrentStore($this->originalStore);
            $this->originalStore = null;
        }
    }

    private function deactivateForCurrentStore(): void
    {
        // Remove configurations for current store
        $this->configWriter->delete('sovendusvouchernetwork');
        // Add other configuration cleanup as needed
    }
}