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

            {%field text/title/"Заголовок"/6%}

            {%field text/name/"URL ID"/6%}

            {%includeview images%}

            <hr class="separator">

            {%field number/price/"Цена"/2%}

            {%field text/currency/"Валюта"/2%}

            {%field number/area/"Площадь"/2%}

            <hr class="separator">

            {%field type/type/"Тип"/2%}

            <hr class="separator">

            {%field editor/description_min/"Короткое описание"/6%}

            {%field editor/description/"Полное описание"/6%}

            <hr class="separator">

            {%field editor/scopes/"Сферы применения"/6%}

            {%field editor/characteristics/"Характеристики"/6%}

            <hr class="separator">

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