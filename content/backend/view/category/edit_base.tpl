<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Базовая информация%}{%ifset .ID%} <small>{%lang ID%}: {%.ID%}</small>{%endif%}</div>
        	<div class="col-sm-4 action">
                <a style="margin-right: 32px" target="_blank" href="/{%if language_name == config_default_language%}{%else%}{%language_name%}/{%endif%}{%name%}" class="animated"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> {%lang Открыть на сайте%}</a>
                <a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/" class="animated"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {%lang Добавить запись%}</a>
            </div>
        </h3>
    </div>

	<div class="panel-body">
		<form role="form" rel="{%oc%}" action="{%controllerName%}/save" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
        	<input value="{%.ID%}" type="hidden" name="object" />

            {%field text/title/"Название"/6%}

            {%field text/name/"URL ID"/6%}

            {%controllerdynamic parent_select%}

            {%field editor_outside/text/"Текст страницы"/6%}

            {%includeview image%}

            {%field gallery_ID/gallery_ID/"Показывать работы из раздела"/4%}

            <div class="page-header separator">
                <h4 class="row">
                    <div class="col-sm-8">{%lang Форма обратной связи%}</div>
                </h4>
            </div>

            {%field text/form_title/"Заголовок"/6%}
            {%field textarea/form_text/"Текст"/6%}
            {%field text/form_button_text/"Текст на кнопке"/6%}
            {%field form_button_type/form_button_type/"Действие кнопки"/4%}



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