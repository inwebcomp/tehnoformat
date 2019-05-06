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
            
            <div class="form-group{%ifset .err_name%} has-error{%endif%}">
                <label class="control-label col-md-2" for="name">{%lang Идентификатор%}:</label>
                <div class="col-md-6">
                	<input value="{%.name%}" type="text" name="params[name]" class="form-control" id="name">
                    {%ifset .err_name%}<span class="help-block">{%.err_name_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_advantage%} has-error{%endif%}">
                <label class="control-label col-md-2" for="advantage">{%lang Плюс топлива%}:</label>
                <div class="col-md-6">
                	<input value="{%.advantage%}" type="text" name="params[advantage]" class="form-control" id="advantage">
                    {%ifset .err_advantage%}<span class="help-block">{%.err_advantage_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_pos%} has-error{%endif%}">
                <label class="control-label col-md-2" for="pos">{%lang Позиция%}:</label>
                <div class="col-md-2">
                	<input value="{%.pos%}" type="number" name="params[pos]" class="form-control" id="pos" min="0" step="10">
                    {%ifset .err_pos%}<span class="help-block">{%.err_pos_mess%}</span>{%endif%}
                </div>
            </div>

            <div class="form-group{%ifset .err_description%} has-error{%endif%}">
                <label class="control-label col-md-2" for="description">{%lang Описание%}:</label>
                <div class="col-md-6">
                    <textarea name="params[description]" class="form-control" id="ckeditor">{%.description%}</textarea>
                    {%ifset .err_description%}<span class="help-block">{%.err_description_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_description_min%} has-error{%endif%}">
                <label class="control-label col-md-2" for="description_min">{%lang Краткая характеристика%}:</label>
                <div class="col-md-6">
                    <textarea name="params[description_min]" class="form-control" id="ckeditor2">{%.description_min%}</textarea>
                    {%ifset .err_description_min%}<span class="help-block">{%.err_description_min_mess%}</span>{%endif%}
                </div>
            </div>
            
            {%includeview image%}
            
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