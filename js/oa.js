// JavaScript Document

var vreQry = searchToObject();
var vrePath = window.location.pathname;
var vrePathPts = vrePath.split('/');
var vrePage = vrePathPts.pop();
var vreBasePath = vrePath.replace(vrePage,'');

(function ($) {
  
  function estOAPrep(){
    console.log('OA Prep');
    
    if($('#estJSpth').hasClass('estOA')){}
    
    $('.estOABlock').each(function(i,blk){
      var h3 = $(blk).find('h3');
      var dv1 = $(blk).find('div.estOATabCont');
      
      $(h3).on({
        click : function(e){
          e.stopPropagation();
          console.log(dv1);
          if($(dv1).is(':visible')){$('.estOABlock').removeClass('expand');}
          else{
            $('.estOABlock').removeClass('expand').promise().done(function(){$(blk).addClass('expand')});
            }
          }
        });
      });
    
        
    
    
    }
  
  
  
  $(document).ready(function(){
    estOAPrep();
    });

})(jQuery);