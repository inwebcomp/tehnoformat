<div class="cms_actions_top panel panel-default">
        
    {%ifset __edit%}
        {%ifset controller_actions_fast_add%}
            <div class="col">
                <a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%_fast_add_value%}" class="nodecoration animated_all"><div class="add-action"><span>{%lang Добавить%}</span></div></a>
            </div>
        {%endif%}
    {%endif%}
    
    {%ifset controller_actions_fast_actions%}
        {%block select%}
            {%if .num > 0%}
                {%ifset items.ID%}
                    <div class="col cms_actions_row animated_all">
                        {%ifset controller_actions_fast_save%}<div class="cms_action save" rel="fast_save"></div>{%endif%}
                        {%ifset controller_actions_fast_block%}<div class="cms_action block" rel="fast_block"></div>{%endif%}
                        {%ifset controller_actions_fast_unblock%}<div class="cms_action unblock" rel="fast_unblock"></div>{%endif%}
                        {%ifset controller_actions_fast_delete%}<div class="cms_action delete" rel="fast_delete"></div>{%endif%}
                    </div>
                {%endif%}
            {%endif%}
        {%end%}
    {%endif%}

	{%includeview filters%}

</div>

	{%includeview filters_base%}
	

