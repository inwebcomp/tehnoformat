<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Фильтр%}{%ifset .ID%} <small>{%lang ID%}: {%.ID%}</small>{%endif%}</div>
        	<div class="col-sm-4 action"><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/0/{%category_ID%}" class="animated"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {%lang Добавить запись%}</a></div>
        </h3>
    </div>
    
	<div class="panel-body">

		<form role="form" rel="{%oc%}" action="{%controllerName%}/save" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
        	<input value="{%.ID%}" type="hidden" name="object" />
        	<input value="{%category_ID%}" type="hidden" name="params[category_ID]" />
        
        	<div class="form-group{%ifset .err_param%} has-error{%endif%}">
                <label class="control-label col-md-2" for="param">{%lang Параметр%}:</label>
                <div class="col-md-4">
                    <select name="params[param]" class="form-control" id="param">
                        <option value="" {%if param == ""%}selected{%endif%}>{%lang Выберите значение%}</option>
                        {%list params%}
                            <option value="{%.param%}" {%if param == .param%}selected{%endif%}>{%.title%} ({%.param%})</option>
                        {%end%}
                    </select>
                    {%ifset .err_param%}<span class="help-block">{%.err_param_mess%}</span>{%endif%}
                </div>
            </div>
        
        	<div class="form-group{%ifset .err_urlid%} has-error{%endif%}">
                <label class="control-label col-md-2" for="urlid">{%lang URLID%}:</label>
                <div class="col-md-4">
                	<input value="{%.urlid%}" type="text" name="params[urlid]" class="form-control" id="urlid">
                    {%ifset .err_urlid%}<span class="help-block">{%.err_urlid_mess%}</span>{%endif%}
                </div>
            </div>
        
        	<div class="form-group{%ifset .err_name%} has-error{%endif%}">
                <label class="control-label col-md-2" for="name">{%lang Заголовок группы параметров%}:</label>
                <div class="col-md-4">
                	<input value="{%.name%}" type="text" name="params[name]" class="form-control" id="name">
                    {%ifset .err_name%}<span class="help-block">{%.err_name_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_type%} has-error{%endif%}" id="type">
                <label class="control-label col-md-2" for="type">{%lang Тип%}:</label>
                <div class="col-md-3">
                    <select name="params[type]" class="form-control">
                    	<option value="list" {%if type == "list"%}selected{%endif%}>{%lang Флажки%}</option>
                        <option value="radio" {%if type == "radio"%}selected{%endif%}>{%lang Переключатель%}</option>
                        <option value="slider" {%if type == "slider"%}selected{%endif%}>{%lang Слайдер%}</option>
                    </select>
                    {%ifset .err_type%}<span class="help-block">{%.err_type_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_not_in_children%} has-error{%endif%}">
                <label class="control-label col-md-2" for="not_in_children">{%lang Показывать только в своей категории%}:</label>
                <div class="col-md-2">
                	<div class="checkbox"><input{%if .not_in_children == 1%} checked{%endif%} type="checkbox" name="params[not_in_children]" class="form-control" id="not_in_children" /><label></label></div>
                    {%ifset .err_not_in_children%}<span class="help-not_in_children">{%.err_not_in_children_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_pos%} has-error{%endif%}">
                <label class="control-label col-md-2" for="pos">{%lang Позиция%}:</label>
                <div class="col-md-2">
                	<input value="{%.pos%}" type="number" name="params[pos]" class="form-control" id="pos" min="0" step="10">
                    {%ifset .err_pos%}<span class="help-block">{%.err_pos_mess%}</span>{%endif%}
                </div>
            </div>

            {%field checkbox/hidden/"Сворачивать по умолчанию"/2%}
            
            {%field checkbox/block/"Заблокирован"/2%}
                
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                	{%includeview button_save%}
                    {%includeview button_save_and_back%}
                    {%includeview button_delete%}
                    {%includeview button_back%}
               </div>
            </div>
        </form> 
        
        {%js js_filters%}
	</div>
</div>
