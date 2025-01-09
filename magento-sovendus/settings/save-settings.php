<?php

namespace Vendor\Module\Controller\Adminhtml\Settings;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{
    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * @var JsonFactory 
     */
    protected $resultJsonFactory;

    /**
     * @param Context $context
     * @param WriterInterface $configWriter
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        WriterInterface $configWriter, 
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->configWriter = $configWriter;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Save Sovendus settings
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        try {
            // Get JSON POST data
            $rawPost = $this->getRequest()->getContent();
            
            if (!$rawPost) {
                throw new LocalizedException(__('No input received'));
            }

            $data = json_decode($rawPost, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new LocalizedException(__('JSON parse error: ' . json_last_error_msg()));
            }

            if (!isset($data['settings'])) {
                throw new LocalizedException(__('Missing settings in payload'));
            }

            // Save settings to core_config_data table
            foreach ($data['settings'] as $path => $value) {
                $this->configWriter->save('sovendusvouchernetwork/' . $path, $value);
            }

            return $resultJson->setData([
                'success' => true,
                'message' => __('Settings saved successfully')
            ]);

        } catch (\Exception $e) {
            return $resultJson->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check admin permissions
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Vendor_Module::config');
    }
}