$(document).ready(function(){

		/* 1 = 122 */
		/* 2 = 183 */
		/* 4 = 244 */


		var thumberheight = $("#thumbsreel").height();
		//alert(thumberheight);
		
		var rows = thumberheight/61;
		//alert(rows);
		var views = rows-3;
	
		var currentview = 0;
		
		
		
		function updatebuttons(){
			//if views is 0, all things are unclickable.
			//if currentview is 0, thumber-up is unclickable
			//if currentview is greater than 0 AND less than views, they are both clickable, 
			//if current views is equal to views, thumber down is unclickable
			$("#thumber-up").removeClass('clickable').addClass('unclickable');
			$("#thumber-down").removeClass('clickable').addClass('unclickable');
			if(currentview > 0 ) {
				$("#thumber-up").removeClass('unclickable').addClass('clickable');
			}
			if(currentview < views) {
				$("#thumber-down").removeClass('unclickable').addClass('clickable');
			}
		
    	}
    	
    	updatebuttons();
		
		
		// to 0
		$("#thumber-up").click(function(event){
			
			if (currentview > 0) {
					
					$("#thumbsreel").animate({ top: '+=61px', }, 
					50,
					function() {
					});
				
				currentview --;
				updatebuttons();
				//alert(currentview);
				
				
				
			} else {
				//alert("NOPE! at minimum");
			}
			
			
			
		});
		
		
		$("#thumber-down").click(function(event){
			
			if (currentview < views) {
			
					$("#thumbsreel").animate({ top: '-=61px', }, 
					50,
					function() {
					});
					
				currentview ++;
				updatebuttons();
				
				
				//alert(currentview);	
			} else {
				//alert("at maximum");
			}
			
			
			
		});
	
});