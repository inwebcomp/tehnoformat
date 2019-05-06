
    <div class="edit wow {%config_animation_effect_edit_block%} panel panel-default" id="params">
    
        {%ifset mess%}
            <div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
                <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>{%mess%}</strong>
            </div>
        {%endif%}
{%ifcount .params > 0%}    
        <div class="page-header">
            <h3 class="row">
                <div class="col-sm-8">{%lang Параметры%}</div>
            </h3>
        </div>
        
        <div class="panel-body">
    
            <form id="params_form">
                <input value="{%paramgroup_ID%}" type="hidden" name="object" />
                <div class="list wow {%config_animation_effect_list%}">
                    <table class="table table-hover">
                        <thead>
                            {%list .params%}
                                {%ifset ._first%}
                                    <tr>
                                        {%ifset __fast_params_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                                        {%ifset .title%}<th>{%lang Заголовок%}</th>{%endif%}
                                        {%ifset .pos%}<th>{%lang Позиция%}</th>{%endif%}
                                        {%ifset .name%}<th>{%lang URL ID%}</th>{%endif%}
                                    </tr>
                                {%endif%}
                            {%end%}
                        </thead>
                        <tbody>
                            {%list .params%}
                                <tr class="vertical-align{%if .block == 1%} warning{%endif%} with_position">
                                    {%ifset __fast_params_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                                    {%ifset .title%}<th><a href="javascript::void(0)" onclick="$('#params_edit').Request({ controller: '{%controllerName%}', action: 'params_edit', data: 'object={%.paramgroup_ID%}&paramID={%.ID%}' });">{%.title%}</a></th>{%endif%}
                                    {%ifset .pos%}<th class="pos">{%ifset __fast_save%}<div class="input-group input-group-sm"><input name="params[{%.ID%}][pos]" type="text" value="{%.pos%}" class="filter-int form-control small" />{%else%}{%.pos%}{%endif%}</div></th>{%endif%}
                                    {%ifset .name%}<th>{%.name%}</th>{%endif%}
                                    <!--<th class="checkbox_field"><div class="checkbox"><input class="styled" name="params[{%.ID%}][in_catalog]" type="checkbox" value="1" {%if .in_catalog == 1%}checked="checked"{%endif%} /><label></label></div></th>-->
                                </tr>
                            {%end%}
                        </tbody>
                    </table>
                </div>
                <div class="form-group adm-buttons animated_all"> 
                    <div class="col-md-10">
                        {%includeview button_save%}
                        <button type="submit" class="btn btn-danger del animated effect-touch">{%lang Удалить%}</button>
                    </div>
                </div>
            </form> 
        </div>
        
        {%js crud_params%}
{%endif%}
    </div>

