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
            
            {%field select/tpl/"Шаблон страницы"/4%}

            {%ifset ID%}
                <div class="page-header separator">
                    <h4 class="row">
                        <div class="col-sm-8">{%lang Содержимое страницы%}</div>
                    </h4>
                </div>

                {%field editor_outside/text/"Текст страницы"/6%}
            {%endif%}


            <div class="page-header separator">
                <h4 class="row">
                    <div class="col-sm-8">{%lang SEO-данные страницы%}</div>
                </h4>
            </div>

            {%field text/meta_title/"Заголовок"/6%}
            {%field textarea/meta_keywords/"Ключевые слова"/6%}
            {%field textarea/meta_description/"Описание"/6%}




            <div class="page-header separator">
                <h4 class="row">
                    <div class="col-sm-8">{%lang Прочее%}</div>
                </h4>
            </div>

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