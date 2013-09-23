$(document).ready( function() {


	$('.thumb').each(function(){
		
		var k = $(this);
		
		var name = $(this).css('background-image'); 
		
		$(this).css('background-image', 'url(http://www.nightgallery.ca/img/loading.gif)');
	
		var patt=/\"|\'|\)|\(|url/g;
	
		name = name.replace(patt,'');
		
		var c = new Image();
		c.src = name;
		
		var urled = 'url(' + name + ')';
		//alert(urled);
		
		c.onload = function(){
			//alert(name + " loaded");
			k.css('background-image', urled);
	    }
	    
	});

// original code getting bg info
});


