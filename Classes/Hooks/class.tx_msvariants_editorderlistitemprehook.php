<?php

class tx_msvariants_editorderlistitemprehook {

  // This hook is called before displaying a product of an order into a
  // table. Thus, it is called as many times as products an order has.
  // The goal of this hook is to add the SKU variant to the row of the
  // the html table that is received as a parameter.
  //
  // TODO: It remains open to establish the link between the variant ID and
  // the order product ID after having inserted of products of an order into
  // the db. This might be done in the insertOrderPostProc hook of the class
  // tx_mslib_cart. In it is done at this place, there would be the way of
  // retrievent a variant using through an order product ID.
  function editOrderListItemPreHook(&$params, &$reference) {

    error_log("editOrderListItemPreHook - begin");

    // Get relevant data from parameters
    $order = $params['order'];
    $order_id = $order['orders_id'];
    $product_id = $order['products_id'];
    $order_product_id = $order['orders_products_id'];

    // Figure out variantId
    // from table tx_msvariants_domain_model_variantsattributes
    // using option and option values get from
    // tx_multishop_orders_products_attributes using orders_products_id

    // build code_variant which is a string that results from the
    // concatenation of <option_id, option_value_id> pairs of the product
    $qry_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
      'v.products_options_id, v.products_options_values_id',
      'tx_multishop_orders_products_attributes v',
      'v.orders_id='.$order_id.' and v.orders_products_id='.$order_product_id,
      '',
      'v.products_options_id asc, v.products_options_values_id asc'
    );

    $code_variant = '';
    while (($row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_res)) != false) {
      $code_variant .= '||'.$row['products_options_id'].'|'.$row['products_options_values_id'];
    }

    // retrieve all <variant_id, option_id, option_value_id> belonging to all
    // products of the order, group them by variant ID, build a string similar
    // to the previous one for each variant ID group, and compare them with
    // the previous code. If matching exists, the variant ID of the group that
    // matched if the variant ID we were looking for.
    $qry_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
      'v.variant_id, v.option_id, v.option_value_id',
      'tx_msvariants_domain_model_variantsattributes v',
      'v.product_id='.$product_id,
      //'v.variant_id',
      '',
      'v.variant_id asc, v.option_id asc, v.option_value_id asc'
    );

    $found = false;
    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($qry_res);

    while (($row != false) && (!$found)) {

      $curr_variant_id = $row['variant_id'];
      $code = '||'.$row['option_id'].'|'.$row['option_value_id'];

      while((($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($qry_res)) != false) &&
            ($row['variant_id'] == $curr_variant_id)) {

        $code = $code.'||'.$row['option_id'].'|'.$row['option_value_id'];

      }

      // if variant is found, finish searching
      if ($code == $code_variant) {
        $variant_id = $curr_variant_id;
        $found = true;
      }

    }

    // Make a link between the variant_id we found and the orders_products_id
    // by inserting the latest into the variantsorders table

    if ($found) {
      $update_array = array(
        'order_product_id' => $order_product_id
      );

      $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
        'tx_msvariants_domain_model_variantsorders v',
        'v.order_id='.$order_id.' and v.product_id='.$product_id.
        ' and v.variant_id='.$variant_id,
        $update_array
      );

      // retrieve sku variant for the product
      $qry_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
        'v.sku',
        'tx_msvariants_domain_model_variantsorders v',
        'v.order_id='.$order_id.' and v.product_id='.$product_id.
        ' and v.variant_id='.$variant_id
      );

      if ($GLOBALS['TYPO3_DB']->sql_num_rows($qry_res) != 1) {
        // TODO this should never happen - raise exception?
        return;
      }

      $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($qry_res);
      $variant_sku = $row['sku'];

      // add sku variant to displaying row
      $params['row'][2] .= '<br/> SKU variant: '.$variant_sku;

    }


    error_log("editOrderListItemPreHook - end");
  }

}

?>
