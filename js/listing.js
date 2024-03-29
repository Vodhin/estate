// JavaScript Document
var vreQry = searchToObject();
var vrePath = window.location.pathname;
var vrePathPts = vrePath.split('/');
var vrePage = vrePathPts.pop();
var vreBasePath = vrePath.replace(vrePage,'');



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


(function ($) {
  
  function findKeyframesRule(rule) {
    const ss = document.querySelector('head > style');
    console.log(ss);
    }
  
  
  function estLnkBar(){
    $('#estMiniNav').append($('#estMiniSrc').html()).promise().done(function(){
      //$('#estMiniSrc').remove();
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
    console.log('load slideshow');
    if($('#estViewBoxTop').hasClass('estSlideshow')){
      $('#estViewBoxTop').on({
        click : function(e){
          if($(this).hasClass('estSSPaused')){$(this).removeClass('estSSPaused');}
          else{$(this).addClass('estSSPaused');}
          }
        });
      }
      
    
    if($('#estViewBoxSpacesCont').hasClass('estSlideshow')){
      $('#estViewBoxSpacesCont div.estImgSlide').each(function(i,iEle){
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
    
      
    
    if($('#estViewBoxSpacesCont').hasClass('estSpaceDynamic')){
      if($('#estViewSpaceImgPvwCont').is(':visible')){
        //$('#estViewSpaceImgPvwCont').css({'min-height':$('#estViewSpaceBtnCont').outerHeight(true)+'px'});
        }
      
      var picCt = [0,null,0];
      $('#estViewSpaceBtns').find('.estViewSpaceBtn').each(function(i,ele){
        if(!$(ele).hasClass('noPics')){}
        
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
                  var arrT = ((btnT - imgT) - $(btn).height());
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
              //$('.estViewSpaceImgBlock').css({'min-height':picCt[2]+'px'});
              }
            if(picCt[1] !== null){
              $(picCt[1]).click();
              }
            }
          });
      }
    }
  
  
  function estExpandArr(){
    $('.estExpBtn').each(function(i,ele){
      $(ele).on({
        click : function(e){
          e.preventDefault();
          e.stopPropagation();
          var ebTarg = $('#'+$(e.target).data('targ'));
          console.log(ebTarg);
          if($(e.target).hasClass('estEBopen')){
            $(ebTarg).animate({'height':'0px'});
            $(e.target).removeClass('estEBopen');
            }
          else{
            $(ebTarg).show();
            var eH = $(ebTarg).find('div:first-child').outerHeight(true);
            $(ebTarg).animate({'height':eH+'px'});
            $(e.target).addClass('estEBopen');
            }
          }
        });
      });
    }
  
  function estBuildMap(){
    if(document.getElementById('estMap')){
      console.log(estMapPins);
      
      if(estMapPins.agcy.length == 0 && estMapPins.prop.length == 0){
        $('#estMapCont').remove();
        return;
        }
      
      var estJSpth = $('#estJSpth').data('pth');
      
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
              pMrkr += '<div class="estMapPopH1"><div class="estPosR">'+px.prc+'</div>'+px.sta+'</div>';
              
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
                else{map.setView([estMapPins.prop[0].lat, estMapPins.prop[0].lon], 13);}
                }
              });
          });
      }
    }
  
  
  function estFindMenuCont(cEle,tEle){
    if($(cEle).parent() == $(tEle).parent()){return $(cEle).parent();}
    else if($(cEle).parent().parent() == $(tEle).parent().parent()){return $(cEle).parent().parent();}
    else{return null;}
    }
  
  
  function estPrepMenu(){
    if(document.getElementById('estPlugMenu1') && $('#estPlugMenu1').is(':visible')){
      if(document.getElementById('estSidebar1Capt')){
        
        /*
        $('#estSidebar1Capt').appendTo('#estPlugMenu1Cap');
        $('#estMiniNav').appendTo('#estSidebar1Capt');
        $('#estInfoModule').appendTo('#estPlugMenu1');
        $('#estAgntModule').appendTo('#estPlugMenu1');
        $('#estOpenHouseModule').appendTo('#estPlugMenu1').promise().done(function(){
          
          $('#estViewBoxSummarySB').hide();
          if(document.getElementById('estFeaturesModule') && $('#estFeaturesModule').is(':visible')){
            $('#estFeaturesModule').appendTo('#estSideMenuFeat').promise().done(function(){
              if(document.getElementById('estViewSpacePvwCont') && $('#estViewSpacePvwCont').is(':visible')){
                $('#estViewSpaceBtnCont').appendTo('#estSideMenuSpaces');
                $('div.estViewSpaceBtn').removeClass('estBGGrad2').addClass('btn btn-primary');
                
                var smTop = Math.floor($('#estViewSpacePvwCont').offset().top);
                var m3Top = Math.floor($('#estSideMenuSpaces').position().top);
                
                console.log(smTop,m3Top,(smTop-m3Top));
                
                $('#estSideMenuFeat').css({'min-height':(smTop-m3Top)+'px'});
                $('#estSideMenuSpaces').css({'min-height':$('#estViewSpacePvwCont').outerHeight()+'px'});
                
                }
              });
            }
          });
        
        */
        }
      }
    }
  
  
  
  $(document).ready(function(){
    estPrepMenu();
    estDynamicSlideShow();
    estBuildMap();
    estExpandArr();
    estLnkBar();
    //$('a').on({click : function(e){e.preventDefault();}});
    });

})(jQuery);