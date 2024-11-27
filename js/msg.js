// JavaScript Document

(function ($) {

  function estMsgSnd(){
    var estJSpth = $(document).data('estJSpth');
    
    $('.estChkMsgRem').each(function(i,ele){
      if(this.value !== 'itsatrap@'+$(this).data('domn')){
        $('#estMsgFormTabl').parent().remove();
        return;
        }
      }).promise().done(function(){
        var txts = $('#estMsgFormDiv').data('txts');
        $('<button></button>',{'id':'estMsgSend2','class':'btn btn-primary WD100'}).html(txts.send1).on({
            click : function(e){
              e.preventDefault();
              var tdta = {};
              tdta['msg_text'] = $('textarea[name="msg_text"]').val();
              tdta['msg_mode'] = Number($('select[name="msg_mode"]').find('option:selected').val());
              //tdta['msg_from_cc'] = ($('#msg-from-cc').is(':checked') ? 1 : 0);
              $('#estMsgFormTB').find('input').each(function(i,ele){
                tdta[$(ele).prop('name')] = $(ele).val();
                }).promise().done(function(){
                  $.ajax({
                    url: estJSpth+'ui/msg.php',
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
                          if(ret.pvw[1].length > 0){$(ret.pvw[1]).prependTo('#estMsgFormDiv');}
                          $('#estMsgPrevDiv').empty();
                          //$('#estMsgPrevDiv').html(ret.pvw[0]);
                          //estPrevMsgBtns();
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
  
  
  
  function estMsgCounts(mode){
    if(mode == 1){
      $('.estAdmMsgTabCts').each(function(i,ele){
        $(ele).html($('#'+$(ele).data('targ')).find('tr').length);
        });
      }
    else{
      var nCt = 0;
      $('#estInBoxCont').find('div.estInBoxSect').each(function(i,ele){
        var len = $(ele).find('div.estMsgBtn').length;
        $(ele).find('button.estSectBtn span').html(Number(len));
        if($(ele).hasClass('estNewMsgs')){nCt = Number(nCt) + Number(len);}
        if(Number(len) > 0){$(ele).fadeIn(250);}
        else{$(ele).fadeOut(250);}
        }).promise().done(function(){
          $('#estMsgCtNew').html(Number(nCt));
          if(Number(nCt) > 0){$('#estMsgPlsMrk').fadeIn();}
          else{$('#estMsgPlsMrk').fadeOut();}
          });
      }
    }
  
    
  function estReadMsg(btn,del=0){
    var estJSpth = $(document).data('estJSpth');
    var tr = $(btn).closest('tr.estAdmMsgTr');
    var bloc = $(btn).closest('div.estMsgBtn');
    
    $('.estMsgP').hide();
    if(tr.length > 0){
      $('div.estAdmMsgDiv').removeClass('popover fade top in editable-container editable-popup estMsgAdmBlk');
      var dta = $(tr).data();
      $(tr).addClass('estFlipIt');
      }
    else{
      var dta = $(bloc).data();
      }
    console.log(dta);
    
    if(del === 1){
      if(!jsconfirm($(btn).data('msg'))){return;}
      dta.del = Number(dta.idx);
      }
    $.ajax({
      url: estJSpth+'ui/msg.php',
      type:'post',
      data:{'msgRead':'js','tdta':{'msgRead':1,'dta':dta}},
      dataType:'json',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        console.log(ret);
        if(typeof ret.error !== 'undefined'){alert(ret.error);}
        else{
          if(Number(ret.dta.del) == 1){
            if(tr.length > 0){$(tr).remove().promise().done(function(){estMsgCounts(1);});}
            else{$(bloc).remove().promise().done(function(){estMsgCounts();});}
            }
          else{
            dta.read = ret.dta.read;
            $(bloc).data(dta);
            console.log($(bloc).data());
            if(Number(ret.dta.up) == 1){
              var btn2 = $(bloc).find('.estMarkMsg');
              var ttl1 = $(btn2).data('ttl');
              var ttl2 = $(btn2).prop('title');
              $(btn2).data('ttl',ttl2).prop('title',ttl1);
              if(Number(dta.read) > 0){
                $(btn).find('i').removeClass('fa-check').addClass('fa-arrow-up');
                if(tr.length > 0){
                  $(tr).data(dta).prependTo('#estMsgTB-read-'+ret.dta.mode).promise().done(function(){
                    $(tr).removeClass('estFlipIt');
                    estMsgCounts(1);
                    });
                  }
                else{
                  $(bloc).data(dta).prependTo('#estInBoxBelt-read').promise().done(function(){estMsgCounts();});
                  }
                }
              else{
                $(btn).find('i').removeClass('fa-arrow-up').addClass('fa-check');
                if(tr.length > 0){
                  $(tr).data(dta).prependTo('#estMsgTB-new-'+ret.dta.mode).promise().done(function(){
                    $(tr).removeClass('estFlipIt');
                    estMsgCounts(1);
                    });
                  }
                else{
                  $(bloc).data(dta).prependTo('#estInBoxBelt-'+dta.mode).promise().done(function(){estMsgCounts();});
                  }
                }
              }
            }
          }
        },
      error: function(jqXHR, textStatus, errorThrown){
        console.log('ERRORS: '+textStatus+' '+errorThrown+' '+jqXHR.responseText);
        }
      });
    }
  
  
  
  
  function estBindMsgViewBtns(){
    $('.estDelMsg').each(function(i,btn){
      if(!$(btn).hasClass('estEleBound')){
        $(btn).addClass('estEleBound').on({
          click :function(e){
            e.preventDefault();
            e.stopPropagation();
            estReadMsg(this,1);
            }
          });
        }
      });
    
    $('.estMarkMsg').each(function(i,btn){
      if(!$(btn).hasClass('estEleBound')){
        $(btn).addClass('estEleBound').on({
          click :function(e){
            e.preventDefault();
            e.stopPropagation();
            estReadMsg(this);
            }
          });
        }
      });
    
    $('.estMsgP').each(function(i,btn){
      if(!$(btn).hasClass('estEleBound')){
        $(btn).addClass('estEleBound').on({
          click : function(e){
            e.preventDefault();
            e.stopPropagation();
            }
          });
        }
      });
    
    if($('.estMsgBtn').length > 0){
      var likeEle = $('#estLikeIcon-'+$('#estateCont').data('pid'));
      
      $('.estViewMsg').each(function(i,btn){
        if(!$(btn).hasClass('estEleBound')){
          if($(btn).is('div')){
            $(btn).addClass('estEleBound').on({
              click : function(e){
                e.preventDefault();
                e.stopPropagation();
                $('.estMsgBtnBlock').removeClass('estMsgBtnAct');
                var targ = $(btn).parent().parent().find('div.estMsgP');
                
                var btnPar = $(btn).closest('div.estAdmMsgDiv');
                console.log(btnPar);
                if(btnPar.length > 0){
                  }
                
                if($(targ).is(':visible')){
                  $(targ).hide();
                  $('div.estAdmMsgDiv').removeClass('popover fade top in editable-container editable-popup estMsgAdmBlk');
                  }
                else{
                  $('div.estAdmMsgDiv').removeClass('popover fade top in editable-container editable-popup estMsgAdmBlk');
                  $('.estMsgP').hide();
                  $(btn).parent().addClass('estMsgBtnAct');
                  $(btn).closest('div.estAdmMsgDiv').addClass('popover fade top in editable-container editable-popup estMsgAdmBlk');
                  $(targ).show();
                  }
                }
              });
            }
          else{
            $(btn).addClass('estEleBound').on({
              click : function(e){
                e.preventDefault();
                e.stopPropagation();
                $('.estViewMsg').removeClass('estMsgBtnAct');
                var targ = $(btn).parent().find('div.estMsgP');
                if($(targ).is(':visible')){$(targ).hide();}
                else{
                  $('.estMsgP').hide();
                  $(targ).show();
                  $(btn).addClass('estMsgBtnAct');
                  }
                }
              });
            }
          }
        });
      }
    
    
    }
  
  
  function estPrevMsgBtns(){
    
    if(document.getElementById('estAdmMsgBoxes')){
      estBindMsgViewBtns();
      return;
      }
    
    
    if(document.getElementById('estInBoxCont')){
      if(!$('#estInBoxBtn').hasClass('estEleBound')){
        $('#estInBoxBtn').addClass('estEleBound').on({
          click :function(e){
            e.preventDefault();
            e.stopPropagation();
            if($('#estInBoxCont').is(':visible')){$('#estInBoxCont').hide();}
            else{$('#estInBoxCont').show();}
            }
          });
        }
      
      estBindMsgViewBtns();
      
      
      if(!$('#estInBoxCont').hasClass('estEleBound')){
        $('#estInBoxCont').addClass('estEleBound').find('div.estInBoxSect').each(function(i,sect){
          var blt = $(sect).find('div.estMsgBelt');
          
          $(sect).find('button.estSectBtn').on({
            click : function(e){
              e.preventDefault();
              e.stopPropagation();
              if($(blt).is(':visible')){
                $(blt).hide();
                }
              else{
                $('#estInBoxCont').find('div.estMsgBelt').hide();
                $(blt).show();
                }
              }
            });
          });
        }
    
      if(!$('#estPrevMsgBtn').hasClass('estEleBound')){
        $('#estPrevMsgBtn').addClass('estEleBound').on({
          click :function(e){
              e.preventDefault();
              e.stopPropagation();
            if($('#estMsgPrevBelt').is(':visible')){
              $('#estMsgPrevBelt').animate({'height':'0px'},500,'swing',function(){$('#estMsgPrevBelt').hide()});
              $('#estMsgPrevBelt').find('.estMsgP').hide();
              }
            else{
              $('#estMsgPrevBelt').show().animate({'height':'256px'},500,'swing');
              if($('#estMsgPrevBelt').find('.estMsgP').length == 1){$('#estMsgPrevBelt').find('.estMsgP').show();}
              }
            }
          });
        }
      }
    
    }
  
  
  
  function estMsgSys(){
    var estJSpth = $(document).data('estJSpth');
    if(typeof estJSpth == 'undefined'){
      $(document).data('estJSpth',$('#estInBoxCont').data('jspth'));
      }
    
    estPrevMsgBtns();
    
    if(document.getElementById('estMsgWarn')){
      $('#estMsgFormTabl').remove();
      return;
      }
    
    if(document.getElementById('estMsgFormTabl')){
      $('.estChkMsgRem').on({change : function(e){if(this.value !== 'itsatrap@'+$(this).data('domn')){$('#estMsgFormTabl').parent().remove();}}});
      $('#estMsgFormDiv').data({'txts':{'fng':$('#estMsgFNG').html(),'res1':$('#estMsgRes').html(),'send1':$('#estEmS1').html(),'txt0':$('#estEmSent0').html(),'txt1':$('#estEmSent1').html(),'txt2':$('#estEmSent1').html(),'txt3':$('#estPmSent').html(),'txt4':$('#estEmSent4').html(),'txt5':$('#estEmSent5').html(),'txt6':$('#estThks1').html(),'txt7':$('#estThks2').html()}});
      
      $('select[name="msg_mode"]').data({'m':['',$('#estT1').html(),$('#estT2').html(),$('#estT3').html(),''],'t':['',$('#estS1').html(),$('#estS2').html(),$('#estS3').html(),'']}).on({
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
      //$('#estMsgDefs').remove();
      }
    }
  
  
  
  
  
  $(document).ready(function(){
    estMsgSys();
    });

})(jQuery);