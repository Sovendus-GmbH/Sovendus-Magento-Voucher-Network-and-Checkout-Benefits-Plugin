<?php

namespace Sovendus\SovendusApp\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class JsonConfig extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    public function _getElementHtml($element)
    {
        return '<script id="sovloader" type="text/javascript" src="'
            . $this->getViewFileUrl('Sovendus_SovendusApp::js/frontend_react_loader.js') . '"></script>';
    }
}
