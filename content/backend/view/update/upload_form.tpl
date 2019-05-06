<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	{%ifcount update_info > 0%}
    	{%block update_info%}
            <div class="page-header">
                <h3 class="row">
                    <div class="col-sm-8">{%.title%} <small>{%.version%}</small></div>
                    <div class="col-sm-4 action"><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/" class="animated"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {%lang Загрузить другое обновление%}</a></div>
                </h3>
            </div>
        
            <div class="panel-body">
        
                <form id="update-content" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-md-2">{%lang Автор%}:</label>
                        <div class="col-md-6">
                            <span class="help-block as_value">{%.author%}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">{%lang Описание%}:</label>
                        <div class="col-md-6">
                            <span class="help-block as_value">{%.description%}</span>
                        </div>
                    </div>
                    {%ifcount .innovations_list > 0%}
                        <div class="form-group">
                            <label class="control-label col-md-2">{%lang Изменения%}:</label>
                            <div class="col-md-6">
                                <span class="help-block as_value">
                                        <ul>
                                            {%list .innovations_list%}
                                                <li>{%.value%}</li>
                                            {%end%}
                                        </ul>
                                </span>
                            </div>
                        </div>
                    {%endif%}
                    {%ifcount .warnings_list > 0%}
                        <div class="form-group">
                            <label class="control-label col-md-2 text-warning">{%lang Предупреждения%}:</label>
                            <div class="col-md-6">
                                <span class="help-block as_value">
                                        <ul>
                                            {%list .warnings_list%}
                                                <li>{%.value%}</li>
                                            {%end%}
                                        </ul>
                                </span>
                            </div>
                        </div>
                    {%endif%}
                    
                    {%field checkbox/backup/"Создать резервную копию сайта"/2%}
        
                    <div class="form-group adm-buttons animated_all"> 
                        <div class="col-md-offset-2 col-md-10">
                        	<div class="alert alert-warning inline">{%lang Во время обновления лучше не выполнять никаких действий%}</div>
                        
                            <button class="btn btn-success save dont-change animated effect-touch">{%lang Установить обновление%}</button>
                        </div>
                    </div>
                    
                    <script type="text/javascript">
						$('#update-content button').bind('click', function(){
							if(confirm('{%lang Начать обновление?%}'))
								$('#update-content').Request({ controller: '{%controllerName%}', action: 'update', data: "step=0&init=true&"+$("#update-content").serialize() });
							return false;
						});
					</script>
                </form> 
                
            </div>
        {%end%}
    {%else%}
    	{%includeview select_file%}
    {%endif%}
</div>