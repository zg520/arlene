$(document).ready(function() {
	$(".previewImage").one("load", function() {
		var height = $(this).css("height");
		$(this).parent().css("height", height);
		$(this).prev().css("max-width", ($(this).css("width").substring(0, $(this).css("width").length -2) - 10) + "px");
	});
	
});

$(function() {
	var name = $("#name"), password = $("#password"), allFields = $([]).add(name).add(password), tips = $(".validateTips");

	function updateTips(t) {
		tips.text(t).addClass("ui-state-highlight");
		setTimeout(function() {
			tips.removeClass("ui-state-highlight", 1500);
		}, 500);
	}

	function checkLength(o, n, min, max) {
		if (o.val().length > max || o.val().length < min) {
			o.addClass("ui-state-error");
			updateTips("Length of " + n + " must be between " + min + " and " + max + ".");
			return false;
		} else {
			return true;
		}
	}

	function checkRegexp(o, regexp, n) {
		if (!( regexp.test(o.val()) )) {
			o.addClass("ui-state-error");
			updateTips(n);
			return false;
		} else {
			return true;
		}
	}


	$("#dialog-form").keypress(function(e) {
		if (e.keyCode == $.ui.keyCode.ENTER) {
			$("#userForm").submit();
			$(this).dialog("close");
		}
	});
	$("#dialog-form").dialog({
		autoOpen : false,
		height : 350,
		width : 350,
		modal : true,
		resizable : false,
		buttons : {
			"Go" : function() {
				var bValid = true;
				allFields.removeClass("ui-state-error");

				bValid = bValid && checkLength(name, "username", 3, 16);
				bValid = bValid && checkLength(password, "password", 5, 16);

				bValid = bValid && checkRegexp(name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter.");
				bValid = bValid && checkRegexp(password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9");

				if (bValid) {
					$("#userForm").submit();
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

	$("#user-login").click(function() {
		$('#dialog-form').dialog('option', 'title', 'Login');
		$('#userForm').attr('action', '/members/login');
		$("#dialog-form").dialog("open");
	});
	
	$("#user-register").click(function() {
		$('#dialog-form').dialog('option', 'title', 'Register');
		$('#userForm').attr('action', '/members/register');
		$("#dialog-form").dialog("open");
	});
	$(".ui-state-highlight").click(function() {
		$(this).hide(0);
	});
	$("#statusSubmit span").mouseover(function() {
		$('#statusSubmit form').show();
	});
	$("#statusSubmit").mouseleave(function() {
		$('#statusSubmit form').hide();
	});
	$(".ui-state-highlight").each(function(index) {
		$(this).delay(400 * index).fadeIn(300);
	});
});
