<?php
/*
* e107 website system
*
* Copyright (C) 2008-2013 e107 Inc (e107.org)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
* Custom install/uninstall/update routines for estate plugin
**
*/


e107::lan('estate',true, true);

if(!class_exists("estate_setup")){
	class estate_setup
	{

	    function install_pre($var)
		{
			// print_a($var);
		  //e107::getMessage()->addInfo("custom install for ".USERID." ".USERNAME." function.");
			// echo "custom install 'pre' function<br /><br />";
		}

		function install_post($var){
      $sql = e107::getDB();
      $tp = e107::getParser();
      $msg = e107::getMessage();
      
      $MAINAGTID = 0;
      $FUSERS = array();
      $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_class,user_signature,user_image FROM #user WHERE user_admin='1' AND user_perms='0'");
      while($row = $sql->fetch()){array_push($FUSERS,$row);}
      
      if(count($FUSERS)){
        foreach($FUSERS as $k=>$v){
          $NEWID = $sql->insert("estate_agents","'0','1','".$tp->toDB($v['user_name'])."','4','".intval($v['user_id'])."','','0','".$tp->toDB($v['user_signature'])."'");
          if($NEWID > 0){
            if($k == 0){$MAINAGTID = $NEWID;}
            $cmsg .= '<li>'.($k+1).' '.EST_INST_FISRTAGENT.' ID#'.$NEWID.' '.$tp->toHTML($v['user_name']);
            if($sql->insert("estate_contacts","'0','6','".intval($NEWID)."','".strtoupper($tp->toDB(EST_GEN_EMAIL))."','1','".$tp->toDB($v['user_email'])."'")){
              $cmsg .= ' ['.$tp->toHTML($v['user_email']).']';
              }
            $cmsg .= '('.$MAINAGTID.')</li>';
            }
          }
        if($cmsg){$msg->addSuccess('<div>'.EST_INST_INITSETUP.': '.EST_GEN_AGENT.' '.EST_GEN_PROFILES.'<ul>'.$cmsg.'</ul></div>');}
        unset($cmsg,$k,$v,$NEWID,$FUSERS);
        }
      else{$msg->addWarning('<div>'.EST_INST_INITSETUP.': '.EST_GEN_AGENT.' '.EST_GEN_PROFILES.'<ul><li>'.EST_INST_FISRTAGENTFAIL.'</li></ul></div>');}
      
      
      //load Preset Data
      /** inserts some preset data into the following tables:
      * estate_zoning
      * estate_listypes
      * estate_states
      * estate_featcats
      * estate_features
      * estate_subdivcats
      * estate_group
      **/
      
      if($sql->isEmpty('estate_features')){
        $ret3 = e107::getXml(true)->e107Import(e_PLUGIN."estate/xml/presets.xml");
        if(!empty($ret3['success'])){
          $msg->addSuccess(EST_INST_FEATURES1);
          }
        if(!empty($ret3['failed'])){
          $msg->addError(EST_INST_FEATURES2);
          $msg->addDebug(print_a($ret3['failed'],true));
          }
        }
      
      
      //load Sample Property
      if($sql->isEmpty('estate_properties')){
        $ret3 = e107::getXml(true)->e107Import(e_PLUGIN."estate/xml/sample_prop.xml");
        if(!empty($ret3['success'])){
          $propMSG = EST_INST_SAMPLEPROP1;
          if(intval($MAINAGTID) > 0){
            if($sql->update("estate_properties","prop_agent='".$MAINAGTID."', prop_agency='1' WHERE prop_idx='1' LIMIT 1")){
              $propMSG .= ': '.EST_INST_FISRTAGENTASSIGN;
              }
            }
          
          $msg->addSuccess($propMSG);
          $msg->addInfo('<p>'.EST_INST_SAMPLIST.'</p>');
          
          $DESTDIR = e_PLUGIN."estate/media/prop/thm/";
          if(!is_dir($DESTDIR)){$msg->addError(EST_INST_SAMPLEMEDIA0.' '.$DESTDIR.' - '.EST_INST_SAMPLEMEDIA1);}
          else{
            $MDTA = array();
            $i=0;
            $sql->gen("SELECT * FROM #estate_media"); 
            while($rows = $sql->fetch()){$MDTA[$i] = $rows; $i++;}
            if(count($MDTA) == 0){$msg->addError(EST_INST_SAMPLEMEDIA0.' '.$DESTDIR.' - '.EST_INST_SAMPLEMEDIA2);}
            else{
              $FCPY = array('f'=>array(),'s'=>array());
              foreach($MDTA as $mk=>$mv){
                $CPYFILE = $tp->toHTML($mv['media_thm']);
                $SRCFILE = e_PLUGIN.'estate/xml/sample/'.$CPYFILE;
                $DESTFILE = $DESTDIR.$CPYFILE;
                touch($DESTFILE);
                
                if(file_exists($SRCFILE) && is_file($SRCFILE)){
                  if(!copy($SRCFILE,$DESTFILE)){
                    $errors = error_get_last();
                    $FCPY['f'][$mk] = $SRCFILE.' = '.$errors['type'].' - '.$errors['message'];
                    }
                  else{
                    $FCPY['s'][$mk] = $CPYFILE;
                    //@chmod($DESTFILE, 0644);
                    }
                  }
                else{
                  if(!file_exists($SRCFILE)){$FCPY['f'][$mk] = $SRCFILE.' - '.EST_INST_SAMPLEMEDIA4;}
                  if(!is_file($SRCFILE)){$FCPY['f'][$mk] = $SRCFILE.' - '.EST_INST_SAMPLEMEDIA5;}
                  }
                unset($SRCFILE,$DESTFILE,$errors);
                clearstatcache();
                }
              
              if(count($FCPY['f']) > 0){
                foreach($FCPY['f'] as $fci=>$fcv){(trim($fcv) !== '' ? $FCV .= '<li>'.$fcv.'</li>' : '');}
                }
              
              if(count($FCPY['s']) > 0){
                foreach($FCPY['s'] as $sci=>$scv){(trim($scv) !== '' ? $SCV .= '<li>'.$scv.'</li>' : '');}
                }
              if($FCV){$msg->addWarning(EST_INST_SAMPLEMEDIA3.':<ul>'.$FCV.'</ul>');}
              if($SCV){$msg->addSuccess(EST_INST_SAMPLEMEDIA10.':<ul>'.$SCV.'</ul>');}
              unset($FCPY,$fci,$fcv,$sci,$scv,$FCV,$SCV);
              }
            }
          }
        
        if(!empty($ret3['failed'])){
          $msg->addError(EST_INST_SAMPLEPROP2);
          $msg->addDebug(print_a($ret3['failed'],true));
          }
        }
      
      $msg->addInfo('<div><p><b>'.EST_GEN_IMPORTANT.'</b>: '.EST_INST_SETCLASS1.'</p><ul><li>'.EST_INST_CLASS0.'</li><li>'.EST_INST_CLASS1.'</li><li>'.EST_INST_CLASS2.'</li><li>'.EST_INST_CLASS3.'</li></ul><p>'.EST_INST_SETCLASS2.'</p></div>');
      
      if(ADMINPERMS === '0'){
        $msg->addInfo('<p>'.EST_INST_SETCLASS3.'</p>');
        }
      else{
        $msg->addInfo('<p>'.EST_INST_SETCLASS4.'</p><a class="btn btn-primary" href="'.e_ADMIN.'users.php">'.EST_GEN_ASSUSRCLASSES.'</a>');
        }
      
      }

		function uninstall_options()
		{

			/*
      $listoptions = array(0=>'option 1',1=>'option 2');

			$options = array();
			$options['mypref'] = array(
					'label'		=> 'Custom Uninstall Label',
					'preview'	=> 'Preview Area',
					'helpText'	=> 'Custom Help Text',
					'itemList'	=> $listoptions,
					'itemDefault'	=> 1
			);

			return $options;
      */
		}


		function uninstall_post($var){
      $sql = e107::getDB();
      $tp = e107::getParser();
      $msg = e107::getMessage();
      
      $msg->addWarning('<p>'.EST_UNINSTALL1.'<br />'.EST_UNINSTALL2.'</p><p>'.EST_UNINSTALL3.'</p>');
      
      $estFolders = array(
        'agency'=>1,
        'agent'=>1,
        'prop'=>array('full','thm','vid'),
        'subdiv'=>array('full','thm','vid'),
        );
      
      $i=0;
      $fdelmsg = array('err','ok');
      $estPath = e_PLUGIN.'estate/media/';
      foreach($estFolders as $fldr=>$fldv){
        if(is_dir($estPath.$fldr)){
          $fdelmsg['ok'][$i][0] = 'DIR "'.$estPath.$fldr.'":';
          if(is_array($fldv)){
            $si = 1;
            foreach($fldv as $subk=>$subv){
              if(is_dir($estPath.$fldr.'/'.$subv)){
                $fdelmsg['ok'][$i][$si] = 'Sub-Folder "'.$estPath.$fldr.'/'.$subv.'": ';
                if($DIR = opendir($estPath.$fldr.'/'.$subv)){
                  $xi = 0;
                  while(false !== (($FNAME = readdir($DIR)))){
                    if($FNAME !=='.' && $FNAME !=='..' && strtolower($FNAME) !== 'index.html' && strtolower($FNAME) !== 'index.php'){
                      $FTODEL = $estPath.$fldr.'/'.$subv.'/'.$FNAME;
                      if(is_file($FTODEL)){
                        if(@unlink($FTODEL)){$xli .= '<li>'.$FNAME.' - '.EST_GEN_DELETED.'</li>';}
                        else{$fileErr .= '<li>'.$FNAME.' - '.EST_GEN_NOT.' '.EST_GEN_DELETED.'</li>';}
                        $xi++;
                        }
                      else{
                        $fileErr .= '<li>'.$FNAME.' - '.EST_GEN_NOT.' '.EST_GEN_FILE.'</li>';
                        }
                      }
                    }
                  $fdelmsg['ok'][$i][$si] .= ($xi > 0 ? '<a onclick="$(\'#estFileList-'.$i.'-'.$si.'\').show()" >View '.$xi.' '.($xi == 1 ? 'File' : 'Files').'</a><ul id="estFileList-'.$i.'-'.$si.'" style="display:none">'.$xli.'</ul>' : 'No Files');
                  unset($xi,$xli);
                  }
                else{$fdelmsg['ok'][$i][$si] .= 'FAILED TO OPEN';}
                }
              else{
                $fdelmsg['err'][$i][$si] = 'Sub-Folder "'.$estPath.$fldr.'/'.$subv.' NOT OK"';
                }
              $si++;
              }
            }
          else{
            $fdelmsg['ok'][$i][0] = 'DIR "'.$estPath.$fldr.'": ';
            if($DIR = opendir($estPath.$fldr)){
              $xi = 0;
              while(false !== (($FNAME = readdir($DIR)))){
                if($FNAME !=='.' && $FNAME !=='..' && strtolower($FNAME) !== 'index.html' && strtolower($FNAME) !== 'index.php'){
                  $FTODEL = $estPath.$fldr.'/'.$FNAME;
                  if(is_file($FTODEL)){
                    if(@unlink($FTODEL)){$xli .= '<li>'.$FNAME.' - '.EST_GEN_DELETED.'</li>';}
                    else{$fileErr .= '<li>'.$FNAME.' - '.EST_GEN_NOT.' '.EST_GEN_DELETED.'</li>';}
                    }
                  else{$fileErr .= '<li>'.$FNAME.' - '.EST_GEN_NOT.' '.EST_GEN_FILE.'</li>';}
                  
                  $xi++;
                  }
                }
              $fdelmsg['ok'][$i][0] .= ($xi > 0 ? '<a onclick="$(\'#estFileList-'.$i.'\').show()" >View '.$xi.' '.($xi == 1 ? 'File' : 'Files').'</a><ul id="estFileList-'.$i.'" style="display:none">'.$xli.'</ul>' : 'No Files');
              unset($xi,$xli);
              }
            else{$fdelmsg['ok'][$i][0] .= 'FAILED TO OPEN';}
            }
          }
        else{$fdelmsg['err'][$i][0] = 'DIR "'.$estPath.$fldr.'" NOT OK';}
        $i++;
        }
      
      
      if(isset($fileErr)){
        $msg->addError('ERROR FINDING FILES TO DELETE:<ul>'.$fileErr.'</ul>');
        }
      
      if(is_array($fdelmsg['err']) && count($fdelmsg['err']) > 0){
        foreach($fdelmsg['err'] as $mk=>$mv){
          $emsg .= '<li>';
          if(is_array($mv)){
            $emsg .= $mv[0].'<ul>';
            unset($mv[0]);
            foreach($mv as $smk=>$smv){
              if(is_array($smv)){
                $emsg .= $smv[0].'<ul>';
                unset($smv[0]);
                foreach($smv as $pmk=>$pmv){
                  $emsg .= '<li>'.$pmv.'</li>';
                  }
                $emsg .= '</ul>';
                }
              else{
                $emsg .= '<li>'.$smv.'</li>';
                }
              }
            $emsg .= '</ul>';
            }
          else{
            $emsg .= $mv;
            }
          $emsg .= '</li>';
          }
        $msg->addError('<div>Error Deleting Files<ul>'.$emsg.'</ul></div>');
        unset($emsg);
        }
      
      
      if(is_array($fdelmsg['ok']) && count($fdelmsg['ok']) > 0){
        foreach($fdelmsg['ok'] as $mk=>$mv){
          $emsg .= '<li>';
          if(is_array($mv)){
            $emsg .= $mv[0].'<ul>';
            unset($mv[0]);
            foreach($mv as $smk=>$smv){
              if(is_array($smv)){
                $emsg .= $smv[0].'<ul>';
                unset($smv[0]);
                foreach($smv as $pmk=>$pmv){
                  $emsg .= '<li>'.$pmv.'</li>';
                  }
                $emsg .= '</ul>';
                }
              else{
                $emsg .= '<li>'.$smv.'</li>';
                }
              }
            $emsg .= '</ul>';
            }
          else{
            $emsg .= $mv;
            }
          $emsg .= '</li>';
          }
        $msg->addInfo('<div>Deleting Files:<ul>'.$emsg.'</ul></div>');
        unset($emsg);
        
        
        }
      
      
      
      $RUIDS = array();
      if($sql->gen('SELECT user_id,user_name,user_admin,user_perms FROM #user WHERE user_admin="1"')){
        while($row = $sql->fetch()){
          if($row['user_perms'] !== '0'){
            $row['user_perms'] = explode('.',$row['user_perms']);
            if(in_array(EST_PLUGID,$row['user_perms'])){array_push($RUIDS,$row);}
            }
          }
        }
      
      if(count($RUIDS) > 0){
        $ESTCLASSES = array(ESTATE_ADMIN,ESTATE_MANAGER,ESTATE_AGENT);
        
        foreach($RUIDS as $k=>$v){
          $emsg .= '<li>'.EST_UPDATED.' '.$tp->toHTML($v['user_name']).' - ';
          $YRP = array();
          foreach($v['user_perms'] as $pi=>$pv){if($pv !== EST_PLUGID){array_push($YRP,$pv);}}
          
          $YRC = array();
          $v['user_class'] = explode(',',$v['user_class']);
          foreach($v['user_class'] as $ci=>$cv){if(!in_array($cv,$ESTCLASSES)){array_push($YRC,$cv);}}
          
          if($sql->update("user","user_admin='".(count($YRP) > 0 ? '1' : '0')."',user_perms='".implode('.',$YRP)."', user_class='".implode(',',$YRC)."' WHERE user_id='".intval($v['user_id'])."' LIMIT 1")){
            $emsg .= EST_GEN_SUCCESS;
            }
          else{$emsg .= EST_GEN_FAILED;}
          $emsg .= '</li>';
          unset($PRMS,$YRP);
          }
        }
      else{$emsg .= '<li>'.EST_GEN_NONEFOUND.'</li>';}
      $msg->addSuccess('<div>'.EST_INST_LOOKFORADMWPERMS.' (ID: '.EST_PLUGID.')<ul>'.$emsg.'</ul></div>');
		  }




		function upgrade_post($var)
		{
			// $sql = e107::getDb();
		}

	}

}
