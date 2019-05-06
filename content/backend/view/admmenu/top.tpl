<div id="header">
    <div class="change_menu_state left animated effect-touch"><span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span></div>
    
    <div rel="mail_block" class="mail left opener animated effect-touch"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>{%ifset mail_count%}<div class="count">{%mail_count%}</div>{%endif%}</div>

	{%ifset pageTitle%}<div class="pageTitle left{%ifset pageAction%} withAction{%endif%}">{%pageTitle%}</div>{%endif%}
    {%ifset pageAction%}<div class="pageAction left"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>{%pageAction%}</div>{%endif%}
    
    
	<div rel="settings_block" class="settings right opener animated effect-touch"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></div>
    
    {%block user%}<div rel="user_block" class="user right opener animated effect-touch"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span>{%.login%}</span></div>{%end%}
	
    <div rel="language_block" class="language right opener animated effect-touch"><img src="{%root%}/img/lang/{%language_name%}.jpg" class="icon" width="20" height="13"></div>


	<div class="cms_loader animated"><div class="loader"></div></div>
    
 
    <div class="open-container at_left mail_block animated animation_speed_0_5s">
        {%list mail%}
            <a href="{%root%}/backend/{%language_name%}/index/link/mail/edit/{%.ID%}" class="item animated"><div class="subject">{%.subject%}</div><div class="to">{%lang Для%}: {%.to%}</div></a>
        {%end%}
        <a href="{%root%}/backend/{%language_name%}/index/link/mail/items" class="item animated"><div class="to">{%lang Перейти к списку писем%}</div></a>
    </div>

    <div class="open-container user_block animated animation_speed_0_5s">
    	<div class="item quit animated"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span><span>{%lang Выход%}</span></div>
    </div>
    <div class="open-container language_block animated animation_speed_0_5s">
   		{%controller catalog/language%}
    </div>
    <div class="open-container settings_block animated animation_speed_0_5s">
   		{%controller catalog/language_text%}
    </div>
</div>

<script type="text/javascript">
	$('#header .change_menu_state').click(function(){
		$("#admmenu").toggleClass("hideTabs");
		$(".cms_main").toggleClass("notFullWidth");
		
		var state = ($("#admmenu").hasClass("hideTabs")) ? 1 : 0;
		
		$.Request({ type: "json", controller: 'admmenu', action: 'menu_state_set', data: 'state=' + state });
		
		setTimeout(function(){
			$("#header .opener.left").each(function(index, element){
				var left = parseInt($(element).offset().left);
				var container = $("#header .open-container."+$(element).attr("rel"));
				container.css("left", left);
			});
		}, 300);
	});
	
	/*$(".cms_content").ready(function(){
		if($(window).width() < 1040){
			$("#admmenu").addClass("hideTabs");
			$(".cms_main").addClass("notFullWidth");
		}
	});*/
	
	$("#header .quit").bind("click", function(){
		$.Request({ type: "json", controller: "users", action: "quit", complete: function(){ window.location.reload(); } });	
	});
	
	$("#header .opener.right").each(function(index, element){
        var right = $(window).width() - (parseInt($(element).offset().left) + parseInt($(element).outerWidth()));
		var container = $("#header .open-container."+$(element).attr("rel"));
		container.css("right", right);
    });
	
	$("#header .opener.left").each(function(index, element){
        var left = parseInt($(element).offset().left);
		var container = $("#header .open-container."+$(element).attr("rel"));
		container.css("left", left);
    });
	
	var menuT;
	$("#header .opener").bind("click", function(){
		var container = $("#header .open-container."+$(this).attr("rel"));
		var allcontainers = $("#header .open-container."+$(this).attr("rel"));
		var other = $("#header .open-container:not(."+$(this).attr("rel")+")");
		if(container.hasClass("fadeOutDown") || (!container.hasClass("fadeOutDown") && !container.hasClass("fadeInUp"))){
			other.removeClass("fadeInUp").addClass("fadeOutDown");
			container.removeClass("fadeOutDown").addClass("fadeInUp");
			if(menuT){ clearTimeout(menuT); }
			container.stop().show();
			menuT = setTimeout(function(){ other.stop().hide(); }, 500);
		}else{
			allcontainers.removeClass("fadeInUp").addClass("fadeOutDown");
			if(menuT){ clearTimeout(menuT); }
			menuT = setTimeout(function(){ container.stop().hide(); }, 500);
		}
	});	
	
	$(document).bind("mouseup", function(e){
		var container = $("#header .open-container, #header .opener");
		if(container.has(e.target).length === 0){
			if(container.hasClass("fadeInUp")){
				$("#header .open-container").removeClass("fadeInUp").addClass("fadeOutDown");
				if(menuT){ clearTimeout(menuT); }
				menuT = setTimeout(function(){ $("#header .open-container").stop().hide(); }, 500);
			}
		}
	});
</script>