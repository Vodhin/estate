// JavaScript Document

var vreQry = searchToObject();
var vrePath = window.location.pathname;
var vrePathPts = vrePath.split('/');
var vrePage = vrePathPts.pop();
var vreBasePath = vrePath.replace(vrePage,'');

(function ($) {
  
  function estOAPrep(){
    console.log('OA Prep');
    }
  
  
  
  $(document).ready(function(){
    if($('#estJSpth').hasClass('estOA')){
      estOAPrep();
      }
    });

})(jQuery);