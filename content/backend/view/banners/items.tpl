<div id="{%oc%}" class="banners_list wow {%config_animation_effect_list%}">
	{%ifset mess%}
        <div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>{%mess%}</strong>
        </div>
    {%endif%}
    <form id="{%oc%}_form" class="form-horizontal">
        <div class="list panel panel-default">
            <div class="page-header">
                <h3 class="row">
                    <div class="col-sm-12">{%lang Баннеры%}</div>
                </h3>
            </div>
    
            <table class="table table-hover">
                <thead>
                    {%list items%}
                        {%ifset ._first%}
                            <tr>
                                {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                                {%ifset .title%}<th>{%lang Заголовок%}</th>{%endif%}
                                {%ifset .pos%}<th>{%lang Позиция%}</th>{%endif%}
                                {%ifset .base_image%}<th>{%lang Изображение%}</th>{%endif%}
                            </tr>
                        {%endif%}
                    {%end%}
                </thead>
                <tbody>
                    {%list items%}
                        <tr class="vertical-align{%if .block == 1%} warning{%endif%}">
                            {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                            {%ifset .title%}<th class="title"><a href="{%.ID%}">{%.title%}</a></th>{%endif%}
                            {%ifset .pos%}<th class="pos">{%ifset __fast_save%}<div class="input-group input-group-sm"><input name="params[{%.ID%}][pos]" type="text" value="{%.pos%}" class="filter-int form-control small" />{%else%}{%.pos%}{%endif%}</div></th>{%endif%}
                            {%ifset .base_image%}<th>{%if .base_image !== ""%}<a target="_blank" href="{%root%}/image/Banners/{%.ID%}/0x0/{%.base_image%}"><img src="{%root%}/image/Banners/{%.ID%}/200x100/{%.base_image%}" width="200" height="100" /></a>{%endif%}</th>{%endif%}
                        </tr>
                    {%end%}
                </tbody>
            </table>
            <div class="adm-buttons animated_all"> 
            	<button type="submit" class="btn btn-success fast_save animated effect-touch">{%lang Сохранить%}</button>
                <button type="submit" class="btn btn-danger fast_delete animated effect-touch">{%lang Удалить%}</button>
                <button type="submit" class="btn btn-default fast_block animated effect-touch">{%lang Заблокировать%}</button>
                <button type="submit" class="btn btn-default fast_unblock animated effect-touch">{%lang Разблокировать%}</button>
            </div> 
        </div>
    </form>
    <form id="{%oc%}_request_params" class="request_params">
    	{%includeview request_params%}
    </form>
    <script type="text/javascript">
    	
		$("#{%oc%} .table .title a").click(function(e){ 
			e.preventDefault();
			$('#banners_edit').Request({ controller: 'banners', action: 'edit', data: "object="+$(this).attr("href"), loader: "global", complete: function(){  } });
		});

	</script>
</div>
{%js crud_list%}

