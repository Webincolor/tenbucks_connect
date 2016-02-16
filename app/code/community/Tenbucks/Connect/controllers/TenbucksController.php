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

class Tenbucks_Connect_TenbucksController extends Mage_Adminhtml_Controller_Action
{
    public function connectAction()
    {                
        $this->loadLayout();
        $this->_setActiveMenu('tenbucks');
        
        $this->renderLayout();
    }
}