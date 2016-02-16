<?php

class Tenbucks_Connect_Helper_Data extends Mage_Core_Helper_Abstract 
{
    const URI = 'https://apps.tenbucks.io';
    
    const XML_CONFIG_TENBUCKS_CONNECT_GENERAL_GTIN = 'tenbucks_connect/general/gtin';
    
    public function getModuleVersion() {                
        
        return Mage::getConfig()->getModuleConfig('Tenbucks_Connect')->version;        
    }
    
    public function getApiUrl($path = '') {
        
        if($path) {            
            return self::URI . DS . $path;            
        } else {            
            return self::URI;
        }                        
    }
    
    
    public function getGtinCode() {
        return Mage::getStoreConfig(self::XML_CONFIG_TENBUCKS_CONNECT_GENERAL_GTIN);
    }
   
}