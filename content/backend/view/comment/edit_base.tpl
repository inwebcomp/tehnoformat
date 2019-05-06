<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Базовая информация%}{%ifset .ID%} <small>{%lang ID%}: {%.ID%}</small>{%endif%}</div>
        	{%ifset ID%}<div class="col-sm-4 action"><a href="{%root%}/{%language_name%}/{%view_at_page%}" class="animated" target="_blank"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> {%lang Просмотреть страницу с комментарием%}</a></div>{%endif%}
        </h3>
    </div>
    
	<div class="panel-body">

		<form role="form" rel="{%oc%}" action="{%controllerName%}/save" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
        	<input value="{%.ID%}" type="hidden" name="object" />
            
            {%field model/model/"Тип"/2%}

            {%field number/object_ID/"ID"/2%}

            <hr class="separator" />

            {%field text/person/"Автор"/3%}
            
            {%field textarea/text/"Текст"/6%}

			<hr class="separator" />

            {%field checkbox/block/"Заблокирован"/2%}

            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                	{%includeview buttons_default%}
                </div>
            </div>
        </form> 
        
	</div>
</div>

