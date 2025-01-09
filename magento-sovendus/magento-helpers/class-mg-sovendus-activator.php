<?php

namespace Sovendus\VoucherNetwork\Model;

use Sovendus\VoucherNetwork\Helper\StoreManager;

class Activator
{
    private $storeManager;

    public function __construct(
        StoreManager $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    public function activate(): void
    {
        if ($this->storeManager->isMultiStore()) {
            foreach ($this->storeManager->getStores() as $store) {
                $this->storeManager->switchToStore($store->getId());
                // Do store-specific activation
                $this->activateForCurrentStore();
            }
            $this->storeManager->restoreOriginalStore();
        } else {
            $this->activateForCurrentStore();
        }
    }

    private function activateForCurrentStore(): void
    {
        // Store-specific activation logic
    }
}