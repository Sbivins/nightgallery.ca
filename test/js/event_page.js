
$(document).ready(function(){

		
$(".eventtitle").click(function(event){
			var firstClass = $(this).attr('class').split(' ')[0];
			
			$("#thumbsreel div").removeClass('selected').addClass('unselected');
			
			$("#eventrx div."+ firstClass).show();
			$("#eventrx div."+ firstClass).siblings().hide();
			
			$("#eventrx > div > span").show();
			$("#eventrx > div > div").hide();
			
		});
		
		
		
		//ROLL OVER THUMB OPENS THAT ONE.
		$("#thumbsreel div").hover(function(event){
				$("#eventrx div.default").show();
				$("#eventrx div.default").siblings().hide();
	
				$(this).removeClass('unselected').addClass('selected');
				$(this).siblings().removeClass('selected').addClass('unselected');
				var firstClass = $(this).attr('class').split(' ')[0];
				$("#eventrx div."+ firstClass).show();
				$("#eventrx div."+ firstClass).siblings().hide();
				
				$("#artworkinfo div."+ firstClass).show();
				$("#artworkinfo div."+ firstClass).siblings().hide();
		});
		
		/* 1 = 122 */
		/* 2 = 183 */
		/* 4 = 244 */
			
		
	});

	