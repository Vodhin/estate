// JavaScript Document

var vrePath = window.location.pathname;
var vrePathPts = vrePath.split('/');
var vrePage = vrePathPts.pop();
var vreBasePath = vrePath.replace(vrePage,'');
var vreFeud = vreBasePath+'js/adm/feud.php';

var files;

var JQDIV = '<div></div>';
var JQSPAN = '<span></span>';
var JQBTN = '<button></button>';
var JQNPT = '<input />';
var JQOPT = '<option></option>';
var JQTABLE = '<table></table>';
var JQCOLGRP = '<colgroup></colgroup>';
var JQTHEAD = '<thead></thead>';
var JQTBODY = '<tbody></tbody>';
var JQTFOOT = '<tfoot></tfoot>';
var JQTR = '<tr></tr>';
var JQTH = '<th></th>';
var JQTD = '<td></td>';
var JQEDI = '<i class="fa fa-pencil-square-o"></i>';



function estAlertLog(msg){
  alert(msg);
  console.log(msg);
  }


function lightOrDark(color){
  var r, g, b, hsp;
  if(color.match(/^rgb/)){
    color = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);
    r = color[1];
    g = color[2];
    b = color[3];
    } 
  else{
    color = +("0x" + color.slice(1).replace( 
    color.length < 5 && /./g, '$&$&'));
    r = color >> 16;
    g = color >> 8 & 255;
    b = color & 255;
    }
  
  hsp = Math.sqrt(0.299 * (r * r) + 0.587 * (g * g) + 0.114 * (b * b));

  if (hsp>127.5) {return 'light';} 
  else {return 'dark';}
  }



(function ($) {
  var cSave = $('input[type="submit"]');
  var cForm = $('#plugin-estate-OAform');
  
  function estDefDta(tabName){
    var defs = $('body').data('defs');
    var tbl = defs.tbls[tabName];
    var defDta = {};
    if(typeof tbl !== 'undefined'){
      if(typeof tbl.flds !== 'undefined' && typeof tbl.form !== 'undefined'){
        $(tbl.flds).each(function(fi,fldn){
          var fVal = '';
          if(typeof tbl.form[fldn] !== 'undefined'){
            switch(fldn){
              case 'agency_lat' : fVal = defs.prefs.pref_lat; break;
              case 'agency_lon' : fVal = defs.prefs.pref_lon; break;
              case 'agency_zoom' : fVal = defs.prefs.pref_zoom; break;
              case 'agency_addr_lookup' : fVal = defs.prefs.pref_addr_lookup; break;
              case 'agency_timezone' : fVal = defs.user.agency_timezone; break;
              }
          
            if(typeof tbl.form[fldn].str !== 'undefined'){
              if(tbl.form[fldn].str == 'int'){fVal=Number(0);}
              }
            if(typeof tbl.form[fldn].type !== 'undefined'){
              if(tbl.form[fldn].type == 'idx'){fVal=Number(0);}
              }
            }
          defDta[fldn] = fVal;
          });
        }
      }
    //console.log(defDta);
    return defDta;
    }
  
  function estProcDefDta(newDefs=null){
    var defs = $('body').data('defs');
    if(newDefs !== null){
      defs.tbls = newDefs;
      $('body').data('defs',defs);
      }
    
    $.each(defs.tbls, function(tbl,tDta){
      if(typeof tDta.form !== 'undefined'){
        $.each(tDta.form, function(fname,fdta){
          if(fdta.str == 'int' || fdta.type == 'idx'){
            $(defs.tbls[tbl].dta).each(function(gi,gEle){
              defs.tbls[tbl].dta[gi][fname] = Number(defs.tbls[tbl].dta[gi][fname]);
              });
            }
          });
        }
      });
    }
  
  
  function estMediaTitle(tdta,xd=null){
    //console.log(xd,tdta);
    if(typeof tdta == 'undefined'){return '';}
    if(typeof tdta.media_name == 'undefined'){return '';}
    else{
      var defs = $('body').data('defs');
      if(tdta.media_name.length < 2){
        if(Number(tdta.media_lev) == 2){
          tdta.media_name = defs.txt.space+' '+tdta.media_levidx+(Number(tdta.media_levord) > 0 ? ' #'+tdta.media_levord : '');
          }
        else if(Number(tdta.media_lev) == 1){
          tdta.media_name = defs.txt.property+(Number(tdta.media_galord) > 0 ? ' #'+tdta.media_galord : '');
          }
        else{
          tdta.media_name = defs.txt.subdiv+(Number(tdta.media_levord) > 0 ? ' #'+tdta.media_levord : '');
          }
        
        $(tdta.targ).data(tdta);
        
        //save new caption
        var mediaDta = defs.tbls.estate_media.dta.find(x => Number(x.media_idx) === Number(tdta.media_idx));
        var mediaKey = defs.tbls.estate_media.dta.indexOf(mediaDta);
        if(mediaKey > -1){
          var propId = Number($('body').data('propid'));
          var sdta = {'maintbl':'estate_media','mainkey':'media_idx','mainkx':'txt','mainidx':Number(mediaDta.media_idx),'mainfld':'media_name','nval':tdta.media_name};
          console.log('Saving New Caption:',sdta);
          $.ajax({
            url: vreFeud+'?6||0',
            type:'post',
            data:{'fetch':6,'propid':propId,'rt':'js','tdta':sdta},
            dataType:'json',
            cache:false,
            processData:true,
            success: function(ret, textStatus, jqXHR){
              defs.tbls.estate_media.dta[mediaKey] = mediaDta;
              },
            error: function(jqXHR, textStatus, errorThrown){
              console.log('ERRORS: '+textStatus+' '+errorThrown+' '+jqXHR.responseText);
              }
            });
          }
        }
      return tdta.media_name;
      }
    }
  
  function estMediaFldClean(eDta){
    var mediaDta = estDefDta('estate_media');
    mediaDta.media_idx = Number(eDta.media_idx);
    mediaDta.media_propidx = Number(eDta.media_propidx);
    mediaDta.media_lev = Number(eDta.media_lev);
    mediaDta.media_levidx = Number(eDta.media_levidx);
    mediaDta.media_levord = Number(eDta.media_levord);
    mediaDta.media_galord = Number(eDta.media_galord);
    mediaDta.media_asp = eDta.media_asp;
    mediaDta.media_type = Number(eDta.media_type);
    mediaDta.media_thm = eDta.media_thm;
    mediaDta.media_full = eDta.media_full;
    mediaDta.media_name = eDta.media_name;
    return mediaDta;
    }
  
  
  function estNewMediaDta(lev=1){
    var defs = $('body').data('defs');
    var propId = Number($('body').data('propid'));
    var levIdx = 0;
    var levName = '';
    if(document.getElementById('estPopCont')){
      var popIt = $('#estPopCont').data('popit');
      var levDta = $('#estPopForm0').data('levdta');
      if(typeof levDta !== 'undefined'){
        levIdx = levDta[defs.tbls.estate_sects[lev][1]];
        levName = levDta[defs.tbls.estate_sects[lev][2]];//.replace(/[^a-z0-9]/gi,'');
        }
      }
    var mediaDta = estDefDta('estate_media');
    
    mediaDta.media_propidx = propId;
    mediaDta.media_lev = lev;
    mediaDta.media_levidx = levIdx;
    mediaDta.media_asp = 1;
    mediaDta.media_name = levName;
    return mediaDta;
    }
  
  
  
  
  
  function estFileUplFld(mediaDta,mType=1,pvwTarg=null,destTarg=0,preUpFile=null){
    if(preUpFile !== null){
      var UpFileId = $(preUpFile).attr('id');
      var putUpFile = $(preUpFile).parent();
      $(preUpFile).remove().promise().done(function(){
        var upFile = $(JQNPT,{'type':'file','id':UpFileId,'name':UpFileId,'class':'noDISP'}).prop('accept','image/jpeg, image/png, image/gif').data({'media':mediaDta,'filek':0,'filex':0,'desttarg':destTarg}).on({
          blur : function(e){console.log('Blur',e.target.files);},
          change : function(e){
            e.preventDefault();
            prepareUpload(e,upFile,pvwTarg,destTarg);
            }
          }).appendTo(putUpFile).promise().done(function(){
            $(upFile).click();
            });
        });
      }
    else{
      $('#upFile').remove().promise().done(function(){
        var upFile = $(JQNPT,{'type':'file','id':'upFile','name':'upFile'}).data({'media':mediaDta,'filek':0,'filex':0,'desttarg':destTarg}).on({
          blur : function(e){console.log('Blur',e.target.files);},
          change : function(e){
            e.preventDefault();
            prepareUpload(e,upFile,pvwTarg,destTarg);
            }
          });
        
        if(mType == 1){
          $(upFile).prop('accept','image/jpeg, image/png, image/gif');
          if(pvwTarg == null){$(upFile).prop('multiple',true);}
          }
        
        $(upFile).prependTo('#estGalFileSlipCont');
        
        if(destTarg > 3){$('#fileSlipBtn').parent().click();}
        if(destTarg == 0){
          if(pvwTarg == null){
            $('#fileSlipBtn2').prop('disabled',0);
            $('#fileSlipBtn').prop('disabled',0);
            }
          else{
            $('#fileSlipBtn2').prop('disabled',1);
            $('#fileSlipBtn').prop('disabled',1);
            $('#fileSlipBtn').parent().click();
            }
          }
        });
      }
    
    if(typeof upFile !== 'undefined'){return upFile;}
    }
  
  
  function uplFileComplete(lev=1){
    console.log('All Files Uploaded');
    $('#estPopCover').remove();
    var mediaDta = estNewMediaDta(lev);
    estFileUplFld(mediaDta,1);
    estPopHeight(1);
    }
  
  var Upload = function (file) {this.file = file;};
  Upload.prototype.getType = function(){return this.file.type;};
  Upload.prototype.getSize = function(){return this.file.size;};
  Upload.prototype.getName = function(){return this.file.name;};
  
  
  Upload.prototype.doUpload = function(destTarg=0){
    var defs = $('body').data('defs');
    var propId = Number($('body').data('propid'));
    var mediaDta = this.file;
    
    var formData = new FormData();
    formData.append("fetch", 4);
    formData.append("rt", "js");
    formData.append("upload_file", true);
    formData.append("file", this.file, this.getName());
    formData.append("desttarg", destTarg);
    formData.append("propid", propId);
    formData.append("media_idx", Number(mediaDta.media_idx));
    formData.append("media_propidx", Number(mediaDta.media_propidx));
    formData.append("media_lev", Number(mediaDta.media_lev));
    formData.append("media_levidx", Number(mediaDta.media_levidx));
    formData.append("media_levord", Number(mediaDta.media_levord));
    formData.append("media_galord", Number(mediaDta.media_galord));
    formData.append("media_asp", Number(mediaDta.media_asp));
    formData.append("media_type", Number(mediaDta.media_type));
    formData.append("media_thm", mediaDta.media_thm);
    formData.append("media_full", Number(mediaDta.media_full));
    formData.append("media_name", mediaDta.media_name);
    
    if(!document.getElementById('estPopCover')){
      $(JQDIV,{'id':'estPopCover'}).on({
        click : function(e){
          e.stopPropagation();
          $('#estPopCover').remove();
          }
        }).appendTo('#estPopCont');
      }
    
    $.ajax({
      type: "POST",
      url: vreFeud+'?4||0',
      xhr: function(){
        var xhr = $.ajaxSettings.xhr();
        if(xhr.upload){
          xhr.upload.addEventListener('progress', function(event){
            var pctcomp = 0;
            var position = event.loaded || event.position;
            var total = event.total;
            if(event.lengthComputable){pctcomp = Math.ceil(position / total * 100);}
            if(pctcomp > 99){
              $(mediaDta.targ[1]).css({'opacity':100});
              $(mediaDta.targ[3]).attr({value: 100, max: 100}).fadeOut(250,function(){$(mediaDta.targ[3]).remove()});
              }
            else{
              $(mediaDta.targ[3]).attr({value: pctcomp, max: 100});
              if(pctcomp < 15){$(mediaDta.targ[1]).css({'opacity':0.15});}
              else{$(mediaDta.targ[1]).css({'opacity':(pctcomp / 100)});}
              }
            }, false);
          }
        return xhr;
        },
      success: function(ret){
        console.log(ret);
        var xDta = ret.upl.file.fdta;
        $(mediaDta.targ[0]).removeData();
        $(mediaDta.targ[1]).fadeOut(500,function(){$(mediaDta.targ[1]).remove()});
        if(destTarg == 0){
          var gDta = estMediaFldClean(xDta);
          var mediaTitle = estMediaTitle(gDta,'upload image');
          $(mediaDta.targ[0]).data(gDta).css({'background-image':'url('+defs.dir.prop.thm+gDta.media_thm+'?'+Math.floor(Math.random()*(99999-99+1)+99)+')'});
          $(mediaDta.targ[0]).addClass('pvw-'+gDta.media_idx);
          estPrepMediaEditCapt(gDta,mediaTitle,mediaDta.targ[0]);
          }
        },
      error: function(error){
        $('#estPopCover').hide();
        console.log(error);
        console.log(mediaDta);
        alert(error.responseText);
        $(mediaDta.targ[0]).remove();
        },
      async: true,
      data: formData,
      dataType:'json',
      cache: false,
      contentType: false,
      processData: false,
      timeout: 60000
      }).done(function(data){
        console.log(destTarg,data);
        var filex = Number($('#upFile').data('filex'));
        var filek = Number($('#upFile').data('filek')) + 1;
        $('#upFile').data('filek',filek);
        if(filek == filex){
          var fdta = data.upl.file.fdta;
          if(typeof data.alldta !== 'undefined'){
            estProcDefDta(data.alldta.tbls);
            }
          uplFileComplete(fdta.media_lev);
          estBuildGallery();
          }
        });
    };
  
  
  function prepareUpload(e,upFile,pvwTarg,destTarg=0){
    files = e.target.files;
    var propId = Number($('body').data('propid'));
    var mediaDta = $(upFile).data('media');
    var fCount = this.files.length;
    $('.mediaEditBox').remove();
    
    if(this.files && fCount > 0){
      $('#fileSlipBtn2').prop('disabled',1);
      $('#fileSlipBtn').prop('disabled',1);
      $(upFile).data('filex',fCount);
      
      if(pvwTarg == null){
        if(document.getElementById('estMediaMgrCont')){var uplTarg = $('#estMediaMgrCont')}
        else{var uplTarg = $('#estGalleryBelt');}
        }
        
      var fileMx = files.length - 1;
      var fileord = 1;
      if(destTarg == 0){fileord = $(uplTarg).children('.upldPvwBtn').length;}
      var ulbtn = [];
      var upload = [];
        
      $(files).each(function(k,v){
        fileord++;
        ulbtn[k] = [];
        if(pvwTarg !== null){ulbtn[k][0] = pvwTarg;}
        else{ulbtn[k][0] = $(JQDIV,{'class':'upldPvwBtn'}).appendTo(uplTarg);}
        
        ulbtn[k][1] = $(JQDIV).appendTo(ulbtn[k][0]);
        ulbtn[k][2] = $('<img />').appendTo(ulbtn[k][0]);
        ulbtn[k][3] = $('<progress></progress>',{'id':'fileProg-'+k,'value':'0','max':'100'}).appendTo(ulbtn[k][0]);
        
        switch(destTarg){
          case 6 :
            $(uplTarg).addClass('estUploading');
            var fDta = mediaDta;
            fDta.agent_idx = mediaDta.agent_idx;
            fDta.agent_image = 'agent-'+mediaDta.agent_idx;
            break;
          case 5 :
            $(uplTarg).addClass('estUploading');
            var fDta = mediaDta;
            fDta.agency_idx = mediaDta.agency_idx;
            fDta.agency_image = 'agency-'+mediaDta.agency_idx;
            break;
          case 4 :
            break;
          default :
            var fDta = estMediaFldClean(mediaDta);
            fDta.media_asp = 1;
            fDta.media_levord = (Number(fDta.media_levord) == 0 ? fileord : fDta.media_levord);
            break;
          }
        
        $.extend(fDta,{'natw':0,'nath':0,'targ':ulbtn[k],'desttarg':destTarg,'propid':propId});
        
        var reader = new FileReader();
        if(v.type =='image/jpeg' || v.type =='image/png' || v.type =='image/gif'){
          fDta.media_type = 1;
          var img = $(ulbtn[k][2])[0];
          reader.onload = function(e){
            $(ulbtn[k][1]).css({'background-image':'url('+e.target.result+')'});
            $(ulbtn[k][2]).attr('src',e.target.result);
            img.src = e.target.result;
            img.onload = function(){
              fDta.natw = img.naturalWidth;
              fDta.nath = img.naturalHeight;
              if(destTarg == 6){
                fDta.agent_image = fDta.agent_image+'.'+(v.type =='image/jpeg' ? 'jpg' : (v.type =='image/png' ? 'png' : 'gif'));
                }
              else{
                fDta.media_asp = parseFloat(img.naturalWidth / img.naturalHeight).toFixed(3);
                $(ulbtn[k][0]).css({'width':Math.floor($(ulbtn[k][0]).height() * fDta.media_asp)});
                }
              
              
              $(ulbtn[k][0]).data(v);
              $.extend(v,fDta);
              upload[k] = new Upload(v);
              upload[k].doUpload(destTarg);
              $(ulbtn[k][2]).remove();
              }
            }
          reader.readAsDataURL(v);
          }
        else{
          //$(ulbtn[k][0]).addClass('uplIcon').css({'background-image':'url('+imgPth+'/blue64/document.png)'});
          reader.onload = function(e){console.log(e.target);}
          fDta.media_type = 2;
          $.extend(v,fDta);
          $(ulbtn[k][0]).data(v);
          upload[k] = new Upload(v);
          upload[k].doUpload(destTarg);
          }
        
        if(pvwTarg == null){
          $(ulbtn[k][0]).on({
            mouseenter : function(e){estMediaEditBtns(1,this)},
            mouseleave : function(e){estMediaEditBtns(-1)}
            });
          }
        }).promise().done(function(){
          estPopHeight(1);
          $(upload).each(function(k,upl){
            console.log(upl);
            });
          });
      }
    else{
      console.log(files);
      }
    }
  
  
  function estMediaDelGo(mediaDta,targEle){
    var defs = $('body').data('defs');
    var propId = Number($('body').data('propid'));
    if(typeof mediaDta == 'undefined'){estAlertLog('No Media Data'); return;}
    if(typeof mediaDta.media_idx == 'undefined'){estAlertLog('No Media Data'); return;}
    if(mediaDta.media_idx > 0){
      delete mediaDta.targ;
      var sDta = {'fetch':5,'propid':propId,'rt':'js','mediadta':mediaDta};
      console.log(sDta);
      $.ajax({
        url: vreFeud+'?6||0',
        type:'post',
        data:sDta,
        dataType:'json',
        cache:false,
        processData:true,
        success: function(ret, textStatus, jqXHR){
          if(typeof ret.error !== 'undefined'){
            estAlertLog(ret.error);
            }
          else{
            if(typeof ret.alldta !== 'undefined'){
              estProcDefDta(ret.alldta.tbls);
              $(targEle).fadeOut(250, function(){
                $(targEle).remove().promise().done(function(){
                  estMediaDeepReorder();
                  });
                });
              }
            }
          },
        error: function(jqXHR, textStatus, errorThrown){
          console.log('ERRORS: '+textStatus+' '+errorThrown);
          estAlertLog(jqXHR.responseText);
          }
        });
      }
    else{
      estAlertLog('Media ID missing');
      }
    }
  
  function estMediaEditBtns(mode,mediaThm){
    $('#mediaEditBox').remove().promise().done(function(){
      if($(mediaThm).hasClass('estUploading')){return;}
      if(mode == -2){estInitSetupUpl(mediaThm);}
      
      if(mode > 0){
        var defs = $('body').data('defs');
        var mediaEditBox = $(JQDIV,{'id':'mediaEditBox','class':'mediaEditBox upld'}).prependTo(mediaThm);
        var srcBtn = $(JQBTN,{'id':'estAvatSrcBtn','class':'btn btn-primary btn-sm estNoLRBord TAL','title':defs.txt.changeimgsrc}).appendTo(mediaEditBox);
        var uplBtn = $(JQBTN,{'id':'estAvatUplBtn','class':'btn btn-default btn-sm estNoLRBord','title':defs.txt.upload+' '+defs.txt.new1+' '+defs.txt.image}).appendTo(mediaEditBox);
        $(JQSPAN,{'class':'fa fa-upload'}).appendTo(uplBtn);
        
          $(srcBtn).remove();
          $(uplBtn).remove();
          
          var mediaDta = $(mediaThm).data();
          //console.log(mediaDta);
          $(mediaEditBox).data('mediadta',mediaDta);
          var mediaTitle = estMediaTitle(mediaDta,'edit media btns');
          
          var editBtn = $(JQBTN,{'class':'btn btn-default btn-sm estNoRightBord','title':defs.txt.crop+' '+defs.txt.image}).on({
            click : function(e){
              e.preventDefault();
              var mediaDta = $(this).parent().data('mediadta');
              var popDta = {'tbl':'estate_media','fld':'media_thm','defdta':mediaDta,'mediaThm':mediaThm} //,'fnct':{'name':'estSpaceGroupChange'}
              if(document.getElementById('estPopCont')){estPopoverAlt(1,popDta);}
              else{
                var popIt = estBuildPopover([{'tabs':[defs.txt.zoomcrop,defs.txt.image+' '+defs.txt.data],'fnct':{'name':'estSHCropBtn','args':1}}]);
                estEditMEdiaForm(0,popIt,popDta);
                }
              }
            }).appendTo(mediaEditBox);
          $(JQSPAN,{'class':'fa fa-crop-alt'}).appendTo(editBtn); //pencil-square-o
          
          var uplBtn = $(JQBTN,{'class':'btn btn-default btn-sm estNoLRBord','title':defs.txt.mediareplace+' '+mediaTitle}).on({
            click : function(e){
              e.stopPropagation();
              e.preventDefault();
              var mediaDta = $(this).parent().data('mediadta');
              if(mediaDta.media_idx > 0){
                estFileUplFld(mediaDta,1,mediaThm);
                }
              }
            }).appendTo(mediaEditBox);
          $(JQSPAN,{'class':'fa fa-upload'}).appendTo(uplBtn);
          
          var delMedBtn = $(JQBTN,{'class':'btn btn-default btn-sm estNoLeftBord','title':defs.txt.deletes+' '+defs.txt.image+': '+mediaTitle}).on({
            click : function(e){
              e.stopPropagation();
              e.preventDefault();
              var mediaDta = $(this).parent().data('mediadta');
              var targEle = $(this).parent().parent();
              if(jsconfirm(defs.txt.deletes+' '+mediaTitle+' '+defs.txt.image+'?')){
                estMediaDelGo(mediaDta,targEle);
                }
              }
            }).appendTo(mediaEditBox);
          $(JQSPAN,{'class':'fa fa-close'}).appendTo(delMedBtn);
          
        }
      });
    }
  
  
  function estSetMediaMgrSorting(){
    $('#estMediaMgrCont').children('div.upldPvwBtn').sort(function (a, b){
      var cA = $(a).data().media_levord;
      var cB = $(b).data().media_levord;
      return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
      }).appendTo('#estMediaMgrCont').promise().done(function(){
        var mediaGrpCont = document.getElementById('estMediaMgrCont');
        Sortable.create(mediaGrpCont,{
          group: 'estSortMedia', 
          draggable: '.upldPvwBtn',
          sort: true,
          animation: 450,
          //handle: '.ui-sortable-handle',
          pull: true,
          put: true,
          ghostClass: 'sortTR-ghost',
          chosenClass: 'sortTR-chosen', 
          dragClass: 'sortTR-drag',
          onChoose: function(evt){},
          onEnd: function(evt){
            var defs = $('body').data('defs');
            var tDta = [];
            var li = 0;
            $('#estMediaMgrCont').children('div.upldPvwBtn').each(function(i,ele){
              var eDta = $(ele).data();
              if(Number(eDta.media_levord) !== (i + 1)){
                eDta.media_levord = i + 1;
                $('.pvw-'+eDta.media_idx).data(eDta);
                fdta = estMediaFldClean(eDta);
                tDta.push({'tbl':'estate_media','key':'media_idx','fdta':fdta,'del':0});
                }
              if(Number(eDta.media_lev) > 0 && Number(eDta.media_type) == 1 && Number(eDta.media_levord) == 1){
                var mURL = defs.dir.prop.thm+eDta.media_thm+'?'+Math.floor(Math.random()*(99999-99+1)+99);
                $('#estSectThm-'+eDta.media_lev+'-'+eDta.media_levidx).css({'background-image':'url('+mURL+')'});
                }
              }).promise().done(function(){
                estSaveElemOrder(tDta,1);
                });
            }
          });
        });
    }
  
  function estSaveElemOrder(tDta,sect=0){
    if(tDta.length > 0){
      var propId = Number($('body').data('propid'));
      var sDta = {'fetch':3,'propid':propId,'rt':'js','tdta':tDta};
      $.ajax({
        url: vreFeud+'?5||0',
        type:'post',
        data:sDta,
        dataType:'json',
        cache:false,
        processData:true,
        success: function(ret, textStatus, jqXHR){
          //console.log(ret);
          var kx = tDta.length-1;
          if(typeof ret[kx].error !== 'undefined'){
            estAlertLog(ret[kx].error);
            $('#estPopContRes'+0).html(ret[kx].error).fadeIn(200,function(){estPopHeight(1)});
            }
          else{
            if(typeof ret[kx].alldta !== 'undefined'){
              estProcDefDta(ret[kx].alldta.tbls);
              if(sect == 1){
                $(document).find('div.mediaPvwCapt').each(function(pvi,capEle){
                  var pvDta = $(capEle).parent().data();
                  var mediaTitle = estMediaTitle(pvDta,'save img order');
                  $(capEle).html(mediaTitle);
                  });
                }
              }
            }
          },
        error: function(jqXHR, textStatus, errorThrown){
          console.log('ERRORS: '+textStatus+' '+errorThrown);
          estAlertLog(jqXHR.responseText);
          }
        });
      }
    }
  
  
  function estMediaDeepReorder(){
    var defs = $('body').data('defs');
    var propId = Number($('body').data('propid'));
    
    var mGrps = [];
    $(defs.tbls.estate_sects).each(function(si,sDta){
      mGrps[si] = [];
      var levGrep = $.grep(defs.tbls.estate_media.dta, function (element, index) {return  element.media_lev == si;});
      $(defs.tbls[sDta[0]].dta).each(function(ti,tXta){
        
        if(si > 0){var unSort = $.grep(levGrep, function (element, index) {return Number(element.media_levidx) == Number(tXta[sDta[1]]);});}
        else{var unSort = $.grep(levGrep, function (element, index) {return Number(element.media_levidx) == Number(0);});}
        
        mGrps[si][ti] = $(unSort).sort(function(a, b){
          if(a.media_levord == b.media_levord){var cA = a.media_idx, cB = b.media_idx;}
          else{var cA = a.media_levord, cB = b.media_levord;}
          return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
          });
        });
      }).promise().done(function(){
        var tDta = [];
        $(mGrps).each(function(gi,mDta){
          if(mDta.length > 0){
            $(mDta).each(function(ki,kDta){
              var pic1 = 0;
              if(kDta.length > 0){
                $(kDta).each(function(qi,qDta){
                  
                  if(Number(qDta.media_levord) !== (qi + 1)){
                    qDta.media_levord = qi + 1;
                    fdta = estMediaFldClean(qDta);
                    tDta.push({'tbl':'estate_media','key':'media_idx','fdta':fdta,'del':0});
                    var eDta = $('.pvw-'+qDta.media_idx).eq(0).data();
                    if(typeof eDta !== 'undefined'){
                      eDta.media_levord = qi + 1;
                      $('.pvw-'+eDta.media_idx).data(eDta);
                      }
                    }
                  
                  if(Number(qDta.media_lev) > 0 && Number(qDta.media_type) == 1){
                    if(pic1 == 0){
                      var mURL = defs.dir.prop.thm+qDta.media_thm+'?'+Math.floor(Math.random()*(99999-99+1)+99);
                      $('#estSectThm-'+qDta.media_lev+'-'+qDta.media_levidx).css({'background-image':'url('+mURL+')'});
                      pic1++;
                      }
                    }
                  });
                }
              });
            }
          }).promise().done(function(){
            estSaveElemOrder(tDta,1);
            estBuildSpaceList();
            });
        });
    }
  
  function estBuildMediaList(lev=1){
    var defs = $('body').data('defs');
    var propId = Number($('body').data('propid'));
    var noCache = '?'+Math.floor(Math.random() * (99999 - 99 + 1) + 99);
    var ulbtn = [];
    
    var popDta = $('#estPopCont').data();
    if(typeof popDta === 'undefined'){return;}
    
    var optMenuSlide = popDta.popit.frm[0].slide;
    var levDta = $(popDta.popit.frm[0].form).data('levdta');
    //console.log(levDta);
    
    var sectKey = defs.tbls.estate_sects[lev][1];
    var mediaCont = $('#estMediaMgrCont');
    if(levDta[sectKey] == 0){
      $('#estMediaNoGo').html(defs.txt.spaceidzero+' '+defs.txt.media).show();
      $(mediaCont).hide();
      $('#fileSlip').hide();
      $('#fileSlipBtn2').prop('disabled',1);
      }
    else{
      $('#estMediaNoGo').hide();
      $(mediaCont).show();
      $(mediaCont).parent().addClass('estDark65');
      $('#fileSlip').show();
      var newMediaDta = estNewMediaDta(lev);
      estFileUplFld(newMediaDta);
      
      $(mediaCont).empty().promise().done(function(){
        mediaGrep1 = $.grep(defs.tbls.estate_media.dta, function (element, index) {return element['media_lev'] == lev;});
        mediaGrep2 = $.grep(mediaGrep1, function (element, index) {return element['media_levidx'] == levDta[sectKey];});
        
        $(mediaGrep2).each(function(k,mediaDta){
          ulbtn[k] = [];
          ulbtn[k][0] = $(JQDIV,{'class':'upldPvwBtn pvw-'+mediaDta.media_idx}).css({'background-image':'url('+defs.dir.prop.thm+mediaDta.media_thm+noCache+')'}).appendTo('#estMediaMgrCont');
          if(Number(mediaDta.media_asp) !== 0){$(ulbtn[k][0]).css({'width':Math.floor($(ulbtn[k][0]).height() * mediaDta.media_asp)});}
          
          var mediaTitle = estMediaTitle(mediaDta,'build media list');
          var fDta = estMediaFldClean(mediaDta);
          $.extend(fDta,{'natw':0,'nath':0,'targ':ulbtn[k]});
          
          estPrepMediaEditCapt(mediaDta,mediaTitle,ulbtn[k][0]);
          
          $(ulbtn[k][0]).data(fDta).on({
            mouseenter : function(e){estMediaEditBtns(1,this)},
            mouseleave : function(e){estMediaEditBtns(-1)}
            });
          }).promise().done(function(){
            estSetMediaMgrSorting();
            });
        });
      }
    }
  
  
  function estPrepMediaEditCapt(mediaDta,mediaTitle,targ){
    $(JQDIV,{'class':'mediaPvwCapt pvcap-'+mediaDta.media_idx}).prop('contenteditable',true).data('pval',mediaTitle).html(mediaTitle).on({
      click : function(){},
      blur : function(){
        var defs = $('body').data('defs');
        var eDiv = this;
        var pVal = $(eDiv).data('pval').toUpperCase();
        var nVal = $(eDiv).html().toUpperCase();
        if(nVal !== pVal){
          var mediaKey = defs.tbls.estate_media.dta.indexOf(mediaDta);
          if(mediaKey == -1){
            console.log(mediaDta);
            estAlertLog('Unable to save Caption: index key not found');
            return;
            }
          
          $(eDiv).addClass('estFlipIt');
          
          var propId = Number($('body').data('propid'));
          var tdta = {'maintbl':'estate_media','mainkey':'media_idx','mainkx':'txt','mainidx':Number(mediaDta.media_idx),'mainfld':'media_name','nval':nVal};
          
          $.ajax({
            url: vreFeud+'?6||0',
            type:'post',
            data:{'fetch':6,'propid':propId,'rt':'js','tdta':tdta},
            dataType:'json',
            cache:false,
            processData:true,
            success: function(ret, textStatus, jqXHR){
              $(eDiv).removeClass('estFlipIt');
              mediaDta.media_name = nVal;
              defs.tbls.estate_media.dta[mediaKey] = mediaDta;
              var thmDta = $(eDiv).parent().data();
              thmDta.media_name = nVal;
              $(eDiv).parent().data(thmDta);
              $(eDiv).data('pval',nVal);
              },
            error: function(jqXHR, textStatus, errorThrown){
              $(eDiv).removeClass('estFlipIt');
              console.log('ERRORS: '+textStatus+' '+errorThrown);
              estAlertLog(jqXHR.responseText);
              }
            });
          
          }
        
        }
      }).appendTo(targ);
    
    }
  
  
  
  function estBuildGallery(){
    var defs = $('body').data('defs');
    var propId = Number($('body').data('propid'));
    $('#estGalleryBelt').empty().promise().done(function(){
      $('#estGalleryUsed').empty().promise().done(function(){
        if(propId == 0){
          $('#estNoGalWarn').show();
          $('#estate-gallery-tabl').hide();
          }
        else{
          $('#estNoGalWarn').hide();
          $('#estate-gallery-tabl').show();
          
          ulbtn = [];
          var noCache = '?'+Math.floor(Math.random() * (99999 - 99 + 1) + 99);
          $(defs.tbls.estate_media.dta).each(function(k,mediaDta){
            ulbtn[k] = [];
            ulbtn[k][0] = $(JQDIV,{'class':'upldPvwBtn pvw-'+mediaDta.media_idx}).css({'background-image':'url('+defs.dir.prop.thm+mediaDta.media_thm+noCache+')'});
            if(Number(mediaDta.media_galord) > 0){$(ulbtn[k][0]).appendTo('#estGalleryUsed');}
            else{$(ulbtn[k][0]).appendTo('#estGalleryBelt');}
            
            var mediaTitle = estMediaTitle(mediaDta,'build gallery');
            var fDta = estMediaFldClean(mediaDta);
            $.extend(fDta,{'natw':0,'nath':0,'targ':ulbtn[k]});
            
            estPrepMediaEditCapt(mediaDta,mediaTitle,ulbtn[k][0]);
            
            $(ulbtn[k][0]).data(fDta).on({
              mouseenter : function(e){estMediaEditBtns(1,this)},
              mouseleave : function(e){estMediaEditBtns(-1)}
              });
            
            if(Number(mediaDta.media_asp) !== 0){$(ulbtn[k][0]).css({'width':Math.floor($(ulbtn[k][0]).height() * mediaDta.media_asp)});}
            
            }).promise().done(function(){
              $('#estGalleryUsed').children('div.upldPvwBtn').sort(function (a, b){
                var cA = $(a).data().media_galord;
                var cB = $(b).data().media_galord;
                return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
                }).appendTo('#estGalleryUsed').promise().done(function(){
                  estMediaDeepReorder();
                  });
              });
          }
        });
      });
    }
  
  
  
  
  function estSpaceReorder(){
    var defs = $('body').data('defs');
    var tbls=[], trs=[];
    $('#estSpaceGrpDiv').find('table.estateSubTable').each(function(ti,tabl){
      var tblDta = $(tabl).data('tdta').groupdta;
      var grpId = tblDta.grouplist_groupidx; //;
      if(tblDta.grouplist_ord !== (ti + 1)){
        tblDta.grouplist_ord = (ti + 1);
        tbls.push(tblDta);
        }
      
      $(tabl).find('tbody tr').each(function(ri,trw){
        var rwDta = $(trw).data();
        var chng = 0;
        if(rwDta.space_grpid !== grpId){rwDta.space_grpid = grpId; chng++;}
        if(rwDta.space_ord !== (ri + 1)){rwDta.space_ord = (ri + 1); chng++;}
        if(chng > 0){trs.push(rwDta);}
        });
      }).promise().done(function(){
        var tDta = [];
        if(tbls.length > 0){
          $(tbls).each(function(i,dta){tDta.push({'tbl':'estate_grouplist','key':'grouplist_idx','fdta':dta,'del':0});});
          }
        if(trs.length > 0){
          $(trs).each(function(i,dta){tDta.push({'tbl':'estate_spaces','key':'space_idx','fdta':dta,'del':0});});
          }
        //console.log(tDta);
        if(tDta.length > 0){
          //estDragableTR
          var propId = Number($('body').data('propid'));
          var sDta = {'fetch':3,'propid':propId,'rt':'js','tdta':tDta};
          $.ajax({
            url: vreFeud+'?5||0',
            type:'post',
            data:sDta,
            dataType:'json',
            cache:false,
            processData:true,
            success: function(ret, textStatus, jqXHR){
              console.log(ret);
              var kx = tDta.length-1;
              if(typeof ret[kx].error !== 'undefined'){
                estAlertLog(ret[kx].error);
                $('#estPopContRes'+0).html(ret[kx].error).fadeIn(200,function(){estPopHeight(1)});
                }
              else{
                if(typeof ret[kx].alldta !== 'undefined'){
                  estProcDefDta(ret[kx].alldta.tbls);
                  }
                }
              },
            error: function(jqXHR, textStatus, errorThrown){
              console.log('ERRORS: '+textStatus+' '+errorThrown);
              estAlertLog(jqXHR.responseText);
              }
            });
          }
        });
    }
  
  
  
  
  function estTestEles(cForm,saveBtns=[]){
    if(typeof cForm == 'undefined'){return;}
    var btns = $(cForm).find('ul.nav-tabs li.nav-item');
    $(btns).each(function(bi,btn){
      $(btn).removeClass('estFixTab');
      }).promise().done(function(){
        var tabs = $(cForm).find('.tab-pane');
        if(typeof tabs !== 'undefined'){
          if(tabs.length == 0){tabs = [cForm];}
          }
        else{tabs = [cForm];}
        
        var eArr = [];
        $(tabs).each(function(ti,tab){
          eArr[ti] = 0;
          $(tab).find('.estInptCont').each(function(ei,ele){
            $(ele).removeClass('estNoData');
            var chk = $(ele).data('chk');
            if(typeof chk !== 'undefined'){
              $(chk).removeClass('estFixMe');
              if(eArr[ti] == 0 && $(chk).is(':empty')){
                $(ele).addClass('estNoData');
                $(btns[ti]).addClass('estFixTab');
                eArr[ti]++;
                }
              }
            });
          }).promise().done(function(){
            var disab = 0;
            $(eArr).each(function(ti,tx){
              disab += Number(tx);
              }).promise().done(function(){
                $(saveBtns).each(function(i,btn){
                  if(Number(disab) > 0){$(btn).prop('disabled',1);}
                  else{$(btn).prop('disabled',0);}
                  });
                });
            });
        });
    }
  
  
  
  function estPopHeight(hld=0){
    var slideEle = $('#estPopCont').find('div.estPopSlider:visible');
    var slideNo = $(slideEle).data('slide');
    var tabEle = $(slideEle).find('div.estPopBoxTab:visible');
    var popH = Number($(tabEle).parent().outerHeight(true)) + Number($('#estPopH'+slideNo).outerHeight(true));
    if($('#estPopTabBar'+slideNo).is(':visible')){popH += Number($('#estPopTabBar'+slideNo).outerHeight(true));}
    if($('#estPopContRes'+slideNo).is(':visible')){popH += Number($('#estPopContRes'+slideNo).outerHeight(true));}
    var dh = Number(window.innerHeight);
    var popT = Math.floor(dh / 2) - Math.floor(popH / 2);
    if(hld == 1){
      if((popT + popH) > (dh - 64)){
        popT = 64; popH = dh - 32;
        $('#estPopCont').animate({'height':popH+'px','top':popT+'px'});
        }
      else{$('#estPopCont').animate({'height':popH+'px'});}
      }
    else{
      if((popT + popH) > dh){popT = 64; popH = dh - 32;}
      $('#estPopCont').animate({'height':popH+'px','top':popT+'px'});
      //console.log(popT,popH);
      }
    }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  function estSetDIMUbtns(mode,btn,nVal=-1){
    var flds = ['prop_landfreq','prop_hoafrq','prop_hoareq','prop_currency','prop_leasefreq'];
    
    if(mode == 4){var txts = $('body').data('defs').keys.leasefrq;}
    else if(mode == 3){var txts = $('body').data('defs').keys.cursymb;}
    else if(mode == 2){var txts = $('body').data('defs').keys.hoareq;}
    else{var txts = $('body').data('defs').keys.hoafrq;}
    
    if(Number(nVal) > -1){var cv = Number(nVal);}
    else{var cv = Number($('input[name="'+flds[mode]+'"]').val()) +1;}
    
    if(cv >= txts.length){cv = 0;}
    $(btn).html(txts[cv]);
    $('input[name="'+flds[mode]+'"]').val(cv);
    
    $('input[name="prop_hoaland"]').change();
    
    if(cv > 0){
      if(mode == 1){
        $('#estPropHOAReqBtn').prop('disabled',false);
        if(Number($('input[name="prop_hoafee"]').val()) == 0){$('input[name="prop_hoafee"]').val(Number($('input[name="prop_hoafee"]').data('pval')));}
        }
      else if(mode == 0){
        if(Number($('input[name="prop_landfee"]').val()) == 0){$('input[name="prop_landfee"]').val(Number($('input[name="prop_landfee"]').data('pval')));}
        }
      }
    else{
      if(mode == 1){
        $('#estPropHOAReqBtn').prop('disabled',true);
        if(Number(nVal) > -1){$('input[name="prop_hoafee"]').data('pval',$('input[name="prop_hoafee"]').val());}
        $('input[name="prop_hoafee"]').val(Number(0));
        }
      else if(mode == 0){
        if(Number(nVal) > -1){$('input[name="prop_landfee"]').data('pval',$('input[name="prop_landfee"]').val());}
        $('input[name="prop_landfee"]').val(Number(0));
        }
      }
    }
  
  
  
  function estateSetOrigPrice(mode){
    if(mode == 2){
      if(Number($('input[name="prop_listprice"]').data('pval')) < 1){
        $('input[name="prop_listprice"]').data('pval',Number($('input[name="prop_listprice"]').val()));
        }
      }
    else{
      if($('input[name="prop_origprice"]').val() > 0){
        if(Number($('input[name="prop_listprice"]').data('pval')) < 1){
          $('input[name="prop_listprice"]').val($('input[name="prop_origprice"]').val());
          if(mode == 1){$('input[name="prop_listprice"]').data('pval',Number($('input[name="prop_listprice"]').val()));}
          }
        }
      }
    estateSetOpLp();
    }
  
  function estateSetOpLp(){
    $('#propOPpctBtn').html('↑ ↓');
    if(Number($('input[name="prop_listprice"]').val()) > 0 && Number($('input[name="prop_origprice"]').val()) > 0){
      var oplp = parseFloat((1 -(Number($('input[name="prop_listprice"]').val()) / Number($('input[name="prop_origprice"]').val()))) * 100).toFixed(1);
      if(oplp[oplp.length - 1] == 0){oplp = Math.round(oplp);}
      if(oplp > 0){$('#propOPpctBtn').data('oplp',(oplp * -1)).html('↓'+oplp+'%');}
      else if(oplp < 0){$('#propOPpctBtn').data('oplp',(oplp * -1)).html('↑'+(oplp * -1)+'%');}
      }
    }
  
  function propOPpct(mode=0){
    if(mode == -1){
      $('#estClearkCover').remove();
      $('#propOPpctDiv').remove();
      }
    else{
      var targ = $('#propLPdiv').parent();
      var xTarg = $('input[name="prop_listprice"]');
      var xVal = Number($('input[name="prop_origprice"]').val());
      var oplp = Number($('#propOPpctBtn').data('oplp'));
      
      $(JQDIV,{'id':'estClearkCover'}).on({click : function(){propOPpct(-1)}}).appendTo('body');
      var propOPpctDiv = $(JQDIV,{'id':'propOPpctDiv'}).css({'top':(Math.ceil($(targ).offset().top) - 250)+'px','left':($(targ).position().left - 56)+'px'}).appendTo(targ);
      
      $(xTarg).data('cVal',Number($(xTarg).val()));
      var vals = [];
      for(i = -85; i <= 85; i = i+5){vals.push(i)}
      
      $(vals).each(function(bi,bval){
        var cVal = (bval == 0 ? xVal : xVal+ ( bval< 0 ? Math.ceil(xVal * (bval/100)) : Math.floor(xVal * (bval/100))));
        $(JQBTN,{'id':'xPxtBtn'+bval,'class':'btn btn-default btn-sm'}).data('cVal',cVal).html(bval+'%').on({
          mouseenter : function(){
            $(xTarg).val($(this).data('cVal'));
            },
          mouseleave : function(){
            $(xTarg).val($(xTarg).data('cVal'));
            },
          click : function(e){
            e.preventDefault();
            e.stopPropagation();
            $(xTarg).data('cVal',Number($(this).data('cVal'))).val($(this).data('cVal'));
            estateSetOpLp();
            propOPpct(-1);
            }
          }).appendTo(propOPpctDiv);
        }).promise().done(function(){
          $(propOPpctDiv).scrollTop($('#xPxtBtn'+oplp).position().top + Math.floor($(targ).parent().height() / 2) - 200);
          console.log('goto '+oplp);
          });
      }
    }
  
  
  
  
  
  
  
  
  
  
  
  function estBuildSpaceListTbl(i,tbx){
    var defs = $('body').data('defs');
    var propId = Number($('body').data('propid'));
    xt = defs.txt;
    tdta = [];
    tri = 0;
    var tSQP = 0;
    
    if(tbx.grouplist_idx > 0){
      var grouplDta = defs.tbls.estate_group.dta.find(x => Number(x.group_idx) === tbx.grouplist_groupidx);
      var zoneId = Number(grouplDta.group_zone);
      }
    else{
      var zoneId = Number($('select[name="prop_zoning"]').val());
      }
    
    var zoneDta = defs.tbls.estate_zoning.dta.find(x => Number(x.zoning_idx) === zoneId);
    var zoneName = (typeof zoneDta !== 'undefined' ? zoneDta.zoning_name : defs.txt.unk);
    
    if(typeof grouplDta !== 'undefined'){var groupName = grouplDta.group_name+' '+xt.spaces+' ('+zoneName+')';}
    else{var groupName = zoneName+' '+xt.group1+' #'+tbx.grouplist_ord+' '+xt.spaces+' ('+xt.new1+')';}
    
    
    tdta[i] = {'groupdta':tbx,'tbl':'','th':'','tb':'','tr':[],'sqsp':0};
    
    
    tdta[i].tbl = $(JQTABLE,{'id':'estate-spaces-tabl-'+tbx.grouplist_groupidx,'class':'table-striped estateSubTable estNo3rd estDragTable'}).data(tbx);
    $(JQCOLGRP,{'class':'left'}).appendTo(tdta[i].tbl);//.css({'width':'10%'})
    $(JQCOLGRP,{'class':'left'}).appendTo(tdta[i].tbl);//.css({'width':'20%'})
    $(JQCOLGRP,{'class':'left'}).appendTo(tdta[i].tbl);//.css({'width':'*'})
    $(JQCOLGRP,{'class':'left noMobile'}).appendTo(tdta[i].tbl);//.css({'width':'*'})
    $(JQCOLGRP,{'class':'center last'}).appendTo(tdta[i].tbl);//.css({'min-width':'192px'})
    
    tdta[i].th = $(JQTHEAD).appendTo(tdta[i].tbl);
    tdta[i].tb = $(JQTBODY,{'id':'estSortTBody-'+tbx.grouplist_groupidx,'class':'ui-sortable estSortTBody estSortTarg'}).data(tbx).appendTo(tdta[i].tbl);
    
    tdta[i].tr[tri] = [];
    tdta[i].tr[tri][0] = $(JQTR,{'class':'estDragableTR'}).appendTo(tdta[i].th);
    tdta[i].tr[tri][1] = $(JQTH,{'class':'left'}).appendTo(tdta[i].tr[tri][0]);
    tdta[i].tr[tri][2] = $(JQDIV,{'class':'btn-group'}).appendTo(tdta[i].tr[tri][1]);
    
    tdta[i].tr[tri][3] = $(JQBTN,{'class':'e-sort sort-trigger btn btn-default ui-sortable-handle','title':xt.dragto+' '+xt.reorder+' '+xt.section}).html('<i class="fa fa-arrows-alt-v"></i>').on({click : function(e){e.preventDefault()}}).appendTo(tdta[i].tr[tri][2]);
    
    if(tbx.grouplist_idx == 0){
      $(tdta[i].tr[tri][3]).prop('disabled',true).css({'cursor':'not-allowed'}).attr('title',xt.nauntilspaces);
      $(tdta[i].tb).removeClass('estSortTBody');
      $(tdta[i].tbl).removeClass('estDragTable');
      }
    
    tdta[i].tr[tri][4] = $(JQBTN,{'type':'button','class':'btn btn-default ','title':xt.add1+' '+xt.new1+' '+xt.space+': '+groupName}).html('<i class="fa fa-plus"></i>').on({
      click : function(e){
        e.preventDefault();
        estBuildSpace(null,tbx)
        }
      }).appendTo(tdta[i].tr[tri][2]);
    
    $(JQTH,{'class':'left','colspan':2}).html(groupName).appendTo(tdta[i].tr[tri][0]);
    $(JQTH,{'class':'left noMobile'}).html(xt.dimu0).appendTo(tdta[i].tr[tri][0]);
    $(JQTH,{'class':'center last options'}).html(xt.options).appendTo(tdta[i].tr[tri][0]);
    tri++;
    
    // LIST OF SPACES
    //estDragableTR
    grpGrep2 = $.grep(defs.tbls.estate_spaces.dta, function (element, index) {return element.space_grpid == tbx.grouplist_groupidx;});
    $(grpGrep2).each(function(ri,rmdta){
      tdta[i].tr[tri] = [];
      tdta[i].tr[tri][0] = $(JQTR,{'class':'estDragTR'}).data(rmdta).appendTo(tdta[i].tb);
      tdta[i].tr[tri][1] = $(JQTD,{'class':'left noPAD posREL'}).appendTo(tdta[i].tr[tri][0]);
      tdta[i].tr[tri][2] = $(JQTD,{'class':'left'}).html(rmdta.space_name).appendTo(tdta[i].tr[tri][0]);
      tdta[i].tr[tri][3] = $(JQTD,{'class':'left'}).html(rmdta.space_loc).appendTo(tdta[i].tr[tri][0]);
      tdta[i].tr[tri][4] = $(JQTD,{'class':'left noMobile'}).html(rmdta.space_dimxy+' '+xt.dimu0).appendTo(tdta[i].tr[tri][0]);
      tdta[i].tr[tri][5] = $(JQTD,{'class':'last'}).appendTo(tdta[i].tr[tri][0]);
      tdta[i].tr[tri][6] = $(JQDIV,{'class':'btn-group'}).appendTo(tdta[i].tr[tri][5]);
      tdta[i].tr[tri][7] = $(JQDIV,{'id':'estSectThm-2-'+rmdta.space_idx,'class':'estPropThumb'}).appendTo(tdta[i].tr[tri][1]);//
      
      $(tdta[i].tr[tri][7]).on({
        click : function(e){
          e.preventDefault();
          e.stopPropagation();
          //estPropThumb
          var targ = this;
          $('.estThmMgrCont').remove().promise().done(function(){
            mediaGrep1 = $.grep(defs.tbls.estate_media.dta, function (element, index) {return element['media_lev'] == 2;});
            mediaGrep2 = $.grep(mediaGrep1, function (element, index) {return element['media_levidx'] == rmdta.space_idx;});
            if(mediaGrep2.length > 1){
              var xW = $(targ).closest('table').width() - $(targ).closest('tr').find('div.btn-group').outerWidth();
              var mBox0 = $(JQDIV,{'id':'estMediaMgrCont','class':'estThmMgrCont'}).css({'max-width':xW+'px'}).appendTo($(targ).parent()).promise().done(function(){
                  var ulbtn = [];
                  $(mediaGrep2).each(function(k,mediaDta){
                    ulbtn[k] = $(JQDIV,{'class':'upldPvwBtn pvw-'+mediaDta.media_idx}).data(mediaDta).css({'background-image':'url('+defs.dir.prop.thm+mediaDta.media_thm+noCache+')'}).appendTo('#estMediaMgrCont');
                    if(Number(mediaDta.media_asp) !== 0){$(ulbtn[k]).css({'width':Math.floor($(ulbtn[k]).height() * mediaDta.media_asp)});}
                    }).promise().done(function(){
                      estSetMediaMgrSorting();
                      });
                });
              }
            else{
              $(targ).addClass('jiggle');
              setTimeout(function() {$(targ).removeClass('jiggle');}, 500);
              }
            });
          }
        });
      
      
      tdta[i].sqsp =  Number(tdta[i].sqsp) + Number(rmdta.space_dimxy);
      
      var mediaGrep1 = $.grep(defs.tbls.estate_media.dta, function (element, index) {return element['media_levidx'] == rmdta.space_idx;});
      if(mediaGrep1.length > 0){
        var firstThm = mediaGrep1.find(x => x.media_levord === 1);
        var noCache = '?'+Math.floor(Math.random() * (99999 - 99 + 1) + 99);
        if(typeof firstThm !== 'undefined'){$(tdta[i].tr[tri][7]).css({'background-image':'url('+defs.dir.prop.thm+firstThm.media_thm+noCache+')'});}
        else{$(tdta[i].tr[tri][7]).css({'background-image':'url('+defs.dir.prop.thm+mediaGrep1[0].media_thm+noCache+')'});}
        }
      //S32
      $(JQBTN,{'class':'e-sort sort-trigger btn btn-default ui-sortable-handle','title':xt.dragto+' '+xt.reorder+' '+xt.spaces}).html('<i class="fa fa-arrows-alt-v"></i>').on({click : function(e){e.preventDefault()}}).appendTo(tdta[i].tr[tri][6]);
      
      $(JQBTN,{'class':'btn btn-default btn-secondary','title':xt.edit+' '+rmdta.space_name}).html('<i class="fa fa-pencil-square-o"></i>').on({
        click : function(e){
          e.preventDefault();
          var SPDTA = $(this).closest('tr').data();
          var TBX = $(this).closest('tbody').data();
          estBuildSpace(SPDTA,TBX);
          }
        }).appendTo(tdta[i].tr[tri][6]);
      
      $(JQBTN,{'type':'button','class':'action delete btn btn-default','title':xt.deletes+': '+rmdta.space_name}).html('<i class="fa fa-close"></i>').on({
        click : function(e){
          e.preventDefault();
          console.log(rmdta);
          if(jsconfirm(xt.deletes+': '+rmdta.space_name+' - '+xt.areyousure)){
            $.ajax({
              url: vreFeud+'?3||0',
              type:'post',
              data:{'fetch':3,'propid':rmdta.space_propidx,'rt':'js','tdta':[{'tbl':'estate_spaces','key':'space_idx','fdta':rmdta,'del':-1}]},
              dataType:'json',
              cache:false,
              processData:true,
              success: function(ret, textStatus, jqXHR){
                console.log(ret);
                ret = ret[0];
                if(typeof ret.alldta !== 'undefined'){
                  estProcDefDta(ret.alldta.tbls);
                  }
                estBuildSpaceList();
                estBuildGallery();
                  
                if(typeof ret.error !== 'undefined'){
                  estAlertLog(ret.error);
                  }
                },
              error: function(jqXHR, textStatus, errorThrown){
                estBuildSpaceList();
                console.log('ERRORS: '+textStatus+' '+errorThrown);
                estAlertLog(jqXHR.responseText);
                }
              });
            }
          }
        }).appendTo(tdta[i].tr[tri][6]);
      
      tri++;
      });
    
    $(tdta[i].tbl).data('tdta',tdta[i]).appendTo('#estSpaceGrpDiv');
    return tdta[i];
    }
  
    
  function estBuildSpaceList(){
    var defs = $('body').data('defs');
    var propId = Number($('body').data('propid'));
    var propZone = Number($('select[name="prop_zoning"]').val());
    var totSqSp = 0;
    
    $('#estSpaceGrpDiv').empty().promise().done(function(){
      if(propId == 0){
        $(JQDIV,{'class':'s-message alert alert-block warning alert-warning'}).html(defs.txt.needsave+' '+defs.txt.addspaces).appendTo('#estSpaceGrpDiv');
        $('.estSpaceTRrem').parent().parent().remove();
        
        return;
        }
      
      if(propZone == 0 || defs.tbls.estate_zoning.dta.length == 0){
        $(JQDIV,{'class':'s-message alert alert-block warning alert-warning'}).html(defs.txt.property+' '+defs.txt.zoning+' '+defs.txt.required).appendTo('#estSpaceGrpDiv');
        
        return;
        }
      
      var zoneGroups = $.grep(defs.tbls.estate_group.dta, function (element, index) {return  element.group_zone == propZone;});
      
      var xi = 0;
      remGrps = [];
      $(defs.tbls.estate_grouplist.dta).each(function(i,tbx){
        var zGrp = zoneGroups.find(x => x.group_idx === tbx.grouplist_groupidx);
        if(typeof zGrp === 'undefined'){remGrps.push(tbx);}
        else{
          if(Number(zGrp.group_lev) == 2){
            var tRet = estBuildSpaceListTbl(xi,tbx);
            totSqSp = Number(totSqSp) + Number(tRet.sqsp);
            zoneGroups.splice(zoneGroups.indexOf(zGrp), 1);
            xi++;
            }
          }
        }).promise().done(function(){
          $(zoneGroups).each(function(i,tbx){
            if(typeof tbx !== 'undefined' && $('#estSpaceGrpDiv').is(':empty')){
              if(Number(tbx.group_lev) == 2){
                var gDta = estDefDta('estate_grouplist');
                gDta.grouplist_propidx = propId;
                gDta.grouplist_groupidx = tbx.group_idx;
                gDta.grouplist_ord = (xi+1);
                var tRet = estBuildSpaceListTbl(xi,gDta);
                //console.log(tRet);
                totSqSp = Number(totSqSp) + Number(tRet.sqsp);
                xi++;
                }
              }
            });
          }).promise().done(function(){
            if($('#estSpaceGrpDiv').is(':empty')){
              estBuildSpaceListTbl(0,{'grouplist_idx':0,'grouplist_propidx':propId,'grouplist_groupidx':0,'grouplist_ord':1});
              }
              
            $(remGrps).each(function(i,tbx){
              var remDta = defs.tbls.estate_group.dta.find(x => x.group_idx = tbx.grouplist_groupidx);
              if(typeof remDta !== 'undefined'){
                if(Number(remDta.group_lev) == 2){
                  var tRet = estBuildSpaceListTbl(xi,tbx);
                  totSqSp = Number(totSqSp) + Number(tRet.sqsp);
                  xi++;
                  }
                }
              }).promise().done(function(){
                $('.estSpaceTRrem').parent().parent().remove();
                
                
                $('#estSQFT1BtnInner').data('sqft',totSqSp).html(defs.txt.auto+' '+totSqSp);
                $('#estSQFT2BtnInner').data('sqft',totSqSp).html(defs.txt.auto+' '+totSqSp);
                
                var itemGrpCont = document.getElementById('estSpaceGrpDiv');
                Sortable.create(itemGrpCont,{
                  group: 'estDragTable', 
                  draggable: '.estDragTable',
                  sort: true,
                  animation: 450,
                  handle: '.ui-sortable-handle',
                  pull: true,
                  put: true,
                  ghostClass: 'sortTR-ghost',
                  chosenClass: 'sortTR-chosen', 
                  dragClass: 'sortTR-drag',
                  onChoose: function(evt){},
                  onEnd: function(evt){estSpaceReorder();}
                  });
                
                $('.estSortTBody').each(function(ci,oele){
                  var oeleId = $(oele).attr('id');
                  var itemGrpCont = document.getElementById(oeleId);
                  Sortable.create(itemGrpCont,{
                    group: 'estSortTBody', 
                    draggable: '.estDragTR',
                    sort: true,
                    animation: 450,
                    handle: '.ui-sortable-handle',
                    pull: true,
                    put: true,
                    ghostClass: 'sortTR-ghost',
                    chosenClass: 'sortTR-chosen', 
                    dragClass: 'sortTR-drag',
                    onChoose: function(evt){},
                    onEnd: function(evt){estSpaceReorder();}
                    });
                  });
                
                
                $('#estSpaceGrpDiv').children('table.estateSubTable').sort(function (a, b) {
                  var cA = $(a).data('grouplist_ord');
                  var cB = $(b).data('grouplist_ord');
                  return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
                  }).appendTo('#estSpaceGrpDiv').promise().done(function(){
                    $('#estSpaceGrpDiv > table.estateSubTable').find('tbody.estSortTBody').each(function(tbi,tbdy){
                      $(tbdy).children('tr').sort(function (a, b) {
                        var cA = $(a).data('space_ord');
                        var cB = $(b).data('space_ord');
                        return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
                        }).appendTo(tbdy);
                      });
                    });
                });
            });
      });
    }
  
  
  
  
  
  
  
  
  
  
  
  
  function estateBuildDIMUbtns(){
    var defs = $('body').data('defs');
    
    $('select[name="prop_leasedur"]').data('pval',$('select[name="prop_leasedur"]').val());
    $('input[name="prop_landfee"]').data('pval',$('input[name="prop_landfee"]').val());
    $('input[name="prop_landfreq"]').data('pval',$('input[name="prop_landfreq"]').val());
    
    $('input[name="prop_listprice"]').data('pval',Number($('input[name="prop_listprice"]').val()));
    $('input[name="prop_origprice"]').data('pval',Number($('input[name="prop_origprice"]').val()));
    
    
    $('select[name="prop_listype"]').on({
      change : function(){
        if(this.value > 0){
          $('select[name="prop_leasedur"]').val(0).change();
          $('select[name="prop_leasedur"]').closest('tr').fadeOut();
          $('input[name="prop_landfee"]').closest('tr').fadeIn();
          $('input[name="prop_landfee"]').val($('input[name="prop_landfee"]').data('pval')).change();
          $('input[name="prop_origprice"]').removeClass('estNoRightBord');
          $('#estPropLeaseFrqBtn').fadeOut();
          }
        else{
          $('select[name="prop_leasedur"]').closest('tr').fadeIn();
          $('select[name="prop_leasedur"]').val($('select[name="prop_leasedur"]').data('pval')).change();
          $('input[name="prop_landfee"]').val(0).change();
          $('input[name="prop_landfee"]').closest('tr').fadeOut();
          $('input[name="prop_origprice"]').addClass('estNoRightBord');
          $('#estPropLeaseFrqBtn').fadeIn();
          }
        }
      });
    
    $('input[name="prop_origprice"]').on({
      change : function(){estateSetOrigPrice(1)},
      keyup : function(){estateSetOrigPrice(0)}
      });
    
    $('input[name="prop_listprice"]').on({
      change : function(){estateSetOrigPrice(2)},
      keyup : function(){estateSetOpLp()}
      });
    
    
    var LeaseFrqDiv = $(JQDIV,{'class':'WSNWRP'}).appendTo($('input[name="prop_origprice"]').parent());
    var currencyBtn = $(JQBTN,{'id':'estPropCurrBtn','class':'btn btn-default estNoRightBord'}).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        estSetDIMUbtns(3,this);
        }
      }).appendTo(LeaseFrqDiv);
    $('input[name="prop_origprice"]').appendTo(LeaseFrqDiv);
    var LeaseFrqBtn = $(JQBTN,{'id':'estPropLeaseFrqBtn','class':'btn btn-default estNoLeftBord'}).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        estSetDIMUbtns(4,this);
        }
      }).appendTo(LeaseFrqDiv);
    
    
    
    var propLPdiv = $(JQDIV,{'id':'propLPdiv','class':'WSNWRP'}).appendTo($('input[name="prop_listprice"]').parent());
    $(JQBTN,{'id':'propOPpctBtn','class':'btn btn-default estNoRightBord'}).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        propOPpct();
        }
      }).appendTo(propLPdiv);
    $('input[name="prop_listprice"]').appendTo(propLPdiv);
    estSetDIMUbtns(3,currencyBtn,Number($('input[name="prop_currency"]').val()));
    estateSetOpLp();
    
    
    $('input[name="prop_hoaland"]').on({
      change : function(){
        if(this.value == 1){
          $('input[name="prop_landfee"]').val(Number(0));
          $('input[name="prop_landfee"]').closest('tr').hide();
          }
        else{
          $('input[name="prop_landfee"]').closest('tr').show();
          }
        }
      });
    estSetDIMUbtns(4,LeaseFrqBtn,Number($('input[name="prop_leasefreq"]').val()));
    
    var LandLeaseDiv = $(JQDIV,{'class':'WSNWRP'});
    $('input[name="prop_landfee"]').parent().append(LandLeaseDiv);
    $('input[name="prop_landfee"]').appendTo(LandLeaseDiv);
    $('input[name="prop_landfee"]').data('pval',Number($('input[name="prop_landfee"]').val()));
    var LandLeaseBtn = $(JQBTN,{'id':'LandLeaseBtn','class':'btn btn-primary estNoLeftBord FL'}).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        estSetDIMUbtns(0,this);
        }
      }).appendTo(LandLeaseDiv);
    estSetDIMUbtns(0,LandLeaseBtn,Number($('input[name="prop_landfreq"]').val()));
    
    $('input[name="prop_landfee"]').on({
      keyup : function(){
        if(Number(this.value) > 0){$('#LandLeaseBtn').prop('disabled',false).removeProp('disabled');}
        else{$('#LandLeaseBtn').prop('disabled',true);}
        },
      change : function(){
        if(Number(this.value) > 0){$('#LandLeaseBtn').prop('disabled',false).removeProp('disabled');}
        else{
          estSetDIMUbtns(0,$('#LandLeaseBtn'),0);
          $('#LandLeaseBtn').prop('disabled',true);
          }
        }
      });
    $('input[name="prop_landfee"]').change();
    
    var HOADiv = $(JQDIV,{'class':'WSNWRP'});
    $('input[name="prop_hoafee"]').parent().append(HOADiv);
    $('input[name="prop_hoafee"]').appendTo(HOADiv);
    $('input[name="prop_hoafee"]').data('pval',Number($('input[name="prop_hoafee"]').val()));
    var HOAFreqBtn = $(JQBTN,{'id':'estPropHOAFreqBtn','class':'btn btn-primary estNoLRBord FL'}).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        estSetDIMUbtns(1,this);
        }
      }).appendTo(HOADiv);
    estSetDIMUbtns(1,HOAFreqBtn,Number($('input[name="prop_hoafrq"]').val()));
    
    var HOAReqBtn = $(JQBTN,{'id':'estPropHOAReqBtn','class':'btn btn-primary estNoLeftBord FL'}).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        estSetDIMUbtns(2,this);
        }
      }).appendTo(HOADiv);
    estSetDIMUbtns(2,HOAReqBtn,Number($('input[name="prop_hoareq"]').val()));
    
    $('select[name="prop_listype"]').change();
    
    var dimU1v = Number($('input[name="prop_dimu1"]').val());
    var dimu1Btn = $(JQBTN,{'id':'dimu1Btn','class':'btn btn-primary estNoLeftBord'});
    var dimu1Btn2 = $(JQBTN,{'id':'dimu1Btn2','class':'btn btn-primary estNoLeftBord'});
    $(dimu1Btn).html(defs.keys.dim1u[dimU1v][0]).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        var cv = Number($('input[name="prop_dimu1"]').val()) +1;
        if(cv >= defs.keys.dim1u.length){cv = 0;}
        $('input[name="prop_dimu1"]').val(cv);
        $(dimu1Btn).html(defs.keys.dim1u[cv][0]);
        $(dimu1Btn2).html(defs.keys.dim1u[cv][0]);
        }
      });
    $(dimu1Btn2).html(defs.keys.dim1u[dimU1v][0]).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        var cv = Number($('input[name="prop_dimu1"]').val()) +1;
        if(cv >= defs.keys.dim1u.length){cv = 0;}
        $('input[name="prop_dimu1"]').val(cv);
        $(dimu1Btn).html(defs.keys.dim1u[cv][0]);
        $(dimu1Btn2).html(defs.keys.dim1u[cv][0]);
        }
      });
      
    $('input[name="prop_intsize"]').after(dimu1Btn);
    $('input[name="prop_roofsize"]').after(dimu1Btn2);
      
    var estSQFT1Btn = $(JQDIV,{'id':'estSQFT1Btn'});
    $(JQBTN,{'id':'estSQFT1BtnInner','class':'btn btn-default estNoLeftBord'}).data('sqft',0).html('Auto 0').on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        $('input[name="prop_intsize"]').val(Number($(this).data('sqft')));
        }
      }).appendTo(estSQFT1Btn);
    $(dimu1Btn).after(estSQFT1Btn);
      
    $('input[name="prop_intsize"]').on({
      focus : function(){
        $(dimu1Btn).addClass('estSqRightBord');
        $(estSQFT1Btn).show().animate({'width':'192px'});
        },
      blur : function(){
        $(estSQFT1Btn).animate({'width':'0px'},function(){
          $(dimu1Btn).removeClass('estSqRightBord');
          $(estSQFT1Btn).hide();
          });
        }
      });
    
    var estSQFT2Btn = $(JQDIV,{'id':'estSQFT2Btn'});
    $(JQBTN,{'id':'estSQFT2BtnInner','class':'btn btn-default estNoLeftBord'}).data('sqft',0).html('Auto 0').on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        $('input[name="prop_roofsize"]').val(Number($(this).data('sqft')));
        }
      }).appendTo(estSQFT2Btn);
    $(dimu1Btn2).after(estSQFT2Btn);
      
    $('input[name="prop_roofsize"]').on({
      focus : function(){
        $(dimu1Btn2).addClass('estSqRightBord');
        $(estSQFT2Btn).show().animate({'width':'192px'});
        },
      blur : function(){
        $(estSQFT2Btn).animate({'width':'0px'},function(){
          $(dimu1Btn2).removeClass('estSqRightBord');
          $(estSQFT2Btn).hide();
          });
        }
      });
    
    var dimU2v = Number($('input[name="prop_dimu2"]').val());
    var dimu2Btn = $(JQBTN,{'id':'dimu2Btn','class':'btn btn-primary estNoLeftBord'});
    
    $(dimu2Btn).html(defs.keys.dim2u[dimU2v]).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        var cv = Number($('input[name="prop_dimu2"]').val()) +1;
        if(cv >= defs.keys.dim2u.length){cv = 0;}
        $(this).html(defs.keys.dim2u[cv]);
        $('input[name="prop_dimu2"]').val(cv);
        }
      });
    $('input[name="prop_landsize"]').after(dimu2Btn);
    $('input[name="prop_landsize"]').on({
      focus : function(){
        $('#dimu2Btn').addClass('estNoRightBord');
        var estFractBtn = $(JQDIV,{'id':'estFractBtn'});
        $('#dimu2Btn').after(estFractBtn);
        $(JQBTN,{'class':'btn btn-default estSqLeftBord estSqRightBord','title':defs.txt.append+' ¼'}).html('¼').on({
          click : function(e){
            e.preventDefault();
            e.stopPropagation();
            var cVal = $('input[name="prop_landsize"]').val();
            $('input[name="prop_landsize"]').val(cVal+'¼');
            }
          }).appendTo(estFractBtn);
        
        $(JQBTN,{'class':'btn btn-default estNoLRBord','title':defs.txt.append+' ½'}).html('½').on({
          click : function(e){
            e.preventDefault();
            e.stopPropagation();
            var cVal = $('input[name="prop_landsize"]').val();
            $('input[name="prop_landsize"]').val(cVal+'½');
            }
          }).appendTo(estFractBtn);
        
        $(JQBTN,{'class':'btn btn-default estSqLeftBord','title':defs.txt.append+' ¾'}).html('¾').on({
          click : function(e){
            e.preventDefault();
            e.stopPropagation();
            var cVal = $('input[name="prop_landsize"]').val();
            $('input[name="prop_landsize"]').val(cVal+'¾');
            }
          }).appendTo(estFractBtn);
        $(estFractBtn).show().animate({'width':'144px'});
        },
      blur : function(){
        $('#estFractBtn').animate({'width':'0px'},function(){
          $('#dimu2Btn').removeClass('estNoRightBord');
          $('#estFractBtn').remove();
          });
        }
      });
    }
  
  
  
  
  
  
  
  function estPrepPropHrs(){
    $('.estPrefCalActDay').each(function(i,btn){
      if(!$(btn).hasClass('eleBound')){
        $(btn).addClass('eleBound');
        $(btn).on({
          click : function(e){
            e.preventDefault()
            fld = $('input[name="'+$(this).data('for')+'"]');
            if($(fld).val() == 1){
              $(this).removeClass('btn-primary').addClass('btn-default');
              $(fld).val(Number(0));
              $(btn).closest('td').find('input[type="time"]').prop('disabled',true);
              }
            else{
              $(this).removeClass('btn-default').addClass('btn-primary');
              $(fld).val(1);
              $(btn).closest('td').find('input[type="time"]').prop('disabled',false).removeProp('disabled');
              }
            }
          });
        }
      });
    }
  
  
  
  
  
  
  
  
  
  
  
  function estSetPropAddress(){
    var defs = $('body').data('defs');
    
    var ctadr = htmlDecode(0,defs.prefs.pref_addr_lookup).replace(/\s*\n\s*/ig, ', ');
    var lat = defs.prefs.pref_lat;
    var lon = defs.prefs.pref_lon;
    var geo = '';
      
    if($('input[name="prop_lat"]').val().length < 3){$('input[name="prop_lat"]').val(lat);}
    if($('input[name="prop_lon"]').val().length < 3){$('input[name="prop_lon"]').val(lon);}
    if($('input[name="prop_geoarea"]').val().length < 3){$('input[name="prop_geoarea"]').val(geo);}
    
    
    var addr1 = $('input[name="prop_addr1"]').val();
    if(addr1.length > 2){
      var addr2 = $('input[name="prop_addr2"]').val();
      var addr3 = $('select[name="prop_city"]').find('option:selected').text();
      var addr4 = $('select[name="prop_state"]').find('option:selected').text();
      var addr5 = $('select[name="prop_zip"]').find('option:selected').text();
      var ctadr = (addr1.length > 2 ? addr1+(addr2.length > 2 ? ' '+addr2 : '')+', ' : '')+addr3+', '+addr4+' '+addr5;
      }
    if($('#prop_addr_lookup').data('man') == 0){$('#prop_addr_lookup').val(ctadr);}
    return ctadr;
    }
  
  
  
  
  
  function estSetMap(){
    var defs = $('body').data('defs');
    var propId = Number($('body').data('propid'));
    var mapW = defs.prefs.map_width;
    var mapH = defs.prefs.map_height;
    
    var targCont = $('#est_prop_Map').parent();
    var flds = $(targCont).data();
    
    var latFld = flds.latfld;
    var lonFld = flds.lonfld;
    var zoomFld = flds.zoomfld;
    var lat = $(latFld).val();
    var lon = $(lonFld).val();
    var zoom = Number($(zoomFld).val());
    var mapW = $(targCont).width();
    
    $(targCont).empty().promise().done(function(){
      $(JQDIV,{'id':'est_prop_Map','class':'estMap'}).css({'width':mapW+'px'}).appendTo(targCont).promise().done(function(){
        $('input[name="prop_addr_lookup"]').css({'width':+mapW - $('#est_prop_SrchBtn').outerWidth()+'px'});
          var map = L.map('est_prop_Map').setView([lat, lon], zoom);
        	
          var tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        		minZoom: 6,
        		maxZoom: 19,
        		attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        	}).addTo(map);
          
          var marker = L.marker([lat, lon],{
            draggable : true,
            autoPan : true
            }).on({
              dragend : function(event){
                var latlng = event.target.dragging._marker._latlng;
                $(latFld).val(parseFloat(latlng.lat).toFixed(8));
                $(lonFld).val(parseFloat(latlng.lng).toFixed(8));
                }
              }).addTo(map);
          
          L.control.scale().addTo(map);
          
          map.on({
            zoom : function(event){$(zoomFld).val(Number(event.target._zoom));}
            });
        });
      });
    }
  
  
  
  function estBuildMap(){
    var defs = $('body').data('defs');
    var mapSrchRes = $('#est_prop_SrchRes');
    var mapSrchBtn = $('#est_prop_SrchBtn');
    var mapLookupAddr = $('#prop_addr_lookup');
    var latFld = $('input[name="prop_lat"]');
    var lonFld = $('input[name="prop_lon"]');
    var zoomFld = $('input[name="prop_zoom"]');
    var geoFld = $('input[name="prop_geoarea"]');
    
    $('#est_prop_Map').parent().data({'latfld':latFld,'lonfld':lonFld,'zoomfld':zoomFld,'geofld':geoFld});
    
    var mapReset = $(JQBTN,{'id':'mapReset-est_prop_Map','class':'btn btn-primary btn-sm'}).html(defs.txt.resetmap).on({
      click : function(e){
        e.preventDefault();
        $(mapLookupAddr).val($(mapLookupAddr).data('pval'));
        $(latFld).val($(latFld).data('pval'));
        $(lonFld).val($(lonFld).data('pval'));
        $(zoomFld).val($(zoomFld).data('pval'));
        estSetMap();
        }
      }).appendTo(mapSrchRes).promise().done(function(){
        var xHt = Number($(mapSrchRes).height()) - Number($(mapReset).height());
        
        var foundCoordCont = $(JQDIV,{'id':'mapLookupRes-est_prop_Map','class':'estFoundMapCoordRes'}).css({'height':xHt+'px'}).appendTo(mapSrchRes);
        
        mAddr = estSetPropAddress();
        $(mapLookupAddr).val(mAddr);
        
        $(mapSrchBtn).on({
          click : function(e){
            e.preventDefault();
            var defs = $('body').data('defs');
            var addrToGet = $(mapLookupAddr).val();
            $(foundCoordCont).empty().promise().done(function(){
              if(addrToGet.length > 10){
                $(JQDIV,{'id':'MapSearchLoading'}).appendTo(foundCoordCont);
                $.get(location.protocol + '//nominatim.openstreetmap.org/search?format=json&q='+addrToGet, function(data){
                   console.log(data);
                   $('#MapSearchLoading').remove();
                   if(data.length == 0){
                    $(JQBTN,{'class':'btn btn-default btn-sm estMapLUBtn'}).prop('disabled',true).html(defs.txt.addrnotfound).appendTo(foundCoordCont);
                    }
                   else{
                    $(data).each(function(mi,mDta){
                      $(JQBTN,{'class':'btn btn-primary btn-sm estMapLUBtn'}).data(mDta).html(mDta.display_name.toUpperCase()).on({
                        click : function(e){
                          e.preventDefault();
                          $(latFld).val(parseFloat(mDta.lat).toFixed(8));
                          $(lonFld).val(parseFloat(mDta.lon).toFixed(8));
                          estSetMap();
                          }
                        }).appendTo(foundCoordCont);
                      });
                    }
                  });
                }
              else{
                alert(defs.txt.addrtooshort);
                }
              });
            }
          });
        
        
        var pval = $(mapLookupAddr).val();
        if(pval < 4){pval = htmlDecode(0,defs.prefs.pref_addr_lookup).replace(/\s*\n\s*/ig, ', ');}
        $(mapLookupAddr).data({'man':0,'pval':pval}).val(pval).on({
          keyup : function(e){$(e.target).data('man',1);},
          change : function(){$(foundCoordCont).empty();}
          });
        
        $(latFld).data('pval',$(latFld).val());
        $(lonFld).data('pval',$(lonFld).val());
        var zoom = Number($(zoomFld).val());
        if(zoom < 4 || zoom > 19){
          if(Number(defs.prefs.pref_zoom) > 3 && Number(defs.prefs.pref_zoom) < 20){zoom = Number(defs.prefs.pref_zoom);}
          else{zoom = 14;}
          }
        $(zoomFld).data('pval',zoom).val(zoom);
        
        estSetMap();
        });
    }
  
  
  
  
  
  
  
  
  
  function estPrepSelEle(){
    
    }
  
  function estESelEles(){
    var defs = $('body').data('defs');
    
    
    $('.estESelect').each(function(i,ele){
      var fldName = $(ele).prop('name');
      
      $(ele).on({change : function(){estTestEles(cForm,cSave)}});
      
      if($(ele).find('option:selected')){var pval = $(ele).find('option:selected').val();}
      else{var pval = $(ele).val();}
      
      if(typeof defs.tbls !== 'undefined'){
        if(typeof defs.tbls.estate_properties !== 'undefined'){
          if(typeof defs.tbls.estate_properties.form !== 'undefined'){
            if(typeof defs.tbls.estate_properties.form[fldName] !== 'undefined'){
              var formDta = defs.tbls.estate_properties.form[fldName]
              $(ele).data({'form':formDta,'pval':pval});
              
              console.log($(ele).data());
              
              }
            }
          }
        }
      });
    
    }
  
  
  
  function estOAPrep(){
    console.log('OA Prep');
    estProcDefDta();
    var propId = Number($('body').data('propid'));
    var defs = $('body').data('defs');
    
    
    if(defs.tbls.estate_properties.dta.length == 0){
      var newDta = estDefDta('estate_properties');
      if(Number(propId) == 0){newDta.prop_listype = 1;}
      defs.tbls.estate_properties.dta.push(newDta);
      $('body').data('defs',defs);
      $('select[name="prop_listype"]').val(newDta.prop_listype).change();
      }
    
    $('select[name="prop_zoning"]').on({change : function(){estBuildSpaceList();}});
    
    var chk1 = $('select[name="prop_country"]').find('option:selected').val();
    if(!chk1 || chk1 === '0'){
      $('#prop-country').find('option[value="'+defs.prefs.country+'"]').prop('selected','selected');
      $('#prop-country').change();
      }
    
    
    
    $('.estGalCont').each(function(gi,gEle){
      var gContId = $(gEle).attr('id');
      var mediaGrpCont = document.getElementById(gContId);
      Sortable.create(mediaGrpCont,{
        group: 'estSortMedia', 
        draggable: '.upldPvwBtn',
        sort: true,
        animation: 450,
        //handle: '.ui-sortable-handle',
        pull: true,
        put: true,
        ghostClass: 'sortTR-ghost',
        chosenClass: 'sortTR-chosen', 
        dragClass: 'sortTR-drag',
        onChoose: function(evt){},
        onEnd: function(evt){
          
          var tDta = [];
          var mediaDta = defs.tbls.estate_media.dta;
          
          $('#estGalleryBelt').children('div.upldPvwBtn').each(function(i,ele){
            var eDta = $(ele).data();
            if(eDta.media_galord !== 0){
              eDta.media_galord = 0;
              $(ele).data(eDta);
              fdta = estMediaFldClean(eDta);
              tDta.push({'tbl':'estate_media','key':'media_idx','fdta':fdta,'del':0});
              }
            }).promise().done(function(){
              $('#estGalleryUsed').children('div.upldPvwBtn').each(function(i,ele){
                var eDta = $(ele).data();
                if(Number(eDta.media_galord) !== (i + 1)){
                  eDta.media_galord = i + 1;
                  $(ele).data(eDta);
                  fdta = estMediaFldClean(eDta);
                  tDta.push({'tbl':'estate_media','key':'media_idx','fdta':fdta,'del':0});
                  }
                }).promise().done(function(){
                  estSaveElemOrder(tDta,1);
                  });
              });
          }
        });
      });
    
    $('#prop-features').parent().find('span.estCharCtnr').hide();
      $('#prop-features-char-count').hide();
      $('#prop-features').on({
          blur : function(){
            $(this).val($(this).val().replace(/\s*,\s*/ig, ',')).prop('rows',1);
            $(this).parent().find('span.estCharCtnr').fadeOut(200);
            },
          focus : function(){
            if($(this).val().indexOf(',') > -1){$(this).val($(this).val().split(",").join(",\n"));}
            $(this).prop('rows',5);
            $(this).parent().find('span.estCharCtnr').fadeIn(200);
            }
          });
    
    
        $('#prop-bedtot').on({change : function(){estBedBath()}});
        $('#prop-bathhalf').on({change : function(){estBedBath()}});
        $('#prop-bathfull').on({change : function(){estBedBath()}});
        
        
        $('select[name="prop_status"]').on({
          change : function(){
            if(this.value == 2){$('.estDateSched').closest('tr').show();}
            else{$('.estDateSched').closest('tr').hide();}
            }
          }).change();
        
        $('#estSpaceGrpDiv').on({
          click : function(){
            $('.estThmMgrCont').remove();
            }
          });
        
    
    
    if($('#estMobTst').is(':visible')){
      $('.noDesktop').show();
      $('.noMobile').hide();
      $('#plugin-estate-OAform').find('ul.nav').hide();
      $('#plugin-estate-OAform').find('.tab-pane').show();
      
      $('.estOABlock').each(function(i,ele){
        $(ele).find('h3').on({
          click : function(e){
            e.stopPropagation();
            if($(ele).find('div.estOATabCont').is(':visible')){$(ele).removeClass('expand');}
            else{
              $(ele).addClass('expand');
              if(i == 1){estSetMap();}
              }
            }
          });
        });
      
      $('#est_prop_MapCont').appendTo('#est_prop_MapCont_targ');
      $('#est_prop_SrchForm').appendTo('#est_prop_SrchForm_targ');
      $('#est_prop_SrchRes').appendTo('#est_prop_SrchRes_targ');
      $('#est_prop_MapHlpTD').appendTo('#est_prop_MapHlpTD_targ');
      
      
      }
    else{
      $('.noDesktop').hide();
      $('.noMobile').show();
      $('#plugin-estate-OAform').find('ul.nav li').eq(1).on({
        click : function(){
          $('#tab-1').addClass('active');
          estSetMap();
          }
        });
      
      }
      
    
    $('.admin-ui-help-tip').each(function(i,ele){
      $(ele).on({
        mouseenter : function(){$(ele).parent().find('div.field-help').fadeIn(200);},
        mouseleave : function(){$(ele).parent().find('div.field-help').fadeOut(200);}
        });
      });
    
    var bgColor = $('body').css('background-color');
    var lightordark =''; 
    if(typeof bgColor !== 'undefined'){lightordark = ' '+lightOrDark(bgColor);}
    $('div.estInptCont').addClass(lightordark);
    
    $('.estInptCont').each(function(i,ele){
      //$(ele).find('select').width($(ele).width() - $(ele).find('button').outerWidth()+'px');
      });
    
    //$(eSelBtn).on({click : function(e){selectEdit(mainTbl,elem);}}).appendTo(sonar);
    //$(selContA).data('chk',elem);
    
        
    $('.estPropAddr').on({
      change : function(){estSetPropAddress();},
      keyup : function(){estSetPropAddress();},
      blur : function(){estSetPropAddress();}
      });
    
    estBuildMap();
    estBuildGallery();
    estESelEles();
    estPrepPropHrs();
    
        
    var mediaDta = estNewMediaDta(1);
    estFileUplFld(mediaDta,1);
    $('#fileSlipBtn').on({
      click : function(e){
        e.stopPropagation();
        e.preventDefault();
        $('#fileSlip').click();
        }
      });
    
    estTestEles(cForm,cSave);
    }
  
  
  
  $(document).ready(function(){
    var propId = Number($('#plugin-estate-OAform').data('propid'));
    $.ajax({
      url: vreFeud+'?0||0',
      type:'get',
      data:{'fetch':2,'propid':propId,'rt':'js','tbl':''},
      dataType:'json',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        console.log(ret);
        if(typeof ret !== 'undefined' && ret !== null){
          if(typeof ret.error !== 'undefined'){estAlertLog(ret.error);}
          else{
            $('body').data({'defs':ret,'propid':propId});
            estOAPrep();
            }
          }
        else{estAlertLog(jqXHR.responseText);}
        },
      error: function(jqXHR, textStatus, errorThrown){
        console.log('ERRORS: '+textStatus+' '+errorThrown);
        estAlertLog(jqXHR.responseText);
        }
      });
    });

})(jQuery);