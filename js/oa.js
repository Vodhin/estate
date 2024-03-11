// JavaScript Document

var vreQry = searchToObject();
var vrePath = window.location.pathname;
var vrePathPts = vrePath.split('/');
var vrePage = vrePathPts.pop();
var vreBasePath = vrePath.replace(vrePage,'');
var vreFeud = vreBasePath+'js/adm/feud.php';


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