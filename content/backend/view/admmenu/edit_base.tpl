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
            
            <hr class="separator" />
            
            {%controllerdynamic parent_select%} 
            
            <hr class="separator" />
            
            <div class="form-group{%ifset .err_controller%} has-error{%endif%}">
                <label class="control-label col-md-2" for="controller">{%lang Контроллер%}:</label>
                <div class="col-md-6">
                	<input value="{%.controller%}" type="text" name="params[controller]" class="form-control" id="controller">
                    {%ifset .err_controller%}<span class="help-block">{%.err_controller_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_action%} has-error{%endif%}">
                <label class="control-label col-md-2" for="action">{%lang Действие%}:</label>
                <div class="col-md-6">
                	<input value="{%.action%}" type="text" name="params[action]" class="form-control" id="action">
                    {%ifset .err_action%}<span class="help-block">{%.err_action_mess%}</span>{%endif%}
                </div>
            </div>
            
            <hr class="separator" />
            
            <div class="form-group{%ifset .err_icon%} has-error{%endif%}">
                <label class="control-label col-md-2" for="icon">{%lang Иконка%}:</label>
                <div class="col-md-4">
                	<div class="input-group">
                        <input value="{%.icon%}" type="hidden" name="params[icon]" class="form-control" id="icon">
                        <div class="btn-group icons">
                          	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            	<span class="glyphicon glyphicon-{%.icon%}" aria-hidden="true"></span> <span class="caret"></span>
                          	</button>
                          	<ul class="dropdown-menu animated_all" role="menu" for="icon"></ul>
                        </div>
                    </div>
                    {%ifset .err_icon%}<span class="help-block">{%.err_icon_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_color%} has-error{%endif%}">
                <label class="control-label col-md-2" for="color">{%lang Цвет%}:</label>
                <div class="col-md-2">
                	<div class="input-group colorpicker-palette">
                        <input value="{%.color%}" type="text" name="params[color]" class="form-control" id="color">
                        <span class="input-group-addon"><i></i></span>
                    </div>
                    {%ifset .err_color%}<span class="help-block">{%.err_color_mess%}</span>{%endif%}
                </div>
            </div>
            
            <hr class="separator" />
            
            <div class="form-group{%ifset .err_pos%} has-error{%endif%}">
                <label class="control-label col-md-2" for="pos">{%lang Позиция%}:</label>
                <div class="col-md-2">
                	<input value="{%.pos%}" type="number" name="params[pos]" class="form-control" id="pos" min="0" step="10">
                    {%ifset .err_pos%}<span class="help-block">{%.err_pos_mess%}</span>{%endif%}
                </div>
            </div>
            
            <hr class="separator" />
            
            <div class="form-group{%ifset .err_fast%} has-error{%endif%}">
                <label class="control-label col-md-2" for="fast">{%lang Быстрый доступ%}:</label>
                <div class="col-md-2">
                	<div class="checkbox"><input{%if .fast == 1%} checked{%endif%} type="checkbox" name="params[fast]" class="form-control" id="fast" /><label></label></div>
                    {%ifset .err_fast%}<span class="help-block">{%.err_fast_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_last_level%} has-error{%endif%}">
                <label class="control-label col-md-2" for="last_level">{%lang Последний уровень%}:</label>
                <div class="col-md-2">
                	<div class="checkbox"><input{%if .last_level == 1%} checked{%endif%} type="checkbox" name="params[last_level]" class="form-control" id="last_level" /><label></label></div>
                    {%ifset .err_last_level%}<span class="help-block">{%.err_last_level_mess%}</span>{%endif%}
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

