{%ifnotset authFlag%}
	
    <div id="{%oc%}" class="validation_form panel panel-default col-xs-9 col-sm-4 col-md-3 col-lg-3 center-block">
    	<div class="logo"></div>
        
        <div class="panel-body">
    
            <form role="form" rel="{%oc%}" action="{%controllerName%}/validate" class="ajax-form-request">
                <div class="form-group{%ifset .err_login%} has-error{%endif%}">
                    <label class="control-label" for="login">{%lang Логин%}:</label>
                    <div>
                        <input value="{%.login%}" type="text" name="login" class="form-control" id="login">
                        {%ifset .err_login%}<span class="help-block">{%.err_login_mess%}</span>{%endif%}
                    </div>
                </div>
                
                <div class="form-group{%ifset .err_password%} has-error{%endif%}">
                    <label class="control-label" for="password">{%lang Пароль%}:</label>
                    <div>
                        <input value="{%.password%}" type="password" name="password" class="form-control" id="password">
                        {%ifset .err_password%}<span class="help-block">{%.err_password_mess%}</span>{%endif%}
                    </div>
                </div>
                
                <div class="form-group{%ifset .err_remember%} has-error{%endif%}">
                    <div class="checkbox"><input{%if .remember == 1%} checked{%endif%} type="checkbox" name="remember" class="form-control" id="remember" /><label class="control-label" for="remember">{%lang Запомнить меня%}</label></div>
                    
                    {%ifset .err_remember%}<span class="help-remember">{%.err_remember_mess%}</span>{%endif%}
                </div>
                    
                <div class="form-group adm-buttons animated_all">
                    <button type="submit" class="btn btn-primary animated effect-touch">{%lang Вход%}</button>
                </div>
            </form> 
            
        </div>
    </div>
   
{%else%}
	{%if noLangRequest !== ""%}
		<script type="text/javascript">window.location.reload();</script>
    {%else%}
    	{%controller admin/main_page main_page%}
    {%endif%}
{%endif%}

