$(document).ready( function() {

	$('.preloadcontain').each(function(){
		
		var k = $(this);
		
		var name = $(this).css('background-image'); 
		
		$(this).css('background-image', 'url(http://www.nightgallery.ca/img/loading.gif)');
		$(this).css('background-size', 'cover');
	
		var patt=/\"|\'|\)|\(|url/g;
	
		name = name.replace(patt,'');
		
		var c = new Image();
		c.src = name;
		
		var urled = 'url(' + name + ')';
		//alert(urled);
		
		c.onload = function(){
			//alert(name + " loaded");
			//k.addClass('activethumb');
			k.css('background-image', urled);
			k.css('background-size', 'contain');
	    }
	    
	});
	
	$('.preloadcover').each(function(){
		
		var k = $(this);
		
		var name = $(this).css('background-image'); 
		
		$(this).css('background-image', 'url(http://www.nightgallery.ca/img/loading.gif)');
		$(this).css('background-size', 'cover');
	
		var patt=/\"|\'|\)|\(|url/g;
	
		name = name.replace(patt,'');
		
		var c = new Image();
		c.src = name;
		
		var urled = 'url(' + name + ')';
		//alert(urled);
		
		c.onload = function(){
			//alert(name + " loaded");
			//k.addClass('activethumb');
			k.css('background-image', urled);
	    }
	    
	});
	


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
			//k.addClass('activethumb');
			k.css('background-image', urled);
	    }
	    
	});

// original code getting bg info
});


