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
   
            {%controllerdynamic parent_select%} 
            
			{%block paramgroup_ID_block%}
            <div class="form-group{%ifset .err_paramgroup_ID%} has-error{%endif%}">
                <label class="control-label col-md-2" for="paramgroup_ID">{%lang Группа параметров%}:</label>
                <div class="col-md-6">
                    <select name="params[paramgroup_ID]" class="form-control">
                    	{%list .items%}
                            <option value="{%.ID%}" {%if paramgroup_ID == .ID%}selected{%endif%}>{%.title%}</option>
                        {%end%}
                    </select>
                </div>
            </div>
			{%end%}
            
            {%includeview image%}
            
            {%field number/pos/"Позиция"/2%}
            
            <!--{%field checkbox/last_level/"Последний уровень"/2%}-->
            
            <hr class="separator">
            
            {%field text/meta_title/"Мета-заголовок"/8%}
            
			{%field textarea/meta_description/"Мета-описание"/8%}

            {%field checkbox/hide_in_title/"Скрывать в H1 заголовке в каталоге товаров"/2%}

            <hr class="separator">

			{%field editor3/description/"Описаниее"/8%}

            <hr class="separator">

            {%field checkbox/last_level/"Последний уровень"/2%}

            <hr class="separator">

            {%field checkbox/block/"Заблокирован"/2%}
                
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
            		{%includeview buttons_default%}
                </div>
            </div>
        </form> 
        
	</div>
</div>