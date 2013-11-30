$(function() {
	$('.memberInfo').each( function(){
		$(this).find('option[value="' + $(this).find('p').attr('title') +'"]').attr('selected','selected');
	});
	
	$('.memberInfo select').on('change', function(){
		$('#actions').show();
		$(this).off('change');
	});
});