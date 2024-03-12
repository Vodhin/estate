// JavaScript Document

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


(function ($) {
  
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
  
  
  
  
  
  function estOAPrep(){
    console.log('OA Prep');
    estProcDefDta();
    
    
    $('.estOABlock').each(function(i,ele){
      $(ele).find('h3').on({
        click : function(e){
          e.stopPropagation();
          if($(ele).find('div.estOATabCont').is(':visible')){$(ele).removeClass('expand');}
          else{$(ele).addClass('expand');}
          }
        });
      });
    
    $('.admin-ui-help-tip').each(function(i,ele){
      
      });
    
    estPrepPropHrs();
    
    
    var dimU2v = Number($('input[name="prop_dimu2"]').val());
    var dimu2Btn = $(JQBTN,{'id':'dimu2Btn','class':'btn btn-primary estNoLeftBord'});
    
    
    }
  
  
  
  $(document).ready(function(){
    $.ajax({
      url: vreFeud+'?0||0',
      type:'get',
      data:{'fetch':2,'propid':Number($('input[name="prop_idx"]').val()),'rt':'js','tbl':''},
      dataType:'json',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        console.log(ret);
        if(typeof ret !== 'undefined' && ret !== null){
          if(typeof ret.error !== 'undefined'){estAlertLog(ret.error);}
          else{
            $('body').data('defs',ret);
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