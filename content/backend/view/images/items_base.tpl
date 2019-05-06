<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            {%list items%}
                {%ifset ._first%}
                    <tr>
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                        <th>{%lang Название%}</th>
                        <th>{%lang Размер%}</th>
                        <th>{%lang Модель%}</th>
                        <th>{%lang Тип заполнения%}</th>
                        <th>{%lang Цвет фона%}</th>
                        <th></th>
                    </tr>
                {%endif%}
            {%end%}
        </thead>
        <tbody>
            {%list items%}
                <tr class="vertical-align{%if .block == 1%} warning{%endif%}">
                    {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                    <th><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%.name%}</a></th>
                    <th>{%.width%}x{%.height%}</th>
                    <th>{%.model%}</th>
                    <th>{%if .fill_type == "cram"%}{%lang Втиснуть%}{%endif%}{%if .fill_type == "fill"%}{%lang Заполнить%}{%endif%}</th>
                    <th><div class="color-block" style="background-color: {%.bg_color%}"></div></th>
                    <th><a class="create-thumbnails" href="javascript:void(0)" data-name="{%.name%}" data-model="{%.model%}">{%lang Переделать миниатюры%}</a></th>
                </tr>
            {%end%}
        </tbody>
    </table>
	<script type="text/javascript">
		$('#{%oc%} .list .create-thumbnails').bind('click', function(){
			if (confirm('{%lang Это может занять много времени, вы хотите продолжить?%}')){
				return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'create_thumbnails', loader: "global", data: "model="+$(this).data("model")+"&name="+$(this).data("name") });
			}
		});
	</script>
</div>