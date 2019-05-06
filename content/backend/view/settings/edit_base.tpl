<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Базовая информация%}{%ifset .ID%} <small>{%lang ID%}: {%.ID%}</small>{%endif%}</div>
        	<div class="col-sm-4 action"><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/" class="animated"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {%lang Добавить запись%}</a></div>
        </h3>
    </div>
    
	<div class="panel-body">

		<form role="form" rel="{%oc%}" action="{%controllerName%}/save" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
        	<input value="{%.ID%}" type="hidden" name="object" />
        
            <div class="form-group{%ifset .err_title%} has-error{%endif%}">
                <label class="control-label col-md-2" for="title">{%lang Заголовок%}:</label>
                <div class="col-md-6">
                	<input value="{%.title%}" type="text" name="params[title]" class="form-control" id="title">
                    {%ifset .err_title%}<span class="help-block">{%.err_title_mess%}</span>{%endif%}
                </div>
            </div>
            {%ifset developer%}
                <div class="form-group{%ifset .err_name%} has-error{%endif%}">
                    <label class="control-label col-md-2" for="name">{%lang Имя%}:</label>
                    <div class="col-md-6">
                        <input value="{%.name%}" type="text" name="params[name]" class="form-control" id="name">
                        {%ifset .err_name%}<span class="help-block">{%.err_name_mess%}</span>{%endif%}
                    </div>
                </div>
            {%else%}
            	<input type="hidden" name="params[name]" value="{%.name%}" />
            {%endif%}
            
            <div class="form-group{%ifset .err_group_ID%} has-error{%endif%}">
                <label class="control-label col-md-2" for="group_ID">{%lang Группа%}:</label>
                <div class="col-md-6">
                    <select name="params[group_ID]" class="form-control" id="group_ID">
                        {%block settings_groups_block%}
                            {%list .items%}
                                <option {%if group_ID == .ID%}selected{%endif%} value="{%.ID%}">{%.title%}</option>
                            {%end%}
                        {%end%}                  
                    </select>
                    {%ifset .err_group_ID%}<span class="help-block">{%.err_group_ID_mess%}</span>{%endif%}
                </div>
            </div>
            
            {%ifset developer%}
                <div class="form-group{%ifset .err_type%} has-error{%endif%}">
                    <label class="control-label col-md-2" for="type">{%lang Тип%}:</label>
                    <div class="col-md-3">
                        <select name="params[type]" class="form-control">
                            <option value="string"{%if .type == "string"%} selected{%endif%}>{%lang Строка%}</option>
                            <option value="int"{%if .type == "int"%} selected{%endif%}>{%lang Число%}</option>
                            <option value="checkbox"{%if .type == "checkbox"%} selected{%endif%}>{%lang Чекбокс%}</option>
                            <option value="select"{%if .type == "select"%} selected{%endif%}>{%lang Выпадающий список%}</option>
                            <option value="mediafile"{%if .type == "mediafile"%} selected{%endif%}>{%lang Медиафайл%}</option>
                        </select>
                        {%ifset .err_type%}<span class="help-block">{%.err_type_mess%}</span>{%endif%}
                    </div>
                </div>
            {%else%}
            	<input type="hidden" name="params[type]" value="{%.type%}" />
            {%endif%}
            
			{%if .type !== ""%}
				<hr class="separator" />
            {%endif%}
			
            {%if .type == "string"%}
                <div class="form-group{%ifset .err_value%} has-error{%endif%}">
                    <label class="control-label col-md-2" for="value">{%lang Значение%}:</label>
                    <div class="col-md-6">
                        <input value="{%.value%}" type="text" name="params[value]" class="form-control" id="value">
                        {%ifset .err_value%}<span class="help-block">{%.err_value_mess%}</span>{%endif%}
                    </div>
                </div>
            {%endif%}
            {%if .type == "int"%}
                <div class="form-group{%ifset .err_value%} has-error{%endif%}">
                    <label class="control-label col-md-2" for="value">{%lang Значение%}:</label>
                    <div class="col-md-2">
                        <input value="{%.value%}" type="number" name="params[value]" class="form-control" id="value">
                        {%ifset .err_value%}<span class="help-block">{%.err_value_mess%}</span>{%endif%}
                    </div>
                </div>
            {%endif%}
            {%if .type == "checkbox"%}
                <div class="form-group{%ifset .err_value%} has-error{%endif%}">
                    <label class="control-label col-md-2" for="value">{%lang Значение%}:</label>
                    <div class="col-md-2">
                        <div class="checkbox"><input{%if .value == 1%} checked{%endif%} type="checkbox" name="params[value]" class="form-control" id="value" /><label></label></div>
                        {%ifset .err_value%}<span class="help-value">{%.err_value_mess%}</span>{%endif%}
                    </div>
                </div>
            {%endif%}
            {%if .type == "select"%}
            	<div class="form-group{%ifset .err_value%} has-error{%endif%}">
                    <label class="control-label col-md-2" for="value">{%lang Значение%}:</label>
                    <div class="col-md-3">
                        <select name="params[value]" class="form-control">
                            <option value=""{%if .type == ""%} selected{%endif%}>{%lang Выберите значение%}</option>
                            {%list .list%}
                                <option value="{%.value%}"{%if value == .value%} selected{%endif%}>{%.title%}</option>
                            {%end%}
                        </select>
                        {%ifset .err_value%}<span class="help-block">{%.err_value_mess%}</span>{%endif%}
                    </div>
                </div>
            {%endif%}
            {%if .type == "mediafile"%}
                {%controllerdynamic cd_file_Mediafiles%}
            {%endif%}
			
			{%if .type !== ""%}
				<hr class="separator" />
            {%endif%}
            
            <div class="form-group{%ifset .err_pos%} has-error{%endif%}">
                <label class="control-label col-md-2" for="pos">{%lang Позиция%}:</label>
                <div class="col-md-2">
                	<input value="{%.pos%}" type="number" name="params[pos]" class="form-control" id="pos" min="0" step="10">
                    {%ifset .err_pos%}<span class="help-block">{%.err_pos_mess%}</span>{%endif%}
                </div>
            </div>

            <div class="form-group{%ifset .err_block%} has-error{%endif%}">
                <label class="control-label col-md-2" for="block">{%lang Заблокирован%}:</label>
                <div class="col-md-2">
                	<div class="checkbox"><input{%if .block == 1%} checked{%endif%} type="checkbox" name="params[block]" class="form-control" id="block" /><label></label></div>
                    {%ifset .err_block%}<span class="help-block">{%.err_block_mess%}</span>{%endif%}
                </div>
            </div>
                
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                	{%includeview button_save%}
                    {%includeview button_save_and_back%}
                	{%ifset developer%}{%includeview button_delete%}{%endif%}
                	<a href="/backend/{%language_name%}/index/link/{%controllerName%}/items/{%group_ID%}" class="animated"><button type="button" class="btn btn-default to_list animated effect-touch">{%lang Назад%}</button></a>
                </div>
            </div>
        </form> 
        
	</div>
</div>