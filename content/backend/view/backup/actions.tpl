<div class="cms_actions_top panel panel-default">
        
    {%ifset __edit%}
        {%ifset controller_actions_fast_add%}
            <div class="col">
                <a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%_fast_add_value%}" class="nodecoration animated_all"><div class="add-action"><span>{%lang Создать резервную копию%}</span></div></a>
            </div>
        {%endif%}
    {%endif%}
    
    <div class="col cms_actions_row animated_all">
    	<div class="cms_action delete" rel="fast_delete"></div>
    </div>
    
</div>
	

