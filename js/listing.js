// JavaScript Document
var vreQry = searchToObject();
var vrePath = window.location.pathname;
var vrePathPts = vrePath.split('/');
var vrePage = vrePathPts.pop();
var vreBasePath = vrePath.replace(vrePage,'');
var vreFeud = vreBasePath+'ui/msg.php';

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
  
  
  
  function estMsgSnd(){
    $('.estChkMsgRem').each(function(i,ele){
      if(this.value !== 'itsatrap@'+$(this).data('domn')){
        $('#estMsgFormTabl').parent().remove();
        return;
        }
      }).promise().done(function(){
        var txts = $('#estMsgFormDiv').data('txts');
        $('<button></button>',{'id':'estMsgSend2','class':'btn btn-primary'}).html(txts.send1).on({
            click : function(e){
              e.preventDefault();
              var tdta = {};
              tdta['msg_text'] = $('textarea[name="msg_text"]').val();
              tdta['msg_mode'] = Number($('select[name="msg_mode"]').find('option:selected').val());
              //tdta['msg_from_cc'] = ($('#msg-from-cc').is(':checked') ? 1 : 0);
              $('#estMsgFormTB').find('input').each(function(i,ele){
                tdta[$(ele).prop('name')] = $(ele).val();
                }).promise().done(function(){
                  //console.log(tdta);
                  $.ajax({
                    url: vreFeud,
                    type:'post',
                    data:{'sndMsg':'js','tdta':tdta},
                    dataType:'json',
                    cache:false,
                    processData:true,
                    success: function(ret, textStatus, jqXHR){
                      console.log(ret);
                      var msg = '';
                      if(typeof ret.error !== 'undefined'){
                        $(ret.error).each(function(ei,err){
                          msg += (msg.length > 1 ? '.<br /><br />' : '')+htmlDecode(0,err);
                          }).promise().done(function(){
                            alert(msg);
                            });
                        }
                      else{
                        if(typeof ret.msg == 'undefined'){alert(htmlDecode(0,txts.txt0));}
                        else{
                          msg = htmlDecode(0,'<p>'+txts.txt6+' '+ret.msg.msg_to_name+'. '+txts.txt7+'</p>');
                          if(Number(ret.msg.msg_email) == 1){
                            msg += htmlDecode(0,'<p>'+txts.txt1+' '+ret.msg.msg_to_name+'.</p>');
                            
                            if(Number(ret.msg.msg_from_cc) == 1){
                              msg += htmlDecode(0,'<p>'+txts.txt4+' '+ret.msg.msg_from_addr+' '+txts.txt5+'.</p>');
                              }
                            }
                          else{msg += htmlDecode(0,'<p>'+txts.txt0+'.</p>');}
                          
                          if(Number(ret.msg.msg_pm) > 0){
                            msg += htmlDecode(0,'<p>'+ret.msg.msg_to_name+' '+txts.txt3+'.</p>');
                            }
                          $('#estMsgFormTabl').remove();
                          }
                        
                        $('<div></div>',{'id':'estMsgResult'}).html('<h4>'+txts.res1+'</h4>'+msg).prependTo('#estMsgFormDiv');
                        
                        if(typeof ret.pvw !== 'undefined'){
                          if(document.getElementById('estMsgPrevBelt')){$(ret.pvw[0]).prependTo('#estMsgPrevBelt');}
                          else{$(ret.pvw[1]).appendTo('#estMsgPrevDiv');}
                          }
                        }
                      },
                    error: function(jqXHR, textStatus, errorThrown){
                      console.log('ERRORS: '+textStatus+' '+errorThrown+' '+jqXHR.responseText);
                      }
                    });
                  });
                  
              }
            }).prependTo($('#estMsgSend1').parent());
          $('#estMsgSend0').closest('tr').hide();
          $('#estMsgSend1').remove();
          $('#estMsgTerms').fadeOut(200);
          
        });
    }
  
    
  
  function estMsgChk(mode=0){
    $('#estMsgSend1').prop('disabled',true);
    $('.estChkMsg').removeClass('estMsgNG');
    var mVal = Number($('select[name="msg_mode"]').find('option:selected').val());
    //if(mVal == 3){$('#msgTopTR').show();}
    //else{$('#msgTopTR').hide();}
    if(mVal > 0){
      var sForm = 0;
      $('#estMsgFormTB').fadeIn(300);
      $('.estChkMsg').each(function(i,elm){
        if($(elm).val().length < Number($(elm).data('len'))){$(elm).addClass('estMsgNG'); sForm++;}
        if(typeof $(elm).data('req') !== 'undefined' && $(elm).val().indexOf($(elm).data('req')) == -1){$(elm).addClass('estMsgNG'); sForm++;}
        }).promise().done(function(){
          if(sForm > 0 && mode == 1){
            var txts = $('#estMsgFormDiv').data('txts');
            alert(txts.fng);
            }
          else{
            if(mode == 1){estMsgSnd();}
            else{
              $('#estMsgSend0').closest('tr').hide();
              $('#estMsgSend1').prop('disabled',false).removeProp('disabled');
              $('#estMsgFormTF').fadeIn(300);
              }
            }
          });
      }
    else{
      $('#estMsgFormTF').fadeOut(300);
      $('#estMsgFormTB').fadeOut(300);
      }
    }
  
  
  
  
  function estMsgSys(){
    if(document.getElementById('estMsgWarn')){return;}
    if(document.getElementById('estMsgPrevDiv')){
      $('#estMsgPrevDiv h4').on({
        click :function(){
          if($('#estMsgPrevBelt').is(':visible')){
            $('#estMsgPrevBelt').animate({'height':'0px'},500,'swing',function(){$('#estMsgPrevBelt').hide()});
            $('.estMsgP').hide();
            }
          else{$('#estMsgPrevBelt').show().animate({'height':'256px'},500,'swing');}
          }
        });
      
      $('.estMsgBtn').each(function(i,btn){
        $(btn).on({
          click : function(e){
            e.preventDefault();
            var targ = $(btn).find('div.estMsgP')
            if($(targ).is(':visible')){$(targ).hide();}
            else{
              $('.estMsgP').hide();
              $(targ).show();
              }
            }
          })
        });
      }
    
    if(document.getElementById('estMsgFormTabl')){
      $('.estChkMsgRem').on({change : function(e){if(this.value !== 'itsatrap@'+$(this).data('domn')){$('#estMsgFormTabl').parent().remove();}}});
      $('#estMsgFormDiv').data({'txts':{'fng':$('#estMsgFNG').html(),'res1':$('#estMsgRes').html(),'send1':$('#estEmS1').html(),'txt0':$('#estEmSent0').html(),'txt1':$('#estEmSent1').html(),'txt2':$('#estEmSent1').html(),'txt3':$('#estPmSent').html(),'txt4':$('#estEmSent4').html(),'txt5':$('#estEmSent5').html(),'txt6':$('#estThks1').html(),'txt7':$('#estThks2').html()}});
      
      $('select[name="msg_mode"]').data({'m':['',$('#estT1').html(),$('#estT2').html(),''],'t':['',$('#estS1').html(),$('#estS2').html(),'']}).on({
        change : function(e){
          $(this).find('option[value="0"]').remove();
          var ky = Number($(this).find('option:selected').val());
          $('textarea[name="msg_text"]').val($(this).data('m')[ky]);
          if($(this).data('t')[ky].length > 4){$('input[name="msg_top"]').val($(this).data('t')[ky]).prop('disabled',true);}
          else{$('input[name="msg_top"]').prop('disabled',false).removeProp('disabled').val('');}
          estMsgChk();
          },
        click : function(){
          $('#estMsgPrevBelt').css({'height':'0px'}).hide();
          $('.estMsgP').hide();
          }
        });
      $('.estChkMsg').on({blur: function(){estMsgChk()}});
      $('#estMsgSend0').on({click : function(){estMsgChk(1);}});
      $('#estMsgSend1').on({click : function(){estMsgChk(1);}});
      $('#estMsgDefs').remove();
      }
    }
  
  
  
  
  
  $(document).ready(function(){
    estPrepMenu();
    estDynamicSlideShow();
    estBuildMap();
    estExpandArr();
    estLnkBar();
    estMsgSys();
    
    
    var edbtn = $('.estPropListEdtBtn');
    var aCont = $('a.estListBlockA');
    if(edbtn.length > 0){
      if(edbtn.length < aCont.length){
        }
      $(edbtn).each(function(i,ele){
        $(ele).on({
          click: function(e){
            e.preventDefault();
            e.stopPropagation();
            window.location.assign($(this).data('url'));
            }
          });
        });
      }
    });

})(jQuery);