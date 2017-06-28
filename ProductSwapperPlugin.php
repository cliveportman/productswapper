<?php
namespace Craft;

class ProductSwapperPlugin extends BasePlugin
{
    function getName()
    {
         return Craft::t('Product Swapper');
    }

    public function getDescription()
    {
        return "Searches a cart before a product is added to it, looking for a product already in the cart that is the same product type. If one is found, it removes said product then let's Commerce continue adding the line item.";
    }

    function getVersion()
    {
        return '0.1';
    }

    function getDeveloper()
    {
        return 'Clive Portman';
    }

    function getDeveloperUrl()
    {
        return 'https://cliveportman.co.uk';
    }
    
    public function hasSettings()
    {
        return true;
    }

    public function getSettingsHtml()
    {
        $productTypes = craft()->commerce_productTypes->getAllProductTypes();

        return craft()->templates->render('productswapper/_settings', array(
            'settings' => $this->getSettings(),
            'productTypes' => $productTypes,
        ));
    }

    protected function defineSettings()
    {
        return array(
            'allowSwappableProducts' => AttributeType::Bool,
            'swappable' => AttributeType::Mixed,
        );
    }
    
    
    public function init()
    {
        parent::init();

        craft()->on('commerce_cart.onBeforeAddToCart', function (Event $event) {

            // TO ADD: check for the 'allowSwappabeProducts' setting to be turned on
            // before continuing

            craft()->productSwapper->removeProductOfSameType($event->params['order'], $event->params['lineItem']);
        });
    }
    

}