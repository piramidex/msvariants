<?php

class tx_msvariants_admineditproductpreproc {

  function adminEditProductPreProc(&$params, &$reference) {

    xdebug_break();

    // product Attribute
    //if (!$reference->ms['MODULES']['DISABLE_PRODUCT_VARIANTS_TAB_IN_EDITOR']) {

    $product = $params['product'];

    // Get HTML template file
    if ($reference->conf['edit_variants_tmpl_path']) {
    	$template=$reference->cObj->fileResource($reference->conf['edit_variants_tmpl_path']);
    } else {
    	$template=$reference->cObj->fileResource(t3lib_extMgm::siteRelPath('msvariants').'Templates/edit_variants.tmpl');
    }

    // Extract the subparts from the template
    $subparts=array();
    $subparts['template']=$reference->cObj->getSubpart($template, '###TEMPLATE###');
    $subparts['item_variant']=$reference->cObj->getSubpart($subparts['template'], '###ITEM_VARIANT###');
    $subparts['item_option']=$reference->cObj->getSubpart($subparts['template'], '###ITEM_OPTION###');


    // Get variants from db
        $sql_pa=$GLOBALS ['TYPO3_DB']->SELECTquery('vars.variant_id, vars.product_id, vars.variant_price, vars.variant_stock, vars.variant_sku, vattrs.attribute_id, vattrs.option_id, vattrs.option_value_id', // SELECT ...
          'tx_msvariants_domain_model_variants vars, tx_msvariants_domain_model_variantsattributes vattrs', // FROM ...
          "vars.product_id='".$product['products_id']."' and vars.variant_id=vattrs.variant_id", // WHERE.  // TODO should we take into consideration the language popt.language_id = 0?
          '', // GROUP BY...
          '', // ORDER BY...
          '' // LIMIT ...
        );

        $qry_pa=$GLOBALS ['TYPO3_DB']->sql_query($sql_pa);



        // Generate HTML for variants tab block if there are variants

        if ($product['products_id']) {

          if ($GLOBALS['TYPO3_DB']->sql_num_rows($qry_pa)>0) {

            $variants_data = array();
            $variants_attributes_data = array();
            while (($row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc($qry_pa)) != false) {
              if (!$variants_data[$row['variant_id']]) {
                $variants_data[$row['variant_id']] = array(
                  'variant_id' => $row['variant_id'],
                  'product_id' => $row['product_id'],
                  'variant_price' => $row['variant_price'],
                  'variant_stock' => $row['variant_stock'],
                  'variant_sku' => $row['variant_sku'],
                );
              }

              $row['option_name'] = mslib_fe::getRealNameOptions($row['option_id']);
              $row['option_value_name'] = mslib_fe::getNameOptions($row['option_value_id']);
              $variants_attributes_data[$row['variant_id']][] = $row;
            }


            // Generate HTML for variants
            foreach($variants_data as $variant) {

              $variant_name = 'Variant: ';

              // generate HTML for variant's attributes
              $content_options = '';
              $markerArray = array();
              foreach($variants_attributes_data[$variant['variant_id']] as $variant_attribute) {

                $markerArray['OPTION_NAME'] = $variant_attribute['option_name'];
                $markerArray['OPTION_VALUE'] = $variant_attribute['option_value_name'];
                $content_options .= $reference->cObj->substituteMarkerArray($subparts['item_option'], $markerArray, '###|###');

                $variant_name .= $variant_attribute['option_value_name']." - ";
              }

              // generate HTML for variant details
              $markerArray = array();
              $markerArray['LABEL_VARIANT_NAME'] = $variant_name;
              $markerArray['LABEL_PRICE'] = 'Price';
              $markerArray['LABEL_STOCK'] = 'Stock';
              $markerArray['LABEL_SKU'] = 'SKU';
              $markerArray['VARIANT_ID'] = $variant['variant_id'];
              $markerArray['PRICE'] = $variant['variant_price'];
              $markerArray['STOCK'] = $variant['variant_stock'];
              $markerArray['SKU'] = $variant['variant_sku'];


              $content_item_variant = $reference->cObj->substituteMarkerArray($subparts['item_variant'], $markerArray, '###|###');
              $content_item_variant = $reference->cObj->substituteSubpart($content_item_variant, 'ITEM_OPTION', $content_options);

              $content_variants .= $content_item_variant;
            }


            // Generate HTML for variants tab block
            $markerArray = array();
            $markerArray['LABEL_HEADING_TAB_VARIANTS'] = 'VARIANTS';

            $content = $reference->cObj->substituteMarkerArray($subparts['template'], $markerArray, '###|###');
            $content = $reference->cObj->substituteSubpart($content, 'ITEM_VARIANT', $content_variants);


          }
        }


    $params['plugins_extra_tab']['tabs_header'][] = '<li><a href="#product_variants">VARIANTS</a></li>';
    //$params['plugins_extra_tab']['tabs_content'][] =
    //  '<div style="display:none;" id="product_variants" class="tab_content">'.$res.'</div';
    $params['plugins_extra_tab']['tabs_content'][] = $content;

  }


}

?>
