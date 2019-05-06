<div id="admmenu" class="animated{%if menu_state == "1"%} hideTabs{%endif%}">
		
        <div class="admmenu_header animated_all">
            <a href="/" target="_blank" class="to_site nodecoration">{%config_sitename%}</a>
        </div>
        
        <div class="admmenu-list">
		{%list items%}
        	<div class="item-container">
                <a submenu="{%.ID%}" href="/backend/{%language_name%}/index/link/{%.controller%}/{%.action%}/" class="item item effect-touch black3 first_level" rel="{%.controller%}">
                    <span class="glyphicon glyphicon-{%.icon%}" aria-hidden="true" style="color:{%.color%}"></span>
                    <div class="menu_item{%ifset .notification%} with_notification{%endif%}">{%.title%}</div>
                    {%ifset .notification%}<div class="notification badge">{%.notification%}</div>{%endif%}
                </a>
                {%block ._itemsTree%}
                    {%ifcount .items > 0%}
                        <div class="submenu" rel="{%items.ID%}">
                            {%list .items%}
                            <a href="/backend/{%language_name%}/index/link/{%.controller%}/{%.action%}/" class="item item effect-touch black3" rel="{%.controller%}">
                                <span class="glyphicon glyphicon-{%.icon%}" aria-hidden="true" style="color:{%.color%}"></span>
                                <div class="menu_item{%ifset .notification%} with_notification{%endif%}">{%.title%}</div>
                                {%ifset .notification%}<div class="notification badge">{%.notification%}</div>{%endif%}
                            </a>
                            {%end%}
                        </div>
                    {%endif%}
                {%end%}
            </div>
		{%end%}
        </div>
		
        <div class="copyrights">{%block engine%}© {%.name%} {%.version%}{%end%}</div>

        <script type="text/javascript">
			var timeout;
			
			$(".admmenu-list .item-container").hover(function(e){
				$(".admmenu-list").addClass("width");
			}, function(e){
				$(".admmenu-list").removeClass("width");
			});
			
			$('.language .arrow').bind('click', function(){
				$(this).next().toggle();
			});	
			
			var page = window.location.href.split("/")[7];
			var selected = $("#admmenu .item[rel="+page+"]");
			selected.addClass("selected");
			if(selected.hasClass("first_level") == false){
				selected.parent().parent().find(".first_level").addClass("selected");
			}
			
			$(window).resize(function(){
				var height = $(window).height() - $(".admmenu_header").height() - $("#admmenu .copyrights").height();
				$("#admmenu .admmenu-list").css({ height: height });
			});
			
			$(window).resize();

			$(".admmenu-list").niceScroll({
				cursorcolor: '#140e1a',
				cursorborder: 'none',
				cursoropacitymin: '0',
				cursoropacitymax: '1',
				cursorwidth: '0',
				zindex: 999999,
				enablekeyboard: false
			});

		</script>

</div>

