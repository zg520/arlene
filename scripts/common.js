$(document).ready(function(){   
	$(".nav").children("li").each(function() {
    $(this).children("a").css({backgroundImage:"none"});
	}); 
	
	$(".nav").children("li").each(function() {
    var current = "nav current-" + ($(this).attr("class"));
    var parentClass = $(".nav").attr("class");
    if (parentClass != current) {
        $(this).children("a").css({backgroundImage:"none"});
    }
});
function attachNavEvents(parent, myClass) {
    $(parent + " ." + myClass).mouseover(function() {
        $(this).before('
');
        $("div.nav-" + myClass).css({display:"none"})»
        .fadeIn(200);
    }).mouseout(function() {
        $("div.nav-" + myClass).fadeOut(200, function() {
            $(this).remove();
        });
    }).mousedown(function() {
        $("div.nav-" + myClass).attr("class", "nav-" »
        + myClass + "-click");
    }).mouseup(function() {
        $("div.nav-" + myClass + "-click").attr("class", »
        "nav-" + myClass);
    });
}
attachNavEvents(".nav", "home");
attachNavEvents(".nav", "login");
});