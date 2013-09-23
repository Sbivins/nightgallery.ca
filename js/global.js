
$(document).ready(function(){

		//INTO LINK OPENS LIST
		$("#previouspage #previouslx #previouslist ul li a.calitem").hover(function(event){
			//var firstClass = $(this).attr('class').split(' ')[0];
			
			var firstClass = $(this).attr('class').split(' ')[0];
			
			
			$("#previousrx > div."+ firstClass).show();
			$("#previousrx > div."+ firstClass).siblings().hide();
			
		},	function(event){
			//var firstClass = $(this).attr('class').split(' ')[0];
			
		});
			
});




/*
$(document).ready(function(){

		//INTO LINK OPENS LIST
		$(".eventyear").click(function(event){
			//var firstClass = $(this).attr('class').split(' ')[0];
			
			var firstClass = $(this).attr('class').split(' ')[1];
			//alert(firstClass);
			
			$("#previouslist span."+ firstClass).toggle();
			
		});
			
});
*/

