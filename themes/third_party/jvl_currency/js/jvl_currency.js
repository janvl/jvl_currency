$(function ()
{
	$(".jvl_currency input").focus(function(){
		$(this).parent().css("background-position","bottom left");
		$(this).parent().children("span").css("color","#fff");
		$(this).parent().children("span").css("text-shadow","0px 1px 0px #0354b6");
	});
	$(".jvl_currency input").blur(function(){	
		$(this).parent().css("background-position","top left");
		$(this).parent().children("span").css("color","#646c71");
		$(this).parent().children("span").css("text-shadow","0px 1px 0px #fff");
	});
});
