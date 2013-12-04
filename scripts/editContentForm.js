$(function() {
	$(document).ready(function(){

		if($('#userInfo').data("role") == "writer" && $('#articleStatus').data("status") != "awaiting_changes"){
			$('#editArticle').hide();
			$('#editArticle').prop("disabled", true);
		}
	});
	var title = $('#title'), contents = $('#contents'), type = $('#mainContent').data('type'), allFields = $([]).add(title).add(contents), tips = $('.validateTips');

	function updateTips(t) {
		tips.text(t).addClass('ui-state-highlight');
		setTimeout(function() {
			tips.removeClass('ui-state-highlight', 1500);
		}, 500);
	}

	function checkWordLength(element, name, min, max) {
		if (element == undefined || element.val() == undefined) {
			updateTips(name + ' cannot be empty.');
			return false;
		}
		var words = element.val().split(' ');
		if (words.length >= max || words.length < min) {
			element.addClass('ui-state-error');
			updateTips('Length of ' + name + ' must be between ' + min + ' and ' + max + ' words.');
			return false;
		} else {
			return true;
		}
	}


	$('#addNewForm-div').dialog({
		autoOpen : false,
		height : 650,
		width : 650,
		modal : true,
		resizable : true,
		buttons : {
			'Submit' : function() {
				var bValid = true;
				allFields.removeClass('ui-state-error');
				bValid = bValid && checkWordLength(contents, 'Contents', 100, 2000);
				bValid = bValid && checkWordLength(title, 'Title', 2, 100);
				if($('#recommended').length > 0){
					if(!$('#recommended').is(':checked')){
						$('#recommended').val("false");
						$('#recommended').hide();
						$('#recommended').prop('checked', true);
					}
				}
				if (bValid) {
					$('#add-new-form').submit();
					$(this).dialog('close');
				}
			},
			Cancel : function() {
				$(this).dialog('close');
			}
		},
		close : function() {
			allFields.val('').removeClass('ui-state-error');
		}
	});

	$('#editArticle').click(function() {
		$('#id').val($('#mainContent').data('id'));
		$('#title').val($('#articleTitle').text());
		$('#contents').val($('#articleText').text());
		$('#imgUrl').val($('#articleImage').attr('src'));
		$('#add-new-form').attr('action', '/edit/content');
		if($('#userInfo').data("role") != "writer"){
			$('#extraFields').load('/views/SharedArticleStatusDropdown.php', function(){	
					$('#statusMenu option[value="' + $('#articleStatus').data('status') +'"]').attr('selected','selected');
				});
		}
		if (type != "Article") {
			$('#topic').val($('#articleTopic').text());
			if (type == "Review") {
				$('#topic-field').attr("disabled", false);
				$('#topic-field').show();
				$('#rating-field').find($('input:checked')).removeAttr("checked");
				$('#rating-field').find($("input[value=" + $('#articleRating').text()+ "]")).attr("checked", true);
				$('#rating-field').attr("disabled", false);
				$('#rating-field').show();
				$('#addNewForm-div').dialog('option', 'title', 'Edit Review');
				$('#addNewForm-div').dialog('open');
			} else {
				$('#topic-field').attr("disabled", false);
				$('#topic-field').show();
				$('#rating-field').attr("disabled", true);
				$('#rating-field').hide();
				$('#addNewForm-div').dialog('option', 'title', 'Edit Column');
				$('#addNewForm-div').dialog('open');
			}
		} else {
			$('#rating-field').attr("disabled", true);
			$('#rating-field').hide();
			$('#topic-field').attr("disabled", true);
			$('#topic-field').hide();
			$('#addNewForm-div').dialog('option', 'title', 'Edit Article');
			$('#addNewForm-div').dialog('open');
		}
		if($('#articleStatus').data('status') != "awaiting_changes" || $('#articleStatus').data('status') != "under_review"){
			$('#title').prop("disabled", true);
			$('#contents').prop("disabled", true);
			$('#imgUrl').prop("disabled", true);
			$('#topic-field').prop("disabled", true);
			$('#rating-field').prop("disabled", true);
		}
	});
});
