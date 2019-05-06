<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Настройка почтового клиента%}</div>
        </h3>
    </div>
    
	<div class="panel-body">

		<form role="form" rel="{%oc%}" action="{%controllerName%}/save" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
        	<input value="{%.ID%}" type="hidden" name="object" />
        
        	{%field select/type/"Тип отправки"/3%}
        
            {%field text/server/"Сервер"/6%}
            
            {%field text/login/"Имя почтового ящика"/6%}
            
            {%field password/password/"Пароль"/6%}
   
            {%field checkbox/ssl/"SSL сертификат"/2%}
            
            {%field number/port/"Порт"/2%}
                
            {%field text/name/"Имя отправителя"/6%}
                
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                	{%includeview button_save%}
                </div>
            </div>
        </form> 
        
	</div>
</div>