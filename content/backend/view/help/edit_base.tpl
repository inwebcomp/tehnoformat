<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	{%ifset developer%}
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
                
                <hr class="separator" />
                
                {%field editor/description/"Текст"/10%}
                    
                {%field number/pos/"Позиция"/2%}
                    
                <div class="form-group adm-buttons animated_all"> 
                    <div class="col-md-offset-2 col-md-10">
                        {%includeview button_save%}
                        {%includeview button_back%}
                    </div>
                </div>
            </form> 
            
        </div>
    {%else%}
    	<div class="page-header">
            <h3 class="row">
                <div class="col-sm-12">{%.title%}</div>
            </h3>
        </div>
        
        <div class="panel-body help-page-container">
    
            <form role="form" rel="{%oc%}" class="form-horizontal" enctype="multipart/form-data">
                <input value="{%.ID%}" type="hidden" name="object" />

                <div class="form-group">
                    <span class="help-block as_value" style="font-size:15px">{%.description%}</span>
                </div>
                
                <div class="form-group adm-buttons animated_all"> 
                    {%includeview button_back%}
                </div>

            </form> 
            
        </div>
    {%endif%}
</div>