<?php
namespace Craft;

class ProductSwapperService extends BaseApplicationComponent
{

	// REMOVE PRODUCT OF SAME TYPE
	public function removeProductOfSameType(Commerce_OrderModel $cart, Commerce_LineItemModel $newLineItem) 
	{

		ProductSwapperPlugin::log("'removeProductOfSameType' service called.", LogLevel::Info);

		// get the product type of the new line item
		$newProductType = $newLineItem->purchasable->product->type;
		ProductSwapperPlugin::log("New product type is $newProductType.", LogLevel::Info);

		// TO ADD: check the new product type is 'swappable' in the settings before continuing

		// loop through the line items in the cart and remove them if the
		// product type matches the new line item
		$lineItems = $cart->lineItems;
		foreach ($lineItems as $lineItem) {
			if ($lineItem->purchasable->product->type == $newProductType) {
				craft()->commerce_orders->removeLineItemFromOrder($cart, $lineItem);
				ProductSwapperPlugin::log("Matching line item removed.", LogLevel::Info);
			}
		}

		return TRUE;
	}

}