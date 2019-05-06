<script type="text/javascript">

	/* Styled Tooltip */
	
	$(function(){
		$(document).tooltip({ items: ".control-label a" });
	});
		


	/* Hide Alert */
	
	setInterval(function(){ if(parseInt($('.alert:not(.inline)').css("opacity")) > 0){ $('.alert:not(.inline)').disappear(); } }, 2000);
	
	
	
	/* Parent Checkbox */
	
    $('#{%oc%} .parent_checkbox').bind('click', function(){ 
		var checkboxes = $(this).parent().parent().parent().parent().parent().find('input[type=checkbox]');
		checkboxes.prop("checked", $(this).is(':checked'));
    });
	
	
	
	/* Colorpicker */
	
	$('.colorpicker-palette').colorpicker({
		colorSelectors: {
			'#dddddd': '#dddddd',
			
			'#f1c40f': '#f1c40f',
			'#f39c12': '#f39c12',
			'#e67e22': '#e67e22',
			'#d35400': '#d35400',
			'#e74c3c': '#e74c3c',
			'#c0392b': '#c0392b',
			'#ffffff': '#ffffff',
			'#bdc3c7': '#bdc3c7',
			'#95a5a6': '#95a5a6',
			'#7f8c8d': '#7f8c8d',
			
			'#dddddd': '#dddddd',
			'#1abc9c': '#1abc9c',
			'#16a085': '#16a085',
			'#2ecc71': '#2ecc71',
			'#27ae60': '#27ae60',
			'#3498db': '#3498db',
			'#2980b9': '#2980b9',
			'#9b59b6': '#9b59b6',
			'#8e44ad': '#8e44ad',
			'#34495e': '#34495e',
			'#2c3e50': '#2c3e50',
		}
	});
	
	
	
	/* Delete confirmation */
	
	$('#{%oc%} .delete').bind('click', function() {
		if(confirm('{%lang Вы действительно желаете удалить элемент?%}')){
			$.Request({ type: "json", controller: '{%controllerName%}', action: 'delete', data: "object={%ID%}", complete: function(responce){ window.location.href="/backend/{%language_name%}/index/link/{%controllerName%}/items/"; } });
		}
		return false;	
	});
	
	/* Back action */
	
	$('#{%oc%} .back').bind('click', function() {
		location.href = document.referrer;
	});
	
	
	
	/* Icons Dropdown */
	
	var icons = '<li value="cog"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></li><li value="search"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></li><li value="plus"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></li><li value="music"><span class="glyphicon glyphicon-music" aria-hidden="true"></span></li><li value="minus"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></li><li value="cloud"><span class="glyphicon glyphicon-cloud" aria-hidden="true"></span></li><li value="envelope"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></li><li value="pencil"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></li><li value="star"><span class="glyphicon glyphicon-star" aria-hidden="true"></span></li><li value="user"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></li><li value="film"><span class="glyphicon glyphicon-film" aria-hidden="true"></span></li><li value="th-large"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span></li><li value="signal"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span></li><li value="home"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></li><li value="file"><span class="glyphicon glyphicon-file" aria-hidden="true"></span></li><li value="inbox"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span></li><li value="play-circle"><span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span></li><li value="lock"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></li><li value="headphones"><span class="glyphicon glyphicon-headphones" aria-hidden="true"></span></li><li value="tag"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span></li><li value="book"><span class="glyphicon glyphicon-book" aria-hidden="true"></span></li><li value="camera"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span></li><li value="font"><span class="glyphicon glyphicon-font" aria-hidden="true"></span></li><li value="align-justify"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span></li><li value="picture"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></li><li value="facetime-video"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span></li><li value="map-marker"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></li><li value="adjust"><span class="glyphicon glyphicon-adjust" aria-hidden="true"></span></li><li value="edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></li><li value="stop"><span class="glyphicon glyphicon-stop" aria-hidden="true"></span></li><li value="chevron-left"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></li><li value="chevron-right"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></li><li value="info-sign"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></li><li value="exclamation-sign"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span></li><li value="fire"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span></li><li value="eye-open"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></li><li value="calendar"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></li><li value="comment"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></li><li value="shopping-cart"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></li><li value="folder-open"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span></li><li value="bullhorn"><span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span></li><li value="globe"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span></li><li value="wrench"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span></li><li value="tasks"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span></li><li value="filter"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span></li><li value="briefcase"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span></li><li value="dashboard"><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span></li><li value="paperclip"><span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span></li><li value="link"><span class="glyphicon glyphicon-link" aria-hidden="true"></span></li><li value="pushpin"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span></li><li value="usd"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span></li><li value="unchecked"><span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span></li><li value="flash"><span class="glyphicon glyphicon-flash" aria-hidden="true"></span></li><li value="record"><span class="glyphicon glyphicon-record" aria-hidden="true"></span></li><li value="send"><span class="glyphicon glyphicon-send" aria-hidden="true"></span></li><li value="credit-card"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span></li><li value="cutlery"><span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span></li><li value="earphone"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span></li><li value="stats"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span></li><li value="duplicate"><span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span></li><li value="scissors"><span class="glyphicon glyphicon-scissors" aria-hidden="true"></span></li><li value="education"><span class="glyphicon glyphicon-education" aria-hidden="true"></span></li><li value="option-horizontal"><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></li><li value="option-vertical"><span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span></li><li value="menu-hamburger"><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></li><li value="thumbs-up"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span></li>';
	
	$(".btn-group.icons .dropdown-menu").append(icons);
	
	$(".btn-group.icons .dropdown-menu li").bind("click", function(){
		$(this).parent().parent().find("button .glyphicon").replaceWith('<span class="glyphicon glyphicon-'+$(this).attr("value")+'" aria-hidden="true"></span>');
		$("input#"+$(this).parent().attr("for")).val($(this).attr("value"));
	});
	
	
	/* Change save button text */
	{%ifnotset ID%}
		$(".edit .adm-buttons .btn.save:not(.dont-change)").val("{%lang Добавить%}");
		$(".edit .adm-buttons .btn.delete").replaceWith("");
	{%endif%}
	
	
	/* Checking .help-block */
	$(".edit .help-block").each(function(index, element){
        if($(element).text() == ""){
			$(element).replaceWith("");	
		}
    });

	
	/* Dropdown */
	$(".edit .dropdown").each(function(index, element){
        if($(element).find("li.selected").length > 0){
			$(element).find("button .dropdown-title").html($(element).find("li.selected a").html());	
		}
    });
	
	$(".edit .dropdown a").bind("click", function(e){
		e.preventDefault();
		
		var value = $(this).attr("rel");
		var list = $(this).parent().parent();
		var button = $(this).parent().parent().parent().find("button");
		var param = button.attr("rel");
		var input = $(this).parent().parent().parent().parent().find("input#field-"+param);
		
		input.val(value);
		
		list.find("li").removeClass("selected");
		
		$(this).parent().addClass("selected");
		
		if(list.find("li.selected").length > 0){
			button.find(".dropdown-title").html(list.find("li.selected a").html());	
		}
	});
	
	
	{%ifset ID%}
		/* Checking item ID */ 
		var urlParams = window.location.href.split('/');
		if(urlParams[9] == ""){
			history.replaceState(3, "Title 2", window.location.href + "{%ID%}");
		}
	{%endif%}
	
	
	{%ifset ID%}
		/* Copy Object */
		
		$('#{%oc%} .copy-object').bind('click', function() {
			if(confirm('{%lang Подтвердить копирование элемента?%}')){
				$.Request({ type: "json", controller: '{%controllerName%}', action: 'copy', data: "object={%ID%}", complete: function(r){ window.location.href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/"+r.ID; } });
			}
			return false;	
		});
	{%endif%}
	
</script>