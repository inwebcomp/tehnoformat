<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default" id="params_edit">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%ifset ID%}{%lang Изменение параметра%}{%else%}{%lang Добавление параметра%}{%endif%}</div>
        </h3>
    </div>
    
	<div class="panel-body">

		<form onsubmit="$('#params_edit').Request({ controller: '{%controllerName%}', action: 'params_edit', data: 'object={%.paramgroup_ID%}' });" role="form" rel="params" action="{%controllerName%}/save_param" class="ajax-form-request form-horizontal">
        	<input name="object" type="hidden" value="{%paramgroup_ID%}" />
            <input name="paramID" type="hidden" value="{%paramID%}" />
            <input name="params[paramgroup_ID]" type="hidden" value="{%paramgroup_ID%}" />
        
            <div class="form-group{%ifset .err_title%} has-error{%endif%}">
                <label class="control-label col-md-2" for="title">{%lang Заголовок%}:</label>
                <div class="col-md-6">
                	<input value="{%.title%}" type="text" name="params[title]" class="form-control" id="title">
                    {%ifset .err_title%}<span class="help-block">{%.err_title_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_name%} has-error{%endif%}">
                <label class="control-label col-md-2" for="name">{%lang URL ID%}:</label>
                <div class="col-md-6">
                	<input value="{%.name%}" type="text" name="params[name]" class="form-control" id="name">
                    {%ifset .err_name%}<span class="help-block">{%.err_name_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_type%} has-error{%endif%}">
                <label class="control-label col-md-2" for="type">{%lang Тип%}:</label>
                <div class="col-md-6">
                    <select name="params[type]" class="form-control">
                    	<option value="String" {%if type == "String"%}selected="selected"{%endif%}>{%lang Строка%}</option>
                        <!--<option value="Bool" {%if type == "Bool"%}selected="selected"{%endif%}>{%lang Чекбокс%}</option>-->
                        <option value="Text" {%if type == "Text"%}selected="selected"{%endif%}>{%lang Текст%}</option>
                        <option value="Double" {%if type == "Double"%}selected="selected"{%endif%}>{%lang Число%}</option>
                    </select>
                    {%ifset .err_type%}<span class="help-block">{%.err_type_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_num_in%} has-error{%endif%}">
                <label class="control-label col-md-2" for="num_in">{%lang Единица измерения%}:</label>
                <div class="col-md-6">
                	<input value="{%.num_in%}" type="text" name="params[num_in]" class="form-control" id="num_in">
                    {%ifset .err_num_in%}<span class="help-block">{%.err_num_in_mess%}</span>{%endif%}
                </div>
            </div>
            
            <!--<div class="form-group{%ifset .err_required%} has-error{%endif%}">
                <label class="control-label col-md-2" for="required">{%lang Обязателен%}:</label>
                <div class="col-md-2">
                	<div class="checkbox"><input{%if .required == 1%} checked{%endif%} type="checkbox" name="params[required]" class="form-control" id="required" /><label></label></div>
                    {%ifset .err_required%}<span class="help-block">{%.err_required_mess%}</span>{%endif%}
                </div>
            </div>-->
            
            <!--<div class="form-group{%ifset .err_max_length%} has-error{%endif%}">
                <label class="control-label col-md-2" for="max_length">{%lang Макс. длина%}:</label>
                <div class="col-md-2">
                	<input value="{%.max_length%}" type="number" name="params[max_length]" class="form-control" id="max_length" min="0">
                    {%ifset .err_max_length%}<span class="help-block">{%.err_max_length_mess%}</span>{%endif%}
                </div>
            </div>-->
            
            <div class="form-group{%ifset .err_description%} has-error{%endif%}">
                <label class="control-label col-md-2" for="description">{%lang Описание%}:</label>
                <div class="col-md-6">
                    <textarea name="params[description]" class="form-control">{%.description%}</textarea>
                    {%ifset .err_description%}<span class="help-block">{%.err_description_mess%}</span>{%endif%}
                </div>
            </div>

            {%field checkbox/in_catalog/"Отображать в каталоге"/2%}
            
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
                  <button type="submit" class="btn btn-success save animated effect-touch">{%ifset ID%}{%lang Сохранить%}{%else%}{%lang Добавить%}{%endif%}</button>
                  
                  {%ifset .title%}<button type="button" class="btn btn-default add animated effect-touch" onclick="$('#params_edit').Request({ controller: '{%controllerName%}', action: 'params_edit', data: 'object={%.paramgroup_ID%}' });">{%lang Добавить новый%}</button>{%endif%}
               </div>
            </div>
        </form> 
        
	</div>
</div>