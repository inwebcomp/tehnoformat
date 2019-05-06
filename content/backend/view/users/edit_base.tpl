<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Данные входа%}{%ifset .ID%} <small>{%lang ID%}: {%.ID%}</small>{%endif%}</div>
        	<div class="col-sm-4 action"><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/" class="animated"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {%lang Добавить запись%}</a></div>
        </h3>
    </div>
    
	<div class="panel-body">

		<form role="form" rel="{%oc%}" action="{%controllerName%}/save" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
        	<input value="{%.ID%}" type="hidden" name="object" />
        
            <div class="form-group{%ifset .err_login%} has-error{%endif%}">
                <label class="control-label col-md-2" for="login">{%lang Логин%}:</label>
                <div class="col-md-6">
                	<input value="{%.login%}" type="text" name="params[login]" class="form-control" id="login">
                    {%ifset .err_login%}<span class="help-block">{%.err_login_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_email%} has-error{%endif%}">
                <label class="control-label col-md-2" for="email">{%lang E-mail%}:</label>
                <div class="col-md-6">
                	<input value="{%.email%}" type="text" name="params[email]" class="form-control" id="email">
                    {%ifset .err_email%}<span class="help-block">{%.err_email_mess%}</span>{%endif%}
                </div>
            </div>

            <div class="form-group{%ifset .err_type%} has-error{%endif%}">
                <label class="control-label col-md-2" for="type">{%lang Группа%}:</label>
                <div class="col-md-6">
                    <select name="params[type]" class="form-control">
                        {%block usergroup_block%}
                            {%list .items%}
                            {%if .name !== "developer"%}<option {%if type == .name%}selected{%endif%} value="{%.name%}">{%.title%}</option>{%endif%}
                            {%if .name == "developer"%}{%if type == "developer"%}<option selected value="{%.name%}">{%.title%}</option>{%endif%}{%endif%}
                            {%end%}
                        {%end%}
                    </select>
                    {%ifset .err_type%}<span class="help-block">{%.err_type_mess%}</span>{%endif%}
                </div>
            </div>

            <div class="form-group{%ifset .err_status%} has-error{%endif%}">
                <label class="control-label col-md-2" for="status">{%lang Статус%}:</label>
                <div class="col-md-6">
                	<select name="params[status]" class="form-control">
                        <option {%if status == "0"%}selected{%endif%} value="0">{%lang Заблокирован%}</option>
                        <option {%if status == "1"%}selected{%endif%} value="1">{%lang Зарегистрирован%}</option>
                        <option {%if status == "2"%}selected{%endif%} value="2">{%lang Активирован%}</option>
                    </select>
                    {%ifset .err_status%}<span class="help-block">{%.err_status_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_password%} has-error{%endif%}">
                <label class="control-label col-md-2" for="password">{%lang Пароль%}:</label>
                <div class="col-md-4">
                    <input type="password" name="params[password]" class="form-control" id="password" maxlength="32">
                    {%ifset .err_password%}<span class="help-block">{%.err_password_mess%}</span>{%endif%}
                </div>
            </div>
            <div class="form-group{%ifset .err_password2%} has-error{%endif%}">
                <label class="control-label col-md-2" for="password2">{%lang Повторите пароль%}:</label>
                <div class="col-md-4">
                    <input type="password" name="params[password2]" class="form-control" id="password2" maxlength="32">
                    {%ifset .err_password2%}<span class="help-block">{%.err_password2_mess%}</span>{%endif%}
                </div>
            </div>
			
            <!--<div class="form-group{%ifset .err_status%} has-error{%endif%}">
                <label class="control-label col-md-2" for="status">{%lang Активирован%}:</label>
                <div class="col-md-2">
                	<div class="checkbox"><input{%if .status == 1%} checked{%endif%} type="checkbox" name="params[status]" class="form-control" id="status" /><label></label></div>
                    {%ifset .err_status%}<span class="help-status">{%.err_status_mess%}</span>{%endif%}
                </div>
            </div>-->
            
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
