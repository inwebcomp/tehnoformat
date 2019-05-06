<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            {%list items%}
                {%ifset ._first%}
                    <tr>
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                        {%ifset .title%}<th>{%lang Заголовок%}</th>{%endif%}
                        {%ifset .val%}<th>{%lang Значение%}</th>{%endif%}
                        {%ifset .type%}<th>{%lang Тип%}</th>{%endif%}
                        {%ifset .name%}<th>{%lang Имя%}</th>{%endif%}
                    </tr>
                {%endif%}
            {%end%}
        </thead>
        <tbody>
            {%list items%}
                <tr param="{%.param%}" class="vertical-align{%if .block == 1%} warning{%endif%} filter_group_title noselect">
                    <th class="checkbox_field"></th>
                    <th>{%.param_name%}</th>
                    <th></th>
                    <th>{%.type%}</th>
                    <th></th>
                </tr>
                {%block ._itemsTree%}
                	{%list .items%}
                        <tr rel="{%.ID%}" param="{%.param%}" class="vertical-align{%if .block == 1%} warning{%endif%}">
                            {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                            {%ifset .title%}<th{%if .last_level !== "1"%} class="cms_parent"{%endif%}><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%.title%}</a></th>{%endif%}
                            {%ifset .val%}<th>{%.val%}</th>{%endif%}
                            {%ifset .type%}<th>{%.type%}</th>{%endif%}
                            {%ifset .name%}<th>{%.name%}</th>{%endif%}
                        </tr>
                    {%end%}
                {%end%}
            {%end%}
        </tbody>
    </table>
    
    <script type="text/javascript">
		$('.filter_group_title').css("cursor", "pointer").bind('click', function(){
			$('[param=' + $(this).attr('param') + ']:not(.filter_group_title)').toggle();
		});
	</script>
</div>