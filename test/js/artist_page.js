
$(document).ready(function(){

		var hash = document.location.href.split("#")[1];
		if(hash) {
			$("#artistrx div."+ hash).show();
			$("#artistrx div."+ hash).siblings().hide();
		}
		
		
		
		
		//INTO LINK OPENS LIST
		$("#artistlx ul a").click(function(event){
			var firstClass = $(this).attr('class').split(' ')[0];
			
			$("#thumbsreel div").removeClass('selected').addClass('unselected');
			
			$("#artistrx div."+ firstClass).show();
			$("#artistrx div."+ firstClass).siblings().hide();
			
			$("#artworkinfo div").hide();
			
			
		});
		
		
		
		//ROLL OVER THUMB OPENS THAT ONE.
		$("#thumbsreel div").hover(function(event){
				$("#artistrx div.images").show();
				$("#artistrx div.images").siblings().hide();
	
				$(this).removeClass('unselected').addClass('selected');
				$(this).siblings().removeClass('selected').addClass('unselected');
				var firstClass = $(this).attr('class').split(' ')[0];
				$("#artistrx div."+ firstClass).show();
				$("#artistrx div."+ firstClass).siblings().hide();
				
				$("#artworkinfo div."+ firstClass).show();
				$("#artworkinfo div."+ firstClass).siblings().hide();
		});
		
		
});

	