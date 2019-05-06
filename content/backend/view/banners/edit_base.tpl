<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default" id="banners_edit">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%ifset ID%}{%lang Изменение банера%}{%else%}{%lang Добавление банера%}{%endif%}{%ifset .ID%} <small>{%lang ID%}: {%.ID%}</small>{%endif%}</div>
            {%ifset ID%}<div class="col-sm-4 action add_banner_button"><a href="javascript:void(0)" class="animated"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {%lang Добавить банер%}</a></div>{%endif%}
        </h3>
    </div>
    
    {%ifset mess%}
        <div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>{%mess%}</strong>
        </div>
    {%endif%}
    
	<div class="panel-body">

		<form role="form" rel="banners_edit" action="{%controllerName%}/save" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
        	<input value="{%.ID%}" type="hidden" name="object" />
            <input value="{%.parent_ID%}" type="hidden" name="params[parent_ID]" />
        
        	{%includeview images%}
        
            <div class="form-group{%ifset .err_title%} has-error{%endif%}">
                <label class="control-label col-md-2" for="title">{%lang Заголовок%}:</label>
                <div class="col-md-6">
                	<input value="{%.title%}" type="text" name="params[title]" class="form-control" id="title">
                    {%ifset .err_title%}<span class="help-block">{%.err_title_mess%}</span>{%endif%}
                </div>
            </div>
			
			{%field text/name/"URL ID"/6%}
            
            {%ifset .with_text%}
                <div class="form-group{%ifset .err_description%} has-error{%endif%}">
                    <label class="control-label col-md-2" for="description">{%lang Текст баннера%}:</label>
                    <div class="col-md-6">
                        <textarea name="params[description]" class="form-control" id="ckeditor">{%.description%}</textarea>
                        {%ifset .err_description%}<span class="help-block">{%.err_description_mess%}</span>{%endif%}
                    </div>
                </div>
            {%endif%}
            
            <div class="form-group{%ifset .err_href%} has-error{%endif%}">
                <label class="control-label col-md-2" for="href">{%lang Ссылка%}:</label>
                <div class="col-md-6">
                	<input value="{%.href%}" type="text" name="params[href]" class="form-control" id="href">
                    {%ifset .err_href%}<span class="help-block">{%.err_href_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_new_window%} has-error{%endif%}">
                <label class="control-label col-md-2" for="new_window">{%lang Открывать в новой вкладке%}:</label>
                <div class="col-md-2">
                	<div class="checkbox"><input{%if .new_window == 1%} checked{%endif%} type="checkbox" name="params[new_window]" class="form-control" id="new_window" /><label></label></div>
                    {%ifset .err_new_window%}<span class="help-new_window">{%.err_new_window_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_block%} has-error{%endif%}">
                <label class="control-label col-md-2" for="block">{%lang Заблокирован%}:</label>
                <div class="col-md-2">
                	<div class="checkbox"><input{%if .block == 1%} checked{%endif%} type="checkbox" name="params[block]" class="form-control" id="block" /><label></label></div>
                    {%ifset .err_block%}<span class="help-block">{%.err_block_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_pos%} has-error{%endif%}">
                <label class="control-label col-md-2" for="pos">{%lang Позиция%}:</label>
                <div class="col-md-2">
                	<input value="{%.pos%}" type="number" name="params[pos]" class="form-control" id="pos" min="0" step="10">
                    {%ifset .err_pos%}<span class="help-block">{%.err_pos_mess%}</span>{%endif%}
                </div>
            </div>
                
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                    <button type="submit" class="btn btn-success save animated effect-touch">{%ifset ID%}{%lang Сохранить%}{%else%}{%lang Добавить%}{%endif%}</button>
                </div>
            </div>
            <script type="text/javascript">
			$(function(){
				$("#"+$(".banners_list").attr("id")).Request({ controller: 'banners', action: 'items', data: "object={%.parent_ID%}" });
				$("#banners_edit .add_banner_button").bind("click", function(){
					$("#banners_edit").Request({ controller: 'banners', action: 'edit', data: "bannerplace={%.parent_ID%}" });
				});
			});
            </script>
        </form> 
	</div>
</div>

