<?php


class tx_msvariants_customajaxpage {

  function customAjaxPage(&$params, &$ref) {

    error_log("customAjaxPage hook");

    error_log("products path: ".$ref->ms['image_paths']['products']['original']);

    $ref->ms['image_paths']['variants']['original'] = 'uploads/tx_msvariants/images/original';

    error_log("variants path: ".$ref->ms['image_paths']['variants']['original']);


    if ($ref->ADMIN_USER) {
      if (isset($_SERVER["CONTENT_LENGTH"])) {
//        switch ($ref->get['file_type']) {
//          case 'variants_image':
//          for ($x=0; $x<$ref->ms['MODULES']['NUMBER_OF_PRODUCT_IMAGES']; $x++) {
//              // hidden filename that is retrieved from the ajax upload
//            $i=$x;
//            if ($i==0) {
//              $i='';
//            }
//            $field='products_image'.$i;
//            if ($ref->get['file_type']==$field) {


              $temp_file=$ref->DOCUMENT_ROOT.'uploads/tx_multishop/tmp/'.uniqid();
              if (isset($_FILES['qqfile'])) {
                move_uploaded_file($_FILES['qqfile']['tmp_name'], $temp_file);
              } else {
                $input=fopen("php://input", "r");

                $debug_file = fopen("/Applications/XAMPP/xamppfiles/htdocs/typo3/typo3temp/debug.txt", "w");
                fwrite($debug_file, sys_get_temp_dir());
                fclose($debug_file);

                $temp=tmpfile();
                $realSize=stream_copy_to_stream($input, $temp);
                fclose($input);
                $target=fopen($temp_file, "w");
                fseek($temp, 0, SEEK_SET);
                stream_copy_to_stream($temp, $target);
                fclose($target);
              }
              error_log("temp file created");

              $size=getimagesize($temp_file);
              if ($size[0]>5 and $size[1]>5) {
                error_log("size ok");
                $imgtype=mslib_befe::exif_imagetype($temp_file);
                if ($imgtype) {
                  error_log("type ok");
                    // valid image
                  $ext=image_type_to_extension($imgtype, false);
                  if ($ext) {
                    error_log("ext ok");
                    $i=0;
                    $filename=mslib_fe::rewritenamein($ref->get['products_name']).'.'.$ext;
                    $folder=mslib_befe::getImagePrefixFolder($filename);
                    $array=explode(".", $filename);
                    if (!is_dir($ref->DOCUMENT_ROOT.$ref->ms['image_paths']['variants']['original'].'/'.$folder)) {
                      t3lib_div::mkdir($ref->DOCUMENT_ROOT.$ref->ms['image_paths']['variants']['original'].'/'.$folder);
                    }

                                        error_log("mkdir ok");

                    $folder.='/';
                    $target=$ref->DOCUMENT_ROOT.$ref->ms['image_paths']['variants']['original'].'/'.$folder.$filename;
                    if (file_exists($target)) {
                                                              error_log("file exists ok");

                      do {
                        $filename=mslib_fe::rewritenamein($ref->get['products_name']).($i>0 ? '-'.$i : '').'.'.$ext;
                        $folder_name=mslib_befe::getImagePrefixFolder($filename);
                        $array=explode(".", $filename);
                        $folder=$folder_name;
                        if (!is_dir($ref->DOCUMENT_ROOT.$ref->ms['image_paths']['variants']['original'].'/'.$folder)) {
                          t3lib_div::mkdir($ref->DOCUMENT_ROOT.$ref->ms['image_paths']['variants']['original'].'/'.$folder);
                        }
                        $folder.='/';
                        $target=$ref->DOCUMENT_ROOT.$ref->ms['image_paths']['variants']['original'].'/'.$folder.$filename;
                        $i++;
                      } while (file_exists($target));
                    }

                                                            error_log("before copy file ok");

                    if (copy($temp_file, $target)) {
                      $filename=mslib_befe::resizeProductImage($target, $filename, $ref->DOCUMENT_ROOT.t3lib_extMgm::siteRelPath($ref->extKey), 1);
                      $result=array();
                      $result['success']=true;
                      $result['error']=false;
                      $result['filename']=$filename;
                      echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
                      exit();
                    }
                  }
                }
              }
            }
//          }
//          break;
//        }
//      }
    }
//    exit();
//    break;

  }


}


?>
