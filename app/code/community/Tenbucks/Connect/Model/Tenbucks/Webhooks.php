<?php
/**
 * Tenbucks
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to hello@tenbucks.io so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Tenbucks to newer
 * versions in the future.
 *
 * @category   Tenbucks
 * @package    Tenbucks_Connect
 * @copyright  Copyright (c) 2016 Tenbucks. (https://www.tenbucks.io)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Tenbucks <hello@tenbucks.io>
 */

class Tenbucks_Connect_Model_Tenbucks_Webhooks {

    public function hookProduct($productId) {

        $path = 'magento/webhooks/products';

        $data = array(
            'shop_url' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB),
            'product_id' => $productId,
        );

        return $this->call($path, $data);
    }

    public function hookGtin() {

        $path = 'magento/webhooks/gtin';

        $data = array(
            'shop_url' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB),
            'gtin' => Mage::helper('tenbucks_connect')->getGtinCode(),
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
            if ($query = 'OK') {
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
