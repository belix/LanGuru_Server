// All JS Stuff


$(document).ready(function() {
	
	$('#navigation li').click(function(){
		
		var id = $(this).attr('data-id');
		
		$.ajax({
				type: "POST",
				url: window.location.pathname,
				async: true,
				data: { id: id },
				success: function(html) {
					console.log("ajax success");
				}
						
			});
	});	
	
});

