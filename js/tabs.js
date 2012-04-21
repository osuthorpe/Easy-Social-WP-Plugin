jQuery(document).ready(function() {	


  //Get all the LI from the #tabMenu UL
  $('#tabMenu li').click(function(){
    
    //perform the actions when it's not selected
    if (!$(this).hasClass('selected')) {    
           
	    //remove the selected class from all LI    
	    $('#tabMenu li').removeClass('selected');
	    
	    //Reassign the LI
	    $(this).addClass('selected');
	    
	    //Hide all the DIV in .boxBody
	    $('.boxBody div.parent').slideUp('1500');
	    
	    //Look for the right DIV in boxBody according to the Navigation UL index, therefore, the arrangement is very important.
	    $('.boxBody div.parent:eq(' + $('#tabMenu > li').index(this) + ')').slideDown('1500');
	    
	 }
    
  }).mouseover(function() {

    //Add and remove class, Personally I dont think this is the right way to do it, anyone please suggest    
    $(this).addClass('mouseover');
    $(this).removeClass('mouseout');   
    
  }).mouseout(function() {
    
    //Add and remove class
    $(this).addClass('mouseout');
    $(this).removeClass('mouseover');    
    
  });

	//Mouseover with animate Effect for Category menu list
  $('.boxBody #category li').click(function(){

    //Get the Anchor tag href under the LI
    window.location = $(this).children().attr('href');
  }).mouseover(function() {

    //Change background color and animate the padding
    $(this).css('backgroundColor','#888');
    $(this).children().animate({paddingLeft:"20px"}, {queue:false, duration:300});
  }).mouseout(function() {
    
    //Change background color and animate the padding
    $(this).css('backgroundColor','');
    $(this).children().animate({paddingLeft:"0"}, {queue:false, duration:300});
  });  
	
	//Mouseover effect for Posts, Comments, Famous Posts and Random Posts menu list.
  $('#.boxBody li').click(function(){
    window.location = $(this).children().attr('href');
  }).mouseover(function() {
    $(this).css('backgroundColor','#888');
  }).mouseout(function() {
    $(this).css('backgroundColor','');
  });  	
	
});