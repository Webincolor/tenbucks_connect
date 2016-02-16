<?php

class Tenbucks_Connect_Model_System_Config_Source_Attributes {

        
    /**
     * Convert to Admin System Dropdow Array
     *
     * @return array
     */
    public function toOptionArray() {
        
        $attributes = $this->getExternalAttributes();
        array_unshift($attributes, array("value" => "", "label" => Mage::helper('tenbucks_connect')->__("Select attribute to map")));
        
        return $attributes;
    }
    
    /**
     * Retrieve accessible external product attributes
     *
     * @return array
     */
    public function getExternalAttributes()
    {
    	$productAttributes = array();
    	if(file_exists(Mage::getModuleDir(null,'Mage_Catalog') ."Model/Resource/Eav/Mysql4/Product/Attribute/Collection"))
        	$productAttributes  = Mage::getResourceModel('catalog/product_attribute_collection')->load();
        else
        {
        	
	        $entityTypeId = Mage::getSingleton('eav/config')->getEntityType('catalog_product')->getId();
	        $productAttributes = Mage::getResourceModel('eav/entity_attribute_collection')
	            ->setEntityTypeFilter($entityTypeId)
	            ->load();
        }
        
        $attributes = array();

        foreach ($productAttributes as $attr) {
            $code = $attr->getAttributeCode();
            if ($attr->getFrontendInput() == 'hidden') {
                continue;
            }
            $attributes[$code] = $code;
        }


        return $attributes;
    }

}
