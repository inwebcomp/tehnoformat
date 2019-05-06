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
        
            <div class="form-group{%ifset .err_title%} has-error{%endif%}">
                <label class="control-label col-md-2" for="title">{%lang Заголовок%}:</label>
                <div class="col-md-6">
                	<input value="{%.title%}" type="text" name="params[title]" class="form-control" id="title">
                    {%ifset .err_title%}<span class="help-block">{%.err_title_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_name%} has-error{%endif%}">
                <label class="control-label col-md-2" for="name">{%lang Код%}:</label>
                <div class="col-md-6">
                	<input value="{%.name%}" type="text" name="params[name]" class="form-control" id="name">
                    {%ifset .err_name%}<span class="help-block">{%.err_name_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_encoding%} has-error{%endif%}">
                <label class="control-label col-md-2" for="encoding">{%lang Кодировка%}:</label>
                <div class="col-md-6">
                	<input value="{%.encoding%}" type="text" name="params[encoding]" class="form-control" id="encoding">
                    {%ifset .err_encoding%}<span class="help-block">{%.err_encoding_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_block%} has-error{%endif%}">
                <label class="control-label col-md-2" for="block">{%lang Заблокирован%}:</label>
                <div class="col-md-2">
                	<div class="checkbox"><input{%if .block == 1%} checked{%endif%} type="checkbox" name="params[block]" class="form-control" id="block" /><label></label></div>
                    {%ifset .err_block%}<span class="help-block">{%.err_block_mess%}</span>{%endif%}
                </div>
            </div>         
                
            <div class="form-group"> 
            	<div class="col-md-offset-2 col-md-10">
            		<!--<button overwrite="false" type="button" class="btn btn-default index_phrases animated effect-touch">{%lang Проиндексировать недостающие фразы%%}</button>-->
                    <button overwrite="true" type="button" class="btn btn-default index_phrases animated effect-touch">{%lang Проиндексировать все фразы%}</button>
                    
                    <script type="text/javascript">
						$("#{%oc%} .index_phrases").bind('click', function(){
							/*if($(this).attr("overwrite") == "true"){
								if(!confirm("{%lang Внимание! Лишние переводы будут удалены! Вы хотите продолжить?%}")){
									return false;
								}
							}*/
							
							$("#{%oc%}").Request({ controller: '{%controllerName%}', action: 'index_phrases', data: "object={%.ID%}&overwrite="+$(this).attr("overwrite"), loader: "global" });
							
							return false;
						});
					</script>  
            	</div>
            </div>   
             
            <div class="form-group adm-buttons animated_all"> 
            	<div class="col-md-offset-2 col-md-10">
                	{%includeview buttons_default%}
            	</div>
            </div>
        </form> 
        
	</div>
</div>




{%includeview language_values%}