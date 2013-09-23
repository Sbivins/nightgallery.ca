
$(document).ready(function(){

	$("#ads a > div").hide();

	$("#ads a").mouseenter(function(event){
		$(this).children('p').hide();
		$(this).children('div').show();
		//alert("in");
		
	});
	
	$("#ads a").mouseleave(function(event){
		$(this).children('div').hide();
		$(this).children('p').show();
				//alert("out");
	});
	
	
	/*$(".contactlink").mouseover(function(event){
		$(".contactzone").toggle();
		$(".rightcontent").toggle();
		//alert("As you can see, the link no longer took you to jquery.com");
	});
	$(".contactlink").mouseout(function(event){
		$(".contactzone").toggle();
		$(".rightcontent").toggle();
	});
	
	$(".contactzone").mouseover(function(event){
		$(this).show();
		$(".rightcontent").hide();
	});
	
	$(".contactzone").mouseout(function(event){
		$(this).hide();
		$(".rightcontent").show();			
	});	*/
		
});

	