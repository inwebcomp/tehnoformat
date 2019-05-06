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
        
            {%field text/name/"Название"/3%}
            
            {%field text/model/"Модель"/3%}
            
            {%field number/width/"Ширина"/2%}
            
            {%field number/height/"Высота"/2%}
            
            {%field text/bg_color/"Цвет фона"/2%}
            
            {%field text/padding/"Отступы по краям"/2%}
            
            {%field checkbox/retina/"Ретина"/2%}

            {%field checkbox/watermark/"Водяной знак"/2%}
            
            {%field fill_type/fill_type/"Тип заполнения"/2%}

            {%field number/quality/"Качество"/2%}

            <hr class="separator" />

			{%field number/pos/"Позиция"/2%}
            
            {%field checkbox/block/"Заблокирован"/2%}

            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                	{%includeview buttons_default%}
                </div>
            </div>
        </form> 
        
	</div>
</div>