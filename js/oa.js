// JavaScript Document


(function ($) {
  
  function estOASetMap(){
    var defs = $('body').data('defs');
    var propId = Number($('body').data('propid'));
    
    if(typeof L == 'undefined'){
      $('#est_prop_MapCont').remove();
      estAlertLog(defs.txt.map+' '+defs.txt.javafail);
      return;
      }
    
    var nTW = Math.floor($('#est_prop_MapCont').closest('table').width() * 0.60);
    $('#est_prop_MapCont').width(nTW);
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
  
  
  
  function estOABuildMap(){
    
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
        estOASetMap();
        }
      }).appendTo(mapSrchRes).promise().done(function(){
        
        var xHt = Number($(mapSrchRes).height()) - Number($('#mapReset-est_prop_Map').height());
        
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
                          estOASetMap();
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
        
        estOASetMap();
        });
    }
  
  
  function estOAPrep(){
    console.log('OA Prep');
    estProcDefDta();
    var propId = Number($('body').data('propid'));
    var defs = $('body').data('defs');
    var mainTbl = 'estate_properties';
    var cSave = $('input[type="submit"]');
    var cForm = $('#plugin-estate-OAform');
    
    
    $('.estOASubmit').on({
      click : function(e){
        if(!document.getElementById('estEmailNoSend')){
          var txt = $('body').data('defs').txt;
          var bkcover = $(JQDIV,{'id':'estBlackout'}).on({
            click : function(e){
              e.stopPropagation();
              $('#estBlackout').remove();
              }
            }).prependTo('body');
          $(JQDIV,{'id':'estEmlNote'}).html(txt.sendingemails+' '+txt.tomods+'.<br /><br />'+txt.donotreload).appendTo(bkcover);
          }
        }
      });
    
        
    estSetFormEles(mainTbl,cForm,cSave);
    
    
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
              if(i == 1){estOASetMap();}
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
          estOASetMap();
          }
        });
      }
      
    
    $('.admin-ui-help-tip').each(function(i,ele){
      $(ele).on({
        mouseenter : function(){$(ele).parent().find('div.field-help').fadeIn(200);},
        mouseleave : function(){$(ele).parent().find('div.field-help').fadeOut(200);}
        });
      });
    
    //estSaveSpace 
    estateBuildDIMUbtns();
    estBuildGallery();
    //estBuildSpaceList('oa load');
    //estInitDefHrs(1);
    //estBuildEvtTab();
    estOABuildMap();
    estPrepPropHrs();
    
    
    $(JQDIV,{'class':'s-message alert alert-block warning alert-warning'}).html(defs.txt.notavail2).prependTo('#estEventsCont');
    
    var mediaDta = estNewMediaDta(1,'OA estOAPrep');
    estFileUplFld(mediaDta,1,null,1);
    $('#fileSlipBtn').on({
      click : function(e){
        e.stopPropagation();
        e.preventDefault();
        $('#fileSlip').click();
        }
      });
    
    estTestEles(cForm,cSave);
    estCleanup();
    }
  
  
  
  
  function estCleanup(){
    $('.estInptCont').each(function(i,ele){
      var eles = $(ele).children();
      //console.log(eles);
      });
    
    }
  
  
  $(document).ready(function(){
    $('body').on({click : function(){$('.estThmMgrCont').remove()}});
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