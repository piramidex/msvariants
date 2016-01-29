<?php


class tx_msvariants_updateproductposthook {

  function updateProductPostHook(&$params, &$reference) {


    $product_id = $params['products_id'];

    if (!$product_id) { return; }



    // Case 1: variants have not changed
    // because product's attributes and values have not changed

    $variants_ids = $reference->post['variants_ids'];
    $variants_prices = $reference->post['variants_prices'];
    $variants_stocks = $reference->post['variants_stocks'];
    $variants_skus = $reference->post['variants_skus'];

    for($i=0; $i < count($variants_ids); $i++) {
      $update_array = array(
        'variant_id' => $variants_ids[$i],
        'variant_price' => $variants_prices[$i],
        'variant_stock' => $variants_stocks[$i],
        'variant_sku' => $variants_skus[$i],
      );
      $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
        'tx_msvariants_domain_model_variants',
        'variant_id='.$variants_ids[$i], // TODO make this secure with fullQuoteStr
        $update_array
      );
    }


    // Case 2: variants have changed
    // because product's attributes and/or values have changed


  }


}


?>
