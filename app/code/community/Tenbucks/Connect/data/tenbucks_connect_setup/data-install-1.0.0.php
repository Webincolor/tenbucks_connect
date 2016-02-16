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
require_once(Mage::getBaseDir('lib') . '/tenbucks/TenbucksKeysClient.php');

$installer = $this;

$installer->startSetup();


try {

    // generate API Key randomly
    $api_key = Mage::helper('core')->getHash(Mage::helper('core')->getRandomString($length = 7));

    // set API role
    $api_role = Mage::getModel('api/roles')
            ->setName('Tenbucks API')
            ->setPid(false)
            ->setRoleType('G')
            ->save();

    Mage::getModel('api/rules')
            ->setRoleId($api_role->getId())
            ->setResources(array('all'))
            ->saveRel();

    // create API user
    $api_user = Mage::getModel('api/user');

    $api_user->setData(array(
        'username' => 'tenbucks',
        'firstname' => 'tenbucks',
        'lastname' => 'tenbucks',
        'email' => 'hello@tenbucks.io',
        'is_active' => 1,
        'user_roles' => '',
        'assigned_user_role' => '',
        'role_name' => '',
        'roles' => array($api_role->getId())
    ));

    $api_user->setApiKey($api_key);

    $api_user->save()->load($api_user->getId());

    $api_user->setRoleIds(array($api_role->getId()))
            ->setRoleUserId($api_user->getUserId())
            ->saveRelations();

    // Enable Web Service Cache
    $installer->setConfigData('api/config/wsdl_cache_enabled', 1);



//  $store_view_code = array();
//  foreach(Mage::app()->getStores() as $store){
//      $store_view_code[] = $store->getCode();
//  }
    // initialize TenbucksKeysClient library
    $client = new TenbucksKeysClient();

    // collect information
    $opts = array(
        'email' => Mage::getStoreConfig('trans_email/ident_general/email'),
        'company' => Mage::getStoreConfig('general/store_information/name'),
        'platform' => 'Magento',
        'locale' => substr(Mage::app()->getLocale()->getLocaleCode(), 0, 2),
        'country' => Mage::getStoreConfig('general/store_information/merchant_country'),
        'url' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB),
        'gtin' => null, // magento special attributes
        'credentials' => array(
            'key' => md5('tenbucks'), // key
            'secret' => md5($api_key), // secret
        )
    );

    // send information to tenbucks server (secure with HTTPS)
    $query = $client->send($opts);

    // test if information has been sent successfully
    if (array_key_exists('success', $query) && (bool) $query['success']) {
        // success
        Mage::log('tenbucks. data-install script successfully executed.');
    } else {
        // Error
        $installer->setConfigData('tenbucks_connect/general/api_key', $api_key);
        Mage::throwException('tenbucks data-install script error.'.print_r($query,TRUE));
    }
} catch (Exception $ex) {
    Mage::log($ex->getMessage());
}


$installer->endSetup();
