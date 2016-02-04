<?php


class tx_msvariants_updatecartproductprehook {

  function updateCartProductPreHook(&$params, &$reference) {

   error_log("updateCartPostHook - begin");

    $shopping_cart_item = $params['shopping_cart_item'];
    $cart_product = &$params['cart']['products'][$shopping_cart_item];
    $product_id = $cart_product['products_id'];

    $attributes = $cart_product['attributes'];
    foreach($attributes as $attr) {

      $attribute_id = $attr['products_attributes_id'];
      $qry_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
        'va.variant_id',
        'tx_msvariants_domain_model_variantsattributes va',
        'va.product_id='.$product_id.' and va.attribute_id='.$attribute_id
      );

      $ids = array();
      while (($row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_res)) != false) {
        $ids[$row['variant_id']] = '';
      }

      if (!isset($variant_id)) {
        $variant_id = $ids;
      }
      else {
        $variant_id = array_intersect_assoc($variant_id, $ids);
      }

    }


    // Variant found
    if (count($variant_id) == 1) {
      $cart_product['variant_id'] = array_keys($variant_id)[0];
    }
    // Variant not found
    else {

    }



    error_log("updateCartPostHook - end");


  }


}


?>
