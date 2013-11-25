$(function() {
	var name = $("#title"), contents = $("#contents"), password = $("#imageUrl"), allFields = $([]).add(name).add(password), tips = $(".validateTips");

	function updateTips(t) {
		tips.text(t).addClass("ui-state-highlight");
		setTimeout(function() {
			tips.removeClass("ui-state-highlight", 1500);
		}, 500);
	}

	function checkWordLength(content,name, min, max) {
		var words = content.split(" ");
		if (words.length >= max || words.length < min) {
			o.addClass("ui-state-error");
			updateTips("Length of " + name + " must be between " + min + " and " + max + ".");
			return false;
		} else {
			return true;
		}
	}

	$("#dialog-form").dialog({
		autoOpen : false,
		height : 700,
		width : 650,
		modal : true,
		resizable : true,
		buttons : {
			"Add Article" : function() {
				var bValid = true;
				
				allFields.removeClass("ui-state-error");
				bValid = checkWordLength(contents, "content", 100, 2000);
				if (bValid) {
					$("#add-article-form").submit();
					$(this).dialog("close");
				}
			},
			Cancel : function() {
				$(this).dialog("close");
			}
		},
		close : function() {
			allFields.val("").removeClass("ui-state-error");
		}
	});

	$("#add-article").click(function() {
		$("#dialog-form").dialog("open");
	});
});
