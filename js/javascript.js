$(function(){
	hoverClone();
	cloneCycle();
	commentPopup();
});

$(".chzn-select").chosen();

// on the plate_records and library_view pages, when hovered over a well, fade in the div (lower right screen) with the clone and gene
function hoverClone(){
	if ($('body#plate_records, body#library_view').length==1) {
		$('.well, .wellSmall').mouseover(function(){
			wellPosition = $(this).attr('id');
			cloneInfo = $(this).next('.invisible').html();
			if (cloneInfo == '<br>') {
				cloneInfo = 'no clone';
			}
		
			$('#hoverClone').html(wellPosition + ':<br>' + cloneInfo).fadeIn();
		});
	
		$('.plate, .plateSmall').mouseleave(function(){
			$('#hoverClone').fadeOut();
		});
	}
}

// on the new_stamp page, after clicking on a well, cycle through the status options
function cloneCycle(){
	if ($('body#new_stamp').length==1) {
		$('.wellMedium').click(function(){
			status = $(this).attr('class').replace(/wellMedium status/, '');
			$(this).removeClass('status' + status);
			switch(status) {
				case '0': 
					status = 2;
					break;
				case '1':
					status = 0;
					break;
				case '2':
					status = 1;
					break;
			}
			$(this).addClass('status' + status);
			$(this).next('input').val(status);
		});
	}
}

// on the new_stamp page, after clicking a well's comment button, fade in a div with the comment textarea.
function commentPopup() {
	if ($('body#new_stamp').length==1) {
		$('.commentButton').click(function(){
			well_position = $(this).attr('id');
			position = $(this).position();
		
			$('.commentPopup#' + well_position).css('top', position.top + 20).css('left', position.left + 20).fadeIn();
		});
		
		//click on the spanned 'X' (closeButton) to fade out the div
		$('.closeButton').click(function(){
			$('.commentPopup').fadeOut();
		});
	}
}
