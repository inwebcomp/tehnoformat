{%include header_admin%}

{%block items%}
	<div class="cmf_items_left">
		{%ifset mess%}<div class="cmf_{%ifset err%}err_{%endif%}mess">{%mess%}</div>{%endif%}
		{%block select%}
            {%if .num == 0%}
                <center style="float:left;">{%lang Данные отсутствуют%}</center>
            {%else%}
                <form id="cmf_fast_save_form_{%oc%}" method="post" action="{%controllerName%}/fast_save" class="ajax-form-request">
                    {%includeview item_list_items%}
                </form>
                {%includeview pages%}
            {%endif%}
        {%end%}
	</div>
	
    
    {%includeview actions%}

{%end%}

{%include footer_admin%}

{%js crud_items%}