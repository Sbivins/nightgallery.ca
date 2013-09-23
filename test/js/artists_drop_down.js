$(document).ready(function(){

		//INTO LINK OPENS LIST
		$(".exhiblink").mouseover(function(event){
			$("#exhibdrop").toggle();
			
			//alert("As you can see, the link no longer took you to jquery.com");
		});
		
		//OUT OF LIKE CLOSES LIST
		$(".exhiblink").mouseout(function(event){
			$("#exhibdrop").toggle();
		});
		
		//INTO LIST KEEPS LIST
		$("#exhibdrop").mouseover(function(event){
			$(this).show();
		});
		
		//OUT OF LIST CLOSES LIST, SHOWS MAIN IMAGE
		$("#exhibdrop").mouseout(function(event){
			$(this).hide();
			
		});
		
		//OVER ARTIST LINK SHOWS THAT ARTIST'S IMAGE.
		//This works because the artist link and the image are both
		//given the same class (artist's name from query with remove_spaces() on it)
		$(".artists a").mouseover(function(event){
			var firstClass = $(this).attr('class').split(' ')[0];
			//alert(firstClass);
			$("#artistimagepane div."+ firstClass).show();
			$("#artistimagepane").show();
			$("#artistimagepane div."+ firstClass).siblings().hide();
		});
		
});

	