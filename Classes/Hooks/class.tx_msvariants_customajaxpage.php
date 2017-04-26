      <?php


      class tx_msvariants_customajaxpage {

        function customAjaxPage(&$params, &$ref) {

          error_log("customAjaxPage hook");
          //error_log("products path: ".$ref->ms['image_paths']['products']['original']);
          //$ref->ms['image_paths']['variants']['original'] = 'uploads/tx_multishop/images/products/original';
          //error_log("variants path: ".$ref->ms['image_paths']['variants']['original']);

          // TODO: this config should go somewhere else
          $this->msvariants['image_paths']['variants']['dir_images'] = 'uploads/tx_msvariants/images';  
          $this->msvariants['image_paths']['variants']['dir_variants'] = 'uploads/tx_msvariants/images/variants';  
          $this->msvariants['image_paths']['variants']['original'] = 'uploads/tx_msvariants/images/variants/original';  
          $this->msvariants['image_paths']['variants']['normal'] = 'uploads/tx_msvariants/images/variants/normal';  
          $this->msvariants['image_paths']['variants']['50'] = 'uploads/tx_msvariants/images/variants/50';  
          $this->msvariants['image_paths']['variants']['100'] = 'uploads/tx_msvariants/images/variants/100';  
          $this->msvariants['image_paths']['variants']['200'] = 'uploads/tx_msvariants/images/variants/200';  
          $this->msvariants['image_paths']['variants']['300'] = 'uploads/tx_msvariants/images/variants/300';  
          foreach($this->msvariants['image_paths']['variants'] as $path) {
            error_log('path: '.$ref->DOCUMENT_ROOT.$path);
            if (!is_dir($ref->DOCUMENT_ROOT.$path)) {
              t3lib_div::mkdir($ref->DOCUMENT_ROOT.$path);
            }
          }


          if ($ref->ADMIN_USER) {
            if (isset($_SERVER["CONTENT_LENGTH"])) {


              if ($ref->get['file_type'] == 'variants_image') {
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


              if (!is_dir($ref->DOCUMENT_ROOT.'uploads/tx_msvariants/tmp')) {
                t3lib_div::mkdir($ref->DOCUMENT_ROOT.'uploads/tx_msvariants/tmp');
              }

              $temp_file=$ref->DOCUMENT_ROOT.'uploads/tx_msvariants/tmp/'.uniqid();
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
                    if (!is_dir($ref->DOCUMENT_ROOT.$this->msvariants['image_paths']['variants']['original'].'/'.$folder)) {
                      t3lib_div::mkdir($ref->DOCUMENT_ROOT.$this->msvariants['image_paths']['variants']['original'].'/'.$folder);
                    }

                    error_log("mkdir ok");

                    $folder.='/';
                    $target=$ref->DOCUMENT_ROOT.$this->msvariants['image_paths']['variants']['original'].'/'.$folder.$filename;
                    if (file_exists($target)) {
                      error_log("file exists ok");

                      do {
                        $filename=mslib_fe::rewritenamein($ref->get['products_name']).($i>0 ? '-'.$i : '').'.'.$ext;
                        $folder_name=mslib_befe::getImagePrefixFolder($filename);
                        $array=explode(".", $filename);
                        $folder=$folder_name;
                        if (!is_dir($ref->DOCUMENT_ROOT.$this->msvariants['image_paths']['variants']['original'].'/'.$folder)) {
                          t3lib_div::mkdir($ref->DOCUMENT_ROOT.$this->msvariants['image_paths']['variants']['original'].'/'.$folder);
                        }
                        $folder.='/';
                        $target=$ref->DOCUMENT_ROOT.$this->msvariants['image_paths']['variants']['original'].'/'.$folder.$filename;
                        $i++;
                      } while (file_exists($target));
                    }

                    error_log("before copy file ok");

                    if (copy($temp_file, $target)) {
                      $filename=$this->resizeProductImage($target, $filename, $ref->DOCUMENT_ROOT.t3lib_extMgm::siteRelPath($ref->extKey), 1, $ref);
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
          }
      //          }
      //          break;
      //        }
      //      }
        }
      //    exit();
      //    break;

      }

        public function resizeProductImage($original_path, $filename, $module_path, $run_in_background=0, $ref) {

          if (!$GLOBALS['TYPO3_CONF_VARS']['GFX']['jpg_quality']) {
            $GLOBALS['TYPO3_CONF_VARS']['GFX']['jpg_quality']=75;
          }

          if ($filename) {

            // TODO: add preProcHook?

            if ($run_in_background) {
              $suffix_exec_param=' &> /dev/null & ';
            }

            $commands=array();
            $params='';
            if ($GLOBALS['TYPO3_CONF_VARS']['GFX']['im_version_5']=='im6') {
              $params.='-strip';
            }

            $folder=mslib_befe::getImagePrefixFolder($filename);
            foreach(array('300', '200', '100', '50', 'normal') as $size) {

              $dir = PATH_site.$this->msvariants['image_paths']['variants'][$size].'/'.$folder;
              if (!is_dir($dir)) { t3lib_div::mkdir($dir); }
              $target=PATH_site.$this->msvariants['image_paths']['variants'][$size].'/'.$folder.'/'.$filename;
              copy($original_path, $target);

              $maxwidth=$ref->ms['product_image_formats'][($size == 'normal' ? 'enlarged' : $size)]['width'];
              $maxheight=$ref->ms['product_image_formats'][($size == 'normal' ? 'enlarged' : $size)]['height'];
              $commands[]=t3lib_div::imageMagickCommand('convert', $params.' -quality '.$GLOBALS['TYPO3_CONF_VARS']['GFX']['jpg_quality'].' -resize "'.$maxwidth.'x'.$maxheight.'>" "'.$target.'" "'.$target.'"', $GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path_lzw']);

              if ($ref->ms['MODULES']['PRODUCT_IMAGE_SHAPED_CORNERS'] && (in_array($size, array('300', '200')))) {
                $gravities = array('NorthWest' => 'lb', 'NorthEast' => 'rb', 'SouthWest' => 'lo', 'SouthEast' => 'ro');
                foreach($gravities as $key => $value) {
                  $commands[]=$GLOBALS['TYPO3_CONF_VARS']['GFX']["im_path"].'composite -gravity '.$key.' '.$module_path.'templates/images/curves/'.$value.'.png "'.$target.'" "'.$target.'"';
                }
              }
            }

            // TODO: add watermark processing ?

            if (count($commands)) {
              foreach ($commands as $command) {
                exec($command);
              }
            }

            // TODO: add postProcHook ?

            return $filename;
          }
        }




      }


      ?>
