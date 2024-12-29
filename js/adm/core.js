// JavaScript Document



(function ($) {
  // All your code here.
  
  function estUpUserTR(tr,dta,agtDel=0){
    console.log('estUpUserTR');
    var defs = $('body').data('defs');
    var strPerms = '', strClass = '';
    $(tr).addClass('estFlipIt');
    
    
    var nPID = [], nClass = [];
    $(dta.user_perms).each(function(i,pid){
      if(pid !== ''){
        strPerms += (strPerms.length > 0 ? '.': '')+pid;
        nPID.push(pid);
        }
      }).promise().done(function(){
        $(dta.user_class).each(function(i,cid){
          if(cid !== ''){
            strClass += (strClass.length > 0 ? ',': '')+Number(cid);
            nClass.push(cid);
            }
          }).promise().done(function(){
            dta.user_perms = nPID;
            dta.user_class = nClass;
            if(Number(agtDel) > 0){agtDel = Number(dta.agent_idx);}
            
            $.ajax({
              url: vreFeud+'?14||0',
              type:'post',
              data:{'fetch':14,'rt':'js','user_id':dta.user_id,'user_admin':dta.user_admin,'user_class':strClass,'user_perms':strPerms,'agtdel':agtDel},
              dataType:'json',
              cache:false,
              processData:true,
              success: function(ret, textStatus, jqXHR){
                console.log(ret); 
                $(tr).removeClass('estFlipIt');
                if(ret.error){alert(ret.error);}
                else if(ret.updb == 1){
                  console.log(dta.user_name+': Success');
                  
                  if(Number(ret.agtdel) > 0){
                    dta.agent_idx = Number(0);
                    dta.agent_agcy = Number(0);
                    dta.agent_name = '';
                    dta.agent_lev = Number(0);
                    dta.agent_uid = Number(0);
                    dta.agent_image = '';
                    dta.agent_imgsrc = Number(0);
                    dta.agent_txt1 = '';
                    }
                  $(tr).data(dta);
                  }
                else{console.log(dta.user_name+': No Change');}
                
                console.log(dta);
                estSetUsrAdmnCB(tr);
                },
              error: function(jqXHR, textStatus, errorThrown){
                console.log('ERRORS: '+textStatus+' '+errorThrown);
                estAlertLog(jqXHR.responseText);
                }
              });
            });
        });
    
    }
  
  
  function estSetUserTR(tr,agtDel=0){
    var defs = $('body').data('defs');
    var plugid = defs.keys.plugid;
    var dta = $(tr).data();
    
    var upGo = 0, nPID = [], nClass = [];
    
    if($(dta.eles.admcb).is(':checked')){
      if($(tr).hasClass('estUserTbl2') && Number(dta.user_admin) !== 1){
        if(!jsconfirm(defs.txt.makeadmin)){
          $(dta.eles.admcb).prop('checked','').removeProp('checked');
          $(dta.eles.selEle).val('').change();
          return;
          }
        }
      
      $(dta.eles.selEle).prop('disabled',false).removeProp('disabled');
      var defCls = $(dta.eles.admcb).data('class');
      if(defCls == ''){defCls = dta.plgcls[0];}
      $(dta.eles.selEle).val(defCls).find('option').each(function(i,opt){
        if($(opt).val() == defCls){$(opt).prop('selected','selected');}
        else{$(opt).prop('selected','').removeProp('selected');}
        });
      
      
      dta.user_admin = 1;
      if(dta.user_perms.indexOf('0') > -1){var cbv = dta.plgcls[2];}
      else{
        var cbv = $(dta.eles.selEle).find('option:selected').val();
        if(dta.user_perms.indexOf(plugid) == -1){dta.user_perms.push(plugid);}
        }
      nClass.push(cbv);
      $(dta.user_class).each(function(i,cid){
        if(cid == dta.plgcls[2] || cid == dta.plgcls[1] || cid == dta.plgcls[0]){}
        else{nClass.push(cid);}
        }).promise().done(function(){
          dta.user_class = nClass;
          estUpUserTR(tr,dta,agtDel);
          });
      }
    else{
      if(dta.user_perms.indexOf('0') > -1){
        $(dta.eles.admcb).prop('checked','checked');
        $(dta.eles.selEle).val(dta.plgcls[2]).change();
        return;
        }
      else{
        $(dta.eles.selEle).prop('disabled',true);
        $(dta.eles.selEle).val('').find('option').prop('selected','').removeProp('selected');
        
        dta.user_admin = 0;
        var plgIx = dta.user_perms.indexOf(plugid);
        if(plgIx > -1){
          $(dta.user_perms).each(function(i,pid){
            if(pid !== plugid){nPID.push(pid);}
            }).promise().done(function(){
              dta.user_perms = nPID;
              if(nPID.length > 0){dta.user_admin = 1;}
              $(dta.user_class).each(function(i,cid){
                if(cid == dta.plgcls[2] || cid == dta.plgcls[1] || cid == dta.plgcls[0]){upGo++;}
                else{nClass.push(cid);}
                }).promise().done(function(){
                  dta.user_class = nClass;
                  if(upGo > 0){estUpUserTR(tr,dta,agtDel);}
                  });
              });
          }
        }
      }
    }
  
  
  function estSetUsrAdmnCB(tr){
    var defs = $('body').data('defs');
    var dta = $(tr).data();
    var plgGo = 0;
    var agtDel = 0;
    
    if($(dta.eles.admcb).is(':checked')){
      if(Number(dta.user_admin) == 0){
        $(dta.eles.admcb).prop('checked',false).removeProp('checked');
        $(dta.eles.agtBtn).prop('disabled',true);
        $(dta.eles.uAdmn).html('');
        if(Number(defs.prefs.owneragent) == 255){$(dta.eles.owncb).prop('checked',false).removeProp('checked');}
        else{$(dta.eles.owncb).prop('checked',true);}
        }
      else{
        $(dta.eles.agtBtn).prop('disabled',false).removeProp('disabled');
        $(dta.eles.owncb).prop('checked',true);
        if(dta.user_perms.indexOf('0') > -1){
          $(dta.eles.uAdmn).html(defs.txt.main+' '+defs.txt.admin);
          plgGo++;
          }
        else{
          $(dta.eles.uAdmn).html(defs.txt.admin);
          if(dta.user_perms.indexOf(defs.keys.plugid) > -1){plgGo++;}
          }
        }
      }
    else{
      if(Number(dta.user_admin) > 0){
        if(dta.user_perms.indexOf('0') > -1){
          $(dta.eles.uAdmn).html(defs.txt.main+' '+defs.txt.admin);
          plgGo++;
          }
        else{
          $(dta.eles.uAdmn).html(defs.txt.admin);
          if(dta.user_perms.indexOf(defs.keys.plugid) > -1){plgGo++;}
          }
        }
      else{
        $(dta.eles.uAdmn).html('');
        }
      }
    
    if(plgGo > 0){
      $(dta.eles.admcb).prop('checked',true);
      $(dta.eles.owncb).prop('checked',true);
      $(dta.eles.agtBtn).prop('disabled',false).removeProp('disabled');
      }
    else{
      $(dta.eles.agtBtn).prop('disabled',true);
      $(dta.eles.selEle).prop('disabled',true);
      if(Number(defs.prefs.owneragent) == 255){$(dta.eles.owncb).prop('checked',false).removeProp('checked');}
      else{$(dta.eles.owncb).prop('checked',true);}
      }
    
    if(Number(dta.agent_idx) > 0){
      $(dta.eles.agtBtn).attr('title',defs.txt.edit+' '+dta.agent_name);
      $(dta.eles.agtBtn).find('span').eq(0).html(defs.txt.agent+' '+dta.agent_name);
      if(dta.agency_name !== ''){$(dta.eles.agtBtn).find('span').eq(1).html(dta.agency_name);}
      else{$(dta.eles.agtBtn).find('span').eq(1).html('- - -');}
      if(Number(dta.agent_propct) > 0){
        $(dta.eles.agtBtn).find('span').eq(2).html(dta.agent_propct+' '+(Number(dta.agent_propct) == 1 ? defs.txt.listing : defs.txt.listings));
        }
      }
    else{
      $(dta.eles.agtBtn).attr('title',defs.txt.createagnt+' '+dta.user_name);
      $(dta.eles.agtBtn).find('span').eq(0).html(defs.txt.newagt);
      $(dta.eles.agtBtn).find('span').eq(1).html('- - -');
      }
    console.log(dta);
    
    }
  
  
  
  
  function estBindUserTR(tr){
    if(!$(tr).hasClass('estBound')){
      $(tr).addClass('estBound');
      
      var defs = $('body').data('defs');
      var plugid = defs.keys.plugid;
      var plgcls = [Number(defs.classes["ESTATE AGENT"]),Number(defs.classes["ESTATE MANAGER"]),Number(defs.classes["ESTATE ADMIN"])];
      
      var uAdmn = $(tr).find('div.estUsrAdmStat');
      var admcb = $(tr).find('input.estMainAdminCB');
      var owncb = $(tr).find('input.estOwnerPostCB');
      var selEle = $(tr).find('select.estAllUserClass');
      var agtBtn = $(tr).find('button.estUserAgentEdit');
      
      if((defs.user.xro.indexOf('0') == -1 && defs.user.xro.indexOf(plugid) == -1) || Number(defs.user.perm) < 2){
        $(admcb).remove();
        $(selEle).remove();
        $(agtBtn).remove();
        return;
        }
      
      var dta = $(tr).data();
      $.each(dta,function(k,v){$(tr).removeAttr('data-'+k);});
      $(tr).removeData();
      
      
      var up = dta.user_perms.toString();
      if(up.length > 0){up = (up.indexOf('.') > -1 ? up.split('.') : [up]);}
      else{up = [];}
      dta.user_perms = up;
      
      var uc = dta.user_class.toString();
      if(uc.length > 0){uc = (uc.indexOf(',') > -1 ? uc.split(',') : [uc]);}
      else{uc = [];}
      uc = uc.map(Number);
      
      var nClass = [], duc = null, upGo = 0;
      if(uc.indexOf(plgcls[0]) > -1){duc = [plgcls[0],plgcls[2],plgcls[1]];}
      if(uc.indexOf(plgcls[1]) > -1){duc = [plgcls[1],plgcls[2],plgcls[0]];}
      if(uc.indexOf(plgcls[2]) > -1 || dta.user_perms.indexOf('0') > -1){duc = [plgcls[2],plgcls[1],plgcls[0]];}
      $(uc).each(function(i,cid){
        if(duc !== null){
          if(cid == duc[0]){nClass.push(cid);}
          else if(duc.indexOf(cid) > -1){upGo++;}
          else{nClass.push(cid);}
          }
        else{nClass.push(Number(cid));}
        }).promise().done(function(){
          dta.user_class = nClass;
          
          $.extend(dta,{'eles':{'uAdmn':uAdmn,'admcb':admcb,'owncb':owncb,'selEle':selEle,'agtBtn':agtBtn},'plgcls':plgcls})
          
          $(tr).data(dta);
          
          $(agtBtn).on({
            click : function(e){
              e.preventDefault();
              var bDta = $(this).closest('tr').data();
              var url = vrePage+'?mode=estate_agencies&action=';
              if(Number(bDta.user_id) == Number(defs.user.user_id)){url += 'profile';}
              else{url += 'agent&id='+Number(bDta.user_id)+'.'+Number(bDta.agent_idx)+'.'+Number(bDta.agent_agcy);}
              window.location.assign(url);
              }
            });
          
          
          $(selEle).empty().promise().done(function(){
            if(Number(defs.user.user_id) == Number(dta.user_id)){
              $(admcb).prop('disabled',true);
              $(selEle).prop('disabled',true);
              
              if(Number(defs.user.perm) === 4){
                $(JQOPT,{'value':plgcls[2]}).prop('selected','selected').html(defs.userlevs[4]).appendTo(selEle);
                $(admcb).data('class',plgcls[2]).attr('title',defs.txt.nochangeadmin);
                $(selEle).attr('title',defs.txt.nochangeadmin);
                }
              else if(Number(defs.user.perm) === 3){
                $(JQOPT,{'value':plgcls[2]}).prop('selected','selected').html(defs.userlevs[3]).appendTo(selEle);
                $(admcb).data('class',plgcls[2]);
                if(dta.user_class.indexOf(plgcls[2]) == -1){upGo++;}
                }
              else if(Number(defs.user.perm) == 2){
                $(JQOPT,{'value':plgcls[1]}).prop('selected','selected').html(defs.userlevs[2]).appendTo(selEle);
                $(admcb).data('class',plgcls[1]);
                if(dta.user_class.indexOf(plgcls[1]) == -1){upGo++;}
                }
              }
            else{
              if(dta.user_perms.indexOf('0') > -1){
                $(JQOPT,{'value':plgcls[2]}).prop('selected','selected').html(defs.userlevs[4]).appendTo(selEle);
                $(selEle).attr('title',defs.txt.nochangeadmin).prop('disabled',true);
                $(admcb).attr('title',defs.txt.nochangeadmin).prop('disabled',true);
                }
              else{
                $(JQOPT,{'value':''}).html('- - -').appendTo(selEle);
                if(Number(defs.user.perm) > 3){
                  $(JQOPT,{'value':plgcls[2]}).html(defs.userlevs[3]).appendTo(selEle);
                  $(JQOPT,{'value':plgcls[1]}).html(defs.userlevs[2]).appendTo(selEle);
                  $(JQOPT,{'value':plgcls[0]}).html(defs.userlevs[1]).appendTo(selEle);
                  }
                else if(Number(defs.user.perm) > 2){
                  $(JQOPT,{'value':plgcls[1]}).html(defs.userlevs[2]).appendTo(selEle);
                  $(JQOPT,{'value':plgcls[0]}).html(defs.userlevs[1]).appendTo(selEle);
                  }
                else if(Number(defs.user.perm) > 1){
                  $(JQOPT,{'value':plgcls[0]}).html(defs.userlevs[1]).appendTo(selEle);
                  }
                }
                
              
              var nVal = '';
              var udb = 0;
              if(dta.user_perms.indexOf('0') > -1){
                nVal = plgcls[2];
                udb = (dta.user_class.indexOf(plgcls[2]) > -1 ? 0 : 1);
                }
              else if(dta.user_class.indexOf(plgcls[2]) > -1){nVal = plgcls[2];}
              else if(dta.user_class.indexOf(plgcls[1]) > -1){nVal = plgcls[1];}
              else if(dta.user_class.indexOf(plgcls[0]) > -1){nVal = plgcls[0];}
              else if(dta.user_perms.indexOf(defs.keys.plugid) > -1){
                nVal = plgcls[0];
                udb = (dta.user_class.indexOf(plgcls[0]) > -1 ? 0 : 1);
                }
              if(udb > 0){$(selEle).val(nVal).change(); upGo = 0;}
              else{$(selEle).val(nVal).find('option[value="'+nVal+'"]').prop('selected','selected');}
              
              $(admcb).data({'class':nVal,'perms':dta.user_perms}).on({
                click : function(){
                  if(Number(dta.user_id) == 1 || dta.user_perms.indexOf('0') > -1){
                    dta.user_admin = 1;
                    $(dta.eles.admcb).prop('checked',true);
                    alert(defs.txt.nochangeadmin);
                    return;
                    }
                  
                  var agtDel = 0;
                  if(!$(this).is(':checked')){
                    if(Number(dta.agent_idx) > 0){
                      if(jsconfirm('Click OK to delete Agent Profile')){agtDel++;}
                      }
                    }
                  estSetUserTR(tr,agtDel);
                  }
                });
              
              $(selEle).on({
                change : function(){
                  if($(this).val() === ''){$(admcb).prop('checked',false).removeProp('checked');}
                  else{$(admcb).data('class',$(this).val());}
                  estSetUserTR(tr);
                  }
                });
              }
            estSetUsrAdmnCB(tr);
            if(upGo > 0){
              estSetUserTR(tr);
              console.log(dta.user_name,dta.user_perms,dta.user_class);
              }
            });
          });
      }
    }
  
  
  function estPrepUserListTable(){
    var defs = $('body').data('defs');
    if(typeof defs !== 'undefined'){
      $('#estAgentUserTableDiv').find('tr.estUserData').each(function(i,tr){
        estBindUserTR(tr);
        });
      return;
      }
    $.ajax({
      url: vreFeud+'?0||0',
      type:'get',
      data:{'fetch':98,'propid':0,'rt':'js','tbl':''},
      dataType:'json',
      cache:false,
      processData:true,
      success: function(defs, textStatus, jqXHR){
        console.log(defs);
        if(typeof defs !== 'undefined' && defs !== null){
          if(typeof defs.error !== 'undefined'){alert(defs.error);}
          else{
            var yc = defs.user.user_class.toString();
            if(yc.length > 0){defs.user.user_class = (yc.indexOf(',') > -1 ? yc.split(',') : [yc]);}
            else{defs.user.user_class = [];}
            
            var yp = defs.user.xro.toString();
            if(yp.length > 0){defs.user.xro = (yp.indexOf('.') > -1 ? yp.split('.') : [yp]);}
            else{defs.user.xro = [];}
            
            $('body').data({'defs':defs,'propid':0}).promise().done(function(){
              $('#estAgentUserTableDiv').find('tr.estUserData').each(function(i,tr){
                estBindUserTR(tr);
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
  
  
  
  
  function estPrepAgencyListTable(){
    console.log('estPrepAgencyListTable');
    var defs = $('body').data('defs');
    $('#estLocFltr1').append($('#estAddrOpts').html()).on({
      change : function(e){
        var fltr = this.value;
        if(fltr == ''){$('#estAgyListTB > tr').show();}
        else{
          $('#estAgyListTB > tr').hide().promise().done(function(){
            $('#estAgyListTB tr[data-addr="'+fltr+'"]').show();
            });
          }
        }
      });
    
    $('#estNewAgencyBtn').on({
      click :function(e){
        e.preventDefault();
        window.location.assign(vrePage+'?mode=estate_agencies&action=edit');
        }
      });
    
    $('.estAgyVisibBtn').each(function(i,btn){
      $(btn).on({
        click :function(e){
          e.preventDefault();
          if(Number($(btn).val()) == 1){
            $(btn).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
            var nval = 0;
            }
          else{
            $(btn).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
            var nval = 1;
            }
          $(btn).val(nval).closest('tr').addClass('estFlipIt');
          
          var tdta = {'maintbl':'estate_agencies','mainkey':'agency_idx','mainkx':'int','mainidx':Number($(btn).data('idx')),'mainfld':'agency_pub','nval':nval};
          
          $.ajax({
            url: vreFeud+'?6||0',
            type:'post',
            data:{'fetch':6,'propid':0,'rt':'js','tdta':tdta},
            dataType:'json',
            cache:false,
            processData:true,
            success: function(ret, textStatus, jqXHR){
              $(btn).closest('tr').removeClass('estFlipIt');
              },
            error: function(jqXHR, textStatus, errorThrown){
              $(btn).closest('tr').removeClass('estFlipIt');
              console.log('ERRORS: '+textStatus+' '+errorThrown);
              estAlertLog(jqXHR.responseText);
              }
            });
          }
        });
      });
    }
  
  
  function estPrepAgentProfile(aTbl){
    console.log('estPrepAgentProfile');
    var eImg = $('#agtAvatar').find('img.estSecretImg');
    var levdta = $(aTbl).data();
    $.each(levdta,function(k,v){$(aTbl).removeAttr('data-'+k);});
    $(aTbl).removeData();
    
    var estAgentForm = $(aTbl).closest('form');
    var fDta = $(estAgentForm).data();
    $.each(fDta,function(k,v){$(estAgentForm).removeAttr('data-'+k);});
    $(estAgentForm).data('levdta',levdta);
    
    $(eImg).on({load : function(e){estAvatarWH(e.target);}});
    
    $.ajax({
      url: vreFeud+'?0||0',
      type:'get',
      data:{'fetch':98,'propid':0,'rt':'js','tbl':''},
      dataType:'json',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        if(typeof ret !== 'undefined' && ret !== null){
          if(typeof ret.error !== 'undefined'){alert(ret.error);}
          else{
            $('body').data({'defs':ret,'propid':0}).promise().done(function(){
              estBindContactTables();
              estBindFileUplFld(6);
              
              $('#agtAvatar').on({
                mouseenter : function(e){estMediaEditBtns(9,e.target)},
                mouseleave : function(e){estMediaEditBtns(-2,e.target);}
                });
              
              estAvatarWH(eImg);
              estSetAgentImg(6);
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
  


  
  
  //lostresult
  function estPrepPropAgent(){
    var defs = $('body').data('defs');
    
    $('#estAgentContDiv').width($('input[name="prop_name"]').outerWidth());
    if(Number($('input[name="prop_agency"]').val()) == 0){
      $('input[name="prop_agency"]').val(Number($('#estAgencySelBtn').data('agcy')));
      }
    $('#estAgentOptsCont').css({'top':$('#estAgentContDiv').outerHeight(true)+'px'});
    
    if(Number(defs.user.perm) > 1){
      $('#estAgencySelBtn').on({click : function(){estSHAgentSelect(0,this)}});
      $('#estAgentSelBtn').on({click : function(){estSHAgentSelect(0,this)}});
      estBuildAgencyList();
      }
    else{estSetAgtMiniPic();}
    }
  
  
  
  
  
  function estPresetDtaSw(swt){
    if(!$(swt).hasClass('disab')){
      var ul = $(swt).data('ul');
      if($(swt).hasClass('actv')){
        $(swt).removeClass('actv').addClass('inact');
        $($(swt).data('feature_ele')).val(0);
        $(ul).removeClass('actv').addClass('inact');
        $(ul).find('input[type="checkbox"]').prop('disabled',true);
        $(ul).find('li').addClass('inact').promise().done(function(){
          $(ul).find('button').hide();
          estPresetsNIOpt(ul);
          });
        }
      else{
        $(swt).removeClass('inact').addClass('actv');
        $($(swt).data('feature_ele')).val(1);
        $(ul).removeClass('inact').addClass('actv').show();
        $(ul).find('input[type="checkbox"]').prop('disabled',false).removeProp('disabled');
        $(ul).find('li').removeClass('inact').promise().done(function(){
          $(ul).find('button').show();
          estPresetsNIOpt(ul);
          });
        }
      }
    }
  
  
  
  function estPresetsNIOpt(ul){
    var feature_opts = $(ul).data('feature_opts');
    if($(ul).hasClass('inact')){$(feature_opts).val('');}
    else{
      var vals = '';
      $(ul).find('li.estPresetDataLI3 a').each(function(i,lele){
        vals += ($(lele).text().length > 1 ? (vals.length > 1 ? ',' : '')+$(lele).text() : '');
        }).promise().done(function(){
          $(feature_opts).val(vals);
          });
      }
    }
  
  
  
  function estPresetsNewItem(lev=0,targ){
    var defs = $('body').data('defs');
    console.log(lev);
    if(lev == 3){
      $(targ).parent().parent().find('div.estPresetsDataSw').removeClass('inact').addClass('actv');
      var leapf = $(targ).find('button');
      
      var nLI3 = $('<li></li>',{'class':'estPresetDataLI3'}).on({
        click : function(e){e.stopPropagation();}
        }).appendTo(targ);
      
      
      $(JQNPT,{'type':'checkbox','value':'1','checked':'checked'}).on({
        click : function(e){
          e.stopPropagation();
          $(this).parent().remove().promise().done(function(){
            estPresetsNIOpt(targ);
            });
          }
        }).appendTo(nLI3);
      
      $('<a></a>',{'contenteditable':'true'}).html(defs.txt.new1+' '+defs.txt.option).on({
        click : function(e){
          e.stopPropagation();
          estPresetsNIOpt(targ);
          },
        keyup : function(){estPresetsNIOpt(targ);},
        blur : function(){estPresetsNIOpt(targ);}
        }).appendTo(nLI3).promise().done(function(){
          $(leapf).appendTo(targ);
          });
      }
    else if(lev == 1 || lev == 2){
      var nKey = $(targ).parent().data('nkey');
      var newCt = Number($(targ).parent().children('.estPresetDataLI2').length) -1;
      
      var nLI2 = $('<li></li>',{'class':'estPresetDataLI2'});
      $(targ).before(nLI2);
      
      var pret = (lev == 1 ? 'new': 'add');
      
      var feature_keep = $(JQNPT,{'type':'checkbox','value':'1','checked':'checked'}).on({
        click :function(e){
          e.stopPropagation();
          $(nLI2).remove();
          //estPresetsDataXClk(2,this);
          }
        }).appendTo(nLI2);
      var feature_name = $(JQNPT,{'type':'hidden','name': pret+'_feature_name'+nKey+'[]','value':''}).appendTo(nLI2);
      var feature_ele = $(JQNPT,{'type':'hidden','name': pret+'_feature_ele'+nKey+'[]','value':'0'}).appendTo(nLI2);
      var feature_opts = $(JQNPT,{'type':'hidden','name': pret+'_feature_opts'+nKey+'[]','value':''}).appendTo(nLI2);
      
      var nLI3 = $(JQBTN,{'class':'btn btn-primary btn-sm'}).html(' + '+defs.txt.option);
      
      var nA2 = $('<a></a>',{'contenteditable':'true'}).html(defs.txt.new1+' '+defs.txt.item).on({
        keyup : function(){
          $(feature_name).val($(this).text());
          //$(nLI3).html(' + '+$(this).text()+' '+defs.txt.option);
          },
        blur : function(){
          $(feature_name).val($(this).text());
          //$(nLI3).html(' + '+$(this).text()+' '+defs.txt.option);
          }
        }).appendTo(nLI2);
      
      var ul2 = $('<ul></ul>',{'class':'estPresetListLI2ul'}).data({'feature_opts':feature_opts,'nkey':nKey+'[0]'}).appendTo(nLI2);
      
      $(nLI3).on({
        click : function(e){
          e.stopPropagation();
          e.preventDefault();
          estPresetsNewItem(3,ul2);
          }
        }).appendTo(ul2).hide();
      
      var swtch = $(JQDIV,{'class':'estPresetsDataSw inact','title':'Enable Options'}).data({'feature_ele':feature_ele,'feature_opts':feature_opts,'ul':ul2}).html('<div></div>').on({
        click :function(e){
          e.stopPropagation();
          estPresetDtaSw(this);
          }
        }).appendTo(nLI2);
      
      estPresetsDataTog(2,nLI2);
      }
    }
  
  
  
  
  function estPresetsDataTog(lev,ele){
    var levs = ['','.estPresetListLI1ul','.estPresetListLI2ul'];
    var ul = $(ele).children('ul').eq(0);
    $(ele).on({
      click : function(e){
        e.stopPropagation();
        if(!$(ele).hasClass('disab')){
          if($(ul).is(':visible')){$(ul).hide();}
          else{
              if(!$(ul).hasClass('inact')){$(ul).show();}
            }
          }
        }
      });
    }
  
  
  function estPresetsDataXClk(mode,ele){
    var li = $(ele).parent();
    var ul = $(ele).parent().parent();
    if(mode == 1){
      if($(ele).is(':checked')){
        $(li).removeClass('inact').addClass('actv');
        $(li).find('div.estPresetsDataSw').removeClass('disab');
        $(li).find('ul.estPresetListLI1ul').children('li.estPresetDataLI2').removeClass('inact').addClass('actv');
        $(li).find('ul.estPresetListLI1ul').children('li.estPresetDataLI2').find('input[type="checkbox"]').prop('disabled',false).removeProp('disabled').prop('checked','checked');
        $(ul).find('button').show();
        }
      else{
        $(li).removeClass('actv').addClass('inact');
        $(li).find('div.estPresetsDataSw').addClass('disab');
        $(li).find('ul.estPresetListLI1ul').children('li.estPresetDataLI2').find('input[type="checkbox"]').prop('disabled',true);
        $(ul).find('button').hide();
        }
      }
    else if(mode == 2){
      if($(ele).is(':checked')){
        $(li).removeClass('inact').addClass('actv');
        $(li).find('div.estPresetsDataSw').removeClass('disab');
        $(ul).find('button').show();
        }
      else{
        $(li).removeClass('actv').addClass('inact');
        $(li).find('div.estPresetsDataSw').addClass('disab');
        $(ul).find('button').hide();
        }
      }
    else if(mode == 3){
      if($(ele).is(':checked')){
        $(li).removeClass('inact').addClass('actv');
        }
      else{
        $(li).removeClass('actv').addClass('inact');
        }
      }
    }
  
  function estPresetsULShift(cont){
    var mx = $(cont).data('mx');
    var uls = $(cont).find('ul.estPresetsListUL');
    $(uls).each(function(i,ul){
      var ct1 = $(ul).find('li.estPresetDataLI1').length;
      if(ct1 < mx){
        var nextUL = uls[i+1];
        if(typeof nextUL !== 'undefined'){
          var ct2 = $(nextUL).find('li.estPresetDataLI1').length;
          for(xi = 0; xi < (mx-ct1); xi++){$(nextUL).find('li.estPresetDataLI1').eq(xi).appendTo(ul);}
          }
        }
      }).promise().done(function(){
        $(uls).each(function(i,ul){
          if($(ul).is(':empty')){$(ul).parent().remove();}
          });
        });
    
    }
  
  
  function estPresetsAddListType(mode,dta){
    var defs = $('body').data('defs');
    var cont = $('#estPresetsListTypeCont-'+dta.zi).data('mx',Number(dta.mx));
    var newCt = Number($(cont).find('.estNewListType').length);
    var xTarg = $(cont).find('ul.estPresetsListUL').last();
    var lict = $(xTarg).find('li.estPresetDataLI1').length;
    
    if(lict < Number(dta.mx)){var targ = xTarg;}
    else{
      var d1 = $(JQDIV,{'class':'estPresetsListDiv'}).appendTo(cont);
      var targ = $('<ul></ul>',{'class':'estPresetsListUL'}).appendTo(d1);
      }
    
    var nLI = $('<li></li>',{'class':'estPresetDataLI1'}).appendTo(targ);
    
    $(JQNPT,{'type':'checkbox','value':'1','checked':'checked'}).on({
      click : function(e){
        e.stopPropagation();
        $(nLI).remove().promise().done(function(){
          estPresetsULShift(cont);
          });
        }
      }).appendTo(nLI);
    
    if(mode == 2){
      var listype_name = $(JQNPT,{'type':'hidden','name':'imp_listype_name['+dta.zi+']['+newCt+']','class':'estNewListType','value':''});
      }
    else{
      var listype_name = $(JQNPT,{'type':'hidden','name':'new_listype_name['+dta.zi+']['+newCt+']','class':'estNewListType','value':''});
      }
    
    $(listype_name).appendTo(nLI);
    
    $('<a></a>',{'contenteditable':'true'}).html(defs.txt.new1+' '+defs.txt.listype).on({
      keyup : function(){$(listype_name).val($(this).text());},
      blur : function(){$(listype_name).val($(this).text());}
      }).appendTo(nLI);
    
    
    }
  
  
  function estPrepPresetData(){
    var defs = $('body').data('defs');
    
    $('.estPresetsNewListTypeBtn').each(function(ni,btn){
      if(!$(btn).hasClass('estBound')){
        $(btn).addClass('estBound').data({'zi':$(btn).data('zi'),'mx':$(btn).data('mx')}).on({
          click : function(e){
            e.stopPropagation();
            e.preventDefault();
            estPresetsAddListType(1,$(this).data());
            }
          }).removeAttr('data-zi').removeAttr('data-mx');
        }
      });
    
    $('.estPresetsNewGroupBtn').each(function(ni,btn){
      if(!$(btn).hasClass('estBound')){
        $(btn).addClass('estBound').data({'zi':$(btn).data('zi'),'lk':$(btn).data('lk'),'mx':$(btn).data('mx')}).on({
          click : function(e){
            e.stopPropagation();
            e.preventDefault();
            var dta = $(this).data();
            var cont = $('#estPresetsGroupCont-'+dta.zi+'-'+dta.lk).data('mx',Number(dta.mx));
            var newCt = Number($(cont).find('.estNewGroup').length);
            var xTarg = $(cont).find('ul.estPresetsListUL').last();
            var lict = $(xTarg).find('li.estPresetDataLI1').length;
            if(lict < Number(dta.mx)){var targ = xTarg;}
            else{
              var d1 = $(JQDIV,{'class':'estPresetsListDiv'}).appendTo(cont);
              var targ = $('<ul></ul>',{'class':'estPresetsListUL'}).appendTo(d1);
              }
            
            var nLI = $('<li></li>',{'class':'estPresetDataLI1'}).appendTo(targ);
            
            $(JQNPT,{'type':'checkbox','value':'1','checked':'checked'}).on({
              click : function(e){
                e.stopPropagation();
                $(nLI).remove().promise().done(function(){
                  estPresetsULShift(cont);
                  });
                }
              }).appendTo(nLI);
          
            var group_name = $(JQNPT,{'type':'hidden','name':'new_group_name['+dta.zi+']['+dta.lk+']['+newCt+']','class':'estNewGroup','value':''}).appendTo(nLI);
            
            $('<a></a>',{'contenteditable':'true'}).html(defs.txt.new1+' '+defs.txt.group1).on({
              keyup : function(){$(group_name).val($(this).text());},
              blur : function(){$(group_name).val($(this).text());}
              }).appendTo(nLI);
            }
          }).removeAttr('data-zi').removeAttr('data-mx');
        }
      });
    
    
    $('.estPresetsNewBtn').each(function(ni,btn){
      if(!$(btn).hasClass('estBound')){
        $(btn).addClass('estBound').data({'zi':$(btn).data('zi'),'lk':$(btn).data('lk'),'mx':$(btn).data('mx')}).on({
          click : function(e){
            e.stopPropagation();
            e.preventDefault();
            var dta = $(this).data();
            
            $('.estPresetListLI1ul').hide();
            $('.estPresetListLI2ul').hide();
            var cont = $('#estPresetsListCont-'+dta.zi+'-'+dta.lk);
            var newCt = Number($(cont).find('.estNewFeatCat').length);
            var xTarg = $(cont).find('ul.estPresetsListUL').last();
            var lict = $(xTarg).find('li.estPresetDataLI1').length;
            
            if(lict < Number(dta.mx)){var targ = xTarg;}
            else{
              var d1 = $(JQDIV,{'class':'estPresetsListDiv'}).appendTo(cont);
              var targ = $('<ul></ul>',{'class':'estPresetsListUL'}).data('nkey','['+dta.zi+']['+dta.lk+']').appendTo(d1);
              }
            
            var nKey = '['+dta.zi+']['+dta.lk+']['+newCt+']';
            var nLI = $('<li></li>',{'class':'estPresetDataLI1'}).appendTo(targ);
            
            $(JQNPT,{'type':'checkbox','name':'new_featcat_keep'+nKey,'value':'1','checked':'checked'}).on({
              click : function(e){
                e.stopPropagation();
                estPresetsDataXClk(1,this);
                }
              }).appendTo(nLI);
            
            var featcat_name = $(JQNPT,{'type':'hidden','name':'new_featcat_name'+nKey,'class':'estNewFeatCat','value':''}).appendTo(nLI);
            
            var nLI2 = $(JQBTN,{'class':'btn btn-primary btn-sm'}).data('nkey',nKey).html(' + '+defs.txt.item).on({
              click : function(e){
                e.stopPropagation();
                e.preventDefault();
                estPresetsNewItem(1,this);
                }
              });
            
            $('<a></a>',{'contenteditable':'true'}).html(defs.txt.new1+' '+defs.txt.category).on({
              keyup : function(){$(featcat_name).val($(this).text());},
              blur : function(){$(featcat_name).val($(this).text());}
              }).appendTo(nLI);
            
            var ul1 = $('<ul></ul>',{'class':'estPresetListLI1ul'}).data('nkey',nKey).appendTo(nLI).hide();
            $(nLI2).appendTo(ul1);
            estPresetsDataTog(1,nLI);
            }
          }).removeAttr('data-zi').removeAttr('data-lk').removeAttr('data-mx');
        }
      });
    
    
    $('.estPresetsListUL').each(function(i,ul){
      if(!$(ul).hasClass('estBound')){
        $(ul).addClass('estBound').data('nkey',$(ul).attr('data-nkey')).removeAttr('data-nkey');
        }
      
      $(ul).find('li.estPresetDataLI1').each(function(xi,LI1){
        if(!$(LI1).hasClass('estBound')){
          $(LI1).addClass('estBound');
          var featcat_name = $(LI1).find('input[type="hidden"]').eq(0);
          $(LI1).find('input[type="checkbox"]').eq(0).on({
            click : function(e){
              e.stopPropagation();
              estPresetsDataXClk(1,this);
              }
            });
          $(LI1).find('a').eq(0).on({
            keyup : function(){$(featcat_name).val($(this).text())},
            blur : function(){$(featcat_name).val($(this).text());}
            });
          $(LI1).find('button.estNopt1').on({
            click : function(e){
              e.stopPropagation();
              e.preventDefault();
              estPresetsNewItem(2,this);
              }
            });
          }
        
        var ul1 = $(LI1).find('ul.estPresetListLI1ul');
        if(!$(ul1).hasClass('estBound')){
          $(ul1).addClass('estBound').data('nkey',$(ul1).attr('data-nkey')).removeAttr('data-nkey');
          }
        
        $(ul1).find('li.estPresetDataLI2').each(function(yi,LI2){
          if(!$(LI2).hasClass('estBound')){
            $(LI2).addClass('estBound');
            
            var cb2 = $(LI2).find('input[type="checkbox"]').eq(0);
            var a2 = $(LI2).find('a').eq(0);
            var btn3 = $(LI2).find('button.estNopt2');
            var ul2 = $(LI2).find('ul.estPresetListLI2ul');
            var feature_name = $(LI2).find('input[type="hidden"]').eq(0);
            var feature_ele = $(LI2).find('input[type="hidden"]').eq(1);
            var feature_opts = $(LI2).find('input[type="hidden"]').eq(2);
            var swt = $(LI2).find('div.estPresetsDataSw');
            
            $(cb2).on({
              click : function(e){
                e.stopPropagation();
                estPresetsDataXClk(2,this);
                }
              });
            
            $(a2).on({
              keyup : function(){$(feature_name).val($(this).text())},
              blur : function(){$(feature_name).val($(this).text());}
              });
              
            $(btn3).on({
              click : function(e){
                e.stopPropagation();
                e.preventDefault();
                estPresetsNewItem(3,ul2);
                }
              });
            
            
            $(swt).data({'feature_ele':feature_ele,'feature_opts':feature_opts,'ul':ul2}).on({
              click : function(e){
                e.stopPropagation();
                estPresetDtaSw(this);
                }
              });
            
            $(ul2).data({'feature_opts':feature_opts});
            }
            
          
          $(ul2).find('li.estPresetDataLI3').each(function(vi,LI3){
            if(!$(LI3).hasClass('estBound')){
              $(LI3).addClass('estBound').on({click : function(e){e.stopPropagation();}});
              $(LI3).find('input[type="checkbox"]').on({
                click : function(e){
                  e.stopPropagation();
                  $(this).parent().remove().promise().done(function(){
                    estPresetsNIOpt(ul2);
                    });
                  }
                });
              
              $(LI3).find('a').on({
                click : function(e){
                  e.stopPropagation();
                  estPresetsNIOpt(ul2);
                  },
                keyup : function(){estPresetsNIOpt(ul2);},
                blur : function(){estPresetsNIOpt(ul2);}
                });
              }
            }).promise().done(function(){
              estPresetsDataTog(2,LI2);
              });
          }).promise().done(function(){
            estPresetsDataTog(1,LI1);
            });
        });
      });
    }
  
  
  
  
  
  
  
  
  
  //estPresetsCurZoningCont estPresetsCurZoneDivCont
  function estPresetsZoneSave(tdta){
    var defs = $('body').data('defs');
    if(tdta.newzones.length > 0 || tdta.curzones.length > 0 || tdta.delzones.length > 0){
      $('#estPresetsZoningPopover').hide();
      console.log(tdta);
      $.ajax({
        url: vreFeud+'?60||0',
        type:'post',
        data:tdta,
        dataType:'text',
        cache:false,
        processData:true,
        success: function(ret, textStatus, jqXHR){
          var upZones = [];
          $('#estPresetsDataCont').html(ret).promise().done(function(){
            var delZoneRes = $('.estDelZoneDta');
            console.log(delZoneRes);
            if(delZoneRes.length > 0){
              if(document.getElementById('estDelZDiv')){
                $('#estDelZDiv').empty().promise().done(function(){
                  $('.estDelZoneDta').each(function(di,dele){
                    $(dele).on({click : function(){$(this).remove();}}).appendTo('#estDelZDiv');
                    });
                  });
                }
              else{
                var pTop = $('body').find('div.admin-main-content').find('div.block-text');
                $(JQDIV,{'id':'estDelZDiv','class':'div.s-message'}).prependTo(pTop).promise().done(function(){
                  $('.estDelZoneDta').each(function(di,dele){
                    $(dele).on({click : function(){$(this).remove();}}).appendTo('#estDelZDiv');
                    });
                  });
                }
              }
            
            //estPresetsSaveZones
            $('#estPresetsCurZoningCont').empty().promise().done(function(){
              $('select[name="preset_zoneSelect"]').empty().promise().done(function(){
                $('#estPresetsDataCont').find('option.estNewZoneopt').each(function(ni,newOpt){
                  $(newOpt).appendTo($('select[name="preset_zoneSelect"]'));
                  }).promise().done(function(){
                    $('select[name="preset_zoneSelect"]').find('option').each(function(oi,opt){
                      upZones.push({'zoning_idx':$(opt).val(),'zoning_name':$(opt).html()});
                      var dcont = $(JQDIV,{'class':'estPresetsCurZoneDivCont'}).appendTo('#estPresetsCurZoningCont');
                      $(JQNPT,{'type':'checkbox','name':'cur_zoning_keep['+$(opt).val()+']','class':'estCurZoneKeepCB','value':$(opt).val(),'checked':'checked'}).appendTo(dcont);
                      $(JQNPT,{'type':'text','name':'zoning_name['+$(opt).val()+']','class':'tbox form-control input-xlarge ILBLK ui-state-valid','value':$(opt).html()}).attr('data-idx',$(opt).val()).appendTo(dcont);
                      }).promise().done(function(){
                        defs.tbls.estate_zoning.dta = upZones;
                        $('body').data('defs',defs);
                        estAgencyFormOn();
                        });
                    });
                });
              });
            });
          },
        error: function(jqXHR, textStatus, errorThrown){
          console.log('ERRORS: '+textStatus+' '+errorThrown);
          estAlertLog(jqXHR.responseText);
          }
        });
      }
    else{alert(defs.txt.nochanges2+' '+defs.txt.zoning+' '+defs.txt.categories);}
    }
  
  
  function estPresetsZoneProc(){
    var defs = $('body').data('defs');
    var tdta = {'fetch':60,'newzones':[],'curzones':[],'delzones':[]};
    $('#estPresetsCurZoningCont').find('div.estPresetsNewZoneCont').each(function(i,ele){
      var fld = $(ele).find('input[type="text"]');
      if($(fld).val().length > 0){tdta.newzones.push($(fld).val());}
      else{$(ele).remove();}
      }).promise().done(function(){
        
        $('#estPresetsCurZoningCont').find('div.estPresetsCurZoneDivCont').each(function(i,ele){
          var cb = $(ele).find('input[type="checkbox"]');
          var fld = $(ele).find('input[type="text"]');
          var idx = Number($(fld).attr('data-idx'));
          var zname = $(fld).val();
          if($(cb).is(':checked')){
            if(zname.length > 0){
              var zoneDta = defs.tbls.estate_zoning.dta.find(x => Number(x.zoning_idx) === idx);
              if(typeof zoneDta !== 'undefined'){
                var zx = defs.tbls.estate_zoning.dta.indexOf(zoneDta);
                var zoneName = defs.tbls.estate_zoning.dta[zx].zoning_name;
                defs.tbls.estate_zoning.dta[zx].zoning_name = zname;
                if(zoneName !== zname){tdta.curzones.push({'idx':idx,'txt':zname});}
                }
              else{tdta.newzones.push(zname);}
              }
            else{
              $(cb).click();
              tdta.delzones.push({'idx':idx,'txt':zname});
              }
            }
          else{tdta.delzones.push({'idx':idx,'txt':zname});}
          }).promise().done(function(){
            if(tdta.delzones.length > 0){
              if(jsconfirm('Delete '+tdta.delzones.length+' Zoning Categories and Associated Preset Data?')){
                estPresetsZoneSave(tdta);
                }
              else{
                $('#estPresetsCurZoningCont').find('div.estPresetsCurZoneDivCont').each(function(i,ele){
                  var cb = $(ele).find('input[type="checkbox"]');
                  var tfld = $(ele).find('input[type="text"]');
                  });
                }
              }
            else{
              estPresetsZoneSave(tdta);
              }
            });
        });
    }
  
  
  
  
  function estAgencyFormOn(){
    $('#estPresetsCurZoningCont').find('div.estPresetsCurZoneDivCont').each(function(i,ele){
      var cb = $(ele).find('input[type="checkbox"]');
      var tfld = $(ele).find('input[type="text"]');
      $(tfld).data('pval',$(tfld).val());
      if(!$(tfld).hasClass('estBound')){
        $(tfld).addClass('estBound').on({
          blur : function(){
            if($(tfld).val().length < 2){
              $(tfld).val('').prop('disabled',true);
              $(cb).prop('checked',false);
              }
            }
          });
        }
      
      if(!$(cb).hasClass('estBound')){
        $(cb).addClass('estBound').on({
          click : function(){
            if($(this).is(':checked')){
              $(tfld).prop('disabled',false).removeProp('disabled');
              if($(tfld).val().length < 2){$(tfld).val($(tfld).data('pval'));}
              }
            else{$(tfld).prop('disabled',true);}
            }
          });
        }
      });
    
    $('select[name="preset_zoneSelect"]').change();
    
    
    
    $('.estAgntUserBtn').each(function(i,btn){
      if(!$(btn).hasClass('estBound')){
        var bDta = $(btn).data();
        $.each(bDta,function(k,v){$(btn).removeAttr('data-'+k);});
        $(btn).data(bDta).addClass('estBound').on({
          click : function(e){
            e.preventDefault();
            estCompAgentAssign(1,this);
            }
          });
        }
      });
    estContactBtns();
    }
  
  
  function estContactBtns(){
    
    $('.estContBtn').each(function(i,btn){
      if(!$(btn).hasClass('estBound')){
        $(btn).addClass('estBound').on({
          click : function(e){
            e.preventDefault();
            var btn = this;
            if($(btn).hasClass('btn-primary')){
              var flds = $('.contact_data-'+$(btn).attr('data-targ'));
              console.log(flds);
              var fVal = '';
              $(flds).each(function(i,fEle){
                fVal += (fVal.length > 0 ? ' & ' : '')+$(fEle).val();
                }).promise().done(function(){
                  if(fVal.length > 0){
                    if(jsconfirm('Delete "'+fVal+'"?')){
                      $(flds).each(function(i,fEle){$(fEle).val('');});
                      $(btn).removeClass('btn-primary').addClass('btn-default');
                      $('#estContTR-'+$(btn).attr('data-targ')).fadeOut();
                      }
                    }
                  else{
                    $(btn).removeClass('btn-primary').addClass('btn-default');
                    $('#estContTR-'+$(btn).attr('data-targ')).fadeOut();
                    }
                  });
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
        }
      });
    }
  
  
  
  
  
  
  
  
  function estUsrSelectEleChange(ele){
    console.log('estUsrSelectEleChange');
    var frm = $(ele).closest('form');
    var frmDta = $(frm).data('levdta');
    var defdta = $(frm).data('defdta');
    var nDta = $(ele).find('option:selected').data();
    console.log(defdta);
    console.log(frmDta);
    switch($(ele).attr('id')){
      case 'agent-uid' :
        if(Number(defdta.agent_idx) > 0 && Number(defdta.agent_uid) !== Number(nDta.user_id)){
          if(!jsconfirm('Replace '+frmDta.agent_name+' with '+nDta.user_name+'?')){
            $(ele).val(defdta.agent_uid).change();
            return;
            }
          }
        
        if(Number(defdta.agent_uid) == Number(nDta.user_id)){
          frmDta.agent_image = defdta.agent_image;
          frmDta.agent_imgsrc = Number(defdta.agent_imgsrc);
          frmDta.agent_lev = Number(defdta.agent_lev);
          frmDta.agent_name = defdta.agent_name;
          frmDta.agent_txt1 = defdta.agent_txt1;
          }
        else{
          frmDta.agent_image = '';
          frmDta.agent_imgsrc = 0;
          frmDta.agent_lev = 1;
          frmDta.agent_name = nDta.user_name;
          frmDta.agent_txt1 = nDta.user_signature;
          }
        //frmDta.agent_agcy
        //frmDta.agent_idx
        frmDta.agent_uid = Number(nDta.user_id);
        
        frmDta.user_class = nDta.user_class;
        frmDta.user_email = nDta.user_email;
        frmDta.user_id = Number(nDta.user_id);
        frmDta.user_image = nDta.user_image;
        frmDta.user_loginname = nDta.user_loginname;
        frmDta.user_name = nDta.user_name;
        frmDta.user_profimg = nDta.user_profimg;
        frmDta.user_signature = nDta.user_signature;
        
        $(frm).data('levdta',frmDta);
        $('input[name="agent_image"]').val(frmDta.agent_image);
        $('input[name="agent_altimg"]').html(frmDta.user_profimg);
        $('input[name="agent_imgsrc"]').val(frmDta.agent_imgsrc);
        $('input[name="agent_lev"]').val(frmDta.agent_lev);
        $('input[name="agent_name"]').val(frmDta.agent_name);
        $('input[name="agent_txt1"]').html(frmDta.agent_txt1);
        
        
        estSetAgentImg(6);
        break;
      }
    }
  
  
  function estBindContactTables(){
    //console.log('estBindContactTables');
    
    $('table.estContTabl').each(function(i,tabl){
      if(!$(tabl).hasClass('estBound')){
        $(tabl).addClass('estBound');
        var tbody = $(tabl).find('tbody');
        var tfoot = $(tabl).find('tfoot');
        var cDta = $(tabl).data();
        $.each(cDta,function(k,v){$(tabl).removeAttr('data-'+k);});
        $(tabl).data(cDta);
        
        $(tabl).find('tr').each(function(tri,tr){
          estBindContactTR(tr);
          }).promise().done(function(){
            if(tbody.length == 1){
              $(tbody).children('tr').sort(function (a, b) {
                var cA = $(a).data().contact_ord;
                var cB = $(b).data().contact_ord;
                return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
                }).appendTo(tbody).promise().done(function(){
                  Sortable.create($(tbody)[0],{
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
                      $(tbody).children('tr.estContList').each(function(i,ele){
                        var eDta = $(ele).data();
                        li++;
                        if(Number(eDta.contact_ord) !== li){
                          eDta.contact_ord = li;
                          $(ele).data(eDta);
                          tDta.push({'tbl':'estate_contacts','key':'contact_idx','fdta':eDta,'del':0});
                          }
                        }).promise().done(function(){
                          estSaveElemOrder(tDta,2);
                          });
                      }
                    });
                  });
              }
              
            });
        
        }
      });
    }
  
  
  
  
    
  function estAgentPHPform(usrDta,agyDta){
    console.log('estAgentPHPform ',usrDta,agyDta);
    $.ajax({
      url: vreFeud+'?21||0',
      type:'post',
      data:{'usr':usrDta,'agy':agyDta},
      dataType:'text',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        var defs = $('body').data('defs');
        
        var popIt = $('#estPopCont').data('popit');
        popIt.frm[1] = estBuildSlide(1,{'tabs':['Profile','Contacts']});
        $('#estPopCont').data('popit',popIt);
        
        var popFrm = popIt.frm[1];
        $(popFrm.slide).appendTo(popIt.belt);
        $(popFrm.slide).css({'left':'100%'}).hide();
        
        var frmlabl = 'Test';
        $(JQSPAN,{'id':'estAltH3Span1','class':'FL','title':defs.txt.cancelremove}).data('was',frmlabl).html(frmlabl).on({
          click : function(){estRemovePopoverAlt()}
          }).appendTo(popFrm.h3);
          
        console.log(popIt.frm[1]);
        
        $(popIt.frm[1].popCont).append(ret).promise().done(function(){
          var dta = $('#estAgentPHPform1').data();
          $.each(dta,function(k,v){$('#estAgentPHPform1').removeAttr('data-'+k);});
          
          //dta.agent_agcy = Number(agyDta.agency_idx);
          
          $(popIt.frm[1].form[0]).data({'levdta':dta});
          
          $(popIt.frm[1].tabs.tab[0].tDiv[0]).empty().promise().done(function(){
            $('#estAgentPHPform1').appendTo(popIt.frm[1].tabs.tab[0].tDiv[0]).promise().done(function(){
              $(popIt.frm[1].tabs.tab[1].tDiv[0]).empty().promise().done(function(){
                $('#estAgentPHPform2').appendTo(popIt.frm[1].tabs.tab[1].tDiv[0]).promise().done(function(){
                  
                  
                  estBindContactTables();
                  
                  switch(Number(defs.user.perm)){
                    case 4 :
                      break;
                    case 3 :
                      break;
                    case 2 :
                      break;
                    default :
                      break;
                    }
                  $('select.estSelectDta').each(function(i,ele){
                    if(!$(ele).hasClass('estBound')){
                      $(ele).addClass('estBound').on({change : function(e){estUsrSelectEleChange(this)}});
                      var opts = $(ele).find('option').each(function(oi,opt){
                        var optDta = $(opt).data();
                        $.each(optDta,function(k,v){$(opt).removeAttr('data-'+k);});
                        $(opt).data(optDta);
                        });
                      }
                    }).promise().done(function(){
                      
                      $(popIt.frm[1].slide).show().animate({'left':'0px'});
                      $(popIt.frm[0].slide).animate({'left':'-100%'}).promise().done(function(){
                        $(popIt.frm[0].slide).hide();
                        estPopHeight(1);
                      });
                      
                      console.log(dta);
                      $(popIt.frm[1].form[0]).data('defdta',dta);
                      
                      console.log($(popIt.frm[1].form[0]).data());
                      estSetAgentImg(6);
                      
                      $('#agtAvatar').on({
                        mouseenter : function(e){estMediaEditBtns(6,e.target)},
                        mouseleave : function(e){estMediaEditBtns(-2,e.target);}
                        });
                      
                      var eImg = $('#agtAvatar').find('img.estSecretImg');
                      estAvatarWH(eImg);
                      $(eImg).on({load : function(e){estAvatarWH(e.target);}});
                      
                      estBindFileUplFld(6);
                    });
                  });
                });
              });
            });
          });
        },
      error: function(jqXHR, textStatus, errorThrown){
        console.log('ERRORS: '+textStatus+' '+errorThrown);
        estAlertLog(jqXHR.responseText);
        }
      });
    }
  
  
  
  
  function estCompAgentAssign(step,btn){
    var defs = $('body').data('defs');
    if(step == 2){
      var uDta = $('#estAgenciesAvail').data();
      var bDta = $(btn).data();
      console.log(uDta,bDta);
      if(typeof uDta.agent_agcy !== 'undefined' && typeof uDta.agency_idx !== 'undefined'){
        if(Number(uDta.agent_agcy) !== Number(bDta.agency_idx)){
          if(!jsconfirm(defs.txt.reassign+' '+uDta.agent_name+' to '+bDta.agency_name+'?')){return;}
          }
        }
      
      //estAgentPHPform(uDta,bDta);
      }
    
    else if(step == 1){
      var ptarg = $(btn).parent();
      var usrDta = $(btn).data();
      var agencyDta = $(btn).parent().data();
      
      var fTabs = ['Main'];
      var popIt = estBuildPopover([{'tabs':fTabs}]);
      var popFrm = popIt.frm[0];
      
      if(typeof usrDta.agent_idx !== 'undefined' && Number(usrDta.agent_idx) > 0){
        frmLabel = defs.txt.reassign+' '+usrDta.agent_name;
        }
      else{
        var frmLabel = defs.txt.assign+' '+$(btn).find('h4.userName').text();
        }
      
      
      $(JQSPAN,{'class':'FL','title':defs.txt.cancelremove}).html(frmLabel).on({
        click : function(){estRemovePopover()}
        }).appendTo(popFrm.h3);
      
      $(popFrm.h3).parent();
      $(popFrm.h3).addClass('withPic').css({'background-image':$(btn).css('background-image')});
      
      var cancelBtn = $(JQBTN,{'class':'btn btn-primary btn-sm FR'}).html(defs.txt.cancel).on({click : function(){estRemovePopover()}}).appendTo(popFrm.h3);
      popFrm.savebtns.push(cancelBtn);
      //var saveBtn = $(JQBTN,{'class':'btn btn-primary btn-sm FR'}).html(defs.txt.save).on({click : function(){}}).appendTo(popFrm.h3);
      //popFrm.savebtns.push(saveBtn);
      
      $.ajax({
        url: vreFeud+'?20||0',
        type:'post',
        data:{'usr':usrDta,'agency':agencyDta},
        dataType:'text',
        cache:false,
        processData:true,
        success: function(ret, textStatus, jqXHR){
          $(popFrm.popCont).html(ret).promise().done(function(){
            var bDta = $('#estAgenciesAvail').data();
            $.each(bDta,function(k,v){$('#estAgenciesAvail').removeAttr('data-'+k);});
            $(popFrm.popCont).find('.estAgntUserBtn').each(function(i,btn){
              var bDta = $(btn).data();
              $.each(bDta,function(k,v){$(btn).removeAttr('data-'+k);});
              $(btn).data(bDta).addClass('estBound').on({
                click : function(e){
                  e.preventDefault();
                  estCompAgentAssign(2,this);
                  }
                });
              });
            
            if(typeof usrDta.agent_idx !== 'undefined' && Number(usrDta.agent_idx) > 0){
              if(typeof usrDta.agency_idx !== 'undefined' && Number(usrDta.agency_idx) > 0){
                $('#estAgencyRemUsr').on({
                  click : function(e){
                    e.preventDefault();
                    var cmsg = $(this).data('conf');
                    if(!jsconfirm(cmsg)){return;}
                    //do Agent Removal
                    }
                  }).show();
                }
              }
            estPosPopover();
            });
          },
        error: function(jqXHR, textStatus, errorThrown){
          console.log('ERRORS: '+textStatus+' '+errorThrown);
          estAlertLog(jqXHR.responseText);
          }
        });
      }
    }
  
  
  function estPrepPresetsForm(mainId){
    
    $.ajax({
      url: vreFeud+'?0||0',
      type:'get',
      data:{'fetch':2,'propid':0,'rt':'js','tbl':''},
      dataType:'json',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        console.log(ret);
        if(typeof ret !== 'undefined' && ret !== null){
          $('body').data('defs',ret);
          estProcDefDta();
          var defs = ret;
          
          console.log('mainId',mainId);
          
          $('#estPresetsZoneDtaEdit').on({
            click :function(e){
              e.preventDefault();
              e.stopPropagation();
              if($('#estPresetsZoningPopover').is(':visible')){$('#estPresetsZoningPopover').hide();}
              else{$('#estPresetsZoningPopover').show();}
              }
            });
          
          
          $('#estPresetsAddZone').on({
            click :function(e){
              e.preventDefault();
              e.stopPropagation();
              var i = $('#estPresetsCurZoningCont').find('div.estPresetsNewZoneCont').length;
              var dcont = $(JQDIV,{'class':'estPresetsNewZoneCont'}).appendTo('#estPresetsCurZoningCont');
              
              if(typeof opt == 'undefined'){var opt = {'zoning_idx':'','zoning_name':''};}
              $.extend(opt,{'new':i});
              
              var cb = $(JQNPT,{'type':'checkbox','name':'new_zoning_keep['+i+']','value':opt.zoning_idx,'checked':'checked'}).on({
                click : function(){$(this).parent().remove();}
                }).appendTo(dcont);
              
              var inpt = $(JQNPT,{'type':'text','name':'new_zoning_name['+i+']','class':'tbox form-control input-xlarge ILBLK ui-state-valid','value':opt.zoning_name}).attr('data-idx',Number(0)).on({
                change : function(){$(cb).data().zoning_name = $(this).val();},
                blur : function(){
                  if($(inpt).val().length < 2){$(this).parent().remove();}
                  }
                }).appendTo(dcont);
              
              $(cb).data(opt);
              $(inpt).focus();
              }
            });
          
          $('#estPresetsCanZone').on({
            click :function(e){
              e.preventDefault();
              e.stopPropagation();
              $('.estPresetsNewZoneCont').remove();
              $('#estPresetsZoningPopover').hide();
              }
            });
          
          $('select[name="preset_zoneSelect"]').on({
            change :function(){
              var id = this.value;
              $('.estPresetsTable').hide().promise().done(function(){
                $('#estPresetsTable-'+id).show();
                var postPath = $('#estPresetDataForm').attr('action').split('?');
                var postkeys = postPath[1].split('&');
                var nPath = postPath[0]+'?'+postkeys[0]+'&'+postkeys[1]+'&id='+Number(id);
                $('#estPresetDataForm').attr('action',nPath);
                console.log(nPath);
                });
              }
            }).change();
          
          $('#estPresetsSaveZones').on({
            click :function(e){
              e.preventDefault();
              e.stopPropagation();
              estPresetsZoneProc();
              }
            });
          
          if(mainId > 0){$('select[name="preset_zoneSelect"]').val(mainId).change();}
          estPrepPresetData();
          }
        },
      error: function(jqXHR, textStatus, errorThrown){
          console.log('ERRORS: '+textStatus+' '+errorThrown);
          estAlertLog(jqXHR.responseText);
        }
      });
    }


  function estPrepAgencyForm(mainId){
    console.log('estPrepAgencyForm ',mainId);
    $.ajax({
      url: vreFeud+'?0||0',
      type:'get',
      data:{'fetch':2,'propid':0,'rt':'js','tbl':''},
      dataType:'json',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        console.log(ret);
        if(typeof ret !== 'undefined' && ret !== null){
          if(typeof ret.error !== 'undefined'){alert(ret.error);}
          else{
            $('body').data('defs',ret);
            estProcDefDta();
            
            var defs = ret;
            
            $('#etrigger-submit').on({
              click : function(e){
                e.stopPropagation();
                var err = [];
                $(['agency_name']).each(
                  function(i,ele){
                    $('input[name="'+ele+'"]').removeClass('estFrmErr');
                    if($('input[name="'+ele+'"]').val().length < 2){
                      $('input[name="'+ele+'"]').addClass('estFrmErr');
                      err.push($('input[name="'+ele+'"]'));
                      }
                  }).promise().done(function(){
                    if(err.length == 0){
                      return true;
                      }
                    else{
                      e.preventDefault();
                      console.log(err);
                      var defs = $('body').data('defs');
                      $(document).scrollTop($(err[0]).position().top);
                      }
                    });
                }
              });
            
            $('input[name="agency_name"]').on({
              keyup : function(){$('#estProfTab').html(this.value)},
              change : function(){$('#estProfTab').html(this.value)}
              }).change();
            
            estBindContactTables();
            
            $('img.estSecretImg').each(function(ei,eImg){
              estAvatarWH(eImg);
              $(eImg).on({load : function(e){estAvatarWH(e.target);}});
              });
            //estAgentAvatar
            //estInitSetupUpl
            $('#estAgencyImg').on({
              mouseenter : function(e){estMediaEditBtns(8,e.target)},
              mouseleave : function(e){estMediaEditBtns(-2,e.target);}
              });
            
            
            $('.estAgntUserTarg').each(function(i,ele){
              $.each($(ele).data(),function(k,v){$(ele).removeAttr('data-'+k);});
              });
            
            estBindFileUplFld(5);
            estBuildMap('agency');
            estAgencyFormOn();
            estPrepUserListTable();
            }
          }
        }
      });
    }
  
  //END AGENCY FORM
  
  
  
  
  function estBindFileUplFld(mode){
    $('input[type="file"].estInitFile').each(function(i,fEle){
      if(!$(fEle).hasClass('estBound')){
        $(fEle).addClass('estBound').on({
          change : function(e){
            files = e.target.files;
            if(files && files.length > 0 && (files[0].type =='image/jpeg' || files[0].type =='image/png' || files[0].type =='image/gif')){
              var thmDiv = $(e.target).parent();
              var imgEle = $(thmDiv).find('img');
              var reader = new FileReader();
              var img = $(imgEle)[0];
              reader.onload = function(e){
                $(thmDiv).css({'background-image':'url('+e.target.result+')'});
                $(imgEle).attr('src',e.target.result);
                img.src = e.target.result;
                img.onload = function(){
                  var nW = img.naturalWidth;
                  var nH = img.naturalHeight;
                  var asp = parseFloat(img.naturalWidth / img.naturalHeight).toFixed(3);
                  nH = Math.floor($(thmDiv).width() * asp);
                  $(thmDiv).css({'height':nH+'px'});
                  }
                
                
                }
              reader.readAsDataURL(files[0]);
              }
            }
          }).hide();
        }
      });
    }
  
  
  
  
  function estSetSaveCt(mode){
    var defs = $('body').data('defs');
    var fld = $('input[name="prop_saves"]');
    if(mode == 1){$(fld).val(Number($('#propSaveCtNumb').val()));}
    var vCt = Number($(fld).val());
    $('#estPropSaveCt').html(vCt+' '+(vCt == 1 ? defs.txt.save : defs.txt.saves));
    if(mode == 0){$('#propSaveCtNumb').val(vCt);}
    }
  
  function estSetViewCt(mode){
    var defs = $('body').data('defs');
    var fld = $('input[name="prop_views"]');
    if(mode == 1){$(fld).val(Number($('#propViewCtNumb').val()));}
    var vCt = Number($(fld).val());
    $('#estPropViewCt').html(vCt+' '+(vCt == 1 ? defs.txt.view : defs.txt.views));
    if(mode == 0){$('#propViewCtNumb').val(vCt);}
    }
  
  function estatePrepForm(mainTbl){
    var defs = $('body').data('defs');
    estProcDefDta();
    
    var propId = Number($('body').data('propid'));
    var helpInFull = (typeof defs.prefs.helpinfull !== 'undefined' ? Number(defs.prefs.helpinfull) : 0);
    
    var uperm = Number(defs.user.perm);
    
    $('.estSelNoBlank > option').each(function(oi,opt){if($(opt).html() == ''){$(opt).remove();}});
    
    if(typeof defs.tbls[mainTbl] !== 'undefined'){
      var cSave = [$('#etrigger-submit')];
      var cForm = $('#etrigger-submit').closest('form');
      
      $('#estGalleryH2 .estBeltLoop').css({'max-width':($('#admin-ui-edit').width() - 16)+'px'});
      
      estSetFormEles(mainTbl,cForm,cSave);
      
      if(mainTbl == 'estate_properties'){
        
        $('.estNoDataTr').closest('tr').remove();
        $('.estHideDataTr').closest('tr').hide();
        
        if(typeof $('div.admin-main-content h4.caption') !== 'undefined'){
          $('input[name="prop_datecreated"]').closest('tr').hide();
          $('input[name="prop_dateupdated"]').closest('tr').hide();
          if(propId > 0){
            
            var dateUpDiv = $(JQDIV,{'class':'ILBLK FR'}).prependTo('div.admin-main-content h4.caption');
            var dateUpPar = $('input[name="prop_dateupdated"]').parent();
            $('input[name="prop_dateupdated"]').appendTo(dateUpDiv);
            $('<span></span>').html(defs.txt.updated+' ').appendTo(dateUpDiv);
            $('<span></span>',{'id':'propUpdateTxtSpan'}).html($(dateUpPar).html()).appendTo(dateUpDiv);
            }
          
          var viewCtDiv = $(JQDIV,{'id':'propViewCtDiv'}).prependTo('div.admin-main-content h4.caption');
          var viewCtCont = $(JQDIV,{'id':'propViewCtCont','class':'propViewCtCont'}).html(defs.txt.views+': ').appendTo(viewCtDiv);
          
          var viewCtNumb = $(JQNPT,{'type':'number','id':'propViewCtNumb','class':'tbox number e-spinner input-small form-control ILBLK VAM'}).on({
            change : function(){estSetViewCt(1);},
            blur : function(){$(this).parent().hide();}
            }).appendTo(viewCtCont);
          $('<a></a>',{'id':'estPropViewCt'}).on({
            click : function(e){
              if($(viewCtCont).is(':visible')){$(viewCtCont).hide();}
              else{
                $('.propViewCtCont').hide();
                $(viewCtCont).show();
                $(viewCtNumb).focus();
                }
              }
            }).prependTo(viewCtDiv).promise().done(function(){
              
              $(viewCtDiv).prepend(' • ');
              
              var saveCtCont = $(JQDIV,{'id':'propSaveCtCont','class':'propViewCtCont'}).html(defs.txt.saves+': ').appendTo(viewCtDiv);
              var saveCtNumb = $(JQNPT,{'type':'number','id':'propSaveCtNumb','class':'tbox number e-spinner input-small form-control ILBLK VAM'}).on({
                change : function(){estSetSaveCt(1);},
                blur : function(){$(this).parent().hide();}
                }).appendTo(saveCtCont);
                
              $('<a></a>',{'id':'estPropSaveCt'}).on({
                click : function(e){
                  if($(saveCtCont).is(':visible')){$(saveCtCont).hide();}
                  else{
                    $('.propViewCtCont').hide();
                    $(saveCtCont).show();
                    $(saveCtNumb).focus();
                    }
                  }
                }).prependTo(viewCtDiv).promise().done(function(){
                  $(viewCtDiv).prepend(' • ');
                  estSetViewCt(0);
                  estSetSaveCt(0);
                  })
              })
          }
        
        
        if(helpInFull == 0){
          $('#estEventsCont').on({mouseenter : function(){estScrollHlp('#estHlp-sched5')}});
          $('#estGalleryH1').on({mouseenter : function(){estScrollHlp('#estHlp-gal0')}});
          $('#estGalleryH2').on({mouseenter : function(){estScrollHlp('#estHlp-gal3')}});
          $('#estGalleryH4').on({mouseenter : function(){estScrollHlp('#estHlp-gal5')}});
          }
        
        
        $('select[name="prop_city"]').data('pval',Number($('select[name="prop_city"]').val())).on({
          change : function(){estResetSubDivs();}
          });
        
        $('#prop-bldguc').parent().appendTo('#propUnitCont');
        $('#prop-complxuc').parent().appendTo('#propUnitCont');
        $('#prop-bedtot').parent().prependTo('#propBedsCont');
        $('#prop-bathtot').parent().prependTo('#propBathsCont');
        $('#prop-bathhalf').parent().prependTo('#propBathsCont');
        $('#prop-bathfull').parent().prependTo('#propBathsCont');
        
        
        estPrepPropAgent();
        estateBuildDIMUbtns();
        estBuildGallery();
        //estBuildSpaceList('core load');
        estInitDefHrs(1);
        estBuildEvtTab();
        estBuildMap('prop');
        //estInitSetupUpl 
        estBindTemplateSel(2);
        
        var mediaDta = estNewMediaDta(1,'CORE estatePrepForm');
        estFileUplFld(mediaDta,1,null,1);
        $('#fileSlipBtn').on({
          click : function(e){
            e.stopPropagation();
            e.preventDefault();
            $('#fileSlip').click();
            }
          });
        
        estTestEles(cForm,cSave);
        }
      else{console.log('nothing to do');}
      }
    }
  
  
  
  function estGetThmsForList(mode,thmEle){
    var propId = $(thmEle).data('propid');
    $.ajax({
      url: vreFeud+'?76||0',
      type:'get',
      data:{'fetch':76,'propid':propId,'rt':'js'},
      dataType:'json',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        if(typeof ret !== 'undefined'){
          $(thmEle).data('media',ret);
          estSetThmsForList(mode,thmEle);
          }
        else{estAlertLog('Thumbnail list not fetched');}
        },
      error: function(jqXHR, textStatus, errorThrown){
        console.log('ERRORS: '+textStatus+' '+errorThrown);
        estAlertLog(jqXHR.responseText);
        }
      });
    }
  
  
  function estSetThmsForList(mode,thmEle){
    var defs = $('body').data('defs');
    var mediaGrep1 = $(thmEle).data('media');
    if(typeof mediaGrep1 == 'undefined'){
      estGetThmsForList(mode,thmEle);
      return;
      }
    
    var noCache = '?'+Math.floor(Math.random() * (99999 - 99 + 1) + 99);
    if(mode == 2){
      if(mediaGrep1.length < 2){
        $(thmEle).addClass('jiggle');
        setTimeout(function() {$(thmEle).removeClass('jiggle');}, 500);
        $('.estThmMgrCont').remove();
        return;
        }
      
      $('.estThmMgrCont').remove().promise().done(function(){
        var xW = $(thmEle).closest('table').width() - $(thmEle).closest('tr').find('div.btn-group').outerWidth();
        var mBox0 = $(JQDIV,{'id':'estMediaMgrCont','class':'estThmMgrCont'}).css({'max-width':xW+'px'});
        $(mBox0).data('trThm',thmEle).appendTo($(thmEle).parent()).promise().done(function(){
          var defs = $('body').data('defs');
          var ulbtn = [];
          $(mediaGrep1).each(function(k,mediaDta){
            ulbtn[k] = $(JQDIV,{'class':'upldPvwBtn pvw-'+mediaDta.media_idx}).data(mediaDta).css({'background-image':'url('+defs.dir.prop.thm+mediaDta.media_thm+noCache+')'}).appendTo('#estMediaMgrCont');
            if(Number(mediaDta.media_asp) !== 0){$(ulbtn[k]).css({'width':Math.floor($(ulbtn[k]).height() * mediaDta.media_asp)});}
            }).promise().done(function(){
              $('#estMediaMgrCont').children('div.upldPvwBtn').sort(function (a, b){
                var cA = $(a).data().media_galord;
                var cB = $(b).data().media_galord;
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
                      var trThm = $('#estMediaMgrCont').data('trThm');
                      var mediaGrep1 = $(trThm).data('media');
                      var tDta = [];
                      var li = 0;
                      $('#estMediaMgrCont').children('div.upldPvwBtn').each(function(i,ele){
                        var eDta = $(ele).data();
                        if(Number(eDta.media_galord) !== (i + 1)){
                          eDta.media_galord = i + 1;
                          $('.pvw-'+eDta.media_idx).data(eDta);
                          //fdta = estMediaFldClean(eDta);
                          tDta.push({'tbl':'estate_media','key':'media_idx','fdta':eDta,'del':0});
                          }
                        var mDta = mediaGrep1.find(x => Number(x.media_idx) === Number(eDta.media_idx));
                        var mKey = mediaGrep1.indexOf(mDta);
                        if(mKey > -1){mediaGrep1[mKey] = eDta;}
                        }).promise().done(function(){
                          estSetThmsForList(1,thmEle);
                          estSaveElemOrder(tDta,1);
                          });
                      }
                    });
                  });
              });
          });
        });
      }
    else{
      var mDta = mediaGrep1.find(x => Number(x.media_galord) === 1);
      var mKey = mediaGrep1.indexOf(mDta);
      if(mKey == -1){mKey = 0;}
      $(thmEle).css({'background-image':'url('+defs.dir.prop.thm+mediaGrep1[mKey].media_thm+noCache+')'});
      }
    }
  
  
  
  function estThmsForList(ret){
    var helpInFull = (typeof ret.prefs.helpinfull !== 'undefined' ? Number(ret.prefs.helpinfull) : 0);
    
    }
  
  
  function estInitDefHrs(){
    
    $('.estPrefCalActDay').each(function(i,btn){
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
      });
    }
  
  
  
  
  function estSetHelpInFull(){
    var defs = $('body').data('defs');
    var helpInFull = (typeof defs.prefs.helpinfull !== 'undefined' ? Number(defs.prefs.helpinfull) : 0);
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
    }
  
  
  function estTemplateReorder(btn){
    if($(btn.nextSibling).find('input[type="checkbox"]').is(':checked')){
      $(btn).find('input[type="checkbox"]').prop('checked','checked');
      }
    }
  
  
  
  
  function estTemplateOrdCont(mode,sect){
    var defs = $('body').data('defs');
    if(typeof defs.prefs.templates == 'undefined'){
      estAlertLog('No Templates!');
      return;
      }
    if(typeof defs.prefs.templates[sect] == 'undefined'){
      estAlertLog('No Templates For '+sect);
      console.log('Templates: ',defs.prefs.templates);
      return;
      }
    
    var spfx = '';
    var dbSrc = defs.prefs;
    if(mode == 2){
      spfx = 'prop_';
      dbSrc = defs.tbls.estate_properties.dta[0];
      if(dbSrc['prop_template_'+sect] == ''){dbSrc['prop_template_'+sect] = defs.prefs['template_'+sect];}
      if(dbSrc['prop_template_'+sect+'_ord'] == ''){dbSrc['prop_template_'+sect+'_ord'] = defs.prefs['template_'+sect+'_ord'];}
      }
    
    var tKey = spfx+'template_'+sect;
    
    $.each(defs.prefs.templates[sect], function(tname,tdta){
      //console.log(sect,tname,tdta);
      
      var selectOpt = $(JQOPT).val(tname).html(tdta.name).appendTo('select[name="'+spfx+'template_'+sect+'"]');
      var scont = $(JQDIV,{'id':'est'+spfx+sect+'TemplateSect-'+tname,'class':'estTemplateSectCont est'+spfx+sect+'TemplateSectCont'}).appendTo('#est'+spfx+sect+'OrderCont');
      
      
      if(tname == dbSrc[tKey]){
        $(selectOpt).prop('selected','selected');
        $(scont).show();
        }
        
      
      if(typeof tdta.ord === 'undefined' || tdta.ord === null){
        $(JQDIV,{'class':'TAL'}).html(defs.txt.templnoopt).appendTo(scont);
        }
      else{
        $(selectOpt).data(tdta.ord);
        var preford = Object.keys(tdta.ord);
        var tmplK1 = spfx+'template_'+sect;
        if(typeof dbSrc[tmplK1] == 'undefined' || dbSrc[tmplK1] == ''){
          dbSrc[tmplK1] = 'default';
          }
        
        var tmplK2 = spfx+'template_'+sect+'_ord';
        if(typeof dbSrc[tmplK2][tname] !== 'undefined' && dbSrc[tmplK2][tname] !== null){
          preford = Object.keys(dbSrc[tmplK2][tname]);
          }
        
        //this dummy is needed to save the Order Array if only the order has changed
        var dmy = $(JQNPT,{'type':'checkbox','name':spfx+'template_'+sect+'_ord['+tname+'][dummy]'}).val(1).appendTo(scont).hide();
        if(preford.indexOf('dummy') == -1){$(dmy).prop('checked','checked');}
        
        var xi = preford.length;
        $(tdta.ord).each(function(bi,bname){
          
          var btn = $(JQDIV,{'class':'btn btn-default btn-sm TAL','title':defs.txt.reorder+' '+defs.txt.layout});
          var cb = $(JQNPT,{'type':'checkbox','id':spfx+'template-'+sect+'-ord['+tname+']['+bname+']','name':spfx+'template_'+sect+'_ord['+tname+']['+bname+']','title':defs.txt.enabdisab+' '+bname}).val(1).appendTo(btn);
          var lab = $('<label></label>',{'title':defs.txt.enabdisab+' '+bname}).prop('for',spfx+'template-'+sect+'-ord['+tname+']['+bname+']').html(bname).appendTo(btn);
          
          var bord = preford.indexOf(bname);
          if(bord > -1){$(cb).prop('checked','checked');}
          else{bord = xi; xi++;}
          $(btn).data('ord',bord).appendTo(scont);
          }).promise().done(function(){
            var btnCont = document.getElementById('est'+spfx+sect+'TemplateSect-'+tname);
            $(btnCont).children('div.btn').sort(function (a, b) {
              var cA = $(a).data('ord'); var cB = $(b).data('ord');
              return (cA > cB) ? 1 : (cA < cB) ? -1 : 0;
              }).appendTo(btnCont);
              
            Sortable.create(btnCont,{
              draggable: 'div.btn',
              sort: true,
              animation: 450,
              ghostClass: 'sortTR-ghost',
              chosenClass: 'sortTR-chosen', 
              dragClass: 'sortTR-drag',
              onChoose: function(evt){},
              onEnd: function(evt){estTemplateReorder(evt.item);}
              });
            });
        }
      });
    }
  
  
  
  
  function estBindTemplateSel(mode){
    var defs = $('body').data('defs');
    //console.log(defs.prefs.templates);
    
    if(mode == 2){
      var spfx = 'prop_';
      }
    else{
      var spfx = '';
      $('select[name="template_list"]').closest('tr').addClass('estTmplTR');
      $('select[name="template_list"]').prop('name','template_list').on({
        change : function(){}
        }).empty().promise().done(function(){
          $.each(defs.prefs.templates.list, function(tname,tdta){
            var selectOpt = $(JQOPT).val(tname).html(tdta.name).appendTo('select[name="template_list"]');
            });
          });
      }
      
    
    $('#est'+spfx+'viewOrderCont').closest('tr').addClass('estTmplTR');
    $('select[name="'+spfx+'template_view"]').closest('tr').addClass('estTmplTR');
    $('select[name="'+spfx+'template_view"]').prop('name',spfx+'template_view').on({
      change : function(){
        $('.est'+spfx+'viewTemplateSectCont').hide();
        $('#est'+spfx+'viewTemplateSect-'+this.value).show();
        }
      }).empty().promise().done(function(){
        estTemplateOrdCont(mode,'view');
        });
    
    $('#est'+spfx+'menuOrderCont').closest('tr').addClass('estTmplTR');
    $('select[name="'+spfx+'template_menu"]').closest('tr').addClass('estTmplTR');
    $('select[name="'+spfx+'template_menu"]').prop('name',spfx+'template_menu').on({
      change : function(){
        $('.est'+spfx+'menuTemplateSectCont').hide();
        $('#est'+spfx+'menuTemplateSect-'+this.value).show();
        }
      }).empty().promise().done(function(){
        estTemplateOrdCont(mode,'menu');
        });
    }
  
  
  
  function estBuildNANotify(key){
    var chks = 0;
    $('#estPubNotifyCont').find('div.estPubNotifySectBtn').each(function(i,btn){
      var cbx = $(btn).find('input[type="checkbox"]');
      if(!$(btn).hasClass('estEleBound')){
        $(btn).addClass('estEleBound');
        $(cbx).on({
          click : function(e){
            if($(cbx).is(':checked')){$(cbx).data('chk',1);}
            else{$(cbx).data('chk',Number(0));}
            }
          });
        }
      
      
      if(Number($(btn).data('lev')) >= Number(key)){
        $(btn).fadeIn(250);
        if(Number($(cbx).data('chk')) == 1  && !$(cbx).is(':checked')){$(cbx).prop('checked','checked');}
        if($(cbx).is(':checked')){chks++;}
        }
      else{
        if($(cbx).is(':checked')){$(cbx).data('chk',1);}
        else{$(cbx).data('chk',Number(0));}
        $(cbx).prop('checked','').removeProp('checked');
        $(btn).fadeOut(250);
        }
      }).promise().done(function(){
        console.log('chks: '+chks);
        });
    }
  
  
  
  
  function estPrefs(){
    
    $('#prefSetDefTerms').on({
      click : function(e){
        e.preventDefault();
        if($('#contact-terms-def').is(':visible')){
          $('#prefSetDefTerms').html($('#prefSetDefTerms').data('t1'));
          $('#contact-terms-def').fadeOut(200);
          $('#contact-terms').fadeIn(200);
          }
        else{
          $('#prefSetDefTerms').html($('#prefSetDefTerms').data('t2'));
          $('#contact-terms').val('').fadeOut(200);
          $('#contact-terms-def').fadeIn(200);
          }
        }
      }).appendTo('#prefSetDefTermsTarg');
    
    
    $.ajax({
      url: vreFeud+'?0||0',
      type:'get',
      data:{'fetch':0,'propid':0,'rt':'js','tbl':''},
      dataType:'json',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        console.log(ret);
        if(typeof ret !== 'undefined' && ret !== null){
          if(typeof ret.error !== 'undefined'){alert(ret.error);}
          else{
            $('body').data({'defs':ret,'propid':0}).promise().done(function(){
              estSetHelpInFull();
              estBindTemplateSel(1);
              estBuildMap('pref');
              
              $('select[name="public_mod"]').on({
                change : function(){estBuildNANotify(Number(this.value))}
                }).change();
              
              
              $('select[name="public_apr"]').on({
                change : function(){
                  if(this.value == 255){
                    //$('select[name="public_notify"]').closest('tr').fadeIn();
                    }
                  else{
                    //$('select[name="public_notify"]').closest('tr').fadeOut();
                    }
                  }
                }).change();
              
            
              $('select[name="map_jssrc"]').on({
                change : function(){
                  if(this.value == 1){
                    $('#map-url').closest('tr').fadeIn();
                    $('#map-key').closest('tr').fadeIn();
                    }
                  else{
                    $('#map-url').closest('tr').fadeOut();
                    $('#map-key').closest('tr').fadeOut();
                    }
                  }
                });
              $('select[name="map_jssrc"]').change();
              
              $('#estMapUrlReset').on({
                click : function(e){
                  e.preventDefault();
                  $('input[name="map_url"]').val('https://unpkg.com/leaflet@1.9.2/dist/leaflet.js');
                  }
                });
              $('#estMapKeyReset').on({
                click : function(e){
                  e.preventDefault();
                  $('input[name="map_key"]').val('sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=');
                  }
                });
              
              $('#estPrefPropOpt1').on({
                change : function(){
                  $('#estPrefPropSel').change();
                  }
                });
              
              
              $('#estPrefPropSel').on({change : function(){}}); // needed???
              
              
              $('input[name="listing_disp[0]"]').on({
                change : function(){
                  if(this.value == 1){$('input[name="listing_disp[0]"]').parent().find('div.bootstrap-switch-wrapper').find('span').eq(0).click();}
                  }
                });
              
              
              estPrepLocaleForm();
              
              estInitDefHrs();
              
              $('select.estPrefEventSel').on({
                change : function(){
                  $('input[name="eventkeys['+$(this).data('key')+'][ms]"]').val($(this).find('option:selected').data('ms'));
                  }
                });
              
              $('button.estPrefEventDel').on({
                click : function(e){
                  e.preventDefault();
                  var defs = $('body').data('defs');
                  if(jsconfirm(defs.txt.eventkeydel)){
                    $(e.target).closest('tr').remove();
                    alert(defs.txt.updatereq);
                    }
                  }
                });
              
               $('#estNewEventKey').on({
                click : function(e){
                  e.preventDefault();
                  var defs = $('body').data('defs');
                  var key = $('#estEventKeysTB').find('tr').length;
                  var tr = $(JQTR).appendTo('#estEventKeysTB');
                  var td1 = $(JQTD).appendTo(tr);
                  var td2 = $(JQTD).appendTo(tr);
                  var td3 = $(JQTD,{'class':'TAC'}).appendTo(tr);
                  
                  $(JQNPT,{'type':'hidden','name':'eventkeys['+key+'][ms]'}).appendTo(td1);
                  $(JQNPT,{'type':'text','name':'eventkeys['+key+'][l]','class':'tbox form-control input-large','placeholder':defs.txt.newevtname}).appendTo(td1);
                  
                  var sel = $(JQSEL,{'name':'eventkeys['+key+'][t]','class':'tbox form-control input-medium ui-state-valid estPrefEventSel'}).data('key',key).on({
                    change : function(){
                      $('input[name="eventkeys['+$(this).data('key')+'][ms]"]').val($(this).find('option:selected').data('ms'));
                      }
                    }).html($('#estEvtKeyOpts').find('option').clone()).appendTo(td2);
                  
                  //$('#estEvtKeyOpts').find('option').clone().appendTo(sel);
                  $(JQBTN,{'type':'button','name':'eventkey_delete['+key+']','class':'btn btn-small btn-default'}).html('<i class="fa fa-close"></i>').on({
                    click : function(e){
                      e.preventDefault();
                      if(jsconfirm(defs.txt.eventkeydel)){
                        $(e.target).closest('tr').remove();
                        alert(defs.txt.updatereq);
                        }
                      }
                    }).appendTo(td3);
                  }
                });
              
              estSetMap('est_pref_Map');
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
  
  
  
  
  
  function estPrepMainHelp(){
    console.log('Main Help JS');
    }
  
  function estPrepAgencyList(){
    }
  
  
  
  
  
  
  function estSetPropListFilters(btn,tabl){
    var dta = $(tabl).data();
    var tbody = dta.tbody;
    
    
    var limits = dta.limit;
    if($(btn).hasClass('estPropDBPrev')){
      limits[0] = Number(limits[0]) - Number(limits[1]);
      }
    else if($(btn).hasClass('estPropDBNext')){
      limits[0] = Number(limits[0]) + Number(limits[1]);
      }
    
    if(Number(limits[0]) <= 0){limits[0] = 0;}
    
    
    var tdta = {'fltr':{},'mode':dta.mode,'colsp':dta.colspan,'order':dta.order,'limit':dta.limit.join()};
    
    $(tbody).empty().promise().done(function(){
      $(tbody).html('<tr><td colspan="'+tdta.colsp+'"><div class="uplThmCover" style="height:128px"></div></td></tr>');
      });
    //estAgencySelBtn
    
    $(dta.fltrs).each(function(fi,fltr){
      console.log(fltr);
      var chked = $(fltr.ul).find('label.active');
      $(fltr.capt).attr('data-before',Number(fltr.li.length) - (Number(fltr.li.length)-Number(chked.length)));
      if(chked.length > 0 && chked.length !== fltr.li.length){
        $(fltr.capt).addClass('fltrd');
        tdta.fltr[fltr.fld] = [];
        $(chked).each(function(ci,cli){
          tdta.fltr[fltr.fld].push($(cli).data('value'));
          });
        }
      else{
        $(fltr.capt).removeClass('fltrd');
        }
      }).promise().done(function(){
        console.log(tdta);
        $('div.estFltrDiv').removeClass('open');
        
        $.ajax({
          url: vreFeud+'?0||0',
          type:'post',
          data:{'fetch':97,'propid':0,'rt':'html','tdta':tdta},
          dataType:'text',
          cache:false,
          processData:true,
          success: function(ret, textStatus, jqXHR){
            $(tbody).empty().promise().done(function(){
              $(tbody).html(ret).promise().done(function(){
                estSetPropListLimits(tabl);
                });
              });
            },
          error: function(jqXHR, textStatus, errorThrown){
            console.log('ERRORS: '+textStatus+' '+errorThrown);
            estAlertLog(jqXHR.responseText);
            }
          });
        });
    
    }
  
  function estSetPropListLimits(tabl){
    $('#estDBResCount').remove();
    var dta = $(tabl).data();
    
    if(Number(dta.limit[0]) < 1){
      dta.limit[0] = 0;
      $(dta.dbbtns[0]).prop('disabled',true);
      }
    else{$(dta.dbbtns[0]).prop('disabled',false).removeProp('disabled');}
    
    if((Number($(dta.tbody).find('tr').length) - 1) < Number(dta.limit[1])){$(dta.dbbtns[2]).prop('disabled',true);}
    else{$(dta.dbbtns[2]).prop('disabled',false).removeProp('disabled');}
    }
  
  
  function estDBResCount(btn,tabl){
    var dta = $(tabl).data();
    if(document.getElementById('estDBResCount')){
      $('#estDBResCount').remove();
      }
    else{
      var nBox = $(JQDIV,{'id':'estDBResCount'}).appendTo($(btn).parent());
      $([5,10,25,50,100,150,250]).each(function(i,v){
        $(JQBTN,{'class':'btn btn-default'}).html(v).on({
          click : function(e){
            e.preventDefault();
            dta.limit[1] = v;
            $(dta.dbbtns[1]).html(v);
            $(tabl).data(dta);
            $('#estDBResCount').remove();
            dta.limit[0] = Number(0);
            $(tabl).data(dta);
            estSetPropListFilters(this,tabl);
            }
          }).appendTo(nBox);
        });
      }
    }
  
  
  
  function estPrepPropListFilters(){
    $('.estPropListTABx').each(function(tbi,tabl){
      var thead = $(tabl).find('thead');
      var tbody = $(tabl).find('tbody');
      
      var dta = {'tbody':tbody,'thead':thead,'mode':$(tabl).data('mode'),'fltrs':[],'dbbtns':[],'limit':[]};
      
      $(thead).find('ul.estPropListFltrSet').each(function(i,fele){
        var capt = $(fele).parent().find('a.dropdown-toggle');
        var UL = $(fele).find('ul.estPropListFltrUL');
        
        dta.fltrs[i] = {'fld':$(fele).data('fld'),'ul':UL,'li':[],'capt':capt};
        
        $(UL).each(function(ULi,ULEle){
          $(ULEle).find('li.estFltrItm').each(function(fi,fLI){
            var fchk = $(fLI).find('label.form-check');
            if($(fLI).hasClass('estFltrHead')){
              $(fLI).on({
                click : function(e){
                  if($(fchk).hasClass('active')){
                    $(ULEle).find('li.estFltrInd label.form-check').addClass('active');
                    }
                  else{
                    $(ULEle).find('li.estFltrInd label.form-check').removeClass('active');
                    }
                  }
                });
              }
            else{
              dta.fltrs[i].li.push(fchk);
              }
            });
          });
        
        $(fele).find('button.propFltrClr').on({
          click : function(e){
            e.preventDefault();
            $(UL).each(function(ULi,ULEle){
              $(ULEle).find('li.estFltrItm').each(function(fi,fLI){
                $(fLI).find('label.form-check').removeClass('active');
                });
              }).promise().done(function(){
                dta.limit[0] = Number(0);
                $(tabl).data(dta);
                estSetPropListFilters(this,tabl);
                });
            }
          });
        
        $(fele).find('button.propFltrSet').on({
          click : function(e){
            e.preventDefault();
            dta.limit[0] = Number(0);
            $(tabl).data(dta);
            estSetPropListFilters(this,tabl);
            }
          });
        }).promise().done(function(){
        
          $(thead).find('div.estPropListDBLimit').each(function(i,btnCont){
            var dbBtns = $(btnCont).find('button');
            
            $(dbBtns[0]).on({click : function(){estSetPropListFilters(this,tabl);}});
            $(dbBtns[1]).on({click : function(){estDBResCount(this,tabl);}});
            $(dbBtns[2]).on({click : function(){estSetPropListFilters(this,tabl);}});
            
            dta.limit[0] = Number($(btnCont).data('from'));
            dta.limit[1] = Number($(btnCont).data('limit'));
            dta.dbbtns = dbBtns;
            
            }).promise().done(function(){
              
              $(tabl).data(dta);
              estSetPropListLimits(tabl);
              });
          });
      });
    
        //estPropCreate
    
    $('.estPropListTB').find('tr').each(function(i,tr){
      var levdta = $(tr).data();
      $.each(levdta,function(k,v){$(tr).removeAttr('data-'+k);});
      $(tr).find('td:last-child').on({
        click : function(e){
          console.log($(this).parent().data());
          }
        });
      });
    
    }
  
  
  
  function estDoPropILEdit(ele,fld){
    var dta = $(fld).data();
    if(dta.type == 'select'){
      var os = $(fld).find('option:selected');
      var nVal = $(os).val();
      var nTxt = $(os).text();
      }
    else{
      var nVal = $(fld).val();
      var nTxt = nVal;
      }
    
    if(nVal == $(ele).data('cval')){$('#estILEditDiv').remove(); return;}
    var tdta = {'maintbl':'estate_properties','mainkey':'prop_idx','mainkx':'int','mainidx':Number(dta.pid),'mainfld':dta.fld,'nval':nVal};
    if($(ele).hasClass('cursym')){
      $.extend(tdta,{'price':1});
      }
    $(ele).closest('tr').addClass('estFlipIt');
          
    $.ajax({
      url: vreFeud+'?6||0',
      type:'post',
      data:{'fetch':6,'propid':Number(dta.pid),'rt':'js','tdta':tdta},
      dataType:'json',
      cache:false,
      processData:true,
      success: function(ret, textStatus, jqXHR){
        $(ele).closest('tr').removeClass('estFlipIt');
        if(typeof ret.price !== 'undefined'){$(ele).data('cval',nVal).html(ret.price);}
        else{$(ele).data('cval',nVal).html(nTxt);}
        },
      error: function(jqXHR, textStatus, errorThrown){
        $(ele).closest('tr').removeClass('estFlipIt');
        console.log('ERRORS: '+textStatus+' '+errorThrown);
        estAlertLog(jqXHR.responseText);
        }
      });
    $('#estILEditDiv').remove();
    }
  
  
  function estPrepPropILEdits(){
    $('.estPropListILEdit').each(function(i,ele){
      var bDta = $(ele).data();
      $.each(bDta,function(k,v){$(ele).removeAttr('data-'+k);});
      $(ele).data(bDta).addClass('estBound').on({
        click : function(e){
          e.preventDefault();
          e.stopPropagation();
          $('#estILEditDiv').remove().promise().done(function(){
            var dta = $(ele).data();
            
            if(Number(dta.mode) == 2){
              estEditPricing(2,ele);
              return;
              }
            
            var defs = $('body').data('defs');
            var targ = $(ele).parent();
            var pid = dta.pid;
          
            var cont = $(JQDIV,{'id':'estILEditDiv','class':'popover fade in editable-container editable-popup'}).appendTo(targ).fadeIn(250);
            if(dta.type == 'select'){
              var fld = $(JQSEL,{'name':dta.fld,'id':dta.fld,'class':'tbox form-control'});
              var opts = [dta.opts];
              if(dta.opts.indexOf(',') > -1){opts = dta.opts.split(',');}
              $(opts).each(function(i,opt){$(JQOPT).val(dta.key == 'i' ? i : opt).html(opt).appendTo(fld);});
              }
            else if(dta.type == 'number'){var fld = $(JQNPT,{'type':'number','name':dta.fld,'id':dta.fld,'class':'tbox form-control'});}
            else{var fld = $(JQNPT,{'type':'text','name':dta.fld,'id':dta.fld,'class':'tbox form-control'});}
            $(fld).data(dta).appendTo(cont);
            $(fld).val(dta.cval).change();
            var gobtn = $(JQBTN,{'id':'estILEditGo','class':'btn btn-primary'}).data(fld).html('<i class="fa fa-check"></i>').on({
              click : function(e){
                e.preventDefault();
                e.stopPropagation();
                estDoPropILEdit(ele,fld);
                }
              }).appendTo(cont);
            });
          }
        });
      });
    }
  
  
  function estPrepPropListNewForm(){
    var defs = $('body').data('defs');
    
    
    var LeaseFrqDiv = $(JQDIV,{'class':'WSNWRP'}).appendTo($('input[name="prop_origprice"]').parent());
    /*
    var currencyBtn = $(JQBTN,{'id':'estPropCurrBtn','class':'btn btn-default estNoRightBord'}).html(defs.keys.cursymb[$('input[name="prop_currency"]').val()]).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        estSetDIMUbtns(3,this);
        }
      }).appendTo(LeaseFrqDiv);//.hide();
      */
    
    $('input[name="prop_origprice"]').appendTo(LeaseFrqDiv);
    
    var LeaseFrqBtn = $(JQBTN,{'id':'estPropLeaseFrqBtn','class':'btn btn-default estNoLeftBord'}).on({
      click : function(e){
        e.preventDefault();
        e.stopPropagation();
        estSetDIMUbtns(4,this);
        }
      }).appendTo(LeaseFrqDiv);
    
    //estateBuildDIMUbtns();
    estBuildPriceBtns();
    
    estSetDIMUbtns(4,LeaseFrqBtn,Number($('input[name="prop_leasefreq"]').val()));
    
    //estCurrencyCont
    estPrepLocaleForm(3);
    
    
    $('select[name="prop_zoning"]').on({
      change : function(){
        var propZone = Number($(this).val());
        var zoneDta = $.grep(defs.tbls.estate_listypes.dta, function (element, index) {return Number(element.listype_zone) == propZone;});
        $('select[name="prop_type"]').empty().promise().done(function(){
          //$(JQOPT,{'value':'0'}).html('- '+defs.txt.select1+' '+defs.txt.option+' -').appendTo('select[name="prop_type"]');
          $(zoneDta).each(function(i,opt){
            $(JQOPT,{'value':opt.listype_idx}).html(opt.listype_name).appendTo('select[name="prop_type"]');
            }).promise().done(function(){
              $('select[name="prop_type"]').change();
              });
          });
        }
      }).change();
    
        
    
    
    $('select[name="prop_listype"]').on({
      change : function(){
        if(this.value > 0){
          $('select[name="prop_leasedur"]').val(0).change();
          $('select[name="prop_leasedur"]').closest('tr').fadeOut();
          $('input[name="prop_origprice"]').removeClass('estNoRightBord');
          $('#estPropLeaseFrqBtn').fadeOut();
          }
        else{
          $('select[name="prop_leasedur"]').closest('tr').fadeIn();
          $('select[name="prop_leasedur"]').val($('select[name="prop_leasedur"]').data('pval')).change();
          $('input[name="prop_origprice"]').addClass('estNoRightBord');
          $('#estPropLeaseFrqBtn').fadeIn();
          }
        }
      }).change();
    }
  
  
  
  $(document).ready(function(){
    console.log(vreQry);
    $(document).data('estJSpth',$('#estJSpth').data('pth'));
    $('body').on({click : function(){$('.estThmMgrCont').remove()}});
    $('#estJSpth').remove();
    
    $('body').addClass('noFCOutline');
    var eUID = Number($('#estUIDdiv').data('euid'));
    var mainTbl = (typeof(vreQry.mode) !== 'undefined' ? vreQry.mode : 'estate_properties');
    var actn = (typeof(vreQry.action) !== 'undefined' ? vreQry.action : 'list');
    var mainId = (typeof(vreQry.id) !== 'undefined' ? vreQry.id : 0);
    
    $('#estHelpBlock').parent().addClass('noPAD');
    $('#estHelpBlock').parent().parent().find('.panel-heading').attr('id','estHelpHead');
    
    
    var stopForm = $('.estStopForm');
    if(stopForm.length > 0){
      $('button[type="submit"]').remove();
      console.log('Form Stopped');
      return;
      }
    
    if(mainTbl == 'estate_properties' && (actn == 'new' || actn == 'create')){
      window.location.assign(vrePath+'?mode=estate_properties&action=list&tab=1');
      return;
      }
    
    $('.estDBshowLnk').each(function(i,ele){
      $(ele).on({
        click : function(e){
          e.preventDefault();
          $('.'+$(this).attr('data-targ')).show();
          }
        })
      });
    
    $('.divRemTR').parent().parent().remove();
    
    if(document.getElementById('estInitialSetup')){
      return;
      }
    
    if(document.getElementById('estAgentFormTable')){
      //setCookie(eUID+'-estate_agencies-list-0', 1, 1);
      estPrepAgentProfile($('#estAgentFormTable'));
      return;
      }
    
    
    if(document.getElementById('estMainHelpPage')){
      estPrepMainHelp();
      return;
      }
    
    if(mainTbl == 'estate_agencies' && actn == 'edit' && mainId == 0){
      estPrepAgencyForm(mainId);
      return;
      }
    
    
    if($('div.admin-main-content').length > 0){
      var tabNo = 0;
      var navTabs = $('div.admin-main-content').find('li.nav-item');
      if(navTabs.length > 0){
        var navUL = $(navTabs).eq(0).closest('ul');
        var cookieName = eUID+'-'+mainTbl+'-'+actn+'-'+mainId;
        tabNo = getCookie(cookieName);
        if(typeof tabNo == 'undefined'){
          setCookie(cookieName, 0, 1);
          tabNo = 0;
          }
        
        $(navUL).find('li.nav-item').each(function(i,ele){
          $(ele).on({
            click : function(e){
              setCookie(cookieName, i, 1);
              if(mainTbl == 'estate_agencies'){
                if(i == 0){$(btnBar).show();}
                else{$(btnBar).hide();}
                }
              if(i == 1 && document.getElementById('est_prop_Map')){
                estSetMap('est_prop_Map');
                }
              $('.estEditHelpSect').hide().promise().done(function(){
                $('#estEditHelp-'+i).show();
                $('#estHelpBlock').stop().scrollTop('0px');
                });
              }
            });
          });
        }
        
      var btnBar = $('#admin-ui-edit').find('div.buttons-bar');
      
      
      if(mainTbl == 'estate_agencies'){
        if(actn == 'edit' || actn == 'create'){
          estPrepAgencyForm(mainId);
          }
        else if(actn == 'inbox'){}
        else{
          estPrepAgencyListTable();
          estPrepUserListTable();
          if(document.getElementById('estNewUserFormTable')){
            estPrepAgentProfile($('#estNewUserFormTable'));
            }
          if(tabNo > 0){
            if(tabNo > 1 && Number($('input[name="estNewUserPost"]').val()) === 0){tabNo = 1;}
            if(Number($('input[name="estNewUserPost"]').val()) < 0){tabNo = 2;}
            $(navUL).find('li').eq(tabNo).find('a').click();
            }
          }
        return;
        }
      }
    else{
      alert('WARNING: This plugin has interactive elements that do not work with your current Theme and/or Layout. Missing "<div class="admin-main-content">');
      }
    
    if(actn == 'prefs'){
      estPrefs();
      return;
      }
    
    if(mainTbl == 'estate_presets'){
      estPrepPresetsForm(mainId);
      return;
      }
    
    if(mainTbl == 'estate_properties'){
      
      $('select[name="prop_city"]').data('pval',Number($('select[name="prop_city"]').val()));
        
      
      sDta = {'fetch':(actn == 'list' ? 1 : 2),'propid':mainId,'rt':'js','tbl':''};
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
              $('body').data('defs',ret);
              var helpInFull = (typeof ret.prefs.helpinfull !== 'undefined' ? Number(ret.prefs.helpinfull) : 0);
              estSetHelpInFull();
              //nav-tabs
              if(actn == 'list'){
                if(mainTbl == 'estate_properties'){
                  var tabLen = Number($(navUL).find('li').length) - 1;
                  if(tabNo < tabLen){$(navUL).find('li').eq(tabNo).find('a').click();}
                  if(tabLen < 2){$('#estEditHelp-1').html($('#estEditHelp-2').html());}
                  //if(Number(defs.user.perm) >= Number(defs.prefs.public_mod)){}
                  estPrepPropILEdits();
                  estPrepPropListFilters();
                  estPrepPropListNewForm();
                  if(typeof vreQry.tab !== 'undefined'){
                    $('div.admin-main-content').find('li.nav-item').eq(0).closest('ul').find('li').eq(Number(vreQry.tab)).find('a').click();
                    }
                  
                  
                  $('select[name="prop_country"]').find('option[value="'+ret.prefs.country+'"]').prop('selected','selected');
                  $('select[name="prop_country"]').change();
                    console.log('Pref Country Loaded');
                  }
                
                if(!document.getElementById('estPropCreate')){
                  var targ = $('#admin-ui-list-filter').find('div.form-inline:first-child');
                  $(JQBTN,{'type':'button','class':'btn btn-default','title':ret.txt.create}).html(JQADI).on({
                    click : function(){window.location.assign(vreBasePath+'admin_config.php?mode='+mainTbl+'&action=create')}
                    }).appendTo(targ);
                  }
                
                if(helpInFull == 0){
                  $('#plugin-estate-list-table thead').on({mouseenter : function(){estScrollHlp('#estHlp-proplist3')}});
                  $('.estPropThumb').on({
                    mouseenter : function(){estScrollHlp('#estHlp-proplist6')},
                    mouseleave : function(){estScrollHlp('#estHlp-proplist3')}
                    });
                  }
                
                $('.estPropThumb').on({
                  click : function(e){estSetThmsForList(1,this)},
                  dblclick : function(e){
                    e.stopPropagation();
                    e.preventDefault();
                    estSetThmsForList(2,this)
                    }
                  });
                }
              else{
                if(actn == 'edit'){
                  $('body').data('propid',mainId).data('defs',ret).promise().done(function(){
                    
                    estatePrepForm(mainTbl);
                    });
                  }
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
    });


//fa-solid fa-chevron-left
//fa-solid fa-angles-left
//fa-solid fa-chevron-right
//fa-solid fa-angles-right
//fa-solid fa-9 (1,2,3,4,5,6,7,8)
//fa fa-navicon
//fa fa-plus
//fa fa-close
//fa fa-database
//fa fa-ellipsis-h
//fa fa-download
//fa fa-cogs
//ꯆ  fa-pencil **
//꯿  fa-pencil-square-o **


})(jQuery);