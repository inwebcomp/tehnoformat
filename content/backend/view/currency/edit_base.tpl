<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Базовая информация%}{%ifset .ID%} <small>{%lang ID%}: {%.ID%}</small>{%endif%}</div>
        	<div class="col-sm-4 action"><a href="{%root%}/backend/{%language_name%}/index/link/{%controllerName%}/edit/" class="animated"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {%lang Добавить запись%}</a></div>
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
            
            <div class="form-group{%ifset .err_name%} has-error{%endif%}">
                <label class="control-label col-md-2" for="name">{%lang Код ISO%}:</label>
                <div class="col-md-2">
                	<input value="{%.name%}" type="text" name="params[name]" placeholder="USD, EUR, MDL, ..." class="form-control" id="name">
                    {%ifset .err_name%}<span class="help-block">{%.err_name_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_symbol%} has-error{%endif%}">
                <label class="control-label col-md-2" for="symbol">{%lang Символ%}:</label>
                <div class="col-md-2">
                	<input value="{%.symbol%}" type="text" name="params[symbol]" class="form-control" id="symbol">
                    {%ifset .err_symbol%}<span class="help-block">{%.err_symbol_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_round%} has-error{%endif%}">
                <label class="control-label col-md-2" for="round">{%lang Цифр после запятой%}:</label>
                <div class="col-md-2">
                	<input value="{%.round%}" type="number" name="params[round]" class="form-control" id="round">
                    {%ifset .err_round%}<span class="help-block">{%.err_round_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_display_type%} has-error{%endif%}">
                <label class="control-label col-md-2" for="display_type">{%lang Формат валюты%}:</label>
                <div class="col-md-3">
                	<select name="params[display_type]" class="form-control">
                    	<option value="1" {%if display_type == "1"%}selected{%endif%}>0000.00X</option>
                        <option value="2" {%if display_type == "2"%}selected{%endif%}>0000,00X</option>
                        <option value="3" {%if display_type == "3"%}selected{%endif%}>0,000.00X</option>
                        <option value="4" {%if display_type == "4"%}selected{%endif%}>0,000,00X</option>
                        <option value="5" {%if display_type == "5"%}selected{%endif%}>0.000.00X</option>
                        <option value="6" {%if display_type == "6"%}selected{%endif%}>0.000,00X</option>
                        <option value="7" {%if display_type == "7"%}selected{%endif%}>0 000.00X</option>
                        <option value="8" {%if display_type == "8"%}selected{%endif%}>0 000,00X</option>
                    </select>
                    {%ifset .err_display_type%}<span class="help-block">{%.err_display_type_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_space%} has-error{%endif%}">
                <label class="control-label col-md-2" for="space">{%lang Отступ%}:</label>
                <div class="col-md-2">
                	<div class="checkbox"><input{%if .space == 1%} checked{%endif%} type="checkbox" name="params[space]" class="form-control" id="space" /><label></label></div>
                    {%ifset .err_space%}<span class="help-space">{%.err_space_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_def%} has-error{%endif%}">
                <label class="control-label col-md-2" for="def">{%lang Основная валюта%}:</label>
                <div class="col-md-2">
                	<div class="checkbox"><input{%if .def == 1%} checked{%endif%} type="checkbox" name="params[def]" class="form-control" id="def" /><label></label></div>
                    {%ifset .err_def%}<span class="help-def">{%.err_def_mess%}</span>{%endif%}
                </div>
            </div>
            
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
                	{%includeview buttons_default%}
                </div>
            </div>
        </form> 
        
	</div>
</div>