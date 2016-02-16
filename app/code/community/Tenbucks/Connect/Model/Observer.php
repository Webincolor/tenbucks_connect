<?php

class Tenbucks_Connect_Model_Observer {

    public function handle_adminSystemConfigChangedSection(Varien_Event_Observer $observer) {  
        
        return Mage::getModel('tenbucks_connect/tenbucks_webhooks')->hookGtin();
        
    }
    
    public function handle_catalogProductSaveAfter(Varien_Event_Observer $observer) {  
        
        $productId = $observer->getProduct()->getId();        
        
        return Mage::getModel('tenbucks_connect/tenbucks_webhooks')->hookProduct($productId);
        
    }

}