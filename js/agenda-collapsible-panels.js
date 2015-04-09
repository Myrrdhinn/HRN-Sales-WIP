$(document).ready(function(){

$('.SessionHeader').bind('click', function () {
  $(this).siblings(".SessionContent").slideToggle(200);
});

});