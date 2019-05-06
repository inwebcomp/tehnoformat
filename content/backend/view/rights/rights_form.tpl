{%ifset ID%}
<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default" id="{%oc%}">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Добавить права%}</div>
        </h3>
    </div>
    
	<div class="panel-body">

		<form role="form" rel="rights" action="{%controllerName%}/add_rights" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
        	<input value="{%ID%}" type="hidden" name="params[group_ID]" />
            <input value="{%name%}" type="hidden" name="params[name]" />
        
            <div class="form-group{%ifset .err_title%} has-error{%endif%}">
                <label class="control-label col-md-2" for="title">{%lang Заголовок%}:</label>
                <div class="col-md-6">
                	<input value="{%.title%}" type="text" name="params[title]" class="form-control" id="title">
                    {%ifset .err_title%}<span class="help-block">{%.err_title_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_method%} has-error{%endif%}">
                <label class="control-label col-md-2" for="method">{%lang Метод%}:</label>
                <div class="col-md-6">
                	<input value="{%.method%}" type="text" name="params[method]" class="form-control" id="method">
                    {%ifset .err_method%}<span class="help-block">{%.err_method_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_controller%} has-error{%endif%}">
                <label class="control-label col-md-2" for="controller">{%lang Контроллер%}:</label>
                <div class="col-md-6">
                	<input value="{%.controller%}" type="text" name="params[controller]" class="form-control" id="controller">
                    {%ifset .err_controller%}<span class="help-block">{%.err_controller_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                	{%includeview button_save%}
                </div>
            </div>
        </form> 
        
	</div>
</div>
{%endif%}