<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Базовая информация%}{%ifset .ID%} <small>{%lang ID%}: {%.ID%}</small>{%endif%}</div>
        </h3>
    </div>
    
	<div class="panel-body">

		<form role="form" rel="{%oc%}" action="{%controllerName%}/save" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
        	<input value="{%.ID%}" type="hidden" name="object" />
        
            {%field text/name/"Имя"/6%}
            
            {%field text/phone/"Телефон"/6%}
            
            {%field text/email/"Электронный адрес"/6%}
            
            {%field text/city/"Город"/6%}
            
            {%field text/address/"Адрес"/6%}
            
            {%field textarea/note/"Примечание"/6%}
            
            <hr class="separator" />
            
            {%field status/status/"Статус"/3%}
            
            <hr class="separator" />
            
            {%ifset ID%}
            	{%field items/items/"Товары"/10%}
            {%endif%}
                
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                	{%includeview buttons_default%}
                </div>
            </div>
        </form> 
        
	</div>
</div>