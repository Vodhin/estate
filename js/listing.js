// JavaScript Document
/*
var vreQry = searchToObject();
var vrePath = window.location.pathname;
var vrePathPts = vrePath.split('/');
var vrePage = vrePathPts.pop();
var vreBasePath = vrePath.replace(vrePage,'');
*/

function searchToObject() {
  var pairs = window.location.search.substring(1).split('.'), obj = {}, pair, i;
  for (i in pairs){
    if (pairs[i] === "") continue;
    pair = pairs[i].split('.');
    obj[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
    }
  return obj;
  }

function htmlDecode(mode,xVal,dec=0){
  if(xVal !== ''){
    if(mode == 2){return xVal.replace(/[^\d]+/g, '');}
    else if(mode == 1){return $("<textarea/>").text(xVal).html();}
    else{return $("<textarea/>").html(xVal).text();}
    //var dtaname = $('#dtaname-'+dpIdx).val().replace(/[\W_]+/g,''); //shorthand alphanumeric
    }
  else{return '';}
  }

function estAlertLog(msg){
  alert(msg);
  console.log(msg);
  }

  
function setEstCookie(name, value, days) {
  var expires = "";
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toUTCString();
    }
  document.cookie = name + "=" + (value || "") + expires + "; path=/";
  }

function getEstCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
  return null;
  }


function estGetCookDta(name){
  var dta = getEstCookie(name);
  if(typeof dta == 'undefined' || dta == null){dta = [];}
  else if(dta.indexOf(',') > -1){dta = dta.split(',');}
  else{dta = [dta];}
  return dta;
  }


function estSetLike(ele,cok=0){
  var pid = Number($(ele).data('lpid'));
  var aid = Number($(ele).data('laid'));
  var tdta = {'sndLike':1,'pid':pid,'aid':aid,'cok':cok};
  console.log(tdta);
  var estJSpth = $(document).data('estJSpth');
  $.ajax({
    url: estJSpth+'ui/msg.php',
    type:'post',
    data:{'sndLike':'js','tdta':tdta},
    dataType:'json',
    cache:false,
    processData:true,
    success: function(ret, textStatus, jqXHR){
      console.log(ret);
      if(typeof ret.error !== 'undefined'){alert(ret.error); return;}
      if(ret.like > 0){$(ele).addClass('actv'); var nCt = 1;}
      else{$(ele).removeClass('actv'); var nCt = -1;}
      if(document.getElementById('estPropLikeCt-'+pid)){
        nCt = Number($('#estPropLikeCt-'+pid).data('ct')) + nCt;
        $('#estPropLikeCt-'+pid).data('ct',nCt).prop('title',nCt+' '+$('#estPropLikeCt-'+pid).data('t')).html(nCt);
        if(nCt > 0){$('#estPropLikeCt-'+pid).parent().show();}
        else{$('#estPropLikeCt-'+pid).parent().hide();}
        }
      },
    error: function(jqXHR, textStatus, errorThrown){
      console.log('ERRORS: '+textStatus+' '+errorThrown+' '+jqXHR.responseText);
      }
    });
  }





(function ($) {
  /*
  function findKeyframesRule(rule) {
    const ss = document.querySelector('head > style');
    console.log(ss);
    }
  */
  
  function estLnkBar(){
    $('#estMiniNav').append($('#estMiniSrc').html()).promise().done(function(){
      $('#estMiniSrc').remove();
      $('body').on({click : function(){$('#estMiniNav p').hide();}});
      $('#estMiniNav p').css({'top':$('#estMiniNav').outerHeight()+'px','background-color':$('body').css('background-color')});
      $('#estMiniNav a').on({
        click : function(e){
          e.stopPropagation();
          var targ = $(this).next('p');
          if($(targ).is(':visible')){$(targ).hide();}
          else{
            $('#estMiniNav p').hide();
            $(targ).show();
            }
          }
        });
      });
    }
  
  
  function estDynamicSlideShow(){
    if($('#estSlideShow').hasClass('estSlideshow')){
      $('#estSlideShow').on({
        click : function(e){
          if($(this).hasClass('estSSPaused')){$(this).removeClass('estSSPaused');}
          else{$(this).addClass('estSSPaused');}
          }
        });
      }
      
    
    if($('#estSpacesCont').hasClass('estSlideshow')){
      $('#estSpacesCont div.estImgSlide').each(function(i,iEle){
        if($(iEle).data('ict') > 0){
          $('<div class="estSSict">'+$(iEle).data('ict')+'</div>').appendTo(iEle);
          if($(iEle).data('ict') > 1){
            $(iEle).css({'cursor':'pointer'}).on({
              click : function(e){
                if($(this).hasClass('estSSPaused')){$(this).removeClass('estSSPaused');}
                else{$(this).addClass('estSSPaused');}
                }
              });
            $('<div class="estSSPlayPause"></div>').appendTo(iEle);
            }
          }
        });
      }
    
      
    
    if($('#estSpacesCont').hasClass('estSpaceDynamic')){
      var picCt = [0,null,0];
      $('#estViewSpaceBtns').find('.estViewSpaceBtn').each(function(i,ele){
        picCt[0]++;
        if(picCt[1] == null){picCt[1] = ele;}
        var iH = $('#'+$(ele).data('getimg')).outerHeight();
        if(iH > picCt[2]){picCt[2] = Math.floor(iH);}
        
        $(ele).on({
          click : function(e){
            var btn = this;
            if($('#estViewSpaceImgPvwCont').is(':visible')){
              var btnT = $(btn).position().top;
              var imgEle = $(btn).data('getimg');
              var imgH = $('#'+imgEle).outerHeight();
              $('#estViewSpaceImgPvwCont').css({'min-height':$('#estViewSpaceBtnCont').outerHeight(true)+'px'});
              $('#estViewSpaceBtns').find('.estViewSpaceBtn').removeClass('actv').promise().done(function(){$(btn).addClass('actv');});
              $('#estViewSpaceImgCont > div.estViewSpaceImgBlock').hide().promise().done(function(){
                $('#'+imgEle).show();
                var imgT = btnT - (($(btn).outerHeight(true) - $(btn).height()) * 2);
                var imgM = $('#estViewSpaceImgPvwCont').outerHeight(true) - $('#estViewSpaceImgPvwSlider').outerHeight(true);
                imgT = (imgT > imgM ? imgM : (imgT < 0 ? 0 : imgT));
                var arrT = ((btnT - imgT) - $(btn).height())+32;
                if((arrT + $("#estArrBordR").outerHeight(true)) > (imgT + imgH)){arrT = (imgH + imgT) - ($("#estArrBordR").outerHeight(true));}
                $('#estViewSpaceImgPvwSlider').animate({'top':(imgT < 0 ? 0 : imgT)+'px'});
                $('#estArrBordR').animate({'top':(arrT < 4 ? 4 : arrT)+'px'});
                });
              }
            }
          });
        
      }).promise().done(function(){
        if(picCt[0] == 0){
          $('#estViewSpacePvwCont').remove();
          $('#estViewBoxSpaceDynamicCont').addClass('noPics');
          $('#estViewBoxSpaceTileCont').addClass('noPics');
          }
        else{
          if(picCt[2] !== 0){
            console.log(picCt[2]);
            }
          if(picCt[1] !== null){
            $(picCt[1]).click();
            }
          }
        });
      }
    }
  
  
  function estBuildMap(){
    var defs = $('body').data('defs');
    if(typeof estMapPins !== 'undefined' && document.getElementById('estMap')){
      if(estMapPins.agcy.length == 0 && estMapPins.prop.length == 0){
        $('#estMapCont').remove();
        return;
        }
      
      if(typeof L == 'undefined'){
        $('#estMapCont').remove();
        estAlertLog(defs.txt.map+' '+defs.txt.javafail);
        return;
        }
      var estJSpth = $(document).data('estJSpth');
    	var tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    		minZoom: 4,
    		maxZoom: 17,
    		attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
    	 });
      
      if(estMapPins.agcy.length > 0){var latlng = L.latLng(estMapPins.agcy[0].lat, estMapPins.agcy[0].lon);}
      if(estMapPins.prop.length > 0){var latlng = L.latLng(estMapPins.prop[0].lat, estMapPins.prop[0].lon);}
      var map = L.map('estMap', {center: latlng, zoom: 13, layers: [tiles]});
      L.control.scale().addTo(map);
      
      var agIcon = L.icon({
        iconUrl: estJSpth+'js/leaflet/images/marker-icon.png',
        className: 'estMarkOrange',
        iconSize: [25, 40],
        iconAnchor: [15, 40],
        popupAnchor:[0, -35],
        shadowUrl: estJSpth+'js/leaflet/images/marker-shadow.png',
        shadowSize: [45, 35],
        shadowAnchor: [13, 30]
        });
      
      var agcyPoints = [];
      var propPoints = [];
      var agcyPins = L.markerClusterGroup();
      var propPins = L.markerClusterGroup();
      
      $(estMapPins.agcy).each(function(i,ax){
        agcyPoints[i] = L.marker([ax.lat,ax.lon]);
        var marker = L.marker(new L.LatLng(ax.lat,ax.lon), {icon: agIcon, title: ax.name1+': '+ax.name2});
        marker.bindPopup('<div class="estMapPopAgyThm" style="background-image:url('+(ax.thm !== null ? ax.thm : estJSpth+'images/imgnotavail.png')+')"></div><div class="estMapPopH1">'+ax.name1+'</div><div class="estMapPopH2 FSITAL">'+ax.name2+'</div><div class="estMapPopAddr">'+ax.addr+'</div>');
        agcyPins.addLayer(marker);
        }).promise().done(function(){
          if(agcyPoints.length > 0){
            map.addLayer(agcyPins);
            if(agcyPoints.length > 1){
              var group = new L.featureGroup(agcyPoints);
              map.fitBounds(group.getBounds(), {padding: L.point(25, 25)});
              }
            else{map.setView([estMapPins.agcy[0].lat, estMapPins.agcy[0].lon], 13);}
            }
          $(estMapPins.prop).each(function(i,px){
            propPoints[i] = L.marker([px.lat,px.lon]);
        		var marker = L.marker(new L.LatLng(px.lat,px.lon), { title: px.name1 });
            if(px.lnk !== null){
              var pMrkr = '<a href="'+px.lnk+'" class="estMapPopClk">';
              pMrkr += '<div class="estMapPopThm" style=\'background-image:url('+estJSpth+(px.thm !== null ? 'media/prop/thm/'+px.thm : 'images/imgnotavail.png')+')\'></div>';
              pMrkr += '<div class="estMapPopH1">'+px.sta+'</div><div class="estMapPopH1">'+px.prc+'</div>';// class="estPosR"
              
              if(px.drop !== null){
                //if(Number(px.drop) > 0){pMrkr += '<div class="estMapPopH1"><div class="estPosR">↓ '+px.drop+'%</div></div>';}
                //else if(Number(px.drop) < 0){pMrkr += '<div class="estMapPopH1"><div class="estPosR">↑ '+px.drop+'%</div></div>';}
                }
              
              if(px.feat.length > 0){
                pMrkr += '<div class="estMapPopH2"><ul>';
                $(px.feat).each(function(fi,fx){
                  pMrkr += '<li>'+fx+'</li>';
                  }).promise().done(function(){
                    pMrkr += '</ul></div></a>';
                    marker.bindPopup(pMrkr);
      			        propPins.addLayer(marker);
                    });
                }
              else{
                pMrkr += '</a>';
                marker.bindPopup(pMrkr);
      			    propPins.addLayer(marker);
                }
              }
            else{
      			  propPins.addLayer(marker);
              }
            }).promise().done(function(){
              if(propPoints.length > 0){
                map.addLayer(propPins);
                if(propPoints.length > 1){
                  var group = new L.featureGroup(propPoints);
                  map.fitBounds(group.getBounds(), {padding: L.point(25, 25)});
                  }
                else{
                  if(typeof estMapPins.prop[0].lat !== null && typeof estMapPins.prop[0].lon !== null){
                    map.setView([estMapPins.prop[0].lat, estMapPins.prop[0].lon], 13);
                    }
                  }
                }
              });
          });
      }
    }
  
  
  function estPrepSectReorder(){
    if(document.getElementById('estFEReorder')){
      $('#estFEReorder').on({
        click : function(){
          if($('.estReordHandle').eq(0).is(':visible')){
            $('.estReordMenu').hide();
            $('.estReordHandle').hide();
            $('.estReordDiv').show();
            }
          else{
            $('.estReordMenu').show();
            $('.estReordHandle').show();
            $('.estReordDiv').hide();
            }
          }
        });
      
      $(['#estateCont','#estMenuCont']).each(function(ci,cele){
        var dragCont = document.getElementById($(cele).prop('id'));
        if(dragCont !== null){
          var saveBtn = $(cele).find('input[type="submit"]');
          $(cele).find('select.estAdmTemplSel').on({
            change :function(){
              var cDta = $(saveBtn).data();
              var newTmpl = $(this).find('option:selected').val();
              if($(this).find('option:selected').data('ct') > 1){$(cele).find('#estTmplMenuMsg-'+cDta.area+'-2').fadeOut(250);}
              else{$(cele).find('#estTmplMenuMsg-'+cDta.area+'-2').fadeIn(250);}
              
              if(newTmpl !== cDta.template){
                $(dragCont).find('.estReordCont').fadeOut(250);
                $(cele).find('#estTmplMenuMsg-'+cDta.area+'-1').fadeIn(250);
                }
              else{
                $(dragCont).find('.estReordCont').fadeIn(250);
                $(cele).find('#estTmplMenuMsg-'+cDta.area+'-1').fadeOut(250);
                }
              }
            });
          
          var sects = [];
          $(dragCont).find('.estReordCont').each(function(i,ele){
            sects[i] = $(ele).data('sect');
            if($(ele).find('div.estReordDiv').is(':empty')){$(ele).find('label').append(' (No Data)');}
            $(ele).find('input[type="checkbox"]').data('sect',sects[i]).on({
              click : function(){
                if($(this).is(':checked')){$(ele).find('div.estReordDiv').removeClass('noDISP');}
                else{$(ele).find('div.estReordDiv').addClass('noDISP');}
                }
              });
            }).promise().done(function(){
              
              Sortable.create(dragCont,{
                draggable: 'div.estReordCont',
                handle: 'div.estReordHandle',
                sort: true,
                animation: 450,
                ghostClass: 'sortTR-ghost',
                chosenClass: 'sortTR-chosen', 
                dragClass: 'sortTR-drag',
                onChoose: function(evt){},
                onEnd: function(evt){},
                onUnchoose: function(evt){},
                });
              });
          }
        });
      }
    }
  
  
  
  
  function estCardTabs(){
    $('.estCardTopTab').each(function(i,ele){
      var ht = ($(ele).outerHeight() + $(ele).parent()[0].offsetTop) * -1;
      $(ele).animate({'top':ht+'px'});
      });
    
    $('.estCardTopBtn').each(function(i,ele){
      $(ele).on({
        click: function(e){
          e.preventDefault();
          e.stopPropagation();
          if(typeof $(ele).data('eurl') !== 'undefined'){window.location.assign($(ele).data('eurl'));}
          else if(typeof $(ele).data('lpid') !== 'undefined'){estSetLike($(ele));}
          }
        });
      });
    }
  
  
  

  
  $(document).ready(function(){
    $(document).data('estJSpth',$('#estJSpth').data('pth'));
    estDynamicSlideShow();
    estBuildMap();
    estCardTabs();
    estLnkBar();
    estPrepSectReorder();
    $('.DTH256').on({click :function(){$(this).removeClass('DTH256')}});
    $('#estJSpth').remove();
    });

})(jQuery);