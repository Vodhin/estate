// JavaScript Document

var picBaseTypes = ['error','GIF Image','JPEG Image','PNG Image'];
var vreQry = searchToObject();
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
var JQSEL = '<select></select>';
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
var JQADI = '<i class="fa fa-plus"></i>';

  /*
  
  var imgPth = window.location.protocol+'//'+window.location.hostname+'/img/';
  var mediaPth = window.location.protocol+'//'+window.location.hostname+'/media/';
  */

isObject = function(a) {return (!!a) && (a.constructor === Object);};
isArray = function(a) {return (!!a) && (a.constructor === Array);};


$.extend($.expr[':'],{
  unchecked: function (obj) {return (obj.type == 'checkbox' && !$(obj).is(':checked'));},
  ischecked: function (obj) {return (obj.type == 'checkbox' && $(obj).is(':checked'));}
  //eg: $('#uplTab input:unchecked').prop('disabled',true).siblings('label').addClass('DISAB');
  }
);

function htmlDecode(mode,xVal,dec=0){
  if(xVal !== ''){
    if(mode == 2){return xVal.replace(/[^\d]+/g, '');}
    else if(mode == 1){return $("<textarea/>").text(xVal).html();}
    else{return $("<textarea/>").html(xVal).text();}
    //var dtaname = $('#dtaname-'+dpIdx).val().replace(/[\W_]+/g,''); //shorthand alphanumeric
    }
  else{return '';}
  }

$.fn.extend({
  emptySelect: function(oval='',optxt=''){
    $(this).data('bkup',$(this).html()).prop('disabled',true).empty().promise().done(function(){
      //$('<option></option>',{'val':oval}).html(optxt).appendTo(this);
      });
    },
  hasClasses: function(selectors){
    var self = this;
    for(var i in selectors){
      if($(self).hasClass(selectors[i]))
        return true;
        }
    return false;
    }
  });

function setCookie(name, value, days) {
  var expires = "";
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}


function estAlertLog(msg){
  alert(msg);
  console.log(msg);
  }

function searchToObject() {
  var pairs = window.location.search.substring(1).split('&'), obj = {}, pair, i;
  for (i in pairs){
    if (pairs[i] === "") continue;
    pair = pairs[i].split('=');
    obj[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
    }
  return obj;
  }

function estFilterDropDn(mode,btn){}

function estJSONObj(dta) {
  var obj = {};
  if(dta !== 'undefined' && dta.length > 1){
    dta = dta.replace(/^\{|\}$/g,'').split(';');
    for(var i=0,cur,pair;cur=dta[i];i++){
      pair = cur.split(':');
      if(pair[1].indexOf('[') > -1){
        pair[1] = pair[1].replace('[','').replace(']','');
        if(pair[1].indexOf(',') > -1){pair[1] = pair[1].split(',');}
        else{pair[1] = new Array(pair[1]);}
        }
      obj[pair[0]] = /^\d*$/.test(pair[1]) ? +pair[1] : pair[1];
      }
    }
  return obj;
  }



function timeDifference(date1,date2) {
  var date1 = new Date(date1 * 1000);
  var date2 = new Date(date2 * 1000);
  var difference = date2.getTime() - date1.getTime();
  var udiff = (difference / 1000);
  
  var dX = Math.floor(difference/1000/60/60/24);
  difference -= dX*1000*60*60*24;

  var hX = Math.floor(difference/1000/60/60);
  difference -= hX*1000*60*60;
  
  var mX = Math.floor(difference/1000/60);
  return [dX,hX,mX,udiff];
  }



function estFormSetCity(ele){
  var targ = document.getElementById($(ele).attr('data-targ'));
  console.log(targ);
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


function estSetFormEles(mainTbl,cForm,cSave){
  var defs = $('body').data('defs');
  var propId = Number($('body').data('propid'));
  
  
  if(defs.tbls.estate_properties.dta.length == 0){
    var newDta = estDefDta('estate_properties');
    if(Number(propId) == 0){newDta.prop_listype = 1;}
    defs.tbls.estate_properties.dta.push(newDta);
    $('body').data('defs',defs);
    $('select[name="prop_listype"]').val(newDta.prop_listype).change();
    }
  
  
  var helpInFull = (typeof defs.prefs.helpinfull !== 'undefined' ? Number(defs.prefs.helpinfull) : 0);
  
  var uperm = Number(defs.user.perm);
  var bgColor = $('body').css('background-color');
  var lightordark =''; 
  if(typeof bgColor !== 'undefined'){lightordark = ' '+lightOrDark(bgColor);}
  $('div.estInptCont').addClass(lightordark);
  
  $.each(defs.tbls[mainTbl].form,function(fld,fldta){
    var eleName = fld;
    
    if(typeof fldta.type !== 'undefined' && (fldta.type == 'select' || fldta.type == 'eselect')){
      var elem = $('select[name="'+eleName+'"]');
      
      //console.log(eleName,fldta);
      
      
      if(helpInFull == 0 && typeof fldta.hlpm !== 'undefined' && fldta.hlpm !== null){
        $(elem).closest('tr').on({mouseenter : function(){estScrollHlp('#'+fldta.hlpm)}});
        }
      
      if(fldta.type == 'eselect'){
        var selContA = $(JQDIV,{'class':'estInptCont'+lightordark}).appendTo($(elem).parent());
        //console.log(elem,$(elem).val());
        $(elem).addClass('ILBLK').on({change : function(){estTestEles(cForm,cSave)}});
        $(elem).appendTo(selContA).promise().done(function(){
          var sonar = $(JQDIV,{'class':'estSonar'}).appendTo(selContA);
          $(JQDIV,{'class':'estSonarBlip'}).appendTo(sonar);
          var eSelBtn = $(JQBTN,{'type':'button','class':'btn btn-default selEditBtn1','title':defs.txt.add1}).html(JQADI);
          
          if(uperm >= 3){
            $(eSelBtn).attr('title',defs.txt.add1+'/'+defs.txt.edit).html(JQEDI);
            }
          else if(typeof fldta.src.perm !== 'undefined' && uperm >= Number(fldta.src.perm[1])){
            $(eSelBtn).attr('title',defs.txt.add1+'/'+defs.txt.edit).html(JQEDI);
            }
          
          $(eSelBtn).on({click : function(e){selectEdit(mainTbl,elem);}}).appendTo(sonar);
          $(selContA).data('chk',elem);
          });
        }
      
      if(typeof fldta.chng !== 'undefined'){
        if(fldta.chng !== null){
          $(elem).on({
            change : function(){
              $(fldta.chng).each(function(i,fnct){
                var myFunc = window[fnct];
                if(typeof myFunc === 'function'){myFunc();}
                else{alert('javascript function "'+fnct+'" not found');}
                //perm
                });
              }
            });
          }
        }
      //estNoData
      if(typeof fldta.fltrs !== 'undefined'){
        if(fldta.fltrs !== null){
          $(elem).on({
            change : function(){
              var defs = $('body').data('defs');
              var newVal = $(this).find('option:selected').val();
              $.each(fldta.fltrs, function(rfld,fele){
                if(typeof defs.tbls[mainTbl].form[rfld].src !== 'undefined'){
                  var rtbl = defs.tbls[mainTbl].form[rfld].src;
                  $.extend(fldta,{'maintbl':mainTbl,'mainfld':fld});
                  fltrSelect(fldta,rtbl,newVal,rfld,fele);
                  }
                });
              estTestEles(cForm,cSave);
              }
            });
          }
        }
      $(elem).change();
      }
    else{
      if(helpInFull == 0 && typeof fldta.hlpm !== 'undefined' && fldta.hlpm !== null){
        $('[name="'+eleName+'"]').closest('tr').on({mouseenter : function(){estScrollHlp('#'+fldta.hlpm)}});
        }
      }
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
  
  $('select[name="prop_zoning"]').on({change : function(){estBuildSpaceList();}});
  
  var chk1 = $('select[name="prop_country"]').find('option:selected').val();
  if(!chk1 || chk1 === '0'){
    $('#prop-country').find('option[value="'+defs.prefs.country+'"]').prop('selected','selected');
    $('#prop-country').change();
    }

  $('input[name="prop_bedtot"]').on({change : function(){estBedBath()}});
  $('input[name="prop_bedmain"]').on({change : function(){estBedBath(2)}});
  $('input[name="prop_bathhalf"]').on({change : function(){estBedBath()}});
  $('input[name="prop_bathfull"]').on({change : function(){estBedBath()}});
  $('input[name="prop_bathtot"]').on({change : function(){estBedBath(1)}});
  $('input[name="prop_bathmain"]').on({change : function(){estBedBath(3)}});
  
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

  
  $('.estJSmaxchar').each(function(i,ele){
    if($(ele).prop('maxlength') > 10){
      var ctEle = $(JQSPAN,{'class':'estCharCtnr'});
      $(ele).after(ctEle);
      $(ele).data('cntr',ctEle).on({
        keyup : function(){EstCharCnt(this)},
        change : function(){EstCharCnt(this)}
        });
      $(ele).change();
      }
    });
  
  
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
        
        
    $('.estPropAddr').on({
      change : function(){estSetPropAddress();},
      keyup : function(){estSetPropAddress();},
      blur : function(){estSetPropAddress();}
      });
  
  }




function estLoadSubDiv(mode){
  console.log('estLoadSubDiv: '+Number(mode));
  var defs = $('body').data('defs');
  
  var sDta = $('#estSubDivCont').data();
  console.log(sDta);
  
  var HOAFreqBtn = $('#estPropHOAFreqBtn');
  var HOAReqBtn = $('#estPropHOAReqBtn');
  var inptHoaLand = $('input[name="prop_hoaland"]');
  var inptHoaAppr = $('input[name="prop_hoaappr"]');
  var hoaApprSw = $('#prop-hoaappr--switch');
  
  
  var selEle = $('select[name="prop_subdiv"]');
  var optEle = $(selEle).find('option:selected');
  var subdIdx = Number(optEle.length > 0 ? $(optEle).val() : $(selEle).val());
  var ldta = defs.tbls.estate_properties.dta[0];
  
  
  if(Number(sDta.id) > 0 && Number(sDta.id) == Number(ldta.prop_subdiv)){
    
    if(Number(sDta.hoaappr) !== Number(ldta.prop_hoaappr)){
      
      }
    
    if(Number(sDta.hoareq) !== Number(ldta.prop_hoareq)){
      
      }
    
    if(Number(sDta.hoafrq) !== Number(ldta.prop_hoafrq)){
      
      }
      
    if(Number(sDta.hoafee) !== Number(ldta.prop_hoafee)){
      
      }
      
    }
  
  
  if(subdIdx == Number(ldta.prop_subdiv)){
    
    $('input[name="prop_hoaappr"]').val(Number(ldta.prop_hoaappr)); //approval required? 0 or 1
    $('input[name="prop_hoareq"]').val(Number(ldta.prop_hoareq)); //Mandatory? 0 or 1
    $('input[name="prop_hoafrq"]').val(Number(ldta.prop_hoafrq)); //0=none, 1=monthly, 2=quarterly, 3=semi-annual, 4=annual
    $('input[name="prop_hoafee"]').val(ldta.prop_hoafee);
    $('input[name="prop_hoaland"]').val(Number(ldta.prop_hoaland)); //included? 0 or 1
    $('input[name="prop_landfee"]').val(ldta.prop_landfee);
    $('input[name="prop_landfreq"]').val(Number(ldta.prop_landfreq));
    }
  else{
    if(mode == 2){
      if(sDta.id == 0){
        $('input[name="prop_landfee"]').val(Number(0)).change();
        //estSetDIMUbtns(0,$('#LandLeaseBtn'),Number(0));
        }
      $('input[name="prop_hoafee"]').val(sDta.hoafee);
      $('input[name="prop_hoafrq"]').val(sDta.hoafrq);
      $('input[name="prop_hoareq"]').val(sDta.hoareq);
      $('input[name="prop_hoaappr"]').val(sDta.hoaappr);
      estSetDIMUbtns(1,HOAFreqBtn,Number(sDta.hoafrq));
      estSetDIMUbtns(2,HOAReqBtn,Number(sDta.hoareq));
      }
      
    
    //console.log(hoaApprSw);
    //
    //var landFee = $('input[name="prop_landfee"]').val();
    
    //if(hoafee !== Number(sDta.hoafee) || hoafrq !== Number(sDta.hoafrq) || hoareq !== Number(sDta.hoareq) || hoaappr !== Number(sDta.hoaappr)){}
      //if(jsconfirm('Update HOA?')){
        //prop-hoaappr__switch
        //}
      
    
    }
  
    
  
  }

function estResetSubDivs(){
  var defs = $('body').data('defs');
  var cid = Number(defs.tbls.estate_properties.dta[0].prop_city);
  var ncid = Number($('select[name="prop_city"]').val());
  if(ncid !== cid){
    console.log('prop_city -> estResetSubDivs: '+cid+' > '+ncid);
    defs.tbls.estate_properties.dta[0].prop_city = ncid;
    defs.tbls.estate_properties.dta[0].prop_subdiv = Number(0);
    $('select[name="prop_subdiv"]').val(Number(0)).change();
    }
  }

function estSetSubDivEdtBtn(){
  var defs = $('body').data('defs');
  var subdIdx = Number($('select[name="prop_subdiv"]').find('option:selected').val());
  var edtBtn = $('select[name="prop_subdiv"]').parent().find('button.selEditBtn1');
  
  $(edtBtn).prop('title',defs.txt.add1).data('idx',Number(0)).html(JQADI);
  
  if(subdIdx > 0){
    var uperm = Number(defs.user.perm);
    if(uperm >= 3){
      $(edtBtn).prop('title',defs.txt.add1+'/'+defs.txt.edit).data('idx',Number(subdIdx)).html(JQEDI);
      }
    else if(typeof fldta.src.perm !== 'undefined' && uperm >= Number(fldta.src.perm[1])){
      $(eSelBtn).attr('title',defs.txt.add1+'/'+defs.txt.edit).html(JQEDI);
      }
    }
    
  }

function estGetSubDivs(mode=1){
  var selEle = $('select[name="prop_subdiv"]');
  var optEle = $(selEle).find('option:selected');
  var subdIdx = Number(optEle.length > 0 ? $(optEle).val() : $(selEle).val());
  
  estSetSubDivEdtBtn();
  
  if(subdIdx !== Number($('select[name="prop_subdiv"]').data('pval'))){
    selEle.data('pval',subdIdx);
    $('#estCommunityPreviewCont').empty().promise().done(function(){
      $.ajax({
        url: vreFeud+'?81||0',
        type:'get',
        data:{'fetch':81,'subd_idx':subdIdx,'rt':'js'},
        dataType:'text',
        cache:false,
        processData:true,
        success: function(ret, textStatus, jqXHR){
          $('#estCommunityPreviewCont').html(ret).promise().done(function(){
            estLoadSubDiv(mode);
            });
          },
        error: function(jqXHR, textStatus, errorThrown){
          console.log('ERRORS: '+textStatus+' '+errorThrown);
          estAlertLog(jqXHR.responseText);
          }
        });
      });
    }
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


function estAvatarWH(ele){
  if($(ele).prop('src') !== ''){
    var nW = $(ele).prop('naturalWidth');
    var nH = $(ele).prop('naturalHeight');
    $(ele).parent().animate({'height':Math.floor($(ele).parent().outerWidth() * parseFloat(nH / nW).toFixed(2))+'px'});
    }
  }


function estScrollHlp(hlpm){
  if(!$('#estHelpSpan').hasClass('estHlpFull')){
    if($('#estHelpHead').hasClass('estHelpHAuto')){
      $('#estHelpBlock').stop().animate({'scrollTop':$(hlpm).position().top});
      }
    }
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
  
  mediaDta.media_propidx = (lev > 0 ? propId : Number(0));
  mediaDta.media_lev = lev;
  mediaDta.media_levidx = levIdx;
  mediaDta.media_asp = 1;
  mediaDta.media_name = levName;
  return mediaDta;
  }
  
  
  
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


function estPrepMediaEditCapt(mediaDta,mediaTitle,targ){
  if(mediaDta.media_lev == 0){
    $(targ).addClass('estSubDivImg');
    }
  
  
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
          console.log(mediaDta,defs.tbls.estate_media.dta);
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


function estGetFeatCatInfo(lev=1){
  if(lev > 0){
    var ele = $('select[name="space_catid"]');
    var opt = $(ele).find('option[value="'+$(ele).val()+'"]');
    return {'ele':ele,'evlu':$(ele).val(),'txt':$(opt).text(),'cvlu':$(opt).val()};
    }
  else{
    optTxt = $('body').data('defs').txt.subdiv;//$('input[name="subd_name"]').val();
    return {'ele':null,'evlu':0,'txt':(optTxt.length > 0 ? optTxt : txt.subdiv),'cvlu':0};
    }
  }


function uplFileComplete(lev=1,destTarg){
  console.log('All Files Uploaded');
  $('#estPopCover').remove();
  var mediaDta = estNewMediaDta(lev);
  estFileUplFld(mediaDta,1,null,destTarg);
  estPopHeight(1);
  }


function estFileUplFld(mediaDta,mType=1,pvwTarg=null,destTarg=0,preUpFile=null){
  if(Number(mediaDta.media_lev) == 0){destTarg = 1;}
  var tst = {'mediaDta':mediaDta,'mType':mType,'pvwTarg':pvwTarg,'destTarg':destTarg,'preUpFile':preUpFile};
  //console.log(tst);
  
  if(preUpFile !== null){
    var UpFileId = $(preUpFile).attr('id');
    var putUpFile = $(preUpFile).parent();
    $(preUpFile).remove().promise().done(function(){
      var upFile = $(JQNPT,{'type':'file','id':UpFileId,'name':UpFileId,'class':'noDISP'}).prop('accept','image/jpeg, image/png, image/gif').data({'media':mediaDta,'filek':0,'filex':0,'desttarg':destTarg}).on({
        blur : function(e){},
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
        blur : function(e){},
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
      
      if(destTarg > 4){$('#fileSlipBtn').parent().click();}
      else{
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
  
  switch(destTarg){
    case 6 :
      console.log(mediaDta);
      formData.append("agent_idx", Number(mediaDta.agent_idx));
      formData.append("agent_image", mediaDta.agent_image);
      //formData.append("genfilename", mediaDta.agent_name.toLowerCase().replace(/[^a-z0-9]/gi,'')+'-'+Number(mediaDta.agent_idx));
      break;
    
    case 5 :
      formData.append("agency_idx", Number(mediaDta.agency_idx));
      formData.append("agency_image", mediaDta.agency_image);
      //formData.append("genfilename", mediaDta.agency_name.toLowerCase().replace(/[^a-z0-9]/gi,'')+'-'+Number(mediaDta.agency_idx));
      break;
    
    case 1 :
      formData.append("media_idx", Number(mediaDta.media_idx));
      formData.append("media_propidx", Number(0));
      formData.append("media_lev", Number(0));
      formData.append("media_levidx", Number(mediaDta.media_levidx));
      formData.append("media_levord", Number(mediaDta.media_levord));
      formData.append("media_galord", Number(mediaDta.media_galord));
      formData.append("media_asp", Number(mediaDta.media_asp));
      formData.append("media_type", Number(mediaDta.media_type));
      formData.append("media_thm", mediaDta.media_thm);
      formData.append("media_full", mediaDta.media_full);
      formData.append("media_name", mediaDta.media_name);
      break;
    
    default :
      formData.append("media_idx", Number(mediaDta.media_idx));
      formData.append("media_propidx", Number(mediaDta.media_propidx));
      formData.append("media_lev", Number(mediaDta.media_lev));
      formData.append("media_levidx", Number(mediaDta.media_levidx));
      formData.append("media_levord", Number(mediaDta.media_levord));
      formData.append("media_galord", Number(mediaDta.media_galord));
      formData.append("media_asp", Number(mediaDta.media_asp));
      formData.append("media_type", Number(mediaDta.media_type));
      formData.append("media_thm", mediaDta.media_thm);
      formData.append("media_full", mediaDta.media_full);
      formData.append("media_name", mediaDta.media_name);
      break;
    }
  
  if(!document.getElementById('estPopCover')){
    $(JQDIV,{'id':'estPopCover'}).on({
      click : function(e){
        e.stopPropagation();
        $('#estPopCover').remove();
        $('#fileSlipBtn').prop('disabled',false).removeProp('disabled');
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
      if(destTarg < 4){
        var gDta = estMediaFldClean(xDta);
        var mediaTitle = estMediaTitle(gDta,'upload image');
        if(Number(gDta.media_lev) > 0){var urlpth = defs.dir.prop.thm;}
        else{var urlpth = defs.dir.subdiv.thm;}
        $(mediaDta.targ[0]).data(gDta).css({'background-image':'url('+urlpth+gDta.media_thm+'?'+Math.floor(Math.random()*(99999-99+1)+99)+')'});
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
      $('#fileSlipBtn').prop('disabled',false).removeProp('disabled');
      //estMediaDelGo(data.upl.file.fdta,mediaDta.targ[0]);
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
        
        if(destTarg > 3 && destTarg < 7){
          var keyTbl = defs.keys.contabs[destTarg];
          var agtDta = $('input[name="'+keyTbl[1]+'"]').closest('form').data('levdta');
          console.log(agtDta);
          var tstDta = defs.tbls[keyTbl[0]].dta.find(x =>Number(x[keyTbl[1]]) === Number(fdta[keyTbl[1]]));
          console.log(tstDta);
          switch(destTarg){
            case 6 :
              agtDta.agent_image = fdta.agent_image;
              agtDta.agent_imgsrc = fdta.agent_imgsrc;
              $('input[name="agent_image"]').val(fdta.agent_image);
              $('input[name="agent_imgsrc"]').val(fdta.agent_imgsrc);
              break;
              
            case 5 :
              agtDta.agency_image = fdta.agency_image;
              agtDta.agency_imgsrc = fdta.agency_imgsrc;
              $('input[name="agency_image"]').val(fdta.agency_image);
              $('input[name="agency_imgsrc"]').val(fdta.agency_imgsrc);
              break;
              
            case 4 :
              break;
            
            default :
              break;
            }
          $('input[name="'+keyTbl[1]+'"]').closest('form').data('levdta',agtDta);
          estSetAgentImg(destTarg);
          uplFileComplete();
          }
        else{
          uplFileComplete(fdta.media_lev,destTarg);
          estBuildGallery();
          }
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
        case 1 :
          var fDta = estMediaFldClean(mediaDta);
          fDta.media_asp = 1;
          fDta.media_levord = (Number(fDta.media_levord) == 0 ? fileord : fDta.media_levord);
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



function estSetAgentImg(destTarg){
  //console.log('estSetAgentImg');
  var defs = $('body').data('defs');
  var imgUrl = null;
  
  switch(destTarg){
    case 6 :
      var img1 = $('#agtAvatar');
      var img2 = $(img1).find('img');
      var dta = $(img1).closest('form').data('levdta');
      //console.log(dta);
      
      if(Number(dta.agent_imgsrc) == 1){
        //if(dta.agent_image.length > 0){imgUrl = defs.dir.agent+dta.agent_image;}
        imgUrl = defs.dir.agent+dta.agent_image;
        }
      else{
        if($('select[name="agent_uid"]').length == 1){
          
          var eDta = $('select[name="agent_uid"]').find('option:selected').data();
          if(typeof eDta !== 'undefined'){
            if(eDta.user_image.length > 0){imgUrl = eDta.user_image;}
            if(typeof eDta.user_profimg !== 'undefined'){
              if(eDta.user_profimg.length > 0){imgUrl = eDta.user_profimg;}
              }
            }
          }
        else if(typeof dta.user_profimg !== 'undefined' && dta.user_profimg.length > 0){imgUrl = dta.user_profimg;}
        else if(typeof dta.user_image !== 'undefined' && dta.user_image.length > 0){imgUrl = dta.user_image;}
        }
      //estSetAgtMiniPic(dta);
      break;
    
    case 5 :
      var img1 = $('#agencyLogo');
      var img2 = $('#agencyLogoImg');
      var dta = $(img1).closest('form').data('levdta');
      if(Number(dta.agency_imgsrc) == 1 && dta.agency_image.length > 0){
        imgUrl = defs.dir.agency+dta.agency_image;
        }
      else{imgUrl = defs.weblogo;}
      break;
    
    default :
      return;
      break;
    }
  
  if(imgUrl !== null){
    imgUrl += '?'+Math.floor(Math.random()*(99999-99+1)+99);
    $(img1).css({'background-image':'url("'+imgUrl+'")'});
    $(img2).prop('src',imgUrl).load();
    }
  else{
    $(img1).css({'background-image':''});
    $(img2).prop('src','');
    }
  $(img1).closest('form').data('levdta',dta);
  }


function estSetAgtMiniPic(agtDta=null){
  var defs = $('body').data('defs');
  var imgUrl = null;
  var agtId = Number($('input[name="prop_agent"]').val());
  
  if(agtId > 0){
    if(agtDta == null){
      agtDta = defs.tbls.estate_agents.dta.find(x => Number(x.agent_idx) === agtId);
      if(typeof agtDta == 'undefined'){return;}
      }
    
    if(Number(agtDta.agent_imgsrc) == 1 && agtDta.agent_image.length > 0){imgUrl = defs.dir.agent+agtDta.agent_image;}
    else{
      if(Number(agtDta.agent_uid) > 0){
        var eDta = defs.tbls.estate_user.dta.find(x => Number(x.user_id) === Number(agtDta.agent_uid));
        if(typeof eDta !== 'undefined'){imgUrl = eDta.user_profimg;}
        }
      }
    }
  
  if(imgUrl == null){$('#estAgentMinPic').css({'background-image':''});}
  else{
    imgUrl += '?'+Math.floor(Math.random()*(99999-99+1)+99);
    $('#estAgentMinPic').css({'background-image':'url("'+imgUrl+'")'});
    }
  
  
  $('#estAgentEditBtn').remove().promise().done(function(){
    var edtBtn = $(JQBTN,{'id':'estAgentEditBtn','class':'btn btn-default estNoLeftBord selEditBtn1'}).html(JQEDI).on({
      click : function(e){
        e.preventDefault();
        estAgentForm();
        }
      });
    
    if(defs.user.perm > 2){
      $('#estAgentContDiv').removeClass('noEdit');
      $('#estAgentSelBtn').removeClass('estNoLBord').addClass('estNoLRBord');
      $(edtBtn).appendTo('#estAgentEditDiv');
      }
    else{
      if(defs.user.perm == 2 && Number(agtDta.agent_agcy) == Number(defs.user.agency_idx)){
        $('#estAgentContDiv').removeClass('noEdit');
        $('#estAgentSelBtn').removeClass('estNoLBord').addClass('estNoLRBord');
        $(edtBtn).appendTo('#estAgentEditDiv');
        }
      
      else if(Number(agtDta.agent_idx) == Number(defs.user.agent_idx)){
        $('#estAgentContDiv').removeClass('noEdit');
        $('#estAgentSelBtn').removeClass('estNoLBord').addClass('estNoLRBord');
        $(edtBtn).appendTo('#estAgentEditDiv');
        }
      else{
        $('#estAgentContDiv').addClass('noEdit');
        $('#estAgentSelBtn').removeClass('estNoLRBord').addClass('estNoLBord');
        }
      }
    });
  }
  


function estDoFnct(fnct,ele){
  //console.log(fnct);
  switch(fnct.name){
    case 'estAgentChange' :
      //console.log(ele);
      break;
    
    case 'estAgencyChange' :
      //console.log(ele);
      break;
         
    case 'estPropAgencyChange1' :
      console.log(fnct);
      break;
    case 'estPropAgencyChange2' :
      console.log(fnct);
      break;
    
    case 'estSHCropBtn' : 
      if(ele !== $(ele).parent().find('li').eq(fnct.args)[0]){
        $('#cropBtnBar').show();
        }
      else{
        $('#cropBtnBar').hide();
        }
      break;
    
    case 'estSHUploadBtn' : 
      if(ele == $(ele).parent().find('li').eq(fnct.args)[0]){
        $('#fileSlipBtn2').show();
        $('#estSaveSpace1').hide();
        $('#estSaveSpace2').hide();
        }
      else{
        $('#fileSlipBtn2').hide();
        $('#estSaveSpace1').show();
        $('#estSaveSpace2').show();
        }
      break;
    }
  }










function estFeatureSort(){
  $('.estFeatureListSort').each(function(i,targ){
    if($(targ).children('div.estSortMe').length > 1){
      $(targ).children('div.estSortMe').sort(function (a, b) {
        var cA = $(a).data('btnDta').src.dta.feature_name; 
        var cB = $(b).data('btnDta').src.dta.feature_name;
        return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
        }).appendTo(targ);
      }
    });
  }

function EstCharCnt(ele){
  var mxCt = Number($(ele).prop('maxlength'));
  var curCt = Number($(ele).val().length);
  $(ele).data('cntr').html(curCt+'/'+mxCt);
  }


function estCalcSqFt(eleno,ele1,ele2,ele3){
  var ev1 = Number($(ele1).val());
  var ev2 = Number($(ele2).val());
  var ev3 = Number($(ele3).val());
  if(eleno == 3 && (ev1 * ev2) !== ev3){
    if(ev1 == 0 && (ev3 - ev2) > 0){$(ele1).val(ev3 - ev2);}
    else if(ev1 == 0 && (ev3 - ev1) > 0){$(ele2).val(ev3 - ev1);}
    else if(ev3 > 0){
      $(ele1).val(Math.round(Math.sqrt(ev3)));
      $(ele2).val(Math.round(Math.sqrt(ev3)));
      }
    }
  else{$(ele3).val(ev1*ev2);}
  }

function estRemovePopover(mode=null){
  $('#estBlackout').fadeOut().promise().done(function(){$('#estBlackout').remove();});
  $('#estPopCont').animate({'left':'-100vw','opacity':'0'},750,'swing',function(){
    $('#estPopCont').remove();
    var mediaDta = estNewMediaDta(1);
    estFileUplFld(mediaDta,1);
    });
  }

function estRemovePopoverAlt(tab=null){
  var popIt = $('#estPopCont').data('popit');
  $(popIt.frm[0].slide).show().animate({'left':'0px'});
  if(typeof popIt.frm[1] !== 'undefined'){
    $(popIt.frm[1].slide).animate({'left':'100%'}).promise().done(function(){
      $(popIt.frm[1].slide).remove();
      delete popIt.frm[1];
      $('#estPopCont').data('popit',popIt);
      if(tab !== null){$(tab).click();}
      estPopHeight(1);
      });
    }
  else{estPopHeight(1);}
  }

function estFeatureMenuRemove(eBtn=null){
  if(document.getElementById('estFeatOptMenu')){
    var mainBtn = $('#estFeatOptMenu').parent();
    if(Number($('#estFeatOptMenu').data('ch')) == 1){estSaveFeature(0,mainBtn);}
    $('#estFeatOptMenu').remove().promise().done(function(){
      if(eBtn !== null){estFeatOptMenu(eBtn);}
      });
    }
  }

function estPosPopover(w=0){
  if(w !== 0){$('#estPopCont').css({'width':w});}
  $('#estBlackout').fadeIn(200,function(){
    var popL = Math.floor(window.innerWidth / 2) - Math.floor($('#estPopCont').outerWidth() / 2);
    $('#estPopCont').animate({'left':popL+'px'}).fadeIn(200,function(){
      estPopHeight();
      var bgColor = $('#estPopCont').css('background-color');
      if(typeof bgColor !== 'undefined'){
        lightordark = lightOrDark(bgColor);
        $('#estPopCont').find('div.estInptCont').addClass(lightordark);
        }
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



function estFormTr(trEle,tDiv,tbody){
  if(trEle.tr == 0){$(trEle.inpt[0]).prependTo(tDiv);}
  else{
    var ni = 1;
    tr = [];
    tr[0] = $(JQTR).appendTo(tbody);
    if(trEle.label == null){
      tr[ni] = $(JQTD,{'colspan':trEle.cspan,'class':'noPAD WD100'}).appendTo(tr[0]); //1
      }
    else{
      tr[ni] = $(JQTD).appendTo(tr[0]);
      $(trEle.label).each(function(xi,lEle){$(lEle).appendTo(tr[ni]);});
      ni++;
      tr[ni] = $(JQTD).appendTo(tr[0]); //2
      }
      
    if(trEle.wrap !== null){
      tr[ni+1] = $(JQDIV,{'class':'estInptCont dark'}).appendTo(tr[ni]);
      ni++;
      $(trEle.inpt).each(function(i,ele){$(ele).appendTo(tr[ni])});
      }
    else{$(trEle.inpt).each(function(i,ele){$(ele).appendTo(tr[ni])});}
    }
  }
  
function estProcInpt(fld,form,fldStruct){
  var inptDta = {};    
  $inpt = $(form).find('[name="'+fld+'"]');
  if(!$inpt){inptDta[fld] = '';}
  else{
    var sVal = $inpt.val();
    if($inpt.hasClass('noblank') && (!sVal || sVal === '0')){inptDta = 'stop'; $inpt.focus();}
    if(fldStruct[fld].str == 'int' || fldStruct[fld].type == 'idx'){sVal = Number(sVal);}
    inptDta[fld] = sVal;
    }
  return inptDta;
  }  
  
  
function estSetSpaceGroup(ele){
  var defs = $('body').data('defs');
  var propId = Number($('body').data('propid'));
  var popit = $('#estPopCont').data('popit');
  var levdta = $(popit.frm[0].form).data('levdta');
  levdta.space_grpid = Number($(ele).val());
  $(popit.frm[0].form).data('levdta',levdta);
  //console.log(levdta);
  //group_lev
  estTestEles(popit.frm[0].form,popit.frm[0].savebtns);
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

  
  
function estGetSectDta(idx,cVal,destTbl,defdta=null){
  if(destTbl.form[idx].type == 'idx' || destTbl.form[idx].type == 'number' || destTbl.form[idx].str == 'int'){
    var sectDta = destTbl.dta.find(x => Number(x[idx]) === Number(cVal));
    }
  else{var sectDta = destTbl.dta.find(x => x[idx] === cVal);}
  
  if(typeof sectDta == 'undefined'){
    var sectDta = {};
    $.each(destTbl.form, function(dk,dv){
      if(defdta !== null && typeof defdta[dk] !== 'undefined'){sectDta[dk] = defdta[dk];}
      else{
        if(dv.type == 'idx' || dv.type == 'number'){sectDta[dk] = 0;}
        else if(typeof dv.str !== 'undefined'){sectDta[dk] = (dv.str == 'int'? 0 : '');}
        else{sectDta[dk] = '';}
        }
      });
    }
  return sectDta;
  }

function estPopGo(mode,popIt,frmn=0){
  var defs = $('body').data('defs');
  var pDta = $(popIt.frm[frmn].form).data();
  var fldStruct = defs.tbls[pDta.destTbl.table].form;
  
  if(mode > 0){
    $(JQDIV,{'id':'estPopCover'}).on({
      click : function(e){
        e.stopPropagation();
        $('#estPopCover').hide();
        }
      }).appendTo('#estPopCont');
      
    if(typeof pDta.destTbl == 'undefined'){
      estAlertLog(defs.txt.error1+': '+defs.txt.table+' '+defs.txt.missing);
      if(frmn > 0){estRemovePopoverAlt();}
      else{estRemovePopover();}
      return;
      }
    if(typeof pDta.destTbl.idx == 'undefined'){
      estAlertLog(defs.txt.error1+': '+defs.txt.table+' '+defs.txt.missing);
      if(frmn > 0){estRemovePopoverAlt();}
      else{estRemovePopover();}
      return;
      }
    var stopit = 0;
    var inpts = [];
    
    $(pDta.destTbl.flds).each(function(i,fld){
      inpts[i] = estProcInpt(fld,popIt.frm[frmn].form,fldStruct);
      if(inpts[i] == 'stop'){stopit++;}
      }).promise().done(function(){
        if(stopit > 0){
          $('#estPopContRes'+frmn).html(defs.txt.fieldrequired1).fadeIn(200,function(){
            estPopHeight(1);
            $('#estPopCover').hide();
            });
          }
        else{
          var propId = Number($('body').data('propid'));
          var sDta = {'fetch':3,'propid':propId,'rt':'js','tdta':[{'tbl':pDta.destTbl.table,'key':pDta.destTbl.idx,'del':0,'fdta':inpts}]};
          console.log(sDta);
          
          var fldmap = null;
          if(pDta.form.attr !== null){fldmap = pDta.form.attr.src.map;}
          //fldmap[0] = key, fldmap[1]=html, fldmap[2]=seperator for list estPropViewCt
          
          $.ajax({
            url: vreFeud+'?3||0',
            type:'post',
            data:sDta,
            dataType:'json',
            cache:false,
            processData:true,
            success: function(ret, textStatus, jqXHR){
              ret = ret[0];
              $('#estPopCover').remove();
              if(typeof ret.error !== 'undefined'){
                estAlertLog(ret.error);
                $('#estPopContRes'+frmn).html(ret.error).fadeIn(200,function(){estPopHeight(1)});
                }
              else{
                if(typeof ret.alldta !== 'undefined'){
                  estProcDefDta(ret.alldta.tbls);
                  if(typeof ret.chkmedia !== 'undefined' && ret.chkmedia !== 0){
                    estBuildGallery();
                    }
                  }
                
                var matObj = pDta.form.match;
                if(Object.keys(matObj).length > 0){
                  $.each(matObj, function(oi,obj){
                    $(obj.ele).val(obj.vl).change();
                    console.log(obj.vl,obj.ele);
                    });
                  }
                
                var fnct = pDta.form.fnct;
                console.log(fnct);
                
                if(typeof fnct !== 'undefined'){
                  if(fnct !== null && typeof fnct.name !== 'undefined'){
                    var args = (typeof fnct.args !== 'undefined' ? fnct.args : '');
                    switch (fnct.name){
                      case 'estBuildCategoryList' : 
                        estBuildCategoryList(args);
                        break;
                        
                      case 'estUDSubdivision' : 
                        estProcPdta(pDta,fldmap,ret);
                        var popFrm = popIt.frm[0];
                        var levdta = $(popFrm.form).data('levdta');
                        
                        if(Number(ret.newid) > 0){
                          $('select[name="prop_subdiv"]').val(ret.newid).change();
                          if(mode == 4){
                            $('#estMediaNoGo').hide();
                            $('#estMediaMgrCont').show();
                            $('#estFeatureNoGo').hide();
                            $('#fileSlipBtn2').prop('disabled',false).removeProp('disabled');
                            $('#estFeatureMgrCont').show();
                            //estBuildCategoryList(0)
                            //$('#estSaveSpace1').remove();
                             //prop_subdiv
                            }
                          }
                        else if(Number(ret.upres) > 0){
                          estGetSubDivs(2);
                          }
                        break;
                        
                      case 'estBuildSpaceOptns' : 
                        estBuildSpaceOptns();
                        break;
                      
                      case 'estCalEvtChange' :
                        console.log(ret);
                        var d = estJSDate(ret.kv[7]);
                        estEvtTimeSlots(d.ud1);
                        estBuildEvtTab();
                        break;
                      
                      case 'estSpaceGroupChange' : 
                        estBuildSpaceOptns(2);
                        estBuildSpaceList();
                        break;
                        
                      case 'estSaveSpace' :
                        estSaveSpace(Number(ret.kv[0]));
                        break;
                      
                      case 'estPropAgencyChange1' :
                        estProcPdta(pDta,fldmap,ret);
                        $('input[name="prop_agency"]').val(ret.kv[1]);
                        estBuildAgencyList();
                        
                        if(ret.dbmde== 'new'){estAgncyForm();}
                        else{estRemovePopover();}
                        
                        return;
                        break;
                      
                      case 'estPropAgencyChange2' :
                        console.log(fnct.name);
                        console.log(args,ret);
                        estProcPdta(pDta,fldmap,ret);
                        
                        if(Number(ret.kv[0]) > 0){
                          estAgentParOpts(Number(ret.kv[0]));
                          $('input[name="prop_agency"]').val(Number(ret.kv[0]));
                          estBuildAgencyList();
                          
                          }
                        
                        if(ret.dbmde== 'new'){
                          estAgncyForm();
                          }
                        else{
                          estRemovePopoverAlt();
                          }
                        return;
                        break;
                        
                      case 'estPropAgentChange' :
                        console.log('estPropAgentChange');
                        console.log(args,ret);
                        estProcPdta(pDta,fldmap,ret);
                        $('input[name="prop_agency"]').val(ret.kv[1]);
                        $('input[name="prop_agent"]').val(ret.kv[0]);
                        estBuildAgencyList();
                        if(ret.dbmde== 'new'){estAgentForm();}
                        else{estRemovePopover();}
                        break;
                        
                      default :
                        estProcPdta(pDta,fldmap,ret);
                        break;
                      }
                    }
                  else{estProcPdta(pDta,fldmap,ret);}
                  }
                  
                else{estProcPdta(pDta,fldmap,ret);}
                
                if(mode == 4){}
                else if(mode == 3){estRemovePopoverAlt();}
                else if(mode == 2){estPopGo(-1,popIt,frmn);}
                else{estRemovePopover();}
                }
              },
            error: function(jqXHR, textStatus, errorThrown){
              console.log('ERRORS: '+textStatus+' '+errorThrown);
              estAlertLog(jqXHR.responseText);
              estRemovePopover();
              }
            });
          }
        });
    }
  else{
    if(mode == -1){
      $(popIt.frm[frmn].form).find('input').each(function(i,inpt){
        if(!$(inpt).hasClass('estNoClear')){
          var fldName = $(inpt).prop('name');
          if($(inpt).prop('type') == 'checkbox'){$(inpt).prop('checked',false).removeProp('checked');}
          else{
            var nVal = '';
            if(typeof fldStruct[fldName] !== 'undefined'){
              if(fldStruct[fldName].type == 'idx' || fldStruct[fldName].type == 'number'){nVal = Number(0);}
              }
            else if($(inpt).prop('type') == 'number'){nVal = Number(0);}
            $(inpt).val(nVal).change();
            }
          }
        });
      $(popIt.frm[frmn].form).find('textarea').each(function(i,inpt){
        if(!$(inpt).hasClass('estNoClear')){$(inpt).html('').val('').change();}
        });
      }
    else{
      }
    }
  }

function estBuildPopover(frms,mode=null){
  //console.log(mode);
  var defs = $('body').data('defs');
  $('#estPopCont').remove();
  $('#estBlackout').remove().promise().done(function(){
    $(JQDIV,{'id':'estBlackout'}).on({
      click : function(e){
        e.stopPropagation();
        estRemovePopover();
        }
      }).prependTo('body');
    });
    
  var popCont = $(JQDIV,{'id':'estPopCont','class':'popover fade top in editable-container editable-popup'}).prependTo('body');
  var popBelt = $(JQDIV,{'id':'estPopBelt'}).appendTo(popCont);
  var frm = [];
  $(frms).each(function(fi,fdta){
    frm[fi] = estBuildSlide(fi,fdta);
    $(frm[fi].slide).appendTo(popBelt);
    if(fi > 0){$(frm[fi].slide).css({'left':'100%'}).hide();}
    });
  var popIt = {'cont':popCont,'belt':popBelt,'frm':frm};
  $(popCont).data('popit',popIt).on({click : function(e){e.stopPropagation()}});
  return popIt;
  }


  
function estPopoverAlt(altNo,tdta=null){
  console.log(tdta);
  var defs = $('body').data('defs');
  var xt = defs.txt;
  var uperm = Number(defs.user.perm);
  
  if(tdta == null){estAlertLog(xt.missing+' '+xt.form+' '+xt.datasource);return;}
  var popIt = $('#estPopCont').data('popit');
  
  var targEle = $('[name="'+tdta.fld+'"]');
  
  if(typeof defs.tbls[tdta.tbl] == 'undefined'){estAlertLog(xt.datasource+' '+xt.table+' '+xt.missing);return;}
  if(typeof defs.tbls[tdta.tbl].form[tdta.fld] == 'undefined'){estAlertLog(xt.field+' "'+tdta.fld+'" '+xt.notdefined);return;}
  if(typeof defs.tbls[tdta.tbl].form[tdta.fld].src == 'undefined'){estAlertLog(xt.field+' "'+tdta.fld+'" '+xt.datasource+' '+xt.notdefined);return;}
  
  var cVal = '';
  if(typeof tdta.eidx !== 'undefined' && tdta.eidx !== null){cVal = tdta.eidx}
  else if($(targEle).is('select')){cVal = $(targEle).find('option:selected').val();}
  else if($(targEle).is('input')){cVal = $(targEle).val();}
  
  if(typeof popIt.frm[1] !== 'undefined'){
    $(popIt.frm[1].slide).remove();
    delete popIt.frm[1];
    }
  
  if(tdta.tbl == 'estate_media'){
    estEditMEdiaForm(1,popIt,tdta);
    return;
    }
  else if(tdta.tbl == 'estate_events'){
    estEditCalEvent(popIt,tdta);
    return;
    }
  else if(tdta.fld == 'agent_agcy'){
    popIt.frm[1] = estBuildSlide(1,{'tabs':[defs.txt.agency,defs.txt.contacts,defs.txt.location]});
    }
  else{
    popIt.frm[1] = estBuildSlide(1,{'tabs':['TAB 1']});
    }
  
  $('#estPopCont').data('popit',popIt);
  
  var popFrm = popIt.frm[1];
  $(popFrm.slide).appendTo(popIt.belt);
  $(popFrm.slide).css({'left':'100%'}).hide();
  
  var srcTbl = defs.tbls[tdta.tbl];
  var eSrcFrm = srcTbl.form[tdta.fld];
  
  if(eSrcFrm.src == null){
    $.extend(eSrcFrm,{'src':{'tbl':tdta.tbl,'idx':srcTbl.flds[0]}});
    }
  
  var destTbl = defs.tbls[eSrcFrm.src.tbl]; // -> Alt form table, can be 'self' or 'table_name'
  if(typeof destTbl == 'undefined'){estAlertLog('"'+eSrcFrm.src.tbl+'" '+xt.notdefined);return;}
  if(typeof destTbl.form == 'undefined'){estAlertLog('"'+eSrcFrm.src.tbl+'" '+xt.form+' '+xt.notdefined);return;}
  
  if(uperm >= 3 ||(typeof eSrcFrm.src.perm !== 'undefined' && uperm >= Number(eSrcFrm.src.perm[1]))){}
  else{cVal = '';}
  
  var idx = eSrcFrm.src.idx;
  var sectDta = estGetSectDta(idx,cVal,destTbl,tdta.defdta);
  
  var reqMatch = null;
  var reqVal = null;
  if(typeof srcTbl.form[tdta.fld].src.req !== 'undefined' || typeof tdta.req !== 'undefined'){
    if(typeof tdta.req !== 'undefined'){reqMatch = tdta.req;}
    else{reqMatch = srcTbl.form[tdta.fld].src.req;}
    $reqFld = $('[name="'+reqMatch[0]+'"]');
    if(typeof $reqFld !== 'undefined'){
      if($reqFld.is('select')){reqVal = $reqFld.find('option:selected').val();}
      else{reqVal = $reqFld.val();}
      if(!reqVal || reqVal === '0'){
        estAlertLog(reqMatch[2]);
        return;
        }
      }
    }
  
  
  $(popFrm.h3).empty().promise().done(function(){
    var saveBtn = $(JQBTN,{'id':'estAltSave1','class':'btn btn-primary btn-sm FR estAltSave'}).html(xt.save).on({
      click : function(){estPopGo(3,popIt,1);}
      }).appendTo(popFrm.h3);
    popFrm.savebtns.push(saveBtn);
    
    var newBtn = $(JQBTN,{'id':'estAltNew1','class':'btn btn-primary btn-sm FR estAltSave'}).data('step',1).html(xt.new1).on({
      click : function(){
        if($(this).data('step') == 2){estPopGo(2,popIt,1);}
        else{
          $(this).data('step',2).html(xt.new2);
          estPopGo(-1,popIt,1);
          $(saveBtn).html(xt.save2);
          }
        }
      }).appendTo(popFrm.h3);
    
    popFrm.savebtns.push(newBtn);
    
  
    if(sectDta[idx] == 0){
      $(newBtn).data('step',2).html(xt.new2);
      $(saveBtn).html(xt.save2);
      }
    
    if(typeof tdta.eidx !== 'undefined'){
      if(tdta.eidx !== null){
        $(newBtn).data('step',2).html(xt.new2);
        $(saveBtn).html(xt.save2);
        }
      }
    
    
    if(typeof tdta.frmlabl !== 'undefined'){var frmlabl = tdta.frmlabl;}
    else{var frmlabl = (eSrcFrm.labl !== null ? eSrcFrm.labl : xt.alternate+' '+xt.form);}
    $(JQSPAN,{'id':'estAltH3Span1','class':'FL','title':xt.cancelremove}).data('was',frmlabl).html(frmlabl).on({
      click : function(){estRemovePopoverAlt()}
      }).appendTo(popFrm.h3);
    
    var fnct = (typeof tdta.fnct !== 'undefined' ? tdta.fnct : null);
    
    $(popFrm.form).data({'destTbl':{'dta':destTbl.dta,'flds':destTbl.flds,'idx':idx,'table':eSrcFrm.src.tbl},'maintbl':tdta.tbl,'form':{'elem':targEle,'attr':srcTbl.form[tdta.fld],'match':{},'fnct':fnct}});
    
    //console.log($(popFrm.form).data());
    
    if(eSrcFrm.src.tbl == 'estate_agencies'){
      estAgncyForm(tdta.adta,popIt,1);
      $(popIt.frm[1].slide).show().animate({'left':'0px'});
      $(popIt.frm[0].slide).animate({'left':'-100%'}).promise().done(function(){
        $(popIt.frm[0].slide).hide();
        estPopHeight(1);
        });
      return;
      }
    
    var lev = 2;
    if(typeof tdta.fnct !== 'undefined'){
      if(tdta.fnct['name'] == 'estBuildCategoryList'){lev = Number(tdta.fnct['args']);}
      }
    
      
    var frmTRs = estFormEles(destTbl.form,sectDta,reqMatch,lev);
    var tripIt = [];
    $(frmTRs).each(function(ei,trEle){
      estFormTr(trEle,popFrm.tabs.tab[0].tDiv,popFrm.tabs.tab[0].tbody);
      if(trEle.trip !== null){tripIt.push(trEle.trip)}
      }).promise().done(function(){
        $(tripIt).each(function(tpi,trpEle){$(trpEle).change();});
        });
    
    $(popIt.frm[1].slide).show().animate({'left':'0px'});
    $(popIt.frm[0].slide).animate({'left':'-100%'}).promise().done(function(){
      $(popIt.frm[0].slide).hide();
      estPopHeight(1);
      });
    });
  }






function estBuildSlide(fi,fdta){
  if(typeof fdta.tabs == 'undefined'){fdta.tabs = ['Main'];}
  if(typeof fdta.fnct == 'undefined'){fdta.fnct = null;}
  
  ret = {'slide':'','h3':'','savebtns':[],'form':'','tabs':{'ul':'','tab':[]},'popCont':'','popRes':''};
  ret.slide = $(JQDIV,{'id':'estPopSlider'+fi,'class':'estPopSlider'}).on({
    click : function(){estFeatureMenuRemove();}
    }).data('slide',fi);
  
  ret.h3 = $('<h3></h3>',{'id':'estPopH'+fi,'class':'popover-title'}).appendTo(ret.slide);
  ret.form = $('<form></form>',{'id':'estPopForm'+fi,'class':'form-inline editableform estPopForm'}).on({
    click : function(){
      //console.log($(this).data());
      }
    }).appendTo(ret.slide);
  ret.tabs.ul = $('<ul></ul>',{'id':'estPopTabBar'+fi,'class':'nav nav-tabs'}).appendTo(ret.form);
  ret.popCont = $(JQDIV,{'id':'estPopBox'+fi,'class':'popover-content'}).appendTo(ret.form); //tab-content
  ret.popRes = $(JQDIV,{'id':'estPopContRes'+fi,'class':'s-message alert alert-block warning alert-warning'}).on({
    click : function(){$(this).html('').fadeOut(250,function(){estPopHeight(1)});}
    }).appendTo(ret.slide).hide();
  
  if(fdta.tabs.length < 2){$(ret.tabs.ul).css({'display':'none'});}
  $(fdta.tabs).each(function(ti,tele){
    var col = [35,65]; //(typeof tele.cols !== 'undefined' ? tele.cols : [35,65]);
    
    ret.tabs.tab[ti] = {'li':'','tDiv':'','tabl':'','tbody':'','tr':[]};
    ret.tabs.tab[ti].tDiv = $(JQDIV,{'id':'estPopBoxTab-'+fi+'-'+ti,'class':'tab-pane estPopBoxTab'}).appendTo(ret.popCont);
    $(ret.tabs.tab[ti].tDiv).data('slide',fi+'-'+ti);
    if(ti > 0){$(ret.tabs.tab[ti].tDiv).css({'display':'none'});}
    //
    ret.tabs.tab[ti].li = $('<li></li>',{'class':'nav-item estPopTabLi'}).data({'targ':ret.tabs.tab[ti].tDiv,'fnct':fdta.fnct}).on({
      click : function(){
        var tabBtn = this;
        var slideEle = $(tabBtn).closest('div.estPopSlider');
        $(slideEle).find('div.estPopBoxTab').hide().promise().done(function(){
          $(slideEle).find('.estPopTabLi').removeClass('active').promise().done(function(){
            $(tabBtn).data('targ').show();
            $(tabBtn).addClass('active');
            if($(tabBtn).data('fnct') !== null){estDoFnct($(tabBtn).data('fnct'),tabBtn);}
            estPopHeight();
            });
          });
        }
      }).appendTo(ret.tabs.ul);
    $('<a></a>',{'class':'nav-link'}).html(tele).appendTo(ret.tabs.tab[ti].li);
    if(ti == 0){$(ret.tabs.tab[ti].li).addClass('active');}
    
    ret.tabs.tab[ti].tabl = $(JQTABLE,{'class':'table adminform estPopTbl'}).appendTo(ret.tabs.tab[ti].tDiv);
    $(col).each(function(ci,cw){$(JQCOLGRP).css({'width':cw+'%'}).appendTo(ret.tabs.tab[ti].tabl);});
    ret.tabs.tab[ti].thead = $(JQTHEAD).appendTo(ret.tabs.tab[ti].tabl);
    ret.tabs.tab[ti].tbody = $(JQTBODY).appendTo(ret.tabs.tab[ti].tabl);
    ret.tabs.tab[ti].tfoot = $(JQTFOOT).appendTo(ret.tabs.tab[ti].tabl);
    });
  return ret;
  }



function estFormEles(eleForm,sectDta,reqMatch,lev=2){
  var defs = $('body').data('defs');
  var trs = [];
  var tri = 0;
  var uperm = Number(defs.user.perm);
  console.log(eleForm);
  
  $.each(eleForm, function(eli,ele){
    trs[tri] = {'tr':1,'label':'???','inpt':[],'trip':null,'tab':(typeof ele.tab !== 'undefined' ? ele.tab : 0),'wrap':null};
    trs[tri].cspan = ele.cspan;
    
    var plcHldr = (ele.plch !== null ? ele.plch : '');
    
    var labelTxt = null;
    if(typeof ele.labl !== 'undefined' && ele.labl !== null){
      if(ele.labl !== 'no-lab'){labelTxt = ele.labl.toUpperCase();}
      }
    else{labelTxt = (eli.indexOf('_') > -1 ? eli.split('_')[1] : eli).toUpperCase();}
    
    if(labelTxt == null){trs[tri].label = null;}
    else{
      if(typeof ele.chks !== 'undefined' && ele.chks !== null){labelTxt += '*';}
      trs[tri].label = [];
      trs[tri].label[0] = '<span>'+labelTxt+'</span>';
      if(ele.inf !== null){
        var inf1 = $(JQDIV,{'class':'field-help estToolTip'}).attr('data-placement','left').html(ele.inf);
        trs[tri].label[1] = inf1;
        trs[tri].label[2] = $('<i></i>',{'class':'admin-ui-help-tip far fa-question-circle'}).css({'color':'#000000'}).on({
          mouseenter : function(){$(inf1).fadeIn();},
          mouseleave : function(){$(inf1).fadeOut();}
          });
        }
      }
      
    var eleAddCls = '';
    if(typeof ele.chks !== 'undefined' && ele.chks !== null){
      $(ele.chks).each(function(ci,cv){eleAddCls += ' '+cv;});
      }
    
    var eleMatch = null;
    var eleOpts = [];
    var eleHTML = '';
    if(reqMatch !== null && reqMatch[1] == eli){
      if(typeof reqMatch[3] == 'undefined'){reqMatch[3] = 0;}
      eleMatch = $('[name="'+reqMatch[0]+'"]');
      if($(eleMatch).is('select')){
        var eleVal = $(eleMatch).find('option:selected').val();
        eleHTML = $(eleMatch).find('option:selected').text();
        eleOpts = $(eleMatch).html();
        }
      else{
        var eleVal = $(eleMatch).val();
        eleHTML = eleVal;
        }
      //eleVal = htmlDecode(0,eleVal);
      eleAddCls += ' estNoClear';
      }
    else{
      if(ele.type == 'selfselect' || ele.type == 'eselect'  || ele.type == 'select'){
        if(typeof ele.src.opts !== 'undefined'){
          $(ele.src.opts).each(function(oi,otxt){eleOpts[oi] = $(JQOPT,{'value':oi}).html(otxt);});
          }
        else if(typeof ele.src !== null && ele.src.map !== null){
          var optArr = defs.tbls[ele.src.tbl].dta;
          if(typeof ele.src.grep !== 'undefined'){
            if(ele.src.grep[0] == 'featcat_lev'){
              optArr = $.grep(optArr, function(element, index) {return  element['featcat_lev'] == lev;});
              console.log(labelTxt,optArr);
              }
            }
          var xi=0;
          if(typeof ele.src.zero !== 'undefined'){eleOpts[xi] = $(JQOPT,{'value':0}).html(ele.src.zero);xi++;}
          $(optArr).each(function(oi,opt){
            eleOpts[oi+xi] = $(JQOPT,{'value':opt[ele.src.map[0]]}).data(opt).html(opt[ele.src.map[1]]);
            });
          }
        }
      
      var eleVal = (sectDta[eli] !== null ? sectDta[eli] : '');
      }
    
    var hideTarg = null;
    var xFnct = null;
    if(typeof ele.src !== 'undefined' && ele.src !== null){
      if(typeof ele.src.hides !== 'undefined'){hideTarg = ele.src.hides;}
      if(typeof ele.fnct !== 'undefined' && ele.fnct !== null){xFnct = ele.fnct;}
      }
    
    if(ele.type == 'idx' || ele.type == 'hidden'){
      eleVal = htmlDecode(0,eleVal);
      trs[tri].tr = 0;
      trs[tri].inpt[0] = $(JQNPT,{'type':'hidden','name':eli,'value':eleVal,'class':ele.cls+eleAddCls});
      }
    else if(eleMatch !== null && reqMatch[3] == 1){
      eleVal = htmlDecode(0,eleVal);
      trs[tri].inpt[0] = $(JQNPT,{'type':'hidden','name':eli,'value':eleVal,'class':ele.cls+eleAddCls});
      trs[tri].inpt[1] = $(JQNPT,{'type':'text','name':eli+'-dummy','value':eleHTML,'class':'tbox form-control estNoClear input-'+ele.cls,'disabled':'disabled'});
      if(ele.hint !== null){$(trs[tri].inpt[1]).attr('title',ele.hint);}
      }
    else{
      if((ele.type == 'selfselect' || ele.type == 'eselect' || ele.type == 'select')){ // && eleOpts.length > 0
        var eSrcTbl = defs.tbls[ele.src.tbl];
        var eSrcIdx = ele.src.idx;
        if(eli == 'city_timezone' && eleVal == ''){eleVal = $('[name="prop_timezone"]').val();}
        trs[tri].inpt[0] = $(JQSEL,{'name':eli,'value':eleVal,'class':'tbox form-control input-'+ele.cls+eleAddCls}).data({'hide':hideTarg,'xFnct':xFnct}).html(eleOpts).on({
          change : function(){
            var vlu = this.value;
            var hideIt = $(this).data('hide');
            var xFnct = $(this).data('xFnct');
            var tstDta = $(this).find('option:selected').data();
            
            if(eleMatch !== null){$(this).closest('form').data('form').match[eli] = {'ele':eleMatch,'vl':vlu};}
            if(eli == 'city_timezone'){$('[name="prop_timezone"]').val(vlu).change();}
            if(hideIt !== null){
              var hideEle = (hideIt[1] !== '' ? $('[name="'+hideIt[0]+'"]').closest(hideIt[1]) : $('[name="'+hideIt[0]+'"]'));
              if(vlu == '' || Number(vlu) == 0){$(hideEle).hide();}
              else{$(hideEle).show();}
              estPopHeight(1);
              }
            
            if(xFnct !== null && typeof xFnct.change !== 'undefined'){
              $(xFnct.change).each(function(sfi,sfnct){
                console.log(sfnct[0]);
                if(sfnct[1] == 'self'){window[sfnct[0]](this,tstDta);}
                else{
                  var myFunc = window[sfnct[0]];
                  console.log(myFunc);
                  if(typeof myFunc === 'function'){myFunc(tstDta);}
                  else{alert('javascript function "'+sfnct+'" not found');}
                  //window[sfnct[0]](tstDta);
                  }
                });
              }
            }
          });
        
        if(ele.hint !== null){$(trs[tri].inpt[0]).attr('title',ele.hint);}
        
        $(trs[tri].inpt[0]).children('option').sort(function (a, b) {
          var cA = $(a).html(); var cB = $(b).html();
          return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
          }).appendTo($(trs[tri].inpt[0])).promise().done(function(){
            $(trs[tri].inpt[0]).find('option[value="'+eleVal+'"]').prop('selected','selected');
            });
        trs[tri].trip = trs[tri].inpt[0];
        
        if(xFnct !== null && typeof xFnct.btns !== 'undefined'){
          console.log(xFnct.btns);
          trs[tri].wrap = 1;
          $(xFnct.btns).each(function(bi,btn){
            xbi = bi+1;
            trs[tri].inpt[xbi] = $(JQBTN,{'type':'button','name':eli+'-button-'+bi,'value':btn[0],'title':btn[1],'class':'btn btn-default selEditBtn1'}).data({'targ':trs[tri].inpt[0],'conf':btn[2]}).html('<i class="'+btn[4]+'"></i>').on({
              click : function(){
                
                var myFunc = window[btn[3]];
                console.log(myFunc);
                if(typeof myFunc === 'function'){
                  var conf = $(this).data('conf');
                  if(conf.length > 1){
                    if(jsconfirm(conf)){myFunc(this);}
                    }
                  else{myFunc(this);}
                  }
                else{alert('javascript function "'+sfnct+'" not found');}
                }
              });
            });
          }
        
        
        }
      else if(ele.type == 'textarea' || ele.type == 'commalist'){
        trs[tri].inpt[0] = $('<textarea></textarea>',{'name':eli,'cols':50,'rows':(ele.rows !== null ? ele.rows : 4),'placeholder':plcHldr,'class':'tbox form-control e-autoheight input-'+ele.cls+eleAddCls}).html(eleVal);
        if(ele.type == 'commalist'){
          $(trs[tri].inpt[0]).on({
            change : function(){$(this).val($(this).val().replace(/\s*,\s*/ig, ','));},
            blur : function(){$(this).val($(this).val().replace(/\s*,\s*/ig, ','));},
            focus : function(){
              if($(this).val().indexOf(',') > -1){$(this).val($(this).val().split(",").join(",\n"));}
              }
            });
          }
        if(ele.hint !== null){$(trs[tri].inpt[0]).attr('title',ele.hint);}
        }
      else if(ele.type == 'commaline'){
        trs[tri].inpt[0] = $('<textarea></textarea>',{'name':eli,'cols':50,'rows':1,'placeholder':plcHldr,'class':'tbox form-control e-autoheight input-'+ele.cls+eleAddCls}).html(eleVal);
        $(trs[tri].inpt[0]).on({
          change : function(){$(this).val($(this).val().replace(/\s*,\s*/ig, ' • '));},
          blur : function(){$(this).prop('rows',1).val($(this).val().replace(/\s*,\s*/ig, ' • '));},
          focus : function(){
            if($(this).val().indexOf(' • ') > -1){$(this).prop('rows',5).val($(this).val().split(" • ").join("\n"));}//•
            }
          });
        if(ele.hint !== null){$(trs[tri].inpt[0]).attr('title',ele.hint);}
          
        }
      else if(ele.type == 'switch'){
        var eleId = eli.replace('_','-');
        var eleSwName = eli+'__switch';
        var eleSwId = eleId+'--switch';
        trs[tri].inpt[0] = $(JQNPT,{'type':'hidden','name':eli,'id':eleId,'value':eleVal});
        trs[tri].inpt[1] = $(JQDIV,{'class':'bootstrap-switch bootstrap-switch-wrapper form-control bootstrap-switch-small bootstrap-switch-id-'+eleSwId+' bootstrap-switch-animate'}).css({'width':'118px'}).data('targ',trs[tri].inpt[0]);
        var swlb = $(JQDIV,{'class':'bootstrap-switch-container'}).css({'width':'174px'}).appendTo(trs[tri].inpt[1]);
        $(JQSPAN,{'class':'bootstrap-switch-handle-on bootstrap-switch-primary'}).css({'width':'58px'}).html(ele.src[1]).appendTo(swlb);
        $(JQSPAN,{'class':'bootstrap-switch-label'}).css({'width':'58px'}).html('&nbsp;').appendTo(swlb);
        $(JQSPAN,{'class':'bootstrap-switch-handle-off bootstrap-switch-default'}).css({'width':'58px'}).html(ele.src[0]).appendTo(swlb);
        
        $(trs[tri].inpt[1]).data('sw',swlb);
        if(ele.hint !== null){$(trs[tri].inpt[1]).attr('title',ele.hint);}
        
        if(eleVal !== 0){
          $(trs[tri].inpt[1]).addClass('bootstrap-switch-on');
          $(swlb).css({'margin-left':'0px'});
          }
        else{
          $(trs[tri].inpt[1]).addClass('bootstrap-switch-off');
          $(swlb).css({'margin-left':'-58px'});
          }
        
        $(trs[tri].inpt[1]).on({
          click : function(e){
            if($(this).data('targ').val() == 1){
              $(this).data('targ').val(0);
              $(this).removeClass('bootstrap-switch-on').addClass('bootstrap-switch-off');
              $(this).data('sw').animate({'margin-left':'-58px'},'fast');
              }
            else{
              $(this).data('targ').val(1);
              $(this).removeClass('bootstrap-switch-off').addClass('bootstrap-switch-on');
              $(this).data('sw').animate({'margin-left':'0px'},'fast');
              }
            }
          });
        }
      else if(ele.type == 'checkbox'){
        trs[tri].inpt[0] = $(JQDIV,{'class':'bootstrap-switch-container'}).css({'width':'132px'});
        $(JQSPAN,{'class':'bootstrap-switch-handle-on bootstrap-switch-primary'}).css({'width':'44px'}).html(ele.src.on).appendTo(trs[tri].inpt[0]);
        $(JQSPAN,{'class':'bootstrap-switch-label'}).css({'width':'44px'}).html('&nbsp;').appendTo(trs[tri].inpt[0]);
        $(JQSPAN,{'class':'bootstrap-switch-handle-off bootstrap-switch-default'}).css({'width':'44px'}).html('&nbsp;').appendTo(trs[tri].inpt[0]);
        $(JQNPT,{'type':'hidden','name':eli+'__switch','id':eli+'--switch','value':eleVal,'title':'','class':'form-check-input ui-state-valid '+ele.cls+eleAddCls}).attr('data-name',eli).attr('data-type','switch').attr('data-size','small').attr('data-on',ele.src.on).attr('data-off',ele.src.off).attr('data-inverse','0').attr('data-wrapper','wrapper form-control').attr('data-original-title','').appendTo(trs[tri].inpt[0]);
        if(ele.hint !== null){$(trs[tri].inpt[0]).attr('title',ele.hint);}
        
        }
      else if(ele.type == 'datetime'){
        trs[tri].wrap = 1;
        var d = estJSDate(eleVal);
        var dVal = d.y+'-'+d.m+'-'+d.d;
        trs[tri].inpt[0] = $(JQNPT,{'type':'hidden','name':eli,'id':eli,'value':eleVal});
        var tVal = d.hh+':'+d.i;
        trs[tri].inpt[1] = $(JQNPT,{'type':'date','id':eli+'-date','class':'tbox input-medium estInpDT form-control ui-state-valid','value':dVal});
        trs[tri].inpt[2] = $(JQNPT,{'type':'time','id':eli+'-time','class':'tbox input-medium estInpDT form-control ui-state-valid','value':tVal});
        
        $(trs[tri].inpt[1]).data('targ',trs[tri].inpt).on({
          change : function(){estConcatDateTime(this)}
          });
        $(trs[tri].inpt[2]).data('targ',trs[tri].inpt).on({
          change : function(){estConcatDateTime(this)}
          });
        
        }
      else{
        eleVal = htmlDecode(0,eleVal);
        if(ele.type == null){ele.type = 'text';}
        trs[tri].inpt[0] = $(JQNPT,{'type':ele.type,'name':eli,'value':eleVal,'placeholder':plcHldr,'class':'tbox form-control input-'+ele.cls+eleAddCls});
        if(ele.hint !== null){$(trs[tri].inpt[0]).attr('title',ele.hint);}
        }
      }
    tri++;
    });
  return trs;
  }



  

function estBuildMediaMgr(){
  var defs = $('body').data('defs');
  return mmgr;
  }




function estProcPdta(pDta,fldmap,ret){
  if(pDta.form.elem == null){return;}
  console.log(pDta);
  var defs = $('body').data('defs');
  if($(pDta.form.elem).is('select')){
    if(fldmap == null){estAlertLog(defs.txt.nomap);}
    else{
      rk0 = ret.kf.indexOf(fldmap[0]);
      rk1 = ret.kf.indexOf(fldmap[1]);
      if(ret.newid > 0){
        $(JQOPT,{'value':ret.newid}).html(ret.kv[rk1]).appendTo(pDta.form.elem).promise().done(function(){
          $(pDta.form.elem).children('option').sort(function (a, b) {
            var cA = $(a).html(); var cB = $(b).html();
            return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
            }).appendTo($(pDta.form.elem)).promise().done(function(){
              $(pDta.form.elem).val(ret.newid).change();
              });
          });
        }
      else{
        $(pDta.form.elem).find('option[value="'+ret.kv[rk0]+'"]').html(ret.kv[rk1]).promise().done(function(){
          $(pDta.form.elem).children('option').sort(function (a, b) {
            var cA = $(a).html(); var cB = $(b).html();
            return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
            }).appendTo($(pDta.form.elem)).promise().done(function(){
              $(pDta.form.elem).val(ret.kv[rk0]).change();
              });
          
          });
        }
      }
    }
  else{estAlertLog(defs.txt.lostresult);}
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
                
                if(Number(qDta.media_lev) > 0 && Number(qDta.media_type) == 1){ //
                  if(pic1 == 0){
                    //if(Number(qDta.media_lev) > 0){var urlpth = defs.dir.prop.thm;}
                    //else{var urlpth = defs.dir.subdiv.thm;}
                    var urlpth = defs.dir.prop.thm;
                    var mURL = urlpth+qDta.media_thm+'?'+Math.floor(Math.random()*(99999-99+1)+99);
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
  
  
  
  
function estDoCrop(cropper){
  var defs = $('body').data('defs');
  var mDta = $('#cropImage').data();
  var sDta = {'fetch':51,'rt':'js','bs':0,'dir':mDta.finfo.dir,'fname':mDta.finfo.fname,'rot':mDta.cropDta.rt,'sx':mDta.cropDta.sx,'sy':mDta.cropDta.sy,'x1':mDta.cropDta.x1,'x2':mDta.cropDta.x2,'y1':mDta.cropDta.y1,'y2':mDta.cropDta.y2};
  console.log(sDta);
  
  $.ajax({
    url: vreFeud+'?51||0',
    method: 'POST',
    data:sDta,
    dataType:'json',
    cache:false,
    processData:true,
    success: function(ret, textStatus, jqXHR){
      console.log(ret);
      if(typeof ret.error !== 'undefined'){
        estAlertLog(ret.error);
        }
      var mURL = mDta.finfo.dir.abs+mDta.finfo.fname+'?'+Math.floor(Math.random()*(99999-99+1)+99);
      $(mDta.mediaThm).css({'background-image':'url('+mURL+')'});
      $('#cropImage').prop('src',mURL);
      cropper.replace(mURL);
      },
    error: function(jqXHR, textStatus, errorThrown){
      console.log('ERRORS: '+textStatus+' '+errorThrown);
      estAlertLog(jqXHR.responseText);
      }
    });
  }



function estPrepCropper(){
  var defs = $('body').data('defs');

  $('.estCrpBtn').prop('disabled',false).removeProp('disabled');
  $('#cropMsg').remove();
  $('#cropImage').css({'margin':''});
  $('#cBtnMoveL').prop('disabled',false).removeProp('disabled').on({click : function(){cropper.move(-10, 0);}});
  $('#cBtnMoveR').prop('disabled',false).removeProp('disabled').on({click : function(){cropper.move(10, 0);}});
  $('#cBtnMoveU').prop('disabled',false).removeProp('disabled').on({click : function(){cropper.move(0,-10);}});
  $('#cBtnMoveD').prop('disabled',false).removeProp('disabled').on({click : function(){cropper.move(0,10);}});
  $('#cBtnZoomO').prop('disabled',false).removeProp('disabled').on({click : function(){cropper.zoom(-0.1);}});
  $('#cBtnZoomI').prop('disabled',false).removeProp('disabled').on({click : function(){cropper.zoom(0.1);}});
  $('#cBtnRotL').prop('disabled',false).removeProp('disabled').on({click : function(){cropper.rotate($(this).data('dg'));}});
  $('#cBtnRotR').prop('disabled',false).removeProp('disabled').on({click : function(){cropper.rotate($(this).data('dg'));}});
  $('#cBtnFlipH').prop('disabled',false).removeProp('disabled').on({
    click : function(){
      var imgDta = $('#cropImage').data();
      imgDta.cropDta.sx = imgDta.cropDta.sx * -1;
      cropper.scaleX(imgDta.cropDta.sx);
      $('#cropImage').data(imgDta);
      }
    });
  $('#cBtnFlipV').prop('disabled',false).removeProp('disabled').on({
    click : function(){
      var imgDta = $('#cropImage').data();
      imgDta.cropDta.sy = imgDta.cropDta.sy * -1;
      cropper.scaleY(imgDta.cropDta.sy);
      $('#cropImage').data(imgDta);
      }
    });
  
  $('#cBtnReset').prop('disabled',false).removeProp('disabled').on({click : function(){cropper.reset();}});
  
  var image = document.querySelector('#cropImage');
  var cropper = new Cropper(image,{
    viewMode:2,
    autoCropArea:0.99,
    ready: function(){
      var cropper = this.cropper;
      var imd = cropper.getImageData();
      $(image).removeData('cropDta');
      var imgDta = $(image).data();
      console.log(imgDta);
      $.extend(imgDta,{'cropDta':{'x1':imd.left,'y1':imd.top,'x2':imd.naturalWidth,'y2':imd.naturalHeight,'x3':imd.naturalWidth,'y3':imd.naturalHeight,'asp':parseFloat(imd.aspectRatio).toFixed(3),'x4':Math.ceil(imd.width),'y4':Math.ceil(imd.height),'rt':imd.rotate,'sx':imd.scaleX,'sy':imd.scaleY}});
      $('#cropImage').data(imgDta);
      console.log(imgDta);
      },
    crop: function (event){
      var imgDta = $(image).data();
      if(typeof imgDta.cropDta !== 'undefined'){
        imgDta.cropDta.x1 = Math.ceil(event.detail.x);
        imgDta.cropDta.y1 = Math.ceil(event.detail.y);
        imgDta.cropDta.x2 = Math.ceil(event.detail.width);
        imgDta.cropDta.y2 = Math.ceil(event.detail.height);
        imgDta.cropDta.rt = event.detail.rotate;
        imgDta.cropDta.sx = event.detail.scaleX;
        imgDta.cropDta.sy = event.detail.scaleY;
        $('#cropImage').data(imgDta);
        }
      },
    cropend: function (event){
      var cropDta = $('#cropImage').data('cropDta');
      var sDta = {'dsth':cropDta.y2,'dstw':cropDta.x2,'dstx':0,'dsty':0,'rot':cropDta.rt,'scalex':cropDta.sx,'scaley':cropDta.sy,'srch':cropDta.y3,'srcw':cropDta.x3,'srcx':cropDta.x1,'srcy':cropDta.y1}
      console.log(sDta);
      },
    });
  
  $('#cBtnCropPrev').prop('disabled',false).removeProp('disabled').on({click : function(){estDoCrop(cropper)}});
  cropper.crop();
  }
  
  
  
  function estImgReassignForm(mediaDta){
    
    
    }
  
  
  
  function estEditMEdiaForm(slide,popIt,tdta){
    var defs = $('body').data('defs');
    var hlp = defs.txt.cropbtns;
    
    if(slide == 1){
      popIt.frm[slide] = estBuildSlide(slide,{'tabs':[defs.txt.zoomcrop,defs.txt.image+' '+defs.txt.data],'fnct':{'name':'estSHCropBtn','args':1}});
      $(popIt.frm[slide].slide).appendTo(popIt.belt);
      $(popIt.frm[slide].slide).css({'left':'100%'}).hide();
      }
    
    var popFrm = popIt.frm[slide];
    var srcTbl = defs.tbls[tdta.tbl];
    var eSrcFrm = srcTbl.form[tdta.fld];
    if(eSrcFrm.src == null){
      $.extend(eSrcFrm,{'src':{'tbl':tdta.tbl,'idx':srcTbl.flds[0]}});
      }
    
    var destTbl = defs.tbls.estate_media;
    
    var mediaThm = tdta.mediaThm;
    var mediaDta = defs.tbls.estate_media.dta.find(x => Number(x.media_idx) === Number(tdta.defdta.media_idx));
    var mediaKey = defs.tbls.estate_media.dta.indexOf(mediaDta);
    
    if(typeof mediaDta === 'undefined' || mediaKey < 0){
      estAlertLog('Media data not found');
      return;
      }
    
    mediaDta = estMediaFldClean(mediaDta);
    mediaDta.media_asp = Number(mediaDta.media_asp);
    var propId = Number($('body').data('propid'));
    if(Number(mediaDta.media_propidx) == 0){var desttarg = 1;}
    else{var desttarg = 2;}
    
    $.ajax({
      url: vreFeud+'?50||0',
      type:'post',
      data:{'fetch':50,'propid':propId,'rt':'js','desttarg':desttarg,'mediadta':mediaDta},
      dataType:'json',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        if(typeof ret.error !== 'undefined'){
          estAlertLog(ret.error);
          }
        else{
          var mediaTitle = estMediaTitle(mediaDta,'edit media');
          $('#estPopCont').data('popit',popIt);
          
          $(popIt.frm[slide].h3).empty().promise().done(function(){
            $(JQSPAN,{'class':'FL','title':defs.txt.cancelremove}).html(defs.txt.edit+' '+defs.txt.image+' ('+mediaTitle+')').on({
              click : function(){
                if(slide == 1){estRemovePopoverAlt(popIt.frm[0].tabs.tab[3].li);}
                else{estRemovePopover();}
                }
              }).appendTo(popIt.frm[slide].h3);
            
            console.log(popIt.frm[slide]);
            
            
            var cBtnBar = $(JQDIV,{'id':'cropBtnBar'}).prependTo(popIt.frm[slide].popCont);
            
            $(JQBTN,{'id':'cBtnMoveL','class':'btn btn-sm estCrpBtn btn-default','title':hlp[0]}).html('<i class="fa fa-arrow-left"></i>').on({
              click : function(e){e.preventDefault();}
              }).prop('disabled',true).appendTo(cBtnBar);
            $(JQBTN,{'id':'cBtnMoveR','class':'btn btn-sm estCrpBtn btn-default','title':hlp[1]}).html('<i class="fa fa-arrow-right"></i>').on({
              click : function(e){e.preventDefault();}
              }).prop('disabled',true).appendTo(cBtnBar);
            $(JQBTN,{'id':'cBtnMoveU','class':'btn btn-sm estCrpBtn btn-default','title':hlp[2]}).html('<i class="fa fa-arrow-up"></i>').on({
              click : function(e){e.preventDefault();}
              }).prop('disabled',true).appendTo(cBtnBar);
            $(JQBTN,{'id':'cBtnMoveD','class':'btn btn-sm estCrpBtn btn-default','title':hlp[3]}).html('<i class="fa fa-arrow-down"></i>').on({
              click : function(e){e.preventDefault();}
              }).prop('disabled',true).appendTo(cBtnBar);
            $(JQBTN,{'id':'cBtnZoomO','class':'btn btn-sm estCrpBtn btn-default','title':hlp[4]}).html('<i class="fa fa-search-minus"></i>').on({
              click : function(e){e.preventDefault();}
              }).prop('disabled',true).appendTo(cBtnBar);
            $(JQBTN,{'id':'cBtnZoomI','class':'btn btn-sm estCrpBtn btn-default','title':hlp[5]}).html('<i class="fa fa-search-plus"></i>').on({
              click : function(e){e.preventDefault();}
              }).prop('disabled',true).appendTo(cBtnBar);
            $(JQBTN,{'id':'cBtnRotL','class':'btn btn-sm estCrpBtn btn-default','title':hlp[6]}).data('dg',-5).html('<i class="fa fa-rotate-left"></i>').on({
              click : function(e){e.preventDefault();}
              }).prop('disabled',true).appendTo(cBtnBar);
            $(JQBTN,{'id':'cBtnRotR','class':'btn btn-sm estCrpBtn btn-default','title':hlp[7]}).data('dg',5).html('<i class="fa fa-rotate-right"></i>').on({
              click : function(e){e.preventDefault();}
              }).prop('disabled',true).appendTo(cBtnBar);
            $(JQBTN,{'id':'cBtnFlipH','class':'btn btn-sm estCrpBtn btn-default','title':hlp[8]}).html('<i class="fa fa-arrows-alt-h"></i>').on({
              click : function(e){e.preventDefault();}
              }).prop('disabled',true).appendTo(cBtnBar);
            $(JQBTN,{'id':'cBtnFlipV','class':'btn btn-sm estCrpBtn btn-default','title':hlp[9]}).html('<i class="fa fa-arrows-v"></i>').on({
              click : function(e){e.preventDefault();}
              }).prop('disabled',true).appendTo(cBtnBar);
            $(JQBTN,{'id':'cBtnReset','class':'btn btn-sm estCrpBtn btn-default','title':hlp[10]}).html('<i class="fa fa-refresh"></i>').on({
              click : function(e){e.preventDefault();}
              }).prop('disabled',true).appendTo(cBtnBar);
            $(JQBTN,{'id':'cBtnCropPrev','class':'btn btn-sm estCrpBtn btn-default','title':hlp[11]}).css({'color':'#00CC00'}).html('<i class="fa fa-check"></i>').on({
              click : function(e){e.preventDefault();}
              }).prop('disabled',true).appendTo(cBtnBar);
            
            //'<i class="fa fa-check"></i>'
            //'<i class="fa fa-eye"></i>'
                  
            $('#cropBtnBar').css({'background-color':$('#cBtnZoomO').css('background-color')});
            
            var tooltips = [];
            var tabtr = popFrm.tabs.tab;
            var tabx = 0;
            
            var tri = 0;
            tabtr[tabx].tr[tri] = [];
            tabtr[tabx].tr[tri][0] = $(JQTR).appendTo(tabtr[tabx].tbody);
            tabtr[tabx].tr[tri][1] = $(JQTD,{'class':'TAC'}).attr('colspan','2').appendTo(tabtr[tabx].tr[tri][0]);
            var cViewDiv = $(JQDIV,{'id':'cropContainer'}).appendTo(tabtr[tabx].tr[tri][1]);
            
            $(JQDIV,{'class':'estAttribution'}).html(defs.txt.poweredby+': <a href="https://fengyuanchen.github.io/cropperjs/" target="_BLANK">cropper.js</a>').appendTo(tabtr[tabx].tr[tri][1]);
            
            if(typeof ret.typerr !== 'undefined'){
              $(JQDIV,{'id':'cropMsg'}).html(ret.typerr).appendTo(cViewDiv);
              }
            else{
              $(JQDIV,{'id':'cropMsg'}).html(defs.txt.startcrop).on({click : function(){estPrepCropper();}}).appendTo(cViewDiv);
              }
            
             
            if(Number(mediaDta.media_lev) > 0){var fURL = defs.dir.prop.thm+mediaDta.media_thm;}
            else{var fURL = defs.dir.subdiv.thm+mediaDta.media_thm;}
            $.extend(ret,{'fURL':fURL});
            var imgDta = {'finfo':ret,'mediaKey':mediaKey,'mediaDta':mediaDta,'mediaThm':mediaThm}
            $('<img />',{'id':'cropImage'}).data(imgDta).prop('src',fURL+'?'+Math.floor(Math.random()*(99999-99+1)+99)).appendTo(cViewDiv);
            
            
            
            //image data tab
            tabx++;
            var tri = 0;
            
            if(Number(mediaDta.media_propidx) > 0){
              tabtr[tabx].tr[tri] = [];
              tabtr[tabx].tr[tri][0] = $(JQTR,{'class':'estExifTR'}).appendTo(tabtr[tabx].tbody);
              tabtr[tabx].tr[tri][1] = $(JQTD,{'class':'VAM'}).html(defs.txt.reassign+' '+defs.txt.image).appendTo(tabtr[tabx].tr[tri][0]);
              tabtr[tabx].tr[tri][2] = $(JQTD).appendTo(tabtr[tabx].tr[tri][0]);
              
              var estRmSel = $(JQSEL,{'id':'estRmSel'}).data(mediaDta).on({
                change : function(){
                  var urlpth = defs.dir.prop.thm;
                  var curDta = $(this).data();
                  var newDta = $(this).find('option:selected').data();
                  console.log(curDta,newDta);
                  }
                }).appendTo(tabtr[tabx].tr[tri][2]);
              
              var dDta = estRedoMedia(mediaDta,1,Number(0));
              var opt = $(JQOPT).data(dDta).val(0).html(dDta.media_name).appendTo(estRmSel);
              
              
              var spaceDta = $.grep(defs.tbls.estate_spaces.dta, function (element, index) {return element.space_lev == 1;});
              $(spaceDta).each(function(ri,rmdta){
                var dDta = estRedoMedia(mediaDta,2,rmdta.space_idx,rmdta.space_name);
                var opt = $(JQOPT).data(dDta).val(rmdta.space_idx).html(dDta.media_name).appendTo(estRmSel);
                }).promise().done(function(){
                  $(estRmSel).find('option[value="'+Number(mediaDta.media_levidx)+'"]').prop('selected','selected').append('*');
                  $(estRmSel).val(Number(mediaDta.media_levidx)).change();
                  });
              
              tri++;
              }
            
            
            tabtr[tabx].tr[tri] = [];
            tabtr[tabx].tr[tri][0] = $(JQTR,{'class':'estExifTR'}).appendTo(tabtr[tabx].tbody);
            tabtr[tabx].tr[tri][1] = $(JQTD).html(defs.txt.dimensions).appendTo(tabtr[tabx].tr[tri][0]);
            tabtr[tabx].tr[tri][2] = $(JQTD,{'id':'mediaDimLab'}).html(ret.w+' x '+ret.h).appendTo(tabtr[tabx].tr[tri][0]);
            tri++;
            
            if(typeof ret.exif !== 'undefined'){
              $.each(ret.exif, function(ei,edta){
                tabtr[tabx].tr[tri] = [];
                tabtr[tabx].tr[tri][0] = $(JQTR,{'class':'estExifTR'}).appendTo(tabtr[tabx].tbody);
                tabtr[tabx].tr[tri][1] = $(JQTD).html(ei).appendTo(tabtr[tabx].tr[tri][0]);
                tabtr[tabx].tr[tri][2] = $(JQTD).appendTo(tabtr[tabx].tr[tri][0]);
                
                if(ei == 'COMPUTED'){
                  $.each(edta,function(ci,cdta){$(tabtr[tabx].tr[tri][2]).append('<div>'+ci+': '+cdta+'</div>');});
                  }
                else{
                  $(tabtr[tabx].tr[tri][2]).html(edta)
                  }
                
                tri++;
                });
              }
            
            if(slide == 1){
              $(popIt.frm[1].slide).show().animate({'left':'0px'});
              $(popIt.frm[0].slide).animate({'left':'-100%'}).promise().done(function(){
                $(popIt.frm[0].slide).hide();
                estPopHeight(1);
                });
              }
            else{
              estPosPopover();
              }
            });
          }
        },
      error: function(jqXHR, textStatus, errorThrown){
        console.log('ERRORS: '+textStatus+' '+errorThrown);
        estAlertLog(jqXHR.responseText);
        }
      });
    }




function estRedoMedia(mediaDta,lev,levidx,levname=''){
  var defs = $('body').data('defs');
  var propId = Number($('body').data('propid'));
  
  var dDta = estDefDta('estate_media');
  dDta.media_idx = Number(mediaDta.media_idx);
  dDta.media_propidx = (Number(lev) ? Number(propId) : Number(0));
  dDta.media_lev = Number(lev);
  dDta.media_levidx = Number(levidx);
  dDta.media_levord = 1;
  dDta.media_galord = Number(mediaDta.media_galord);
  dDta.media_asp = mediaDta.media_asp;
  dDta.media_type = Number(mediaDta.media_type);
  dDta.media_thm = dDta.media_propidx+'-'+dDta.media_lev+'-'+dDta.media_levidx+'-'+dDta.media_idx+'.'+mediaDta.media_thm.split('.').pop();
  dDta.media_full = (mediaDta.media_full !== '' ? dDta.media_thm : '');
  dDta.media_name = mediaDta.media_name;
  
  switch(lev){
    case 1 :
      levname = defs.txt.property;
      break;
    
    default:
      break;
    }
  
  
  var srcMedia = $.grep(defs.tbls.estate_media.dta, function (element, index) {return element.media_propidx == Number(mediaDta.media_propidx);});
  //console.log(srcMedia);
  
  //var othMedia = $.grep(srcMedia, function (element, index) {return Number(element.media_lev) == dDta.media_lev;});
  
  var othMedia = $.grep(srcMedia, function (element, index) {return Number(element.media_levidx) == Number(levidx);});
  if(othMedia !== 'undefined'){
    if(Number(dDta.media_levidx) !== Number(mediaDta.media_levidx)){
      dDta.media_levord = othMedia.length + 1;
      dDta.media_name = levname+' #'+dDta.media_levord;
      }
    }
  console.log(dDta);
  return dDta;
  }





function fltrSelectGo(fldta,rtbl,newVal,rfld,fele,cVal){
  var defs = $('body').data('defs');
  var grpGrep2 = $.grep(defs.tbls[rtbl.tbl].dta, function (element, index) {return element[fele.map[1]] == newVal;});
  
  $fltrEle = $('select[name="'+rfld+'"');
  $fltrEle.empty().promise().done(function(){
    if(grpGrep2.length > 0){
      if(typeof fele.blank !=='undefined' && fele.blank == 1){$(JQOPT,{'value':''}).html('-').appendTo($fltrEle);}
      
      var opts = [];
      if(typeof fele.map[2] !== 'undefined' && fele.map[2] !== ''){
        var tmp = grpGrep2[0][fele.map[3]];
        if(tmp.indexOf(fele.map[2]) > -1){tmp = tmp.split(fele.map[2]);}
        else{tmp = [tmp];}
        $(tmp).each(function(i,opt){opts.push({'k':opt,'v':opt});});
        }
      else{
        $(grpGrep2).each(function(i,opt){opts.push({'k':opt[rtbl.map[0]],'v':opt[rtbl.map[1]]});});
        }
      
      $(opts).each(function(i,opt){
        $(JQOPT,{'value':opt.k}).html(opt.v).appendTo($fltrEle);
        }).promise().done(function(){
          $fltrEle.children('option').sort(function (a, b) {
            var cA = $(a).html();
            var cB = $(b).html();
            return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
            }).appendTo($fltrEle).promise().done(function(){
              if(typeof cVal !== 'undefined'){
                var cOpt = $fltrEle.find('option[value="'+cVal+'"]');
                if(typeof cOpt !== 'undefined'){$fltrEle.find('option[value="'+cVal+'"]').prop('selected','selected');}
                else{$fltrEle.find('option:first-child').prop('selected','selected');}
                $fltrEle.change();
                }
              else{
                $fltrEle.find('option:first-child').prop('selected','selected');
                $fltrEle.change();
                }
              });
          });
      }
    else{
      if(typeof fele.blank !=='undefined' && fele.blank == 1){$(JQOPT,{'value':''}).html('-').appendTo($fltrEle);}
      }
    });
  }


function fltrSelect(fldta,rtbl,newVal,rfld,fele){
  var defs = $('body').data('defs');
  var propId = Number($('body').data('propid'));
  
  if(typeof rtbl == 'undefined'){return;}
  if(typeof rtbl.tbl == 'undefined'){return;}
  if(typeof defs.tbls[rtbl.tbl] == 'undefined'){return;}
  $fltrEle = $('select[name="'+rfld+'"');
  var cVal = $fltrEle.find('option:selected').val();
  
    
  var grpGrep2 = $.grep(defs.tbls[rtbl.tbl].dta, function (element, index) {return element[fele.map[1]] == newVal;});
  
  if(grpGrep2.length > 1){
    if(newVal.toString() === grpGrep2[0][fele.map[1]].toString()){
      fltrSelectGo(fldta,rtbl,newVal,rfld,fele,cVal);
      return;
      }
    }
  else{
    fltrSelectGo(fldta,rtbl,newVal,rfld,fele,cVal);
    return;
    }
  
  
  if(typeof fele.fetch !== 'undefined'){
    console.log(fele.fetch);
    if(fele.fetch.length < 1){fltrSelectGo(fldta,rtbl,newVal,rfld,fele,cVal); return;}
    
    if(typeof fldta.maintbl == 'undefined'){alert('No Main Table'); return;}
    if(typeof fldta.mainfld == 'undefined'){alert('No Main Table Field'); return;}
    if(typeof defs.tbls[fldta.maintbl] == 'undefined'){alert('No update Main Table Source'); return;}
    if(typeof defs.tbls[fldta.maintbl].flds == 'undefined'){alert('No update Main Table Key'); return;}
    var mainKey = defs.tbls[fldta.maintbl].flds[0];
    
    var mainKx = (typeof defs.tbls[fldta.maintbl].form[mainKey].str !== 'undefined' ? defs.tbls[fldta.maintbl].form[mainKey].str : 'int');
    var goFetch = {'fetchtbl':rtbl.tbl,'fetchkey':fele.map[1],'maintbl':fldta.maintbl,'mainfld':fldta.mainfld,'mainkey':mainKey,'mainidx':propId,'mainkx':mainKx,'nval':newVal}
    
    console.log(goFetch);
    
      $.ajax({
        url: vreFeud+'?1||0',
        type:'post',
        data:{'fetch':6,'propid':propId,'rt':'js','tdta':goFetch},
        dataType:'json',
        cache:false,
        processData:true,
        success: function(ret, textStatus, jqXHR){
          console.log(ret);
          if(typeof ret.alldta !== 'undefined'){estProcDefDta(ret.alldta.tbls);}
          fltrSelectGo(fldta,rtbl,newVal,rfld,fele,cVal);
          },
        error: function(jqXHR, textStatus, errorThrown){
          console.log('ERRORS: '+textStatus+' '+errorThrown);
          estAlertLog(jqXHR.responseText);
          }
        });
    
    }
  else{
    fltrSelectGo(fldta,rtbl,newVal,rfld,fele,cVal);
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
      
      if(mode > 6){
        var upEle = $(mediaThm).find('input[type="file"]');
        var imgEle = $(mediaThm).find('img');
        
        var imgurl = ['',''];
        switch(mode){
          case 9 :
            var btnTxt = [defs.keys.avatarlab[0],defs.keys.avatarlab[1]];
            var srcFld = $('input[name="agent_imgsrc"]');
            imgurl[0] = $('input[name="agent_altimg"]').val();
            imgurl[1] = ($('input[name="agent_image"]').val().length > 2 ? defs.dir.agent+$('input[name="agent_image"]').val() : '');
            break;
          
          case 8 :
            var btnTxt = [defs.txt.website+' '+defs.txt.logo,defs.txt.custom+' '+defs.txt.logo];
            var srcFld = $('input[name="agency_imgsrc"]');
            imgurl[0] = $('input[name="agency_altimg"]').val();
            imgurl[1] = ($('input[name="agency_image"]').val().length > 3 ? defs.dir.agency+$('input[name="agency_image"]').val() : '');
            break;
          }
          
        
        
        if($(mediaThm).hasClass('estNUAvatar')){
          $(uplBtn).show();
          $(mediaEditBox).addClass('upld');
          $(mediaThm).data('upl',2);
          $(srcFld).val(0);
          $(srcBtn).html(defs.keys.avatarlab[2]).on({
            click : function(e){e.preventDefault();}
            });
          }
        else{
          var srcVal = Number($(srcFld).val());
          $(mediaThm).data('upl',srcVal);
          if(srcVal == 1){
            $(uplBtn).show();
            $(mediaEditBox).addClass('upld');
            }
          else{
            $(uplBtn).hide();
            $(mediaEditBox).removeClass('upld');
            }
          
          $(srcBtn).html(btnTxt[srcVal]).on({
            click : function(e){
              e.stopPropagation();
              e.preventDefault();
              var scrVal = Number($(mediaThm).data('upl'));
              if(srcVal == 1){
                srcVal = 0;
                $(uplBtn).hide();
                $(mediaEditBox).removeClass('upld');
                }
              else{
                srcVal = 1;
                $(uplBtn).show();
                $(mediaEditBox).addClass('upld');
                }
              
              $(mediaThm).data('upl',srcVal);
              
              $(srcFld).val(srcVal);
              $(srcBtn).html(btnTxt[srcVal]);
              
              $(mediaThm).css({'background-image':''});
              $(imgEle).prop('src','');
              
              var imgUrl = imgurl[srcVal];
              if(imgUrl !== ''){
                imgUrl += '?'+Math.floor(Math.random()*(99999-99+1)+99);
                $(mediaThm).css({'background-image':'url("'+imgUrl+'")'});
                $(imgEle).prop('src',imgUrl);
                }
              }
            });
          }
          
        $(uplBtn).on({
          click :function(e){
            e.stopPropagation();
            e.preventDefault();
            $(upEle).click();
            }
          });
        }
      
      else if(mode > 3 && mode < 7){
        var keyTbl = defs.keys.contabs[mode];
        mediaDta = $(mediaThm).closest('form').data('levdta');
        $(mediaEditBox).data('mediadta',mediaDta);
        
        switch(mode){
          case 6 :
            $(srcBtn).html(defs.keys.avatarlab[Number(mediaDta.agent_imgsrc)]);
            if(Number(mediaDta.agent_idx) == 0){
              $(srcBtn).html(defs.keys.avatarlab[0]).prop('disabled',true);
              $(uplBtn).prop('disabled',true);
              return;
              }
            else{
              if(Number(mediaDta.agent_uid) == 0){
                $(srcBtn).html(defs.keys.avatarlab[1]).on({click : function(e){e.stopPropagation();e.preventDefault();}});
                if(Number(mediaDta.agent_imgsrc) !== 1){
                  mediaDta.agent_imgsrc = 1;
                  $(mediaThm).closest('form').data('levdta',mediaDta);
                  }
                }
              else{
                $(srcBtn).on({
                  click : function(e){
                    e.stopPropagation();
                    e.preventDefault();
                    var mediaDta = $(mediaThm).closest('form').data('levdta');
                    console.log(mediaDta);
                    if(Number(mediaDta.agent_imgsrc) == 1){mediaDta.agent_imgsrc = 0;}
                    else{mediaDta.agent_imgsrc = 1;}
                    $(mediaThm).closest('form').data('levdta',mediaDta);
                    $(e.target).html(defs.keys.avatarlab[mediaDta.agent_imgsrc]);
                    $('input[name="agent_imgsrc"]').val(Number(mediaDta.agent_imgsrc));
                    estSetAgentImg(mode);
                    if(Number(mediaDta.agent_imgsrc) == 1){$(uplBtn).prop('disabled',false).removeProp('disabled')}
                    else{$(uplBtn).prop('disabled',true);}
                    }
                  });
                }
              }
            
            $(uplBtn).on({
              click : function(e){
                e.stopPropagation();
                e.preventDefault();
                var mediaDta = $(mediaThm).closest('form').data('levdta');
                if(mediaDta.agent_idx > 0){estFileUplFld(mediaDta,1,mediaThm,6);}
                }
              });
            break;
          
          case 5 :
            if(Number(mediaDta.agency_idx) == 0){
              $(srcBtn).html(defs.keys.avatarlab[0]).prop('disabled',true);
              $(uplBtn).prop('disabled',true);
              return;
              }
            else{
              if(Number(mediaDta.agency_imgsrc) == 1){
                $(srcBtn).html(defs.txt.custom+' '+defs.txt.logo);
                $(uplBtn).prop('disabled',false).removeProp('disabled')
                }
              else{
                $(srcBtn).html(defs.txt.website+' '+defs.txt.logo);
                $(uplBtn).prop('disabled',true);
                }
              
              $(srcBtn).on({
                click : function(e){
                  e.stopPropagation();
                  e.preventDefault();
                  var mediaDta = $(mediaThm).closest('form').data('levdta');
                  if(Number(mediaDta.agency_imgsrc) == 1){
                    mediaDta.agency_imgsrc = 0;
                    $(e.target).html(defs.txt.website+' '+defs.txt.logo);
                    }
                  else{
                    mediaDta.agency_imgsrc = 1;
                    $(e.target).html(defs.txt.custom+' '+defs.txt.logo);
                    }
                  $(mediaThm).closest('form').data('levdta',mediaDta);
                  
                  $('input[name="agency_imgsrc"]').val(Number(mediaDta.agency_imgsrc));
                  estSetAgentImg(mode);
                  if(Number(mediaDta.agency_imgsrc) == 1){$(uplBtn).prop('disabled',false).removeProp('disabled')}
                  else{$(uplBtn).prop('disabled',true);}
                  }
                });
              }
            $(uplBtn).on({
              click : function(e){
                e.stopPropagation();
                e.preventDefault();
                var mediaDta = $(mediaThm).closest('form').data('levdta');
                if(mediaDta.agency_idx > 0){estFileUplFld(mediaDta,1,mediaThm,5);}
                }
              });
            break;
          
          case 4 :
            break;
          }
        }
      else{
        $(srcBtn).remove();
        $(uplBtn).remove();
        
        var mediaDta = $(mediaThm).data();
        $(mediaEditBox).data('mediadta',mediaDta);
        var mediaTitle = estMediaTitle(mediaDta,'edit media btns');
        
        var editBtn = $(JQBTN,{'class':'btn btn-default btn-sm estNoRightBord','title':defs.txt.edit+' '+defs.txt.image}).on({
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
        $(JQSPAN,{'class':'fa fa-pencil-square-o'}).appendTo(editBtn); //fa fa-crop
        
        var uplBtn = $(JQBTN,{'class':'btn btn-default btn-sm estNoLRBord','title':defs.txt.mediareplace+' '+mediaTitle}).on({
          click : function(e){
            e.stopPropagation();
            e.preventDefault();
            var mediaDta = $(this).parent().data('mediadta');
            console.log(mediaDta);
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
              //if(Number(eDta.media_lev) > 0){var urlpth = defs.dir.prop.thm;}
              //else{var urlpth = defs.dir.subdiv.thm;}
              var urlpth = defs.dir.prop.thm;
              var mURL = urlpth+eDta.media_thm+'?'+Math.floor(Math.random()*(99999-99+1)+99);
              $('#estSectThm-'+eDta.media_lev+'-'+eDta.media_levidx).css({'background-image':'url('+mURL+')'});
              }
            }).promise().done(function(){
              estSaveElemOrder(tDta,1);
              });
          }
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
  console.log(levDta);
  
  var sectKey = defs.tbls.estate_sects[lev][1];
  var mediaCont = $('#estMediaMgrCont');
  if(levDta[sectKey] == 0){
    $('#estMediaNoGo').html(defs.txt.savefirst+' '+defs.txt.media).show();
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
    if(lev > 0){estFileUplFld(newMediaDta);}
    else{estFileUplFld(newMediaDta,1,null,1);}
    
    
    $(mediaCont).empty().promise().done(function(){
      mediaGrep1 = $.grep(defs.tbls.estate_media.dta, function (element, index) {return element['media_lev'] == lev;});
      mediaGrep2 = $.grep(mediaGrep1, function (element, index) {return element['media_levidx'] == levDta[sectKey];});
      
      $(mediaGrep2).each(function(k,mediaDta){
        ulbtn[k] = [];
        if(Number(mediaDta.media_lev) > 0){var urlpth = defs.dir.prop.thm+mediaDta.media_thm+noCache;}
        else{var urlpth = defs.dir.subdiv.thm+mediaDta.media_thm+noCache;}
        ulbtn[k][0] = $(JQDIV,{'class':'upldPvwBtn pvw-'+mediaDta.media_idx}).css({'background-image':'url('+urlpth+')'}).appendTo('#estMediaMgrCont');
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




function estSetMap(mapId){
  var defs = $('body').data('defs');
  var propId = Number($('body').data('propid'));
  var mapW = defs.prefs.map_width;
  var mapH = defs.prefs.map_height;
  
  var targCont = $('#'+mapId).parent();
  var flds = $(targCont).data();
  
  var latFld = flds.latfld;
  var lonFld = flds.lonfld;
  var zoomFld = flds.zoomfld;
  var lat = $(latFld).val();
  var lon = $(lonFld).val();
  var zoom = Number($(zoomFld).val());
  
  var mapW = $(targCont).width();
  
  $(targCont).empty().promise().done(function(){
    $(JQDIV,{'id':mapId,'class':'estMap'}).css({'width':mapW+'px'}).appendTo(targCont).promise().done(function(){
      //.animate({'width':mapW+'px','height':mapH+'px'}).css({'width':mapW+'px','height':mapH+'px'})
        //estSubMap
        var map = L.map(mapId).setView([lat, lon], zoom);
      	
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


function estBuildMap(actn){
  var defs = $('body').data('defs');
  var mapId = 'est_'+actn+'_Map';
  var mapSrchRes = $('#est_'+actn+'_SrchRes');
  var mapSrchBtn = $('#est_'+actn+'_SrchBtn');
  var mapLookupAddr = $('#'+actn+'_addr_lookup');
  var latFld = $('input[name="'+actn+'_lat"]');
  var lonFld = $('input[name="'+actn+'_lon"]');
  var zoomFld = $('input[name="'+actn+'_zoom"]');
  var geoFld = $('input[name="'+actn+'_geoarea"]');
  
  $('#'+mapId).parent().data({'latfld':latFld,'lonfld':lonFld,'zoomfld':zoomFld,'geofld':geoFld});
  
  var mapReset = $(JQBTN,{'id':'mapReset-'+mapId,'class':'btn btn-primary btn-sm'}).html(defs.txt.resetmap).on({
    click : function(e){
      e.preventDefault();
      $(mapLookupAddr).val($(mapLookupAddr).data('pval'));
      $(latFld).val($(latFld).data('pval'));
      $(lonFld).val($(lonFld).data('pval'));
      $(zoomFld).val($(zoomFld).data('pval'));
      estSetMap(mapId);
      }
    }).appendTo(mapSrchRes).promise().done(function(){
      var xHt = Number($(mapSrchRes).height()) - Number($(mapReset).height());
      var foundCoordCont = $(JQDIV,{'id':'mapLookupRes-'+mapId,'class':'estFoundMapCoordRes'}).css({'height':xHt+'px'}).appendTo(mapSrchRes);
      
      if(actn == 'prop'){
        mAddr = estSetPropAddress();
        $(mapLookupAddr).val(mAddr);
        }
      else{
        var linkAddr = $('textarea[name="'+actn+'_addr"]');
        if(linkAddr.length == 0){linkAddr = $('input[name="'+actn+'_addr"]');}
        if(linkAddr.length == 1){
          $(mapLookupAddr).val($(linkAddr).val().replace(/\s*\n\s*/ig, ', '));
          $(linkAddr).on({
            keyup : function(e){
              if($(mapLookupAddr).data('man') == 0){
                $(mapLookupAddr).val($(linkAddr).val().replace(/\s*\n\s*/ig, ', '));
                }
              },
            change : function(e){
              if($(mapLookupAddr).data('man') == 0){
                $(mapLookupAddr).val($(linkAddr).val().replace(/\s*\n\s*/ig, ', '));
                }
              }
            });
          }
        
        if($(latFld).val() == 0){$(latFld).val(defs.prefs.pref_lat);}
        if($(lonFld).val() == 0){$(lonFld).val(defs.prefs.pref_lon);}
        }
      
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
                        estSetMap(mapId);
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
        change : function(){$(foundCoordCont).empty();} //estSetMap(mapId);
        });
      
      $(latFld).data('pval',$(latFld).val());
      $(lonFld).data('pval',$(lonFld).val());
      var zoom = Number($(zoomFld).val());
      if(zoom < 4 || zoom > 19){
        if(Number(defs.prefs.pref_zoom) > 3 && Number(defs.prefs.pref_zoom) < 20){zoom = Number(defs.prefs.pref_zoom);}
        else{zoom = 14;}
        }
      $(zoomFld).data('pval',zoom).val(zoom);
      
      estSetMap(mapId);
      });
  }



function estBuildContTbl(cTarg,tblKey,cDta=null){
    var defs = $('body').data('defs');
    var tKeys = defs.keys.contabs[tblKey];
    if(cDta == null){cDta = estDefDta(tKeys[0]);}
    
    console.log(cDta);
    
    $(cTarg.tfoot).empty().promise().done(function(){
      $(cTarg.tbody).empty().promise().done(function(){

        var kDta = null;
        var kDtaSrc = defs.tbls[tKeys[0]].dta;
        var zDta = kDtaSrc.find(x => Number(x[tKeys[1]]) === Number(cDta[tKeys[1]]));
        if(typeof zDta !== 'undefined'){kDta = kDtaSrc[kDtaSrc.indexOf(zDta)];}
        if(kDta == null){
          //$('#estPopContRes'+0).html(defs.txt.please+' '+defs.txt.save+' '+defs.txt.first).show();
          return;
          }
        
        var tblIdx = kDta[tKeys[1]];
        var xDta = $.grep(defs.tbls.estate_contacts.dta, function (element, index) {return element.contact_tabkey == tblKey;});
        var curContDta = $.grep(xDta, function (element, index) {return element.contact_tabidx == tblIdx;});
        
        $(cTarg.tbody).attr('id','estContactTableTB');
        $.extend(cDta,{'key':Number(tblKey),'idx':Number(tblIdx)});
        
        $(cTarg.tabl).data(cDta);
        
        var tr = [];
        var tri = 0;
        $(curContDta).each(function(yi,yDta){
          estBuildContactTR(cTarg.tbody,yDta,tri);
          tri++;
          }).promise().done(function(){
            var yDta = estDefDta('estate_contacts');
            yDta.contact_tabkey = tblKey;
            yDta.contact_tabidx = tblIdx;
            yDta.contact_key = defs.txt.new1;
            estBuildContactTR(cTarg.tfoot,yDta,tri);
            
            $(cTarg.tbody).children('tr').sort(function (a, b) {
              var cA = $(a).data().contact_ord;
              var cB = $(b).data().contact_ord;
              return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
              }).appendTo(cTarg.tbody);
            
            var estSortAgContTbl = document.getElementById('estContactTableTB');
            Sortable.create(estSortAgContTbl,{
              group: 'estAgContTbl', 
              draggable: 'tr.estContList',
              sort: true,
              animation: 450,
              handle: 'button.estContMove',
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
                $('#estContactTableTB').children('tr.estContList').each(function(i,ele){
                  var eDta = $(ele).data();
                  if(Number(eDta.contact_ord) !== (i + 1)){
                    eDta.contact_ord = i + 1;
                    $(ele).data(eDta);
                    tDta.push({'tbl':'estate_contacts','key':'contact_idx','fdta':eDta,'del':0});
                    }
                  }).promise().done(function(){
                    estSaveElemOrder(tDta,2);
                    });
                }
              });
            });
        });
      });
    }



  
function estContTypePresets(ele=null){
  $('#estContTypePresetCont').remove().promise().done(function(){
    if(ele !== null){
      var tr = $(ele).parent().parent();
      var dv1 = $(JQDIV,{'id':'estContTypePresetCont','class':'popover'}).on({
        click : function(e){$('#estContTypePresetCont').remove();}
        }).appendTo(tr);
      
      if(!$(ele).closest('table').hasClass('estContInMain')){
        var pos = $(ele).offset();
        var wd = $(ele).outerWidth()+16;
        $(dv1).css({'position':'fixed','top':(pos.top - 28)+'px','left':(wd+pos.left)+'px'});
        }
      
      $('<h4></h4>').html('X').appendTo(dv1);
      var dv2 = $(JQDIV).appendTo(dv1);
      
      var defs = $('body').data('defs');
      //console.log(defs.keys.contkeys);
      $(defs.keys.contkeys).each(function(i,txt){
        $(JQBTN,{'class':'bth btn-default btn-sm'}).html(txt.toUpperCase()).on({
          click : function(e){
            e.preventDefault();
            $(ele).parent().find('button.contType').html(txt.toUpperCase());
            $(ele).parent().find('input[type="text"].contTypeTxt').val(txt.toUpperCase()).change();
            $('#estContTypePresetCont').remove();
            }
          }).appendTo(dv2);
        });
      }
    });
  }



function estPrepContGoBtn(tr){
  var defs = $('body').data('defs');
  var txt1 = $(tr).find('input[type="text"].contTypeTxt');
  var txt2 = $(tr).find('input[type="text"].contData');
  var btn1 = $(tr).find('button.contType');
  var btn2 = $(tr).find('button.estContMove');
  var btn3 = $(tr).find('button.estContGo');
  var eleGo = 0;
  
  var contDta = $(tr).data();
  //console.log(contDta);
  
  if(Number(contDta.contact_idx) == 0){eleGo++;}
  
  var nVal1 = $(txt1).val().toUpperCase();
  
  $(txt1).val(nVal1).hide();
  $(btn1).html(nVal1).show();
  if(contDta.contact_key !== nVal1){eleGo++;}
  if(defs.keys.contkeys.indexOf(nVal1) == -1){
    defs.keys.contkeys.push(nVal1);
    $('body').data('defs',defs);
    }
  
  
  if(Number(contDta.contact_idx) > 0){
    if(contDta.contact_data !== $(txt2).val()){eleGo++;}
    }
  
  if($(tr).parent().is('tbody')){
    if(eleGo == 0){estSetContGoBtn(-1,tr);}
    else{estSetContGoBtn(0,tr);}
    }
  else{estSetContGoBtn(0,tr);}
  }


function estBindContactTR(tr){
  if(!$(tr).hasClass('estBound')){
    var defs = $('body').data('defs');
    var tblDta = $(tr).closest('table').data();
    
    var txt1 = $(tr).find('input[type="text"].contTypeTxt');
    var txt2 = $(tr).find('input[type="text"].contData');
    var btn1 = $(tr).find('button.contType');
    var btn2 = $(tr).find('button.estContMove');
    var btn3 = $(tr).find('button.estContGo');
    
    var contDta = $(tr).data();
    $.each(contDta,function(k,v){$(tr).removeAttr('data-'+k);});
    
    if(typeof contDta.contact_key == 'undefined'){var contDta = estDefDta('estate_contacts');}
    if(Number(contDta.contact_tabidx) !== Number(tblDta.idx)){contDta.contact_tabidx = Number(tblDta.idx);}
    if(Number(contDta.contact_tabkey) !== Number(tblDta.key)){contDta.contact_tabkey = Number(tblDta.key);}
    
    $(tr).data(contDta).addClass('estBound');
    
    $(txt1).on({
      keyup : function(e){
        $(e.target).val($(e.target).val().toUpperCase());
        },
      change : function(e){
        $(e.target).val($(e.target).val().toUpperCase());
        estPrepContGoBtn(tr);
        },
      focus :function(e){estContTypePresets(e.target);},
      blur : function(e){
        $(btn1).show();
        $(txt1).hide();
        estPrepContGoBtn(tr);
        },
      });
    
    $(txt2).on({
      keyup : function(){estPrepContGoBtn(tr);},
      change : function(){estPrepContGoBtn(tr);},
      focus : function(){
        $('input[type="text"].contTypeTxt').hide();
        $('button.contType').show();
        estContTypePresets();
        }
      });
    
    $(btn1).on({
      click : function(e){
        e.preventDefault();
        //estContTypePresetCont
        $('input[type="text"].contTypeTxt').hide().promise().done(function(){
          $('button.contType').show().promise().done(function(){
            $(e.target).parent().find('button.contType').hide();
            $(e.target).parent().find('input[type="text"].contTypeTxt').show().focus();
            });
          });
        }
      });
    
    $(btn3).on({
      click : function(e){
        e.preventDefault();
        estContactUpDate(0,btn3);
        }
      });
    
    estPrepContGoBtn(tr);
    }
  }
  
  
function estBuildContactTR(tBody,yDta,tri){
  var defs = $('body').data('defs');
  var tr = $(JQTR,{'class':'estContList'}).data(yDta).appendTo(tBody);
  var td = $(JQTD,{'colspan':2}).appendTo(tr);
  var contDiv = $(JQDIV,{'class':'contactD1'}).appendTo(td);
  $(JQBTN,{'class':'btn btn-primary contType','title':defs.txt.clkcust}).html(yDta.contact_key).appendTo(contDiv);
  $(JQNPT,{'type':'text','name':'contact_key['+Number(yDta.contact_tabkey)+']['+Number(yDta.contact_idx)+']','value':yDta.contact_key,'class':'tbox form-control btn-primary contTypeTxt'}).appendTo(contDiv).hide();
  $(JQNPT,{'type':'text','name':'contact_data['+Number(yDta.contact_tabkey)+']['+Number(yDta.contact_idx)+']','value':yDta.contact_data,'class':'tbox form-control contData'}).appendTo(contDiv);
  
  if(yDta.contact_idx > 0){
    $(td).addClass('noPAD');
    $(JQBTN,{'class':'btn btn-default estContMove','title':defs.txt.sort}).html('<i class="fa fa-arrows-v"></i>').appendTo(contDiv);
    $(JQBTN,{'class':'btn btn-default estContGo','title':defs.txt.deletes}).data('del',-1).css({'color':'#CC0000'}).html('<i class="fa fa-close"></i>').appendTo(contDiv);
    }
  else{
    $(td).addClass('noPADLR');
    $(JQBTN,{'class':'btn btn-default estContGo','title':defs.txt.save}).data('del',0).css({'color':'#00CC00'}).html('<i class="fa fa-save"></i>').appendTo(contDiv);
    }
  estBindContactTR(tr);
  }

  
function estContactUpDate(mode,btn){
  var defs = $('body').data('defs');
  var yTR = $(btn).closest('tr');
  var yDta = $(yTR).data();
  console.log(mode,yDta);
  
  if(yDta.contact_tabkey == 0){
    alert('Agent Not Saved, cannot add Contact Info');
    return;
    }
  
  if(mode > 1){
    if(mode == 4){
      //do update
      var newKey = $(yTR).find('input[type="text"].contTypeTxt').val();
      var newDta = $(yTR).find('input[type="text"].contData').val();
      var delMe = $(yTR).find('button.estContGo').data('del');
      if(typeof delMe == 'undefined'){delMe = 0;}
      
      if(delMe === -1){if(!jsconfirm(defs.txt.deletes+' '+defs.txt.contact+' '+newKey+' '+newDta+'?')){return;}}
      if(delMe > -1 && newKey.length < 1){return;}
      if(delMe > -1 && newDta.length < 1){return;}
      
      var upGo = 0;
      if(delMe == -1 && yDta.contact_idx > 0){upGo++;}
      if(newKey !== yDta.contact_key){upGo++;}
      if(newDta !== yDta.contact_data){upGo++;}
      
      if(upGo > 0){
        var propId = $('body').data('propid');
        if(typeof propId == 'undefined'){propId = 0;}
        
        var cTbl = $(btn).closest('table');
        var tBody = $(cTbl).find('tbody');
        var tFoot = $(cTbl).find('tfoot');
        var ydPar = $(yTR).parent();
        var nxtTr = $(tBody).find('tr').length+1;
        
        $(cTbl).find('button.estContGo').prop('disabled',true);
        $(cTbl).find('button.estContMove').prop('disabled',true);
        
        yDta.contact_key = newKey;
        yDta.contact_data = newDta;
        if(yDta.contact_idx == 0){yDta.contact_ord = nxtTr;}
        
        var xDta = [];
        $(defs.tbls.estate_contacts.flds).each(function(fi,fk){
          var psh = {};
          psh[fk] = yDta[fk];
          xDta.push(psh);
          }).promise().done(function(){
            
            if($(ydPar).is('tfoot')){$(tFoot).fadeOut(200);}
            var sDta = {'fetch':3,'propid': Number(propId),'rt':'js','tdta':[{'tbl':'estate_contacts','key':'contact_idx','fdta':xDta,'del':delMe}]};
            console.log(delMe,sDta);
            $.ajax({
              url: vreFeud+'?5||0',
              type:'post',
              data:sDta,
              dataType:'json',
              cache:false,
              processData:true,
              success: function(ret, textStatus, jqXHR){
                $(cTbl).find('button.estContGo').prop('disabled',false).removeProp('disabled');
                $(cTbl).find('button.estContMove').prop('disabled',false).removeProp('disabled');
                $(tFoot).fadeIn(200);
                ret = ret[0];
                console.log(ret);
                if(typeof ret.error !== 'undefined'){
                  estAlertLog(ret.error);
                  $('#estPopContRes'+0).html(ret.error).fadeIn(200,function(){estPopHeight(1)});
                  }
                else{
                  if(typeof ret.alldta !== 'undefined'){estProcDefDta(ret.alldta.tbls);}
                  if($(ydPar).is('tfoot')){
                    if(ret.newid > 0){yDta.contact_idx = ret.newid;}
                    
                    estBuildContactTR(tBody,yDta,nxtTr);
                    $(yTR).remove().promise().done(function(){
                      $(tBody).children('tr').sort(function (a, b) {
                        var cA = $(a).data().contact_ord;
                        var cB = $(b).data().contact_ord;
                        return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
                        }).appendTo(tBody);
                      
                      nxtTr++;
                      var nDta = estDefDta('estate_contacts');
                      nDta.contact_tabkey = yDta.contact_tabkey;
                      nDta.contact_tabidx = yDta.contact_tabidx;
                      nDta.contact_key = defs.txt.new1;
                      nDta.contact_ord = nxtTr;
                      
                      estBuildContactTR(ydPar,nDta,nxtTr);
                      estPopHeight(1);
                      });
                    }
                  else{
                    if(delMe == -1){
                      $(yTR).remove().promise().done(function(){
                        estPopHeight(1);
                        });
                      }
                    else{
                      console.log('yDta saved');
                      $(yTR).data(yDta);
                      $(yTR).find('button.estContGo').data('del',-1).css({'color':'#CC0000'}).html('<i class="fa fa-close"></i>');
                      }
                    }
                  }
                },
              error: function(jqXHR, textStatus, errorThrown){
                $(tFoot).fadeIn(200);
                console.log('ERRORS: '+textStatus+' '+errorThrown);
                estAlertLog(jqXHR.responseText);
                }
              });
            });
        }
      }
    else{
      var newDta = $(yTR).find('input[type="text"].contData').val();
      if(newDta.length > 1 && newDta !== yDta.contact_data){
        if(mode == 3){
          //keyup
          }
        else if(mode == 2){
          //change
          }
        estSetContGoBtn(0,yTR);
        }
      else{estSetContGoBtn(-1,yTR);}
      }
    }
  else{
    if(mode == 1){
      var ntIdx = 0;
      var curKey = $(btn).text();
      var curIdx = defs.keys.contkeys.indexOf(curKey);
      if(curIdx > -1 && curIdx + 1 < defs.keys.contkeys.length){ntIdx = curIdx + 1;}
      var newKey = defs.keys.contkeys[ntIdx];
      $(btn).html(newKey);
      if(yDta.contact_idx > 0){
        if(newKey !== yDta.contact_key){estSetContGoBtn(0,yTR);}
        else{estSetContGoBtn(-1,yTR);}
        }
      }
    else{
      //add/remove data
      estContactUpDate(4,btn);
      }
    }
  }

function estSetContGoBtn(mode,yTR){
  var defs = $('body').data('defs');
  var txt1 = $(yTR).find('input[type="text"].contTypeTxt');
  var txt2 = $(yTR).find('input[type="text"].contData');
  var btn3 = $(yTR).find('button.estContGo');
  var contDta = $(yTR).data();
  
  if($(yTR).parent().is('tbody')){
    if(mode == -1){
      $(btn3).attr('title',defs.txt.deletes).data('del',-1).css({'color':'#CC0000'}).html('<i class="fa fa-close"></i>');
      //$(txt1).removeProp('name');
      //$(txt2).removeProp('name');
      }
    else{
      $(btn3).attr('title',defs.txt.save).data('del',0).css({'color':'#00CC00'}).html('<i class="fa fa-save"></i>');
      //$(txt1).prop('name','contact_key['+Number(contDta.contact_tabkey)+']['+Number(contDta.contact_idx)+']');
      //$(txt2).prop('name','contact_data['+Number(contDta.contact_tabkey)+']['+Number(contDta.contact_idx)+']');
      }
    
    if(Number(contDta.contact_tabidx) == 0){
      $(btn3).prop('disabled',true);
      }
    else{
      $(btn3).prop('disabled',false).removeProp('disabled');
      }
    
    }
  }
  
  



  
  
  
function estSubDivForm(mainTbl,targ,frmn=0){
  var mainIdx = Number($('body').data('propid'));
  var targName = $(targ).prop('name');
  var cVal = $(targ).find('option:selected').val();
  return [mainTbl,targ,targName,cVal];
  }


function estBuildSubDivForm(popIt){
  var defs = $('body').data('defs');
  var mainIdx = Number($('body').data('propid'));
  var popFrm = popIt.frm[0];
  var levdta = $(popFrm.form).data('levdta');
  
  if(Number(levdta.subd_idx) == 0){
    $('#fileSlipBtn2').prop('disabled','disabled');
    }
  
  var tabtr =  popFrm.tabs.tab;
  
  var tabx = 3;
  tri = 0;
  tabtr[tabx].tr[tri] = [];
  tabtr[tabx].tr[tri][0] = $(JQTR).appendTo(tabtr[tabx].tbody);
  tabtr[tabx].tr[tri][1] = $(JQTD,{'colspan':2,'class':'noPAD'}).appendTo(tabtr[tabx].tr[tri][0]);
  $(JQDIV,{'id':'estFeatureNoGo','class':'s-message alert alert-block warning alert-warning'}).appendTo(tabtr[tabx].tr[tri][1]);
  fBox0 = $(JQDIV,{'id':'estFeatureMgrCont'}).appendTo(tabtr[tabx].tr[tri][1]);
  
  fBox1a = $(JQDIV,{'id':'estFeatureMgrLcol'}).appendTo(fBox0);
  fBox1b = $(JQDIV,{'id':'estFeatureListHead'}).appendTo(fBox1a);
  $(JQBTN,{'class':'btn btn-primary btn-sm'}).html(xt.new1+' '+xt.feature).on({
    click : function(e){e.preventDefault(); estAddEditFeature(0);}
    }).appendTo(fBox1b);
  $(JQNPT,{'type':'hidden','name':'feature_name','value':''}).appendTo(fBox1b);
  fBox1c = $(JQDIV,{'id':'estFeatureListCont','class':'estFeatNotUsed estFeatureListSort'}).appendTo(fBox1a);
  
  
  fBox2a = $(JQDIV,{'id':'estFeatureMgrRcol'}).appendTo(fBox0);
  fBox2b = $(JQDIV,{'id':'estFeatureUsedHead'}).appendTo(fBox2a);
  fBox2c = $(JQBTN,{'class':'btn btn-primary btn-sm'}).on({click : function(e){e.preventDefault();}}).appendTo(fBox2b);
  fBox2d = $(JQDIV,{'id':'estFeatureUsedCont','class':'estFeatInUse estFeatureListSort'}).appendTo(fBox2a);
  
  $('#estPopCont').data('ftarg',{'label':fBox2c,'addbtn':fBox1b,'targ':[fBox1c,fBox2d]});
  
  estBuildCategoryList(0);
  
  tabx = 4;
  tri = 0;
  tabtr[tabx].tr[tri] = [];
  tabtr[tabx].tr[tri][0] = $(JQTR).appendTo(tabtr[tabx].tbody);
  tabtr[tabx].tr[tri][1] = $(JQTD,{'colspan':2,'class':'noPAD'}).appendTo(tabtr[tabx].tr[tri][0]);
  
  $(JQDIV,{'id':'estMediaNoGo','class':'s-message alert alert-block warning alert-warning'}).appendTo(tabtr[tabx].tr[tri][1]);
  
  var mBox0 = $(JQDIV,{'id':'estMediaMgrCont'}).on({
    click : function(){
      var newMediaDta = estNewMediaDta(0);
      estFileUplFld(newMediaDta);
      }
    }).appendTo(tabtr[tabx].tr[tri][1]).promise().done(function(){
      estPosPopover();
      estBuildMediaList(0);
      estTestEles(popFrm.form,popFrm.savebtns);
      });
  }











function estBuildSpaceOptns(sTep=0){
  var defs = $('body').data('defs');
  var propId = Number($('body').data('propid'));
  var targ = [$('select[name="space_grpid"]'),$('select[name="space_catid"]')];
  
  var popit = $('#estPopCont').data('popit');
  var cForm = $(targ[0]).closest('form');
  
  var levDta = $(cForm).data('levdta');
  
  propZone = Number($('select[name="prop_zoning"]').val());
  var zoneDta = defs.tbls.estate_zoning.dta.find(x => x.zoning_idx === propZone);
  var zoneName = (typeof zoneDta !== 'undefined' ? zoneDta.zoning_name : defs.txt.unk);
  
   //residential, commercial...
  var zoneGrpX = $.grep(defs.tbls.estate_group.dta, function (element, index) {return element.group_zone == propZone;});
  //above groups only for "spaces"
  var zoneGroups = $.grep(zoneGrpX, function (element, index) {return element.group_lev == 2;});
  
  $(targ[0]).empty().promise().done(function(){
    $(zoneGroups).each(function(i,opt){
      $(JQOPT,{'value':opt.group_idx}).html(opt.group_name).appendTo(targ[0]);
      }).promise().done(function(){
        $(targ[0]).children('option').sort(function (a, b) {
          var cA = $(a).html(); var cB = $(b).html();
          return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
          }).appendTo(targ[0]).promise().done(function(){
            var zDta = zoneGroups.find(x => x.group_idx === levDta.space_grpid);
            if(typeof zDta !== 'undefined'){
              $(targ[0]).find('option[value="'+levDta.space_grpid+'"]').prop('selected','selected');
              $(targ[0]).val(levDta.space_grpid).change();
              }
            else{
              if($(targ[0]).find('option').length > 0){
                var dOpt = Number($(targ[0]).find('option').eq(0).val());
                levDta.space_grpid = dOpt;
                $(cForm).data('levdta',levDta);
                $(targ[0]).find('option').eq(0).prop('selected','selected');
                $(targ[0]).change();
                }
              }
            estTestEles(popit.frm[0].form, popit.frm[0].savebtns);
            });
        });
    });
  
  //residential, commercial...
  var zoneCatX = $.grep(defs.tbls.estate_featcats.dta, function (element, index) {return element.featcat_zone == propZone;});
   // above groups only for 'spaces'
  var zoneCats = $.grep(zoneCatX, function (element, index) {return element.featcat_lev == 2;});
  
  $(targ[1]).empty().promise().done(function(){
    $(zoneCats).each(function(i,opt){
      $(JQOPT,{'value':opt.featcat_idx}).html(opt.featcat_name).appendTo(targ[1]);
      }).promise().done(function(){
        $(targ[1]).children('option').sort(function (a, b) {
          var cA = $(a).html(); var cB = $(b).html();
          return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
          }).appendTo(targ[1]).promise().done(function(){
            var zDta = zoneCats.find(x => x.featcat_idx === levDta.space_catid);
            if(typeof zDta !== 'undefined'){
              $(targ[1]).find('option[value="'+levDta.space_catid+'"]').prop('selected','selected');
              $(targ[1]).val(levDta.space_catid).change();
              }
            else{
              if($(targ[1]).find('option').length > 0){
                var dOpt = Number($(targ[1]).find('option').eq(0).val());
                levDta.space_catid = dOpt;
                $(cForm).data('levdta',levDta);
                $(targ[1]).find('option').eq(0).prop('selected','selected');
                $(targ[1]).change();
                }
              }
            estTestEles(popit.frm[0].form, popit.frm[0].savebtns);
            });
        });
    });
  }



function estBuildSpace(SPDTA,tbx){
  var spaceId = (SPDTA !== null ? SPDTA.space_idx : 0);
  var spaceLev = (SPDTA !== null ? SPDTA.space_lev : 1);
  var spaceLevIdx = (SPDTA !== null ? SPDTA.space_levidx : 0);
  
  var defs = $('body').data('defs');
  var propId = Number($('body').data('propid'));
  var spaceFrm = defs.tbls.estate_spaces.form;
  var xt = defs.txt;
  
  var uperm = Number(defs.user.perm);
  var SpIc = [JQADI,defs.txt.add1];
  if(uperm >= 3){SpIc = [JQEDI,defs.txt.add1+'/'+defs.txt.edit];}
  
  var levmap = defs.keys.levmap;
  
  var popIt = estBuildPopover([{'tabs':[xt.main,xt.features,xt.description,xt.media],'fnct':{'name':'estSHUploadBtn','args':3}}]); //fnct acts on tab click
  var popFrm = popIt.frm[0];
  
  //need to configure form for 'spaceLev' value
  //spaceLev: 0=subdiv, 1=property, 2=spaces, 3=city space, 4=subdiv space
  
  var zoneDta = defs.tbls.estate_zoning.dta.find(x => Number(x.zoning_idx) === Number($('select[name="prop_zoning"]').val()));
  var zoneName = (typeof zoneDta !== 'undefined' ? zoneDta.zoning_name : defs.txt.unk);
  
  var destTbl = defs.tbls.estate_spaces;
  var levDta = destTbl.dta.find(x => Number(x.space_idx) === Number(spaceId));
  if(typeof levDta !== 'undefined'){var frmLabel = levDta.space_name;}
  else{
    var frmLabel = xt.new1+' '+zoneName+' '+xt.space;
    var levDta = estDefDta('estate_spaces');
    levDta.space_propidx = propId;
    levDta.space_lev = 1;
    levDta.space_levidx = propId;
    levDta.space_grpid = tbx.grouplist_groupidx;
    levDta.space_ord = (Number(destTbl.dta.length)+1);
    }
  
  $(popFrm.form).prop('enctype','multipart/form-data');
  
  var fileSlipBtn = $(JQBTN,{'type':'button','id':'fileSlipBtn2','class':'btn btn-primary btn-sm FR'}).html(xt.upload+' '+xt.media).on({
    click : function(e){
      e.stopPropagation();
      e.preventDefault();
      $('#fileSlip').click();
      }
    });
  
  
  
  var titSpan = $(JQSPAN,{'class':'FL','title':xt.cancelremove}).data('was',frmLabel).html(frmLabel).on({
    click : function(){estRemovePopover()}
    }).appendTo(popFrm.h3);
  
  var saveBtn2 = $(JQBTN,{'id':'estSaveSpace2','class':'btn btn-primary btn-sm FR'}).html(xt.save2).on({click : function(){
    estPopGo(1,popIt,0);
    }});
  
  var saveBtn1 = $(JQBTN,{'id':'estSaveSpace1','class':'btn btn-primary btn-sm FR'}).html(xt.save).on({click : function(){
    estPopGo(4,popIt,0);
    $(saveBtn2).show();
    }});
  
  $(saveBtn2).appendTo(popFrm.h3);
  $(saveBtn1).appendTo(popFrm.h3);
  $(fileSlipBtn).appendTo(popFrm.h3).hide();
  popFrm.savebtns.push(saveBtn1);
  popFrm.savebtns.push(saveBtn2);
  popFrm.savebtns.push(fileSlipBtn);
  
  if(levDta.space_idx == 0){$(saveBtn2).hide();}
  
  
  
  $(popFrm.form).data('levdta',levDta).data('destTbl',{'dta':destTbl.dta,'flds':destTbl.flds,'idx':'space_idx','table':'estate_spaces'});
  $(popFrm.form).data('maintbl',null).data('form',{'elem':null,'attr':null,'match':{},'fnct':{'name':'estSaveSpace'}});
  
  var newMediaDta = estNewMediaDta(2);
  estFileUplFld(newMediaDta);
  
  $(JQNPT,{'type':'hidden','name':'space_propidx','class':'estNoClear','value':Number(levDta.space_propidx)}).prependTo(popFrm.form);
  $(JQNPT,{'type':'hidden','name':'space_levidx','class':'estReOrd','value':Number(levDta.space_levidx)}).prependTo(popFrm.form);
  $(JQNPT,{'type':'hidden','name':'space_lev','class':'estNoClear','value':Number(levDta.space_lev)}).prependTo(popFrm.form);
  $(JQNPT,{'type':'hidden','name':'space_idx','value':Number(levDta.space_idx)}).prependTo(popFrm.form);
  
  var tabx = 0;
  var tri = 0;
  var tabtr = popFrm.tabs.tab;
  var tooltips = [];
  // TAB 0
  tabtr[tabx].tr[tri] = [];
  tabtr[tabx].tr[tri][0] = $(JQTR).appendTo(tabtr[tabx].tbody);
  tabtr[tabx].tr[tri][1] = $(JQTD).html(xt.name+'*').appendTo(tabtr[tabx].tr[tri][0]);
  tabtr[tabx].tr[tri][2] = $(JQTD).appendTo(tabtr[tabx].tr[tri][0]);
  $(JQNPT,{'type':'text','name':'space_name','value':levDta.space_name,'placeholder':xt.space+' '+xt.name,'class':'tbox form-control input-xlarge noblank'}).on({
    change : function(){
      if(this.value.length > 0){$(titSpan).html(this.value);}
      else{$(titSpan).html($(titSpan).data('was'));}
      },
    keyup : function(){
      if(this.value.length > 0){$(titSpan).html(this.value);}
      else{$(titSpan).html($(titSpan).data('was'));}
      }
    }).appendTo(tabtr[tabx].tr[tri][2]);
  tri++;
  
  
  tabtr[tabx].tr[tri] = [];
  tabtr[tabx].tr[tri][0] = $(JQTR).appendTo(tabtr[tabx].tbody);
  tabtr[tabx].tr[tri][1] = $(JQTD).html(xt.location).appendTo(tabtr[tabx].tr[tri][0]);
  tabtr[tabx].tr[tri][2] = $(JQTD).appendTo(tabtr[tabx].tr[tri][0]);
  $(JQNPT,{'type':'text','name':'space_loc','value':levDta.space_loc,'placeholder':xt.posithnt,'class':'tbox form-control input-xlarge'}).appendTo(tabtr[tabx].tr[tri][2]);
  tri++;
  
  
  tabtr[tabx].tr[tri] = [];
  tabtr[tabx].tr[tri][0] = $(JQTR).appendTo(tabtr[tabx].tbody);
  tabtr[tabx].tr[tri][1] = $(JQTD).html(xt.group1).appendTo(tabtr[tabx].tr[tri][0]);
  
  tabtr[tabx].tr[tri][2] = $(JQTD).appendTo(tabtr[tabx].tr[tri][0]);
  tabtr[tabx].tr[tri][3] = $(JQDIV,{'class':'estInptCont'}).appendTo(tabtr[tabx].tr[tri][2]);
  space_grpid = $(JQSEL,{'name':'space_grpid','value':levDta.space_grpid,'class':'tbox form-control xlarge input-xlarge ILBLK oneBtn'}).on({
     //change : function(){estSetSpaceGroup(this)} //group_lev
    }).appendTo(tabtr[tabx].tr[tri][3]);
    
  var sonar = $(JQDIV,{'class':'estSonar'}).appendTo(tabtr[tabx].tr[tri][3]);
  $(JQDIV,{'class':'estSonarBlip'}).appendTo(sonar);
  
  if(typeof spaceFrm.space_grpid.src.perm !== 'undefined' && uperm >= Number(spaceFrm.space_grpid.src.perm[1])){
    SpIc = [JQEDI,defs.txt.add1+'/'+defs.txt.edit];
    }
  $(JQBTN,{'type':'button','class':'btn btn-default selEditBtn1','title':SpIc[1]}).html(SpIc[0]).on({
    click : function(e){
      var defs = $('body').data('defs');
      var defDta = estDefDta('estate_group');
      defDta.group_lev = 2;
      defDta.group_zone = Number($('select[name="prop_zoning"]').val());
      estPopoverAlt(1,{'tbl':'estate_spaces','fld':'space_grpid','defdta':defDta,'fnct':{'name':'estSpaceGroupChange'}});
      }
    }).appendTo(sonar);
  $(tabtr[tabx].tr[tri][3]).data('chk',space_grpid);
  tri++;
  
  
  tabtr[tabx].tr[tri] = [];
  tabtr[tabx].tr[tri][0] = $(JQTR).appendTo(tabtr[tabx].tbody);
  tabtr[tabx].tr[tri][1] = $(JQTD).html(xt.category).appendTo(tabtr[tabx].tr[tri][0]);
  tabtr[tabx].tr[tri][2] = $(JQTD).appendTo(tabtr[tabx].tr[tri][0]);
  tabtr[tabx].tr[tri][3] = $(JQDIV,{'class':'estInptCont'}).appendTo(tabtr[tabx].tr[tri][2]);
  space_catid = $(JQSEL,{'name':'space_catid','value':levDta.space_catid,'class':'tbox form-control xlarge input-xlarge ILBLK oneBtn'}).on({
    change : function(){estBuildCategoryList(2);}
    }).appendTo(tabtr[tabx].tr[tri][3]);
  
  var sonar = $(JQDIV,{'class':'estSonar'}).appendTo(tabtr[tabx].tr[tri][3]);
  $(JQDIV,{'class':'estSonarBlip'}).appendTo(sonar);
  if(typeof spaceFrm.space_catid.src.perm !== 'undefined' && uperm >= Number(spaceFrm.space_catid.src.perm[1])){
    SpIc = [JQEDI,defs.txt.add1+'/'+defs.txt.edit];
    }
  $(JQBTN,{'type':'button','class':'btn btn-default selEditBtn1','title':SpIc[1]}).html(SpIc[0]).on({
    click : function(e){
      var defs = $('body').data('defs');
      var defDta = estDefDta('estate_featcats');
      defDta.featcat_lev = 2;
      defDta.featcat_zone = Number($('select[name="prop_zoning"]').val());
      estPopoverAlt(1,{'tbl':'estate_spaces','fld':'space_catid','defdta':defDta,'fnct':{'name':'estBuildSpaceOptns'}});
      }
    }).appendTo(sonar);
  $(tabtr[tabx].tr[tri][3]).data('chk',space_catid);
  tri++;
  
  
  tabtr[tabx].tr[tri] = [];
  tabtr[tabx].tr[tri][0] = $(JQTR).appendTo(tabtr[tabx].tbody);
  tabtr[tabx].tr[tri][1] = $(JQTD).html(xt.dimensions+' '+xt.xbyy).appendTo(tabtr[tabx].tr[tri][0]);
  tabtr[tabx].tr[tri][2] = $(JQTD).appendTo(tabtr[tabx].tr[tri][0]);
  tabtr[tabx].tr[tri][3] = $(JQDIV,{'class':'estInptCont'}).appendTo(tabtr[tabx].tr[tri][2]);
  var dimux = $(JQNPT,{'type':'number','name':'space_dimx','value':levDta.space_dimx,'min':0,'class':'tbox form-control input-small ILBLK'}).appendTo(tabtr[tabx].tr[tri][3]);
  var dimuy = $(JQNPT,{'type':'number','name':'space_dimy','value':levDta.space_dimy,'min':0,'class':'tbox form-control input-small ILBLK MLFT3'}).appendTo(tabtr[tabx].tr[tri][3]);
  tri++;
  
  tabtr[tabx].tr[tri] = [];
  tabtr[tabx].tr[tri][0] = $(JQTR).appendTo(tabtr[tabx].tbody);
  tabtr[tabx].tr[tri][1] = $(JQTD).html(xt.dimensions+' '+xt.sqr).appendTo(tabtr[tabx].tr[tri][0]);
  tabtr[tabx].tr[tri][2] = $(JQTD).appendTo(tabtr[tabx].tr[tri][0]);
  tabtr[tabx].tr[tri][3] = $(JQDIV,{'class':'estInptCont'}).appendTo(tabtr[tabx].tr[tri][2]);
  var dimuxy = $(JQNPT,{'type':'number','name':'space_dimxy','value':levDta.space_dimxy,'min':0,'step':2,'class':'tbox form-control input-small FL ILBLK estNoRightBord'}).appendTo(tabtr[tabx].tr[tri][3]);
  
  
  if(Number(levDta.space_idx) > 0){var dimuLev = Number(levDta.space_dimu);}
  else{var dimuLev = Number($('input[name="prop_dimu1"]').val());}
  var dimu = $(JQNPT,{'type':'hidden','name':'space_dimu','value':dimuLev}).appendTo(tabtr[tabx].tr[tri][3]);
  
  var spaceDimuBtn = $(JQBTN,{'id':'spaceDimuBtn','class':'btn btn-default estNoLeftBord'});
  $(spaceDimuBtn).html(defs.keys.dim1u[dimuLev][0]).on({
    click : function(e){
      e.preventDefault();
      e.stopPropagation();
      var defs = $('body').data('defs');
      var cv = Number($('input[name="space_dimu"]').val()) +1;
      if(cv >= defs.keys.dim1u.length){cv = 0;}
      $('input[name="space_dimu"]').val(cv);
      $(spaceDimuBtn).html(defs.keys.dim1u[cv][0]);
      }
    }).appendTo(tabtr[tabx].tr[tri][3]);
  
  
  $(dimux).on({change : function(){estCalcSqFt(1,dimux,dimuy,dimuxy);}});
  $(dimuy).on({change : function(){estCalcSqFt(2,dimux,dimuy,dimuxy);}});
  $(dimuxy).on({change : function(){estCalcSqFt(3,dimux,dimuy,dimuxy);}});
  
  tri++;
  
  
  
  //TAB 1
  tabx++;
  tri = 0;
  tabtr[tabx].tr[tri] = [];
  tabtr[tabx].tr[tri][0] = $(JQTR).appendTo(tabtr[tabx].tbody);
  tabtr[tabx].tr[tri][1] = $(JQTD,{'colspan':2,'class':'noPAD'}).appendTo(tabtr[tabx].tr[tri][0]);
  $(JQDIV,{'id':'estFeatureNoGo','class':'s-message alert alert-block warning alert-warning'}).appendTo(tabtr[tabx].tr[tri][1]);
  fBox0 = $(JQDIV,{'id':'estFeatureMgrCont'}).appendTo(tabtr[tabx].tr[tri][1]);
  
  fBox1a = $(JQDIV,{'id':'estFeatureMgrLcol'}).appendTo(fBox0);
  fBox1b = $(JQDIV,{'id':'estFeatureListHead'}).appendTo(fBox1a);
  $(JQBTN,{'class':'btn btn-primary btn-sm'}).html(xt.new1+' '+xt.feature).on({
    click : function(e){e.preventDefault(); estAddEditFeature(2);}
    }).appendTo(fBox1b);
  $(JQNPT,{'type':'hidden','name':'feature_name','value':''}).appendTo(fBox1b);
  fBox1c = $(JQDIV,{'id':'estFeatureListCont','class':'estFeatNotUsed estFeatureListSort'}).appendTo(fBox1a);
  
  
  fBox2a = $(JQDIV,{'id':'estFeatureMgrRcol'}).appendTo(fBox0);
  fBox2b = $(JQDIV,{'id':'estFeatureUsedHead'}).appendTo(fBox2a);
  fBox2c = $(JQBTN,{'class':'btn btn-primary btn-sm'}).on({click : function(e){e.preventDefault();}}).appendTo(fBox2b);
  fBox2d = $(JQDIV,{'id':'estFeatureUsedCont','class':'estFeatInUse estFeatureListSort'}).appendTo(fBox2a);
  
  $('#estPopCont').data('ftarg',{'label':fBox2c,'addbtn':fBox1b,'targ':[fBox1c,fBox2d]});
  tri++;
  
  //TAB 2
  tabx++;
  tri = 0;
  tabtr[tabx].tr[tri] = [];
  tabtr[tabx].tr[tri][0] = $(JQTR).appendTo(tabtr[tabx].tbody);
  tabtr[tabx].tr[tri][1] = $(JQTD,{'colspan':2,'class':'noPAD'}).appendTo(tabtr[tabx].tr[tri][0]);
  tabtr[tabx].tr[tri][2] = $('<textarea></textarea>',{'name':'space_description','cols':50,'rows':8,'placeholder':xt.description,'class':'tbox form-control e-autoheight WD100'}).html(levDta.space_description).appendTo(tabtr[tabx].tr[tri][1]);
  tri++;
  
  
  
  //TAB 3
  tabx++;
  tri = 0;
  $(popFrm.tabs.tab[tabx].tDiv);//.addClass('estDark65')
  tabtr[tabx].tr[tri] = [];
  tabtr[tabx].tr[tri][0] = $(JQTR).appendTo(tabtr[tabx].tbody);
  tabtr[tabx].tr[tri][1] = $(JQTD,{'colspan':2,'class':'noPAD'}).appendTo(tabtr[tabx].tr[tri][0]);
  
  $(JQDIV,{'id':'estMediaNoGo','class':'s-message alert alert-block warning alert-warning'}).appendTo(tabtr[tabx].tr[tri][1]);
  
  var mBox0 = $(JQDIV,{'id':'estMediaMgrCont'}).on({
    click : function(){
      var newMediaDta = estNewMediaDta(2);
      estFileUplFld(newMediaDta);
      }
    }).appendTo(tabtr[tabx].tr[tri][1]);
  
  estBuildSpaceOptns();
  estBuildCategoryList(2);
  estBuildMediaList(2);
  estPosPopover();
  }


function estBuildFeatBtn(lev,btnDta){
  bDiv = {'cont':'','btn':[]};
  bDiv.cont = $(JQDIV,{'class':'estSortMe'});//.data('btnDta',btnDta);
  if(Number(btnDta.grp[0]) > 0){$(bDiv.cont).addClass('estFeatHasOpts');}
  if(btnDta.grp[1] !== btnDta.grp[2]){
    var txt = $('body').data('defs').txt;
    $(bDiv.cont).attr('title',txt.featurenobelong).addClass('estFeatRem');
    }
  
  bDiv.btn[0] = $(JQBTN,{'class':'btn btn-default btn-sm'}).on({
  click : function(e){e.preventDefault(); estAddEditFeature(lev,this);}
  }).appendTo(bDiv.cont);
  $(JQSPAN,{'class':'fa fa-pencil-square-o'}).appendTo(bDiv.btn[0]);
  
  bDiv.btn[1] = $(JQBTN,{'class':'btn btn-default btn-sm'}).html(btnDta.src.dta.feature_name).data('btndta',btnDta).on({
  click : function(e){
    e.preventDefault();
    estAddRemFeat(this);
    }
  }).appendTo(bDiv.cont);
  
  bDiv.btn[2] = $(JQBTN,{'class':'btn btn-default btn-sm'}).on({
    click : function(e){e.preventDefault(); e.stopPropagation(); estFeatOptMenu(this);}
    }).html(btnDta.act.dta.featurelist_dta !== '' ? btnDta.act.dta.featurelist_dta : '- - -').appendTo(bDiv.cont);
  
  
  bDiv.btn[3] = $(JQBTN,{'class':'btn btn-default btn-sm'}).on({
  click : function(e){e.preventDefault(); estAddEditFeature(lev,this);}
  }).appendTo(bDiv.cont);
  $(JQSPAN,{'class':'fa fa-pencil-square-o'}).appendTo(bDiv.btn[3]);
  
  $(bDiv.cont).data('btnDta',btnDta);
  return bDiv;
  }




function estFeatOptMenu(eBtn){
  if(document.getElementById('estFeatOptMenu')){
    estFeatureMenuRemove(eBtn);
    return;
    }
  
  var mainBtn = $(eBtn).parent();
  var btnDta = $(mainBtn).data('btnDta');
  
  var eDta = btnDta.src.dta;
  var mtchDta = btnDta.act.dta;
  
  pTop = $(eBtn).position().top + $(eBtn).outerHeight();
  pLft = $(eBtn).position().left;
  pWid = $(eBtn).outerWidth();
  
  sOpt = btnDta.src.opt;
  optlist = btnDta.src.dta[sOpt.fld];
  if(optlist.indexOf(',') > -1){optlist = optlist.split(',');}
  else{optlist = [optlist];}
  
  aOpt = btnDta.act.opt;
  actList = btnDta.act.dta[aOpt.fld];
  if(actList.indexOf(',') > -1){actList = actList.split(',');}
  else{actList = [actList];}

  var wDta = btnDta.act;
  var menuCont = $(JQDIV,{'id':'estFeatOptMenu','class':'estOutsideRemove'}).css({'top':pTop+'px','left':pLft+'px','width':pWid+'px'}).data('ch',0).on({
    click : function(e){e.stopPropagation();}
    }).appendTo(mainBtn);
  
  var contH = $(menuCont).outerHeight();
  var contP = $(menuCont).position();
  
  $(optlist).each(function(oi,opt){
    var mDiv = $(JQDIV).html(opt).on({
      click :function(e){
        if($(this).hasClass('actv')){$(this).removeClass('actv');}
        else{$(this).addClass('actv');}
        $(menuCont).data('ch',1);
        btnDta.act.dta[aOpt.fld] = '';
        $(eBtn).empty().promise().done(function(){
          $(menuCont).children('div.actv').each(function(xi,xele){
            btnDta.act.dta[aOpt.fld] += (xi > 0 ? ',' : '')+$(xele).html();
            $(eBtn).append((xi > 0 ? ', ' : '')+$(xele).html());
            }).promise().done(function(){
              if($(eBtn).is(':empty')){$(eBtn).html('- - -');}
              $(mainBtn).data('btnDta',btnDta);
              });
          });
        }
      }).appendTo(menuCont);
    if(actList.indexOf(opt) > -1){$(mDiv).addClass('actv');}
    }).promise().done(function(){
      $(menuCont).children('div').sort(function (a, b) {
        var cA = $(a).html(); var cB = $(b).html();
        return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
        }).appendTo(menuCont);
      });
  }


function estSaveFeature(delX,mainBtn){
  var defs = $('body').data('defs');
  var propId = Number($('body').data('propid'));
  var popDta = $('#estPopCont').data();
  var btnDta = $(mainBtn).data('btnDta');
  var defDta = defs.tbls[btnDta.act.tbl].dta;
  var defFlds = defs.tbls[btnDta.act.tbl].flds;
  
  var lev = Number(btnDta.lev);
  
  if(defDta.length > 0){
    var exDta = defDta.find(x => Number(x.featurelist_key) === Number(btnDta.src.dta.feature_idx));
    if(typeof exDta !== 'undefined'){var lev = Number(exDta.featurelist_lev);}
    }
  
  $(mainBtn).addClass('estFlipIt');
  
  inpts = [];
  $(defFlds).each(function(fi,fele){
    inpts[fi] = {};
    inpts[fi][fele] = btnDta.act.dta[fele];
    }).promise().done(function(){
      var sDta = {'fetch':3,'propid':propId,'rt':'js','tdta':[{'tbl':'estate_featurelist','key':'featurelist_idx','del':delX,'fdta':inpts}]};
      //console.log(sDta);
      
      $.ajax({
        url: vreFeud+'?5||0',
        type:'post',
        data:sDta,
        dataType:'json',
        cache:false,
        processData:true,
        success: function(ret, textStatus, jqXHR){
          ret = ret[0];
          $(mainBtn).removeClass('estFlipIt');
          if(typeof ret.error !== 'undefined'){
            estAlertLog(ret.error);
            $('#estPopContRes'+0).html(ret.error).fadeIn(200,function(){estPopHeight(1)});
            }
          else{
            if(typeof ret.alldta !== 'undefined'){
              estProcDefDta(ret.alldta.tbls);
              estBuildCategoryList(lev);
              }
            }
          },
        error: function(jqXHR, textStatus, errorThrown){
          console.log('ERRORS: '+textStatus+' '+errorThrown);
          estAlertLog(jqXHR.responseText);
          estRemovePopover();
          }
        });
      });
  }


function estAddRemFeat(eBtn){
  var mainBtn = $(eBtn).parent();
  var btnDta = $(mainBtn).data('btnDta');
  
  if($(mainBtn).hasClass('estFeatRem')){
    estSaveFeature(-1,mainBtn);
    $(mainBtn).fadeOut(200, function(){$(mainBtn).remove()});
    }
  else{
    var cloneDiv = $(mainBtn).clone(true);
    if($(mainBtn).parent().hasClass('estFeatInUse')){
      var destTarg = btnDta.targ[0]; // remove feature
      var delX = -1;
      }
    else{
      var destTarg = btnDta.targ[1]; // add feature
      var delX = 0;
      }
    
    $(cloneDiv).addClass('estCloned').css({'top':$(mainBtn).offset().top+'px','left':$(mainBtn).offset().left+'px','width':$(mainBtn).outerWidth()+'px'}).appendTo($(mainBtn).parent()).fadeIn(200,function(){
      $(mainBtn).fadeOut(200);
      $(cloneDiv).animate({'left':$(destTarg).offset().left+'px','width':$(destTarg).width()+'px'},'swing',function(){
        $(cloneDiv).fadeOut(200,function(){$(cloneDiv).remove()});
        $(mainBtn).prependTo(destTarg).fadeIn(200);
        estSaveFeature(delX,mainBtn);
        estFeatureSort();
        })
      });
    }
  }

function estAddEditFeature(lev=1,eBtn=null){
  var defs = $('body').data('defs');
  var propId = Number($('body').data('propid'));
  var catInf = estGetFeatCatInfo(lev);
  var fTarg = $('#estPopCont').data('ftarg');
  
  if(eBtn == null){
    var fLabl = defs.txt.new1+' '+catInf.txt+' '+defs.txt.feature;
    var eledta = estDefDta('estate_features');
    eledta.feature_cat = Number(catInf.evlu);
    }
  else{
    var eledta = $(eBtn).parent().data('btnDta').src.dta;
    var fLabl = catInf.txt+': '+eledta.feature_name;
    }
  var tDta = {'tbl':'estate_features','fld':'feature_name','frmlabl':fLabl,'eidx':eledta.feature_idx,'fnct':{'name':'estBuildCategoryList','args':lev}};
  if(lev > 0){
    $.extend('tDta',{'req':['space_catid','feature_cat',defs.txt.category+' '+defs.txt.required,1]});
    }
  estPopoverAlt(1,tDta);
  }


function estBuildCategoryList(lev=1){
  var defs = $('body').data('defs');
  var propId = Number($('body').data('propid'));
  var popDta = $('#estPopCont').data();
  if(typeof popDta === 'undefined'){return;}
  
  var optMenuSlide = popDta.popit.frm[0].slide;
  var levDta = $(popDta.popit.frm[0].form).data('levdta');
  var sectKey = defs.tbls.estate_sects[lev][1];
  
  if(levDta[sectKey] == 0){
    $('#estFeatureMgrCont').hide();
    $('#estFeatureNoGo').html(defs.txt.savefirst+' '+defs.txt.features).show();
    }
  else{
    var catInf = estGetFeatCatInfo(lev);
    console.log(catInf);
    
    $(popDta.popit.frm[0].form).data('levdta').space_catid = Number(catInf.evlu);
    
    if(catInf.ele !== null && catInf.evlu == 0){
      $('#estFeatureMgrCont').hide();
      $('#estFeatureNoGo').html(defs.txt.spacecatzero).show();
      }
    else{
      $('#estFeatureMgrCont').show();
      $('#estFeatureNoGo').hide();
      $(popDta.ftarg.label).html(catInf.txt+' '+defs.txt.features);
      
      grpGrepX = $.grep(defs.tbls.estate_featurelist.dta, function (element, index) {return element['featurelist_lev'] == lev;});
      grpGrep1 = $.grep(grpGrepX, function (element, index) {return element['featurelist_levidx'] == levDta[sectKey];});
      grpGrep2 = $.grep(defs.tbls.estate_features.dta, function (element, index) {return element['feature_cat'] == catInf.evlu;});
      
      $(popDta.ftarg.targ[1]).empty().promise().done(function(){
        $(popDta.ftarg.targ[0]).empty().promise().done(function(){
          var bDivs = [];
          $(grpGrep2).each(function(i,ele){
            var actDta = grpGrep1.find(x => x.featurelist_key === ele.feature_idx);
            if(typeof actDta !== 'undefined'){
              grpGrep1.splice(grpGrep1.indexOf(actDta), 1);
              }
            else{
              actDta = estDefDta('estate_featurelist');
              actDta.featurelist_propidx = propId;
              actDta.featurelist_lev = lev;
              actDta.featurelist_levidx = levDta[sectKey];
              actDta.featurelist_key = ele.feature_idx;
              }
            var btnDta = {'act':{'tbl':'estate_featurelist','key':'featurelist_key','opt':{'type':1,'fld':'featurelist_dta'},'dta':actDta},'grp':[Number(ele.feature_ele),Number(ele.feature_cat),Number(catInf.evlu)],'lev':lev,'src':{'tbl':'estate_features','key':'feature_idx','dta':ele,'opt':{'type':1,'fld':'feature_opts'}},'targ':popDta.ftarg.targ};
            
            bDivs[i] = estBuildFeatBtn(lev,btnDta);
            if(Number(actDta.featurelist_idx) > 0){$(bDivs[i].cont).appendTo(popDta.ftarg.targ[1]);}
            else{$(bDivs[i].cont).appendTo(popDta.ftarg.targ[0]);}
            }).promise().done(function(){
              $(grpGrep1).each(function(i,ele){
                var remDta = defs.tbls.estate_features.dta.find(x => x.feature_idx === ele.featurelist_key);
                console.log(remDta,ele);
                if(typeof remDta !== 'undefined'){
                  var btnDta = {'act':{'tbl':'estate_featurelist','key':'featurelist_key','opt':{'type':1,'fld':'featurelist_dta'},'dta':ele},'grp':[Number(remDta.feature_ele),Number(remDta.feature_cat),Number(catInf.evlu)],'lev':lev,'src':{'tbl':'estate_features','key':'feature_idx','dta':remDta,'opt':{'type':1,'fld':'feature_opts'}},'targ':popDta.ftarg.targ};
                  bDivs[i] = estBuildFeatBtn(lev,btnDta);
                  if(Number(ele.featurelist_idx) > 0){$(bDivs[i].cont).appendTo(popDta.ftarg.targ[1]);}
                  else{$(bDivs[i].cont).appendTo(popDta.ftarg.targ[0]);}
                  }
                  
                }).promise().done(function(){
                  estFeatureSort();
                  });
              });
          });
        });
      }
    }
  }

function estSaveSpace(spaceId){
  var defs = $('body').data('defs');
  $('input[name="space_idx"]').val(spaceId);
  console.log(defs);
  
  var levDta = defs.tbls.estate_spaces.dta.find(x => x.space_idx === spaceId);
  console.log(levDta);
  
  var popit = $('#estPopCont').data('popit');
  $(popit.frm[0].form).data('levdta',levDta);
  estTestEles(popit.frm[0].form, popit.frm[0].savebtns);
  
  var grpLstDta = defs.tbls.estate_grouplist.dta.find(x => Number(x.grouplist_groupidx) === Number(levDta.space_grpid));
  
  if(typeof grpLstDta == 'undefined'){
    console.log('get grpLstDta');
    var grpOrd = 1;
    var fdta = [{'grouplist_idx':0},{'grouplist_propidx':levDta.space_propidx},{'grouplist_groupidx':levDta.space_grpid},{'grouplist_ord':grpOrd}];

    $.ajax({
      url: vreFeud+'?5||0',
      type:'post',
      data:{'fetch':3,'propid':levDta.space_propidx,'rt':'js','tdta':[{'tbl':'estate_grouplist','key':'grouplist_idx','del':0,'fdta':fdta}]},
      dataType:'json',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        ret = ret[0];
        console.log(ret);
        if(typeof ret.error !== 'undefined'){
          estAlertLog(ret.error);
          $('#estPopContRes'+0).html(ret.error).fadeIn(200,function(){estPopHeight(1)});
          }
        else{
          if(typeof ret.alldta !== 'undefined'){
            estProcDefDta(ret.alldta.tbls);
            estBuildCategoryList(2);
            estBuildMediaList(2);
            estBuildSpaceList();
            estPopHeight();
            }
          }
        },
      error: function(jqXHR, textStatus, errorThrown){
        console.log('ERRORS: '+textStatus+' '+errorThrown);
        estAlertLog(jqXHR.responseText);
        estRemovePopover();
        }
      });
    }
  else{
    estBuildCategoryList(2);
    estBuildMediaList(2);
    estBuildSpaceList();
    estPopHeight();
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
  
  
  tdta[i].tbl = $(JQTABLE,{'id':'estate-spaces-tabl-'+tbx.grouplist_groupidx,'class':'table-striped estateSubTable estDragTable'}).data(tbx);
  $(JQCOLGRP,{'class':'left'}).appendTo(tdta[i].tbl);//.css({'width':'10%'})
  $(JQCOLGRP,{'class':'left'}).appendTo(tdta[i].tbl);//.css({'width':'20%'})
  $(JQCOLGRP,{'class':'left'}).appendTo(tdta[i].tbl);//.css({'width':'*'})
  $(JQCOLGRP,{'class':'left'}).appendTo(tdta[i].tbl);//.css({'width':'*'})
  $(JQCOLGRP,{'class':'center last'}).appendTo(tdta[i].tbl);//.css({'min-width':'192px'})
  
  tdta[i].th = $(JQTHEAD).appendTo(tdta[i].tbl);
  tdta[i].tb = $(JQTBODY,{'id':'estSortTBody-'+tbx.grouplist_groupidx,'class':'ui-sortable estSortTBody estSortTarg'}).data(tbx).appendTo(tdta[i].tbl);
  
  tdta[i].tr[tri] = [];
  tdta[i].tr[tri][0] = $(JQTR,{'class':'estDragableTR'}).appendTo(tdta[i].th);
  tdta[i].tr[tri][1] = $(JQTH,{'class':'left'}).appendTo(tdta[i].tr[tri][0]);
  tdta[i].tr[tri][2] = $(JQDIV,{'class':'btn-group'}).appendTo(tdta[i].tr[tri][1]);
  
  tdta[i].tr[tri][3] = $(JQBTN,{'class':'e-sort sort-trigger btn btn-default ui-sortable-handle','title':xt.dragto+' '+xt.reorder+' '+xt.section}).html('<i class="fa fa-arrows-v"></i>').on({click : function(e){e.preventDefault()}}).appendTo(tdta[i].tr[tri][2]);
  
  if(tbx.grouplist_idx == 0){
    $(tdta[i].tr[tri][3]).prop('disabled',true).css({'cursor':'not-allowed'}).attr('title',xt.nauntilspaces);
    $(tdta[i].tb).removeClass('estSortTBody');
    $(tdta[i].tbl).removeClass('estDragTable');
    }
  
  tdta[i].tr[tri][4] = $(JQBTN,{'type':'button','class':'btn btn-default ','title':xt.add1+' '+xt.new1+' '+xt.space+': '+groupName}).html(JQADI).on({
    click : function(e){
      e.preventDefault();
      estBuildSpace(null,tbx)
      }
    }).appendTo(tdta[i].tr[tri][2]);
  
  $(JQTH,{'class':'left','colspan':2}).html(groupName).appendTo(tdta[i].tr[tri][0]);
  $(JQTH,{'class':'left'}).html(xt.dimu0).appendTo(tdta[i].tr[tri][0]);
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
    tdta[i].tr[tri][4] = $(JQTD,{'class':'left'}).html(rmdta.space_dimxy+' '+xt.dimu0).appendTo(tdta[i].tr[tri][0]);
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
                  if(Number(mediaDta.media_lev) > 0){var urlpth = defs.dir.prop.thm+mediaDta.media_thm+noCache;}
                  else{var urlpth = defs.dir.subdiv.thm+mediaDta.media_thm+noCache;}
                  ulbtn[k] = $(JQDIV,{'class':'upldPvwBtn pvw-'+mediaDta.media_idx}).data(mediaDta).css({'background-image':'url('+urlpth+')'}).appendTo('#estMediaMgrCont');
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
      if(Number(firstThm.media_lev) > 0){var urlpth = defs.dir.prop.thm;}
      else{var urlpth = defs.dir.subdiv.thm;}
        
      if(typeof firstThm !== 'undefined'){$(tdta[i].tr[tri][7]).css({'background-image':'url('+urlpth+firstThm.media_thm+noCache+')'});}
      else{$(tdta[i].tr[tri][7]).css({'background-image':'url('+urlpth+mediaGrep1[0].media_thm+noCache+')'});}
      }
    
    $(JQBTN,{'class':'e-sort sort-trigger btn btn-default ui-sortable-handle','title':xt.dragto+' '+xt.reorder+' '+xt.spaces}).html('<i class="fa fa-arrows-v"></i></i>').on({click : function(e){e.preventDefault()}}).appendTo(tdta[i].tr[tri][6]);
    
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
  
  var levmap = defs.keys.levmap;
  //space_lev
  //space_levidx
  
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




function estFilterAgents(mode,agyDta){
  var defs = $('body').data('defs');
  var xt = defs.txt;
  if(mode == 2){
    $('#estAgentOptsR').empty().promise().done(function(){
      
      var agentId = Number($('input[name="prop_agent"]').val());
      var aGrep1 = $.grep(defs.tbls.estate_agents.dta, function (element, index) {return  element.agent_agcy == agyDta.agency_idx});
      var agyBtn = [];
      $(aGrep1).each(function(ai,aDta){
        var aGo = 0;
        if(Number(aDta.agent_idx) == agentId){aGo++;}
        if(Number(defs.user.perm) > 2){aGo++;}
        if(Number(aDta.agent_agcy) == Number(defs.user.agency_idx)){
          if(Number(defs.user.perm) == 2 || Number(defs.user.perm) >= Number(aDta.user_mgr)){aGo++;}
          }
        
        if(aGo > 0){
          agyBtn[ai] = $(JQBTN,{'class':'btn btn-default TAL'}).html(aDta.agent_name);
          if(Number(aDta.agent_idx) == agentId){$(agyBtn[ai]).addClass('agsel');}
          $(agyBtn[ai]).on({
            click : function(e){
              e.preventDefault();
              e.stopPropagation();
              var abDta = $(this).data();
              $('input[name="prop_agency"]').val(abDta.agent_agcy);
              $('input[name="prop_agent"]').val(abDta.agent_idx);
              estBuildAgencyList();
              estSHAgentSelect(-1);
              }
            });
          $(agyBtn[ai]).data(aDta).appendTo('#estAgentOptsR');
          }
        
          
        }).promise().done(function(){
          if($('#estAgentOptsR').is(':empty') && Number(defs.user.perm) > 1){
            var aDta = estDefDta('estate_agents');
            agyBtn[0] = $(JQDIV,{'class':'estBtnCont'}).appendTo('#estAgentOptsR');
            agyBtn[1] = $(JQBTN,{'type':'button','class':'btn btn-default estAgncySelBtn','title':xt.new1+' '+xt.agent}).data(aDta).on({
              click : function(e){
                e.preventDefault();
                e.stopPropagation();
                estAgentForm(this);
                estSHAgentSelect(-1);
                }
              });
            $(agyBtn[1]).html(JQEDI+xt.new1+' '+xt.agent).appendTo(agyBtn[0]);
            }
          
          });
      });
    }
  
  else if(mode == 1){
    
    var agncyId = Number($('input[name="prop_agency"]').val());
    var agncyChk = false;
      
      
    $('#estAgentOptsL').empty().promise().done(function(){
      $('#estAgentOptsR').empty().promise().done(function(){
        var aGrep1 = defs.tbls.estate_agencies.dta;
        var agyBtn = [];
        
        if(aGrep1.length === 0 && defs.user.perm > 1){
          var aDta = estDefDta('estate_agencies');
          agyBtn[0] = $(JQDIV,{'class':'estBtnCont'}).appendTo('#estAgentOptsL');
          agyBtn[1] = $(JQBTN,{'type':'button','class':'btn btn-default estAgncySelBtn WD100','title':xt.new1+' '+xt.location}).data(aDta).on({
            click : function(e){
              e.preventDefault();
              e.stopPropagation();
              estAgncyForm($(this).data());
              }
            }).html(JQEDI+xt.new1+' '+xt.location).appendTo(agyBtn[0]);
          return;
          }
        
        $(aGrep1).each(function(ai,aDta){
          agyBtn[ai] = [];
          agyBtn[ai][0] = $(JQDIV,{'class':'estBtnCont'}).appendTo('#estAgentOptsL');
          
          agyBtn[ai][1] = $(JQBTN,{'type':'button','class':'btn btn-default estAgncySelBtn estDblChevron','title':xt.select1+' '+xt.location}).data(aDta).on({
            click : function(e){
              e.preventDefault();
              e.stopPropagation();
              $('#estAgentOptsL').find('button.estAgncySelBtn').removeClass('agsel').promise().done(function(){
                $(e.target).addClass('agsel');
                estFilterAgents(2,aDta);
                });
              }
            }).html(aDta.agency_name).appendTo(agyBtn[ai][0]);
          
          
          if(defs.user.perm < 2){$(agyBtn[ai][1]).addClass('WD100');}
          else{
            agyBtn[ai][2] = $(JQDIV,{'class':'estSonar'}).appendTo(agyBtn[ai][0]);
            agyBtn[ai][3] = $(JQBTN,{'type':'button','class':'btn btn-default estNoLeftBord selEditBtn1'}).data(aDta).on({
              click :function(e){
                e.preventDefault();
                e.stopPropagation();
                estAgncyForm($(this).data());
                }
              });
            if(defs.user.perm > 2){
              $(agyBtn[ai][3]).attr('title',xt.add1+'/'+xt.edit+' '+xt.location).html(JQEDI).appendTo(agyBtn[ai][2]);
              }
            else if(Number(aDta.agency_idx) == Number(defs.user.agent_agcy)){
              $(agyBtn[ai][3]).attr('title',xt.edit+' '+xt.location).html(JQEDI).appendTo(agyBtn[ai][2]);
              }
            else{
              $(agyBtn[ai][1]).addClass('WD100');
              }
            }
            
          
          if(Number(aDta.agency_idx) == agncyId){
            $(agyBtn[ai][1]).addClass('agsel');
            agncyChk = true;
            }
          
          }).promise().done(function(){
            var selAgncy = $('#estAgentOptsL').find('button.agsel');
            if(selAgncy.length > 0){
              var aDta = $(selAgncy[0]).data();
              estFilterAgents(2,aDta);
              }
            else if(agncyChk == true){estFilterAgents(2,agncyId);}
            });
        });
      });
      
    }  
  
  }
  
function estBuildAgencyList(){
  var defs = $('body').data('defs');
  var agencyId = Number($('input[name="prop_agency"]').val());
  var agentId = Number($('input[name="prop_agent"]').val());
  //console.log(defs.tbls.estate_agents.dta);
  
  if(agentId > 0){
    if(Number(defs.user.perm) > 1 || Number(defs.user.agent_idx) == agentId){
      var ag2dta = defs.tbls.estate_agents.dta.find(x => Number(x.agent_idx) === agentId);
      if(typeof ag2dta !== 'undefined'){
        if(agencyId !== Number(ag2dta.agent_agcy)){
          agencyId = Number(ag2dta.agent_agcy);
          $('input[name="prop_agency"]').val(agencyId);
          $('#estAgencySelBtn').data('agcy',agencyId);
          defs.tbls.estate_properties.dta[0].prop_agency = agencyId;
          $('body').data('defs',defs);
          //console.log(defs.tbls.estate_properties.dta[0]);
          estAlertLog(defs.txt.agent+' '+defs.txt.infochanged+'. '+defs.txt.please+' '+defs.txt.updatethis+' '+defs.txt.listing);
          }
          
        $('#estAgentSelBtnTxt').html(ag2dta.agent_name);
        estSetAgtMiniPic(ag2dta);
        }
      }
    
    if(agencyId > 0){
      var agncyDta = defs.tbls.estate_agencies.dta.find(x => Number(x.agency_idx) === agencyId);
      //console.log(agncyDta);
      if(typeof agncyDta !== 'undefined'){
        $('#estAgencySelBtn').html(agncyDta.agency_name);
        estFilterAgents(1,agncyDta);
        }
      }
    }
  else{
    console.log('new Agent Listing or is Member Listing');
    }
  }


function estSHAgentSelect(mode,btn){
  if(mode == -1){
    $('#estAgentOptsCont').animate({'height':'0px'},function(){
      $('#estAgentOptsCont').hide();
      $('#estClearkCover').remove();
      $('#estAgentContDiv').removeClass('open').css({'z-index':''}).data('sh',0);
      $('#estAgentSelBtn').removeClass('estNoLBord').addClass('estNoLRBord');
      });
    }
  else{
    var btnSH = Number($('#estAgentContDiv').data('sh'));
    if(btnSH == -1){return;}
    else{
      $('#estAgentContDiv').data('sh',-1);
      if(btnSH == 1){estSHAgentSelect(-1)}
      else{
        $(JQDIV,{'id':'estClearkCover'}).on({click : function(){estSHAgentSelect(-1)}}).appendTo('body');
        $('#estAgentSelBtn').removeClass('estNoLRBord').addClass('estNoLBord');
        $('#estAgentOptsCont').show().animate({'height':Number($('#estAgentContDiv').parent().outerHeight() * 4)+'px'},function(){
          $('#estAgentContDiv').css({'z-index':'100005'}).data('sh',1).addClass('open');
          });
        }
      }
    }
  }





function estAgncyForm(aDta,popIt=null,slide=0){
  var defs = $('body').data('defs');
  var propId = Number($('body').data('propid'));
  var keyTbl = defs.keys.contabs[5];
  var destTbl = defs.tbls[keyTbl[0]];
  
  if(slide == 0){
    estSHAgentSelect(-1);
    var popIt = estBuildPopover([{'tabs':[defs.txt.agency,defs.txt.contacts,defs.txt.location],'fnct':{'name':'estAgencyChange','args':5}},{'sect':keyTbl[0]}]);
    }
  
  var popFrm = popIt.frm[slide];
  
  if(typeof aDta == 'undefined' || aDta == null){
    aDta = destTbl.dta.find(x => Number(x.agent_agcy) === Number($('input[name="prop_agency"]').val()));
    if(typeof aDta == 'undefined'){aDta = estDefDta(keyTbl[0]);}
    }
  
  if(Number(aDta[keyTbl[1]]) > 0){frmLabel = aDta[keyTbl[2]];}
  else{frmLabel = defs.txt.new1+' '+defs.txt.agency;}
  
  //$(popFrm.h3).empty().promise().done(function(){});
  
  if(slide == 0){
    var titSpan = $(JQSPAN,{'id':'estAgencyH3Span'+Number(slide),'class':'FL','title':defs.txt.cancelremove}).data('was',frmLabel).html(frmLabel).on({
      click : function(){estRemovePopover()}
      }).appendTo(popFrm.h3);
    
    var saveBtn1 = $(JQBTN,{'id':'estSaveAgency','class':'btn btn-primary btn-sm FR'}).html(defs.txt.save).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        var aDta = $('input[name="'+keyTbl[1]+'"]').closest('form').data('levdta');
        console.log(aDta);
        if(Number(aDta[keyTbl[1]]) == 0){estPopGo(4,popIt,0);}
        else{estPopGo(1,popIt,0);}
        }
      }).appendTo(popFrm.h3);
    
    popFrm.savebtns.push(saveBtn1);
    
    if(Number(defs.user.perm) > 2){
      var newDta = estDefDta(keyTbl[0]);
      var saveBtn2 = $(JQBTN,{'id':'estNewAgency','class':'btn btn-primary btn-sm FR'}).data(newDta).html(defs.txt.new1+' '+defs.txt.agency).on({
        click : function(e){
          e.preventDefault();
          e.stopPropagation();
          estAgncyForm($(this).data());
          return;
          }
        }).appendTo(popFrm.h3);
      popFrm.savebtns.push(saveBtn2);
      if(Number(aDta[keyTbl[1]]) == 0){$(saveBtn2).hide();}
      }
    }
  
  $(popFrm.form).data({'levdta':aDta,'destTbl':{'dta':destTbl.dta,'flds':destTbl.flds,'idx':keyTbl[1],'table':keyTbl[0]},'maintbl':null,'form':{'elem':null,'attr':null,'match':{},'fnct':{'name':'estPropAgencyChange'+(Number(slide)+1),'args':5}}});
  
  console.log(popIt);
  
  var fldTRs = [];
  var tri = 0;
  var tabtr = popFrm.tabs.tab;
  
  estBuildContTbl(tabtr[1],5,aDta);
  
  $(tabtr[2].li[0]).on({click : function(e){estSetMap('est_agency_Map');}});
  
  var sectDta = estGetSectDta(keyTbl[1],aDta[keyTbl[1]],destTbl);
  var reqMatch = null;
  var frmTRs = estFormEles(destTbl.form,sectDta,reqMatch);
  
  
  $('<colgroup></colgroup>').css({'width':'25%'}).prependTo(popFrm.tabs.tab[0].tabl[0]);
  var colGrp = $(popFrm.tabs.tab[0].tabl[0]).find('colgroup');
  $(colGrp[1]).css({'width':'50%'});
  $(colGrp[2]).css({'width':'25%'});
  
  $(frmTRs).each(function(ei,trEle){
    estFormTr(trEle, popFrm.tabs.tab[trEle.tab].tDiv, popFrm.tabs.tab[trEle.tab].tbody);
    }).promise().done(function(){
      estTestEles(popIt.frm[0].form, popIt.frm[0].savebtns);
      estPosPopover('60vw');
      
      var avBody = $(popFrm.tabs.tab[0].tDiv).find('tbody');
      var rSpan = $(avBody).find('tr').length -1;
      
      $(avBody).find('tr').each(function(ti,trw){
        if(ti == 0){
          var avatTD = $(JQTD,{'class':'noPADTB','rowspan':rSpan}).appendTo(trw);
          
          var agencyLogo = $(JQDIV,{'id':'agencyLogo','class':'estAgentAvatar'}).on({
            mouseenter : function(e){estMediaEditBtns(5,e.target)},
            mouseleave : function(e){estMediaEditBtns(-1)}
            }).appendTo(avatTD);
          
          var agencyLogoImg = $('<img />',{'id':'agencyLogoImg','src':'','alt':''}).css({'display':'none'}).on({
            load : function(){
              if($(this).prop('src') !== ''){
                var nW = $(this).prop('naturalWidth');
                var nH = $(this).prop('naturalHeight');
                $(agencyLogo).animate({'height':Math.floor($(agencyLogo).outerWidth() * parseFloat(nH / nW).toFixed(2))+'px'}).promise().done(function(){
                  estPopHeight();
                  });
                }
              }
            }).appendTo(agencyLogo);
          }
        else if(ti >= rSpan){
          $(trw).find('td:last-child').prop('colspan',3);
          }
        }).promise().done(function(){
          
          $('input[name="agency_name"]').on({
            change : function(){
              if(this.value.length > 0){$(titSpan).html(this.value);}
              else{$(titSpan).html($(titSpan).data('was'));}
              },
            keyup : function(){
              if(this.value.length > 0){$(titSpan).html(this.value);}
              else{$(titSpan).html($(titSpan).data('was'));}
              }
            });
          
          $('input[name="agency_addr_lookup"]').data('man',0).on({
            change : function(){$(this).data('man',1);},
            keyup : function(){$(this).data('man',1);}
            })
          
          $('input[name="agency_addr"]').on({
            change : function(){
              if($('input[name="agency_addr_lookup"]').data('man') == 0){
                var ctadr = htmlDecode(0,$('input[name="agency_addr"]').val()).replace(/\s*\n\s*/ig, ', ');
                $('input[name="agency_addr_lookup"]').val(ctadr);
                }
              },
            keyup : function(){
              if($('input[name="agency_addr_lookup"]').data('man') == 0){
                var ctadr = htmlDecode(0,$('input[name="agency_addr"]').val()).replace(/\s*\n\s*/ig, ', ');
                $('input[name="agency_addr_lookup"]').val(ctadr);
                }
              }
            });
          
          if(slide > 0){
            $(popFrm.savebtns[1]).html(defs.txt.new1+' '+defs.txt.agency).on({
              click : function(e){
                aDta = estDefDta(keyTbl[0]);
                $(popFrm.form).data({'levdta':aDta});
                estBuildContTbl(tabtr[1],5,aDta);
                estSetAgentImg(5);
                estBuildMap('agency');
                }
              });
            }
          
          var mapCg = $(tabtr[2].tbody[0]).parent().find('colgroup');
          $(mapCg).eq(0).css({'width':'25%'});
          $(mapCg).eq(1).css({'width':'75%'});
          
          var mtr = $(JQTR).appendTo(tabtr[2].tbody[0]);
          var mtd1 = $(JQTD).appendTo(mtr);
          var mtd2 = $(JQTD).appendTo(mtr);
          
          $(JQDIV,{'id':'est_agency_SrchRes','class':'estMapBtnCont'}).css({'max-width':'192px'}).appendTo(mtd1);
          var lucont = $(JQDIV,{'class':'estInptCont form-group '}).appendTo(mtd2);
          var estSubMapCont = $(JQDIV,{'id':'est_agency_MapCont'}).appendTo(mtd2);
          var estSubMap = $(JQDIV,{'id':'est_agency_Map','class':'estMap'}).appendTo(estSubMapCont);
          
          addr_lookup = $(JQNPT,{'id':'agency_addr_lookup','class':'tbox form-control input-xlarge estMapLookupAddr'}).appendTo(lucont);
          var SrchBtn = $('#est_agency_SrchBtn');
          if(SrchBtn.length == 1){$(SrchBtn).appendTo(lucont);}
          else{SrchBtn = $(JQBTN,{'id':'est_agency_SrchBtn','class':'btn btn-default estMapSearchBtn'}).appendTo(lucont);}
          
          $(SrchBtn).css({'width':'15%'}).html('<i class="fa fa-search"></i>');
          $(addr_lookup).css({'width':'85%'});
          
          estSetAgentImg(5);            
          estBuildMap('agency');
          });
      });
  }
  
  
  
  
  
  
function xxestEditAgencies(popIt,tdta){
  console.log(popIt);
  console.log(tdta);
  $(popIt.frm[1].slide).show().animate({'left':'0px'});
    $(popIt.frm[0].slide).animate({'left':'-100%'}).promise().done(function(){
      $(popIt.frm[0].slide).hide();
      estPopHeight(1);
      });
  }
  
  
function estloadAgentForm(agtDta){
  var defs = $('body').data('defs');
  $('input[name="agent_idx"]').closest('form').data('levdta',agtDta);
  var xDta = defs.tbls.estate_agents.dta.find(x => Number(x.agent_idx) === Number(agtDta.agent_idx));
  if(typeof xDta == 'undefined'){
    xDta = agtDta; 
    defs.tbls.estate_agents.dta.push(agtDta);
    }
  var xIdx = defs.tbls.estate_agents.dta.indexOf(xDta);
  if(xIdx > -1){defs.tbls.estate_agents.dta[xIdx] = agtDta;}
  $('input[name="agent_name"]').val(agtDta.agent_name);
  $('input[name="agent_image"]').val(agtDta.agent_image);
  $('input[name="agent_imgsrc"]').val(agtDta.agent_imgsrc);
  $('textarea[name="agent_txt1"]').html(agtDta.agent_txt1);
  $('select[name="agent_uid"]').find('option').each(function(oi,uidOpt){
    var optDta = $(uidOpt).data();
    if(optDta.user_id > 0){
      if(optDta.user_id == agtDta.agent_uid){$(uidOpt).prop('selected','selected');}
      else{
        $(uidOpt).removeProp('selected');
        var inuDta = defs.tbls.estate_agents.dta.find(x => Number(x.agent_uid) === Number(optDta.user_id));
        if(typeof inuDta !== 'undefined'){$(uidOpt).prop('disabled',true);}
        else{$(uidOpt).prop('disabled',false).removeProp('disabled');}
        }
      }
    });
  estSetAgentImg(6); //*** not here?
  }


function estSetAgentUID(){
  var defs = $('body').data('defs');
  var agtDta = $('select[name="agent_uid"]').closest('form').data('levdta');
  var eDta = $('select[name="agent_uid"]').find('option:selected').data();
  
  
  if(Number(eDta.user_id) > 0){
    if(jsconfirm(defs.txt.agtupdta)){
      agtDta.agent_uid = Number(eDta.user_id);
      agtDta.agent_name = eDta.user_name;
      agtDta.agent_txt1 = eDta.user_signature;
      
      if(Number(agtDta.agent_idx) == 0){
        agtDta.agent_image = '';
        if(eDta.user_image.length > 0){agtDta.agent_imgsrc = 0;}
        estloadAgentForm(agtDta);
        }
      else{
        if(eDta.user_image.length > 0){
          if(agtDta.agent_imgsrc == 1){
            if(!jsconfirm(defs.txt.agtuspropic)){estloadAgentForm(agtDta);}
            else{
              agtDta.agent_imgsrc = 0;
              estloadAgentForm(agtDta);
              }
            }
          else{estloadAgentForm(agtDta);}
          }
        else{
          if(agtDta.agent_imgsrc == 0){
            if(agtDta.agent_image.length > 0){agtDta.agent_imgsrc = 1;}
            else{agtDta.agent_imgsrc = 0;}
            }
          estloadAgentForm(agtDta);
          }
        }
      }
    }
  else{
    if(jsconfirm(defs.txt.agtclrdta)){
      agtDta.agent_name = '';
      //agtDta.agent_image = '';
      agtDta.agent_imgsrc = 0;
      agtDta.agent_txt1 = '';
      estloadAgentForm(agtDta);
      }
    else{
      estloadAgentForm(agtDta);
      }
    }
  }


function estSetAgentPar(){
  var defs = $('body').data('defs');
  var ele = $('select[name="agent_agcy"]');
  var parId = Number($(ele).find('option:selected').val());
  $(ele).removeClass('oneBtn');
  $('#estAgyEdtBtn2').remove().promise().done(function(){
    if(Number(defs.user.perm) > 1){
      var edt = 0;
      if(Number(defs.user.perm) > 2){edt = 2;}
      else if(Number(defs.user.perm) == 2 && parId == Number(defs.user.agent_agcy)){edt = 1;}
      if(edt > 0){
        $(ele).addClass('oneBtn');
         $(JQBTN,{'type':'button','id':'estAgyEdtBtn2','class':'btn btn-default selEditBtn1','title':(edt == 2 ? xt.add1+'/' : '')+xt.edit}).html(JQEDI).on({
          click : function(e){
            e.preventDefault();
            var aDta = $(ele).find('option:selected').data();
            var defDta = {'agency_idx':parId};
            estPopoverAlt(1,{'tbl':'estate_agents','fld':'agent_agcy','frmlabl':aDta.agency_name,'adta':aDta,'defdta':defDta,'fnct':{'name':'estPropAgencyChange2'}});
            }
          }).appendTo('#estAgtEdtInner');
        }
      }
    });
  }

function estAgentParOpts(parId){
  var defs = $('body').data('defs');
  $('select[name="agent_agcy"]').empty().promise().done(function(){
    var aGrep2 = defs.tbls.estate_agencies.dta;
    var agyOpt = [];
    $(aGrep2).each(function(ai,aDta){
      var aGo = 0;
      if(Number(defs.user.perm) > 2){aGo++;}
      if(Number(defs.user.perm) == 2 && Number(aDta.agency_idx) == Number(defs.user.agency_idx)){aGo++;}
      if(Number(defs.user.agent_agcy) == Number(aDta.agency_idx)){aGo++;}
      if(aGo > 0){
        agyOpt[ai] = $(JQOPT,{'value':aDta.agency_idx}).data(aDta).html(aDta.agency_name).appendTo('select[name="agent_agcy"]');
        if(Number(aDta.agency_idx) == Number(parId)){$(agyOpt[ai]).prop('selected','selected');}
        }
      }).promise().done(function(){
        estSetAgentPar();
        });
    });
  }

function estLoadAgentUIDs(aDta){
  var defs = $('body').data('defs');
  var uidOpt = [];
  
  $('select[name="agent_uid"]').empty().promise().done(function(){
    $(defs.tbls.estate_user.dta).each(function(ai,optDta){
      uidOpt[ai] = $(JQOPT,{'value':optDta.user_id}).data(optDta).html(optDta.user_name+' ('+optDta.user_loginname+')');
      
      if(Number(optDta.user_id) == Number(aDta.agent_uid)){
        $(uidOpt[ai]).prop('selected','selected').appendTo('select[name="agent_uid"]');
        }
      else{
        if(Number(defs.user.perm) == 4){$(uidOpt[ai]).appendTo('select[name="agent_uid"]');}
        else{
          var inuDta = defs.tbls.estate_agents.dta.find(x => Number(x.agent_uid) === Number(optDta.user_id));
          if(typeof inuDta !== 'undefined'){}
            if(Number(defs.user.perm) == 2){
              $(uidOpt[ai]).prop('disabled',true).appendTo('select[name="agent_uid"]');
              }
          
          }
        }
      });
    });
  }

function estAgentForm(btn=null){
  var defs = $('body').data('defs');
  var propId = Number($('body').data('propid'));
  var keyTbl = defs.keys.contabs[6];
  var destTbl = defs.tbls[keyTbl[0]];
  
  var tabTxt = [defs.txt.agent,defs.txt.contacts];
  
  if(btn !== null){var aDta = $(btn).data();}
  else{var aDta = destTbl.dta.find(x => Number(x.agent_idx) === Number($('input[name="prop_agent"]').val()));}
  
  if(typeof aDta == 'undefined'){aDta = estDefDta(keyTbl[0]);}
  
  if(Number(aDta[keyTbl[1]]) > 0){frmLabel = aDta.user_role+' '+aDta[keyTbl[2]];}
  else{frmLabel = defs.txt.new1+' '+defs.txt.agent;}
  
  var popIt = estBuildPopover([{'tabs':tabTxt,'fnct':{'name':'estPropAgentChange','args':6}},{'sect':keyTbl[0]}]); //fnct acts on tab click
  var popFrm = popIt.frm[0];
  
  $('<colgroup></colgroup>').css({'width':'25%'}).prependTo(popFrm.tabs.tab[0].tabl[0]);
  var colGrp = $(popFrm.tabs.tab[0].tabl[0]).find('colgroup');
  $(colGrp[1]).css({'width':'50%'});
  $(colGrp[2]).css({'width':'25%'});
  
  var titSpan = $(JQSPAN,{'id':'estAgentH3Span0','class':'FL','title':defs.txt.cancelremove}).data('was',frmLabel).html(frmLabel).on({
    click : function(){estRemovePopover()}
    }).appendTo(popFrm.h3);
  
  var saveBtn1 = $(JQBTN,{'id':'estSaveAgent','class':'btn btn-primary btn-sm FR'}).html(defs.txt.save).on({
    click : function(e){
      e.preventDefault();
      e.stopPropagation();
      var aDta = $('input[name="'+keyTbl[1]+'"]').closest('form').data('levdta');
      console.log(aDta);
      if(Number(aDta[keyTbl[1]]) == 0){estPopGo(4,popIt,0);}
      else{estPopGo(1,popIt,0);}
      }
    }).appendTo(popFrm.h3);
  
  popFrm.savebtns.push(saveBtn1);
  
  if(Number(defs.user.perm) > 1){
    var newDta = estDefDta(keyTbl[0]);
    var saveBtn2 = $(JQBTN,{'id':'estNewAgent','class':'btn btn-primary btn-sm FR'}).data(newDta).html(defs.txt.new1+' '+defs.txt.agent).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        estAgentForm(this);
        return;
        }
      }).appendTo(popFrm.h3);
    popFrm.savebtns.push(saveBtn2);
    }
  
  
  if(Number(aDta[keyTbl[1]]) == 0){$(saveBtn2).hide();}
  $(popFrm.form).data({'levdta':aDta,'destTbl':{'dta':destTbl.dta,'flds':destTbl.flds,'idx':keyTbl[1],'table':keyTbl[0]},'maintbl':null,'form':{'elem':null,'attr':null,'match':{},'fnct':{'name':'estPropAgentChange','args':6}}});
  
  var fldTRs = [];
  var tabx = 0;
  var tri = 0;
  var tabtr = popFrm.tabs.tab;
  
  estBuildContTbl(tabtr[1],6,aDta);
  
  var sectDta = estGetSectDta(keyTbl[1],aDta[keyTbl[1]],destTbl);
  var reqMatch = null;
  var frmTRs = estFormEles(destTbl.form,sectDta,reqMatch);
  
  $(frmTRs).each(function(ei,trEle){
    estFormTr(trEle, popFrm.tabs.tab[trEle.tab].tDiv, popFrm.tabs.tab[trEle.tab].tbody);
    }).promise().done(function(){
      estTestEles(popIt.frm[0].form, popIt.frm[0].savebtns);
      estPosPopover('60vw');
      
      var avBody = $(popFrm.tabs.tab[0].tDiv).find('tbody');
      var rSpan = $(avBody).find('tr').length -1;
      
      $(avBody).find('tr').each(function(ti,trw){
        if(ti == 0){
          var avatTD = $(JQTD,{'class':'noPADTB','rowspan':rSpan}).appendTo(trw);
          
          var agtAvatar = $(JQDIV,{'id':'agtAvatar','class':'estAgentAvatar'}).on({
            mouseenter : function(e){estMediaEditBtns(6,e.target)},
            mouseleave : function(e){estMediaEditBtns(-1)}
            }).appendTo(avatTD);
          
          var agtAvtImg = $('<img />',{'id':'agtAvtImg','src':'','alt':''}).css({'display':'none'}).on({
            load : function(){
              if($(this).prop('src') !== ''){
                var nW = $(this).prop('naturalWidth');
                var nH = $(this).prop('naturalHeight');
                $(agtAvatar).animate({'height':Math.floor($(agtAvatar).outerWidth() * parseFloat(nH / nW).toFixed(2))+'px'}).promise().done(function(){
                  estPopHeight();
                  });
                }
              }
            }).appendTo(agtAvatar);
          }
        else if(ti >= rSpan){
          $(trw).find('td:last-child').prop('colspan',3);
          }
        }).promise().done(function(){
          estSetAgentImg(6);
          
          var inptCt1 = $(JQDIV,{'class':'estInptCont'}).appendTo($('select[name="agent_agcy"]').closest('td'));
          var sonar = $(JQDIV,{'id':'estAgtEdtInner','class':'estSonar'}).appendTo(inptCt1);
          $(JQDIV,{'class':'estSonarBlip'}).appendTo(sonar).promise().done(function(){
            $('select[name="agent_agcy"]').on({change : function(){estSetAgentPar()}}).prependTo(inptCt1);
            estAgentParOpts(aDta.agent_agcy); //estAgyEdtBtn2
            });
          
          
          $('input[name="agent_name"]').on({
            change : function(){
              if(this.value.length > 0){$(titSpan).html(this.value);}
              else{$(titSpan).html($(titSpan).data('was'));}
              },
            keyup : function(){
              if(this.value.length > 0){$(titSpan).html(this.value);}
              else{$(titSpan).html($(titSpan).data('was'));}
              }
            });
          
          $('select[name="agent_uid"]').data('levdta',aDta).on({change : function(){estSetAgentUID();}});
          estLoadAgentUIDs(aDta);
          });
      });
  
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


function estateSetOpLp(){
  $('#propOPpctBtn').data('oplp',0).html('↑ ↓');
  if(Number($('input[name="prop_listprice"]').val()) > 0 && Number($('input[name="prop_origprice"]').val()) > 0){
    var oplp = parseFloat((1 -(Number($('input[name="prop_listprice"]').val()) / Number($('input[name="prop_origprice"]').val()))) * 100).toFixed(1);
    if(oplp[oplp.length - 1] == 0){oplp = Math.round(oplp);}
    if(oplp > 0){$('#propOPpctBtn').data('oplp',(oplp * -1)).html('↓'+oplp+'%');}
    else if(oplp < 0){$('#propOPpctBtn').data('oplp',(oplp * -1)).html('↑'+(oplp * -1)+'%');}
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




  
function propOPpct(mode=0){
  if(mode == -1){
    $('#estClearkCover').remove();
    $('#propOPpctDiv').remove();
    }
  else{
    var targ = $('#propLPdiv');//.parent();
    var xTarg = $('input[name="prop_listprice"]');
    var xVal = Number($('input[name="prop_origprice"]').val());
    var oplp = Number($('#propOPpctBtn').data('oplp'));
    
    $(JQDIV,{'id':'estClearkCover'}).on({click : function(){propOPpct(-1)}}).appendTo('body');
    var propOPpctDiv = $(JQDIV,{'id':'propOPpctDiv'}).css({'top':Number(-176)+'px','left': Number(-72)+'px'}).appendTo(targ);
    
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
        if(document.getElementById('xPxtBtn'+oplp)){
          $(propOPpctDiv).scrollTop($('#xPxtBtn'+oplp).position().top + Math.floor($(targ).parent().height() / 2) - 200);
          }
        });
    }
  }



function estateBuildDIMUbtns(){
  console.log('estateBuildDIMUbtns');
  var defs = $('body').data('defs');
  //Number($('select[name="prop_subdiv"]').val())
  $('select[name="prop_subdiv"]').data('pval',-1).on({
    change : function(){estGetSubDivs(1)}
    });
  
  $('input[name="prop_landfee"]').data('pval',$('input[name="prop_landfee"]').val());
  $('input[name="prop_landfreq"]').data('pval',Number($('input[name="prop_landfreq"]').val()));
  
  $('select[name="prop_leasedur"]').data('pval',$('select[name="prop_leasedur"]').val());
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
  
  var propLPdiv = $(JQDIV,{'id':'propLPdiv'}).appendTo($('input[name="prop_listprice"]').parent());
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
        //$('input[name="prop_landfee"]').val(Number($('input[name="prop_landfee"]').data('pval')));
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
  
  estGetSubDivs(1);
  }




function selectEdit(mainTbl,targ,frmn=0){
  var defs = $('body').data('defs');
  var mainIdx = Number($('body').data('propid'));
  var targName = $(targ).prop('name');
  var uperm = Number(defs.user.perm);
  var srcTbl = defs.tbls[mainTbl];
  if(typeof srcTbl == 'undefined'){return;}
  if(typeof srcTbl.form[targName].src.tbl == 'undefined'){return;}
  var tbl = srcTbl.form[targName].src.tbl;
  var idx = srcTbl.form[targName].src.idx;
  
  var cVal = 0;
  //console.log(srcTbl.form[targName]);
  if(uperm >= 3){
    var cVal = $(targ).find('option:selected').val();
    }
  else if(typeof srcTbl.form[targName].src.perm !== 'undefined' && uperm >= Number(srcTbl.form[targName].src.perm[1])){
    var cVal = $(targ).find('option:selected').val();
    }
  
  var destTbl = defs.tbls[tbl];
  if(typeof destTbl == 'undefined'){return;}
  if(typeof destTbl.dta == 'undefined'){return;}
  
  var sectDta = estGetSectDta(idx,cVal,destTbl);
  
  var reqMatch = null;
  var reqVal = null;
  if(typeof srcTbl.form[targName].src.req !== 'undefined'){
    reqMatch = srcTbl.form[targName].src.req;
    $reqFld = $('[name="'+reqMatch[0]+'"]');
    if(typeof $reqFld !== 'undefined'){
      if($reqFld.is('select')){reqVal = $reqFld.find('option:selected').val();}
      else{reqVal = $reqFld.val();}
      if(!reqVal || reqVal === '0'){
        alert(reqMatch[2]);
        return;
        }
      }
    }
  
  var fTabs = [defs.txt.main];
  var exTabs = [];
  
  if(typeof defs.keys.popform[tbl] !== 'undefined'){
    if(typeof defs.keys.popform[tbl].tabs !== 'undefined'){
      $(defs.keys.popform[tbl].tabs).each(function(ti,eTab){
        fTabs.push(eTab.li);
        });
      }
    }
  
  var frmLabel = $(targ).closest('tr').find('td:first-child').html().toUpperCase();
  var fnct = null;
  
  if(targName == 'prop_subdiv'){
    var popIt = estBuildPopover([{'tabs':fTabs,'fnct':{'name':'estSHUploadBtn','args':4}}]);
    var popFrm = popIt.frm[0];
    $(popFrm.form).prop('enctype','multipart/form-data');
    
    var fileSlipBtn = $(JQBTN,{'type':'button','id':'fileSlipBtn2','class':'btn btn-primary btn-sm FR'}).html(xt.upload+' '+xt.media).on({
      click : function(e){
        e.stopPropagation();
        e.preventDefault();
        $('#fileSlip').click();
        }
      });
    
    
    var saveBtn2 = $(JQBTN,{'id':'estSaveSpace2','class':'btn btn-primary btn-sm FR'}).html(xt.save2).on({click : function(){
      estPopGo(1,popIt,0);
      }});
  
    var saveBtn1 = $(JQBTN,{'id':'estSaveSpace1','class':'btn btn-primary btn-sm FR'}).html(xt.save).on({click : function(){
      estPopGo(4,popIt,0); //prop_subdiv
      }});
    
    $(saveBtn2).appendTo(popFrm.h3);
    $(saveBtn1).appendTo(popFrm.h3);
    $(fileSlipBtn).appendTo(popFrm.h3).hide();
    popFrm.savebtns.push(saveBtn1);
    popFrm.savebtns.push(saveBtn2);
    popFrm.savebtns.push(fileSlipBtn);
    
    if(sectDta[idx] == 0){$(saveBtn2).hide();}
    //if(levDta.subd_idx == 0){$(saveBtn2).hide();}
    popFrm.savebtns.push(saveBtn1);
    popFrm.savebtns.push(saveBtn2);
    popFrm.savebtns.push(fileSlipBtn);
    fnct = {'name':'estUDSubdivision'};
    }
  else{
    var popIt = estBuildPopover([{'tabs':fTabs}]);
    var popFrm = popIt.frm[0];
    
    var saveBtn = $(JQBTN,{'class':'btn btn-primary btn-sm FR'}).html(defs.txt.save).on({click : function(){estPopGo(1,popIt);}}).appendTo(popFrm.h3);
    popFrm.savebtns.push(saveBtn);
    
    var newBtn = $(JQBTN,{'class':'btn btn-primary btn-sm FR'}).data('step',1).html(defs.txt.new1).on({
      click : function(){
        if($(this).data('step') == 2){estPopGo(2,popIt);}
        else{
          $(this).data('step',2).html(defs.txt.new2); estPopGo(-1,popIt);
          $(saveBtn).html(defs.txt.save2);
          }
        }
      }).appendTo(popFrm.h3);
    popFrm.savebtns.push(newBtn);
    
    if(sectDta[idx] == 0){
      $(newBtn).data('step',2).html(defs.txt.new2);
      $(saveBtn).html(defs.txt.save2);
      }
    }
  
  
  
  $(JQSPAN,{'class':'FL','title':defs.txt.cancelremove}).html(frmLabel).on({
    click : function(){estRemovePopover()}
    }).appendTo(popFrm.h3);
  
  var tbdy = popFrm.tabs.tab[0].tbody;
  if(typeof tdta !== 'undefined' && typeof tdta.fnct !== 'undefined'){fnct = tdta.fnct;}
  
  $(popFrm.form).data('levdta',sectDta).data('destTbl',{'dta':destTbl.dta,'flds':destTbl.flds,'idx':idx,'table':tbl});
  $(popFrm.form).data('maintbl',mainTbl).data('form',{'elem':targ,'attr':srcTbl.form[targName],'match':{},'fnct':fnct});
  
  var frmTRs = estFormEles(destTbl.form,sectDta,reqMatch);
  $(frmTRs).each(function(ei,trEle){
    estFormTr(trEle, popFrm.tabs.tab[trEle.tab].tDiv, popFrm.tabs.tab[trEle.tab].tbody);
    }).promise().done(function(){
      if(targName == 'prop_subdiv'){
        estBuildSubDivForm(popIt);
        }
      else{
        estPosPopover();
        estTestEles(popFrm.form,popFrm.savebtns);
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
  var agydta = defs.tbls.estate_agencies.dta.find(x => Number(x['agency_idx']) === Number($('input[name="prop_agency"]').val()));
  if(typeof agydta !== 'undefined'){
    var ctadr = htmlDecode(0,agydta.agency_addr).replace(/\s*\n\s*/ig, ', ');
    var lat = agydta.agency_lat;
    var lon = agydta.agency_lon;
    var geo = agydta.agency_geoarea;
    }
  else{
    var ctadr = htmlDecode(0,defs.prefs.pref_addr_lookup).replace(/\s*\n\s*/ig, ', ');
    var lat = defs.prefs.pref_lat;
    var lon = defs.prefs.pref_lon;
    var geo = '';
    }
  
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

    
function estBedBath(mode=0){
  var defs = $('body').data('defs');
  var featEle = $('textarea[name="prop_features"]');
  var bedT = Number($('input[name="prop_bedtot"]').val());
  var bedM = Number($('input[name="prop_bedmain"]').val());
  
  if(mode == 2){
    if(bedM > bedT){$('input[name="prop_bedtot"]').val(bedM);}
    }
  else{
    if(bedM > bedT){$('input[name="prop_bedmain"]').val(bedT);}
    }
  
  var bathF = Number($('input[name="prop_bathfull"]').val());
  var bathH = Number($('input[name="prop_bathhalf"]').val());
  
  var bathT = Number($('input[name="prop_bathtot"]').val());
  var bathM = Number($('input[name="prop_bathmain"]').val());
  
  var bthSum = Number(bathF + bathH);
  if(mode == 1){
    bathF = bathT - bathH;
    console.log(bathF);
    $('input[name="prop_bathfull"]').val(bathF);
    bthSum = Number(bathF + bathH);
    }
  else{
    $('input[name="prop_bathtot"]').val(bthSum);
    }
  
  if(bathM > bthSum){$('input[name="prop_bathmain"]').val(bthSum);}
  
  var fbt = bathF.toString();
  if(bathH > 0){
    if(bathH > 1){fbt += ' '+defs.txt.full+' + '+bathH.toString()+' '+defs.txt.half;}
    else{fbt += defs.txt.fracts[1];}
    }
  fbt += ' '+defs.txt.bath;//(bathF > 1 || bathH > 1 ? defs.txt.baths : defs.txt.bath);
  
  var propFeatX = $(featEle).val();
  
  if(propFeatX.length > 2){
    if(propFeatX.indexOf(',') > -1){var featArr = propFeatX.split(',')}
    else{var featArr = [propFeatX];}
  
    if(mode == 0){
      if(propFeatX.indexOf(' ') > -1){propFeatX = propFeatX.split(' ');}
      else{propFeatX = [propFeatX];}
      $(propFeatX).each(function(i,dta){
        if(dta.indexOf(defs.txt.bed) > -1){propFeatX[i] = propFeatX[i]+(propFeatX.length > i ? ',' : '');}
        if(dta.indexOf(defs.txt.bath) > -1){propFeatX[i] = propFeatX[i]+(propFeatX.length > i ? ',' : '');}
        }).promise().done(function(){
          $(featEle).val(propFeatX.join(' '));
          estBedBath(1);
          return;
          });
      }
    
    var bbF = [-1,-1];
    $(featArr).each(function(i,dta){
      //console.log(dta);
      if(dta.indexOf(defs.txt.bed) > -1){bbF[0] = i;}
      if(dta.indexOf(defs.txt.bath) > -1){bbF[1] = i;}
      }).promise().done(function(){
        console.log(bbF);
        if(bbF[0] > -1){featArr[bbF[0]] = bedT+' '+defs.txt.bed;}
        else{featArr.push(bedT+' '+defs.txt.bed);}
        if(bbF[1] > -1){featArr[bbF[1]] = fbt;}
        else{featArr.push(fbt);}
        
        $(featEle).val(featArr.join(','));
        });
    }
  else{
    $(featEle).val(bedT+' '+defs.txt.bed+','+fbt);
    }
  }
  
function estBuildGallery(){
  //console.log('estBuildGallery');
  
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
          
          if(Number(mediaDta.media_lev) > 0){
            // media_lev = 0 Subdivision, 1 Property, 2 Property Spaces , 3 ???, 4 ???
            var urlpth = defs.dir.prop.thm;
            ulbtn[k][0] = $(JQDIV,{'class':'upldPvwBtn pvw-'+mediaDta.media_idx}).css({'background-image':'url('+urlpth+mediaDta.media_thm+noCache+')'});
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
            }
          else{
            //Not a property image 
            var urlpth = defs.dir.subdiv.thm;
            }
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

