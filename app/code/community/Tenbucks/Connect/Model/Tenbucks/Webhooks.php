<?php

class Tenbucks_Connect_Model_Tenbucks_Webhooks {

    public function hookProduct($productId) {

        $path = 'magento/webhooks/products';

        $data = array(
            'shop' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB),
            'external_id' => $productId,
        );

        return $this->call($path, $data);
    }

    public function hookGtin() {

        $path = 'magento/webhooks/gtin';

        $data = array(
            'shop' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB),
            'external_id' => Mage::helper('tenbucks_connect')->getGtinCode(),
        );

        return $this->call($path, $data);
    }

    private function call($path, array $data = array()) {

        $url = Mage::helper('tenbucks_connect')->getApiUrl($path);

        $client = new Zend_Http_Client($url);

        $client->setParameterGet($data);

        try {
            //    $time_start_zend = microtime(true);

            $query = $client->request();
            if (array_key_exists('success', $query) && (bool) $query['success']) {
                // success
                return true;
            } else {
                // Error
                Mage::throwException(print_r($query, TRUE));
            }
            //Mage::log(print_r($response,true));
            //    $zend_time_cumulative = microtime(true) - $time_start_zend;
            //    Mage::log(Mage::helper('tenbucks_connect')->__('Request time: %ss',round($zend_time_cumulative,4)));            
        } catch (Zend_Http_Client_Adapter_Exception $e) {
            Mage::log(Mage::helper('tenbucks_connect')->__('Error in tenbucks webhook %s : %s', $path, $e->getMessage()));
            return false;
        }
    }

}
