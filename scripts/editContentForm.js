$(function() {
	var title = $('#title'), contents = $('#contents'), allFields = $([]).add(title).add(contents), tips = $('.validateTips');

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
		$('#id').val($('#articleId').text());
		$('#title').val($('#articleTitle').text());
		$('#contents').val($('#articleText').text());
		$('#imgUrl').val($('#articleImage').attr('src'));
		$('#extraFields').load('/views/SharedArticleStatusDropdown.php', function(){	
				$('#statusMenu option[value="' + $('#articleStatus').attr('title') +'"]').attr('selected','selected');
			});
		if ($('#articleTopic').length > 0) {
			$('#topic').val($('#articleTopic').text());
			if ($('#articleRating').length > 0) {
				$('#add-new-form').attr('action', '/edit/editReview');
				$('#topic-field').attr("disabled", false);
				$('#topic-field').show();
				$('#rating-field').attr("disabled", false);
				$('#rating-field').show();
				$('#addNewForm-div').dialog('option', 'title', 'Edit Review');
				$('#addNewForm-div').dialog('open');
			} else {
				$('#add-new-form').attr('action', '/edit/editColumn');
				$('#topic-field').attr("disabled", false);
				$('#topic-field').show();
				$('#rating-field').attr("disabled", true);
				$('#rating-field').hide();
				$('#addNewForm-div').dialog('option', 'title', 'Edit Column');
				$('#addNewForm-div').dialog('open');
			}
		} else {
			$('#add-new-form').attr('action', '/edit/article');
			$('#rating-field').attr("disabled", true);
			$('#rating-field').hide();
			$('#topic-field').attr("disabled", true);
			$('#topic-field').hide();
			$('#addNewForm-div').dialog('option', 'title', 'Edit Article');
			$('#addNewForm-div').dialog('open');
		}
	});
});
