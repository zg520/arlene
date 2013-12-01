$(function() {
	
	var members = new Array();
	
	$('.memberInfo').each( function(){
		var role = $(this).find(".userRole").val();
		$(this).find('option[value="' + role +'"]').attr('selected','selected');
		members[$(this).find(".userId").val()] = role;
		if(role == "publisher"){
			$(this).find(".memberTypeMenu").prop('disabled', true);
		}
	});
	
	$('.memberInfo select').on('change', function(){
		if(!$('#actions').is(":visible")){
			$('#actions').show();
		}
		var parentDiv = $(this).parent().closest('div');
		var userId = parentDiv.find(".userId");
		
		var userRole = parentDiv.find(".userRole");
		userRole.attr('value', parentDiv.find(".memberTypeMenu :selected").val());
		
		if(members[userId] != userRole){
			parentDiv.find(".userRole").removeAttr('disabled');
			parentDiv.find(".userId").removeAttr('disabled');
		}
		
	});
	
	$("#apply").click(function(){
		$(".memberTypeMenu").prop("disabled", true);
		$("#members").submit();
	});
});