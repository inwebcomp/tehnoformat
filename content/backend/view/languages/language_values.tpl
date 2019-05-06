<div id="language_values">
	{%ifset mess%}
        <div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>{%mess%}</strong>
        </div>
    {%endif%}
    {%list values%}
         <form id="language_values_form_{%.name%}" class="form-horizontal">
            <div class="list wow {%config_animation_effect_list%} panel panel-default">
                <div class="page-header">
                    <h3 class="row">
                        <div class="col-sm-12">{%lang Перевод блока%} «{%.title%}»</div>
                    </h3>
                </div>
        
                <table class="table table-hover">
                    <thead>
                        {%list .items%}
                            {%ifset ._first%}
                                <tr>
                                    <th></th>
                                    {%ifset .name%}<th>{%lang Оригинальная фраза%}</th>{%endif%}
                                    {%ifset .value%}<th>{%lang Перевод%}</th>{%endif%}
                                </tr>
                            {%endif%}
                        {%end%}
                    </thead>
                    <tbody>
                        {%list .items%}
                            <tr class="vertical-align">
                                <th class="col-sm-1"><a tabindex="0" href="javascript:void(0)" onclick="return $(this).parent().parent().replaceWith('');">{%lang Удалить%}</a></th>                            	
                                <th class="col-sm-6">{%.name%}</th>
                                {%ifset .value%}<th class="col-sm-5"><div class="input-group input-group-sm col-sm-12"><input tabindex="1" name="elements[{%values.name%}][{%.key%}]" type="text" value="{%.value%}" class="form-control" /></div></th>{%endif%}
                            </tr>
                        {%end%}
                    </tbody>
                </table>
                <div class="adm-buttons animated_all"> 
                    {%includeview button_save%}
                </div> 
            </div>
        </form>
        <script type="text/javascript">
            $("#language_values_form_{%.name%} .btn.save").bind('click', function() {
                $("#language_values").Request({ controller: '{%controllerName%}', action: 'fast_values_save', data: $('#language_values_form_{%.name%}').serialize() + '&object={%name%}' });
                return false;
            });
        </script>
    {%end%} 
</div>