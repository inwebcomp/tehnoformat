{%block rights%}
    <div class="edit wow {%config_animation_effect_edit_block%} panel panel-default" id="{%oc%}">
    
        {%ifset mess%}
            <div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
                <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>{%mess%}</strong>
            </div>
        {%endif%}
    	
        <div class="page-header">
            <h3 class="row">
                <div class="col-sm-8">{%lang Права%}</div>
            </h3>
        </div>
        
        <div class="panel-body">
    
            <form rel="{%oc%}" id="form_{%oc%}" action="{%controllerName%}/fast_save" class="ajax-form-request">
                <input value="{%ID%}" type="hidden" name="ID" />
                <div class="list wow {%config_animation_effect_list%}">
                    <table class="table table-hover">
                        <thead>
                            {%list .items%}
                                {%ifset ._first%}
                                    <tr>
                                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                                        {%ifset .title%}<th>{%lang Заголовок%}</th>{%endif%}
                                        {%ifset .method%}<th>{%lang Действие%}</th>{%endif%}
                                        {%ifset .controller%}<th>{%lang Контролер%}</th>{%endif%}
                                        {%ifset .name%}<th>{%lang Группа%}</th>{%endif%}
                                        {%ifset .allow%}<th>{%lang Доступно%}</th>{%endif%}
                                    </tr>
                                {%endif%}
                            {%end%}
                        </thead>
                        <tbody>
                        	{%if .count > 0%}
                                {%list .items%}
                                    <tr class="vertical-align">
                                        {%ifset __fast_delete%}<td class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></td>{%endif%}
                                        {%ifset .title%}<td>{%.title%}</td>{%endif%}
                                        {%ifset .method%}<td>{%.method%}</td>{%endif%}
                                        {%ifset .controller%}<td>{%.controller%}</td>{%endif%}
                                        {%ifset .name%}<td>{%.name%}</td>{%endif%}
                                        {%ifset .allow%}<td class="checkbox_field"><div class="checkbox"><input class="styled" name="params[{%.ID%}][allow]" type="checkbox" value="1"{%if .allow == 1%}checked="checked"{%endif%} /><label></label></div></td>{%endif%}
                                    </tr>
                                {%end%}
                            {%else%}
                            	<tr class="vertical-align"><td colspan="6">{%lang Не найдено записей%}</td></tr>
                            {%endif%}
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
        
        {%js crud_list%}

    </div>
{%end%}
    