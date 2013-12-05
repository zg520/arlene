$(function() {
	var title = $('#title'), 
	contents = $('#contents'),
	url = $('#imgUrl'), 
	type = "article",
	columnTopics = $('#add-column').data('topics'),
	reviewTopics = $('#add-review').data('topics'),
	allFields = $([]).add(title).add(contents), 
	tips = $('.validateTips');

	function getBaseUrl(){
		if(location.href.indexOf('#') > -1){
			return location.href.substring(0, location.href.length -1);
		}
		return location.href;
	}
	function updateTips(t) {
		tips.text(t).addClass('ui-state-highlight');
		setTimeout(function() {
			tips.removeClass('ui-state-highlight', 1500);
		}, 500);
	}

	function checkWordLength(element, name, min, max) {
		if(element == undefined || element.val() == undefined){
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
	
	function isValidUrl(element){
		if(element == undefined || element.val() == undefined){
			updateTips(name + ' cannot be empty.');
			return false;
		}
		if(/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(element.val())){
			return true;
		}
			element.addClass('ui-state-error');
			updateTips('Invalid Image url entered.');
			return false;
	}
	function checkTopics(element, topics){

		if(element == undefined || element.val() == undefined){
			updateTips(name + ' cannot be empty.');
			return false;
		}
		var topic = element.val();
		if(topics.indexOf(topic) == -1){
			element.addClass('ui-state-error');
			updateTips('Allowed values for topics are: ' + topics.join());
			return false;
		}
		return true;
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
				bValid = bValid && isValidUrl(url);
				if(type == "column"){
					bValid = bValid && checkTopics($('#topic'), columnTopics);
				}
				if(type == "review"){
					bValid = bValid && checkTopics($('#topic'), reviewTopics);
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

	$('#add-article').click(function() {
		$('#add-new-form').attr('action', getBaseUrl() + '/addArticle');
		$('#rating-field').attr("disabled", true);
		$('#rating-field').hide();
		$('#topic-field').attr("disabled", true);
		$('#topic-field').hide();
		$('#addNewForm-div').dialog('option', 'title', 'New Article');
		$('#addNewForm-div').dialog('open');

	});
	
	$('#add-column').click(function() {
		type = "column";
		$('#add-new-form').attr('action',  getBaseUrl() + '/addColumn');
		$('#topic-field').attr("disabled", false);
		$('#topic').autocomplete({source: columnTopics});
		$('#topic-field').show();
		$('#rating-field').attr("disabled", true);
		$('#rating-field').hide();
		$('#addNewForm-div').dialog('option', 'title', 'New Column');
		$('#addNewForm-div').dialog('open');
	});
	$('#add-review').click(function() {
		type = "review";
		$('#add-new-form').attr('action', getBaseUrl() + '/addReview');
		$('#topic-field').attr("disabled", false);
		$('#topic').autocomplete({source: reviewTopics});
		$('#topic-field').show();
		$('#rating-field').attr("disabled", false);
		$('#rating-field').show();
		$('#addNewForm-div').dialog('option', 'title', 'New Review');
		$('#addNewForm-div').dialog('open');
	});
});
