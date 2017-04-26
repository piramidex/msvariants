<?php


class tx_msvariants_insertordersproductpreproc {

  // This hook is called before inserting a product order into the db,
  // thus it is called as many times as products an order has.
  // But notice again that it is called before inserting a row for the
  // current product into the orders table of the db.
  //
  // TODO: In fact, it would have been better to perform this task (i.e. inserting
  // variant details about the current product) after inserting the order
  // details for the product. Such a hook exists, it is called
  // insertOdersProductPostProc but the parameters that are sent through
  // this hook are not enough to perform this task. Thus, this issue
  // remains open.
  function insertOrdersProductPreProc(&$params, &$reference) {

    error_log("insertOrdersProductPreProc - begin");

    $order_id = $params['insertArray']['orders_id'];
    $cart_product_item = $params['value'];
    $product_id = $cart_product_item['products_id'];
    $variant_id = $cart_product_item['variant_id'];


    // Get variant's details from the db
    $qry_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
      'v.variant_price, v.variant_sku',
      'tx_msvariants_domain_model_variants v',
      'v.variant_id='.$variant_id.' and v.product_id='.$product_id
    );

    if ($GLOBALS['TYPO3_DB']->sql_num_rows($qry_res) != 1) {
      // TODO this should never happen - raise exception?
      return;
    }

    $variant_data = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_res);

    // Insert variant details about the order into the db
    $insert_array = array(
      'order_id' => $order_id,
      'product_id' => $product_id,
      'variant_id' => $variant_id,
      'price' => $variant_data['variant_price'],
      'quantity' => $cart_product_item['qty'],
      'sku' => $variant_data['variant_sku']
    );

    // TODO should we insert here the following logic:
    // - check if after the order the variants gets out of stock
    // - if such is the case, notify adming about this event
    // - and disable product variant! (ohh... that's new - we should do this in product detail script)

    $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery(
      'tx_msvariants_domain_model_variantsorders', $insert_array
    );

    // Insert variant attributes details about the order into the db
    $qry_res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
      'va.attribute_id, va.option_id, va.option_value_id',
      'tx_msvariants_domain_model_variantsattributes va',
      'va.variant_id='.$variant_id.' and va.product_id='.$product_id
    );

    if ($GLOBALS['TYPO3_DB']->sql_num_rows($qry_res) <= 0) {
      // TODO this should never happen - raise exception?
      return;
    }

    while (($row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_res)) != false) {

      $row['option_name'] = mslib_fe::getRealNameOptions($row['option_id']);
      $row['option_value_name'] = mslib_fe::getNameOptions($row['option_value_id']);

      $insert_array = array(
        'order_id' => $order_id,
        'product_id' => $product_id,
        'variant_id' => $variant_id,
        'attribute_id' => $row['attribute_id'],
        'option_id' => $row['option_id'],
        'option_value_id' => $row['option_value_id'],
        'option_name' => $row['option_name'],
        'option_value_name' => $row['option_value_name']
      );

      $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery(
        'tx_msvariants_domain_model_variantsattributesorders', $insert_array
      );

    } // while

    error_log("insertOrdersProductPreProc - end");


  }

}

?>
