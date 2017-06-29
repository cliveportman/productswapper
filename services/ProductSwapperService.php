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

		// within settings, the var isSwappable is a keyed array
		// the keys are all the product type slugs
		// if a product type has been saved as swappable then it's value will be 1
		// otherwise it will be empty
		$settings = craft()->plugins->getPlugin('productswapper')->getSettings();
		foreach ($settings->isSwappable as $key => $value) {

			// if the key matches the product type and it isn't empty
			// the product type has been marked as swappable
			if ($key == $newProductType && !empty($value)) {

				// loop through the line items in the cart and remove them if the
				// product type matches the new line item
				$lineItems = $cart->lineItems;
				foreach ($lineItems as $lineItem) {
					if ($lineItem->purchasable->product->type == $newProductType) {
						craft()->commerce_orders->removeLineItemFromOrder($cart, $lineItem);
						ProductSwapperPlugin::log("Matching line item removed.", LogLevel::Info);
					}
				}
			}
		}

		return TRUE;
	}

}