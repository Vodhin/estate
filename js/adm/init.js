// JavaScript Document
//// NOT USED?!?!?
//(function ($) {
  // All your code here.
  var JQDIV = '<div></div>';
  var JQSPAN = '<span></span>';
  var JQBTN = '<button></button>';
  var JQINPT = '<input />';
  var JQOPTION = '<option></option>';
  var JQTABLE = '<table></table>';
  var JQCOLGRP = '<colgroup></colgroup>';
  var JQTHEAD = '<thead></thead>';
  var JQTBODY = '<tbody></tbody>';
  var JQTFOOT = '<tfoot></tfoot>';
  var JQTR = '<tr></tr>';
  var JQTH = '<th></th>';
  var JQTD = '<td></td>';


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


  
  function estMediaEditBtns(mode,mediaThm){
    $('#mediaEditBox').remove().promise().done(function(){
      if($(mediaThm).hasClass('estUploading')){return;}
      
      if(mode > 0){
        var defs = $('body').data('defs');
          var keyTbl = defs.keys.contabs[mode];
          
          var mediaEditBox = $(JQDIV,{'id':'mediaEditBox','class':'mediaEditBox'}).prependTo(mediaThm);
          var srcBtn = $(JQBTN,{'id':'estAvatSrcBtn','class':'btn btn-primary btn-sm estNoLRBord TAL','title':defs.txt.image+' Source'}).appendTo(mediaEditBox);
          var uplBtn = $(JQBTN,{'id':'estAvatUplBtn','class':'btn btn-default btn-sm estNoLRBord','title':defs.txt.upload+' '+defs.txt.new1+' '+defs.txt.image}).appendTo(mediaEditBox);
          $(JQSPAN,{'class':'fa fa-upload'}).appendTo(uplBtn);
          
          $(srcBtn).css({'width':($(mediaThm).width() - $(uplBtn).outerWidth())+'px'});
          
          
          switch(mode){
            case 6 :
              mediaDta = {'agent_idx':Number($('input[name="agent_idx"]').val()),'agent_uid':Number($('input[name="agent_uid"]').val()),'agent_imgsrc':Number($('input[name="agent_imgsrc"]').val()),'agent_image':$('input[name="agent_image"]').val()};
              console.log(mediaDta);
              
              $(mediaEditBox).data('mediadta',mediaDta);
              
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
                    $(mediaEditBox).data('mediadta',mediaDta);
                    $('input[name="agent_imgsrc"]').val(1);
                    }
                  }
                else{
                  $(srcBtn).on({
                    click : function(e){
                      e.stopPropagation();
                      e.preventDefault();
                      var mediaDta = $(mediaEditBox).data('mediadta');
                      if(Number(mediaDta.agent_imgsrc) == 1){mediaDta.agent_imgsrc = 0;}
                      else{mediaDta.agent_imgsrc = 1;}
                      $('input[name="agent_imgsrc"]').val(Number(mediaDta.agent_imgsrc));
                      $(mediaEditBox).data('mediadta',mediaDta);
                      
                      $(e.target).html(defs.keys.avatarlab[mediaDta.agent_imgsrc]);
                      //estSetAgentImg(mode);
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
                  //if(mediaDta.agent_idx > 0){estFileUplFld(mediaDta,1,mediaThm,6);}
                  }
                });
              break;
            
            case 5 :
              break;
            
            case 4 :
              $(srcBtn).html(defs.txt.company+' '+defs.txt.logo).on({
                click : function(e){
                  e.stopPropagation();
                  e.preventDefault();
                  }
                });
              $(uplBtn).on({
                click : function(e){
                  e.stopPropagation();
                  e.preventDefault();
                  //var mediaDta = $(mediaThm).closest('form').data('levdta');
                  //if(mediaDta.company_idx > 0){estFileUplFld(mediaDta,1,mediaThm,4);}
                  }
                });
              break;
            }
          }
        });
    }



  function estAgtInit(){
    
    
    
    }

  //estAgentAvatar
  
  
  function estInitSetup(){
    estProcDefDta();
    
    $('#agency-addr').on({
      keyup : function(){
        $('#agency_addr_lookup').val(this.value);
        },
      change : function(){
        $('#agency_addr_lookup').val(this.value);
        }
      });
    
    $('.estContBtn').on({
      click : function(e){
        e.preventDefault();
        var btn = this;
        if($(btn).hasClass('btn-primary')){
          var fVal = $('#contact_data-'+$(btn).attr('data-targ')).val();
          if(fVal !== ''){
            if(jsconfirm('Delete "'+fVal+'"?')){
              $('#contact_data-'+$(btn).attr('data-targ')).data('pval',fVal).val('');
              $(btn).removeClass('btn-primary').addClass('btn-default');
              $('#estContTR-'+$(btn).attr('data-targ')).fadeOut();
              }
            }
          else{
            $(btn).removeClass('btn-primary').addClass('btn-default');
            $('#estContTR-'+$(btn).attr('data-targ')).fadeOut();
            }
          }
        else{
          $(btn).removeClass('btn-default').addClass('btn-primary');
          $('#estContTR-'+$(btn).attr('data-targ')).fadeIn();
          var pVal = $('#contact_data-'+$(btn).attr('data-targ')).data('pval');
          if(typeof pVal !== 'undefined'){
            if(jsconfirm('Restore "'+pVal+'"?')){
              $('#contact_data-'+$(btn).attr('data-targ')).val(pVal);
              }
            }
          }
        }
      });
    
    $('.estSpecImgUploadDiv').each(function(i,tEle){
      
      //agent_img
        
      });
      
      
    
    
    
    
    
    estBuildMap('agcy');
    
    $('#map_subSrchBtn').on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        }
      });
    }
  
  

  $(document).ready(function(){
    console.log(vreQry);
    $('body').addClass('noFCOutline');
    
    var stopForm = $('.estStopForm');
    if(stopForm.length > 0){
      return;
      }
    
    
    

    sDta = {'fetch':2,'propid':0,'rt':'js','tbl':''};
    $.ajax({
      url: vreFeud+'?0||0',
      type:'get',
      data:sDta,
      dataType:'json',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        console.log(ret);
        if(typeof ret !== 'undefined' && ret !== null){
          if(typeof ret.error !== 'undefined'){alert(ret.error);}
          else{
            $('body').data('propid',0).data('defs',ret).promise().done(function(){
              var helpInFull = (typeof ret.prefs.helpinfull !== 'undefined' ? Number(ret.prefs.helpinfull) : 0);
              if(helpInFull == 0){
                $('#estHelpHead').addClass('estHelpHAuto').on({
                  click : function(){
                    if($(this).hasClass('estHelpHAuto')){
                      $(this).removeClass('estHelpHAuto').addClass('estHelpHMan');
                      $('#estHelpBlock').removeClass('estHelpBAuto');
                      }
                    else{
                      $(this).removeClass('estHelpHMan').addClass('estHelpHAuto');
                      $('#estHelpBlock').addClass('estHelpBAuto');
                      }
                    }
                  });
                }
              
              estInitSetup();
              });
            }
          }
        },
      error: function(jqXHR, textStatus, errorThrown){
        console.log('ERRORS: ' + textStatus);
        console.log('ERRORS: ' + errorThrown);
        }
      });
    
    
    
    
    
    
    });
  //});