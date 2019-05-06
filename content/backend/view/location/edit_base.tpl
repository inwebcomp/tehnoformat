<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Базовая информация%}{%ifset .ID%} <small>{%lang ID%}: {%.ID%}</small>{%endif%}</div>
        	<div class="col-sm-4 action">
                {%ifset .ID%}<a href="javascript:void(0)" class="copy-object animated" rel="{%.ID%}"><span class="fa fa-copy" aria-hidden="true"></span> {%lang Создать дубликат%}</a> &nbsp; &nbsp;{%endif%} 
                <a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/" class="animated"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {%lang Добавить запись%}</a>
            </div>
        </h3>
    </div>
    
	<div class="panel-body">

		<form role="form" rel="{%oc%}" action="{%controllerName%}/save" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
        	<input value="{%.ID%}" type="hidden" name="object" />
			
			<input value="1" type="hidden" name="params[some_field]" />
        
            {%field text/title/"Название"/6%}

            <hr class="separator" />
            
            {%field number/pos/"Позиция"/2%}

			{%field checkbox/block/"Заблокирован"/2%}
            
            <hr class="separator" />
            
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                	{%includeview buttons_default%}
                </div>
            </div>
        </form> 
        
	</div>
</div>