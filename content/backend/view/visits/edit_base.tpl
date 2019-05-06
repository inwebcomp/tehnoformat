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
        
            <div class="form-group{%ifset .err_ip%} has-error{%endif%}">
                <label class="control-label col-md-2" for="ip">{%lang IP адрес%}:</label>
                <div class="col-md-6">
                	<input value="{%.ip%}" type="text" name="params[ip]" class="form-control" id="ip">
                    {%ifset .err_ip%}<span class="help-block">{%.err_ip_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_browser%} has-error{%endif%}">
                <label class="control-label col-md-2" for="browser">{%lang Браузер%}:</label>
                <div class="col-md-6">
                	<input value="{%.browser%}" type="text" name="params[browser]" class="form-control" id="browser">
                    {%ifset .err_browser%}<span class="help-block">{%.err_browser_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_user_agent%} has-error{%endif%}">
                <label class="control-label col-md-2" for="user_agent">{%lang Информация%}:</label>
                <div class="col-md-6">
                	<input value="{%.user_agent%}" type="text" name="params[user_agent]" class="form-control" id="user_agent">
                    {%ifset .err_user_agent%}<span class="help-block">{%.err_user_agent_mess%}</span>{%endif%}
                </div>
            </div>

			<div class="form-group{%ifset .err_updated%} has-error{%endif%}">
                <label class="control-label col-md-2" for="updated">{%lang Время посещения%}:</label>
                <div class="col-md-6">
                	<input value="{%.updated%}" type="datetime" name="params[updated]" class="form-control" id="updated" readonly="readonly">
                    {%ifset .err_updated%}<span class="help-block">{%.err_updated_mess%}</span>{%endif%}
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