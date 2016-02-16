<?php

/**
 * Tenbucks
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file tb-LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.tenbucks.io/tb-LICENSE.txt
 *
 * @package     Tenbucks_Connect
 * @copyright   Copyright (c) 2016 Tenbucks (https://www.tenbucks.io)
 * @author	Tenbucks <hello@tenbucks.io>
 *
 */
class Tenbucks_Connect_Block_Adminhtml_View extends Mage_Adminhtml_Block_Widget_Container {    
    
    public function __construct() {

        $this->_controller = 'adminhtml_tenbucks';
        $this->_blockGroup = 'tenbucks_connect';
        $this->_headerText = Mage::helper('tenbucks_connect')->__('My Applications');
        $this->_addButton('standalone', array(
            'label' => Mage::helper('tenbucks_connect')->__('Standalone Mode'),
            'onclick' => "window.open('".$this->getIframeUrl(true)."')",
            'class' => 'add-widget'
        ));
        parent::__construct();
    }
    
    public function getIframeUrl($standalone = false) {
        
        $params['url'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $params['platform'] = 'Magento';
        $params['magento_version'] = Mage::getVersion();
        $params['module_version'] = Mage::helper('tenbucks_connect')->getModuleVersion();                
        
        $redirect = $this->getRequest()->getParam('redirect');
        
        switch($redirect) {            
            case 'account':
                $params['redirect'] = 'account';
                break;
            
            default :
                $params['redirect'] = 'apps';
                break;
        }
        
        if ($standalone) {
            $params['standalone'] = 1;
        } else {
            $params['standalone'] = 0;
        }
        
        return Mage::helper('tenbucks_connect')->getApiUrl('dispatch').'?'.http_build_query($params);
    }
    
    

}
