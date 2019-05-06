{%include header_free_style%}

{%block items%}
	{%ifset mess%}
    	<div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
        	<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<strong>{%mess%}</strong>
        </div>
    {%endif%}
    
    {%block header%}
        <div class="page-header list panel panel-default">
            <h3 class="row">
                <div class="col-sm-12">{%.title%} {%ifset .sub_title%}<p>«{%.sub_title%}»</p>{%endif%}</div>
            </h3>
        </div>
    {%end%}
    
    {%list items%}{%end%}
    {%includeview actions%}
    
	{%block select%}
        <form id="{%oc%}_request_params" class="request_params">
    		{%includeview request_params%}
        </form>
    	{%if .num > 0%}
        	<form id="cms_actions_form_{%oc%}" method="post" action="{%controllerName%}/fast_save" class="ajax-form-request">
                {%includeview items_base%}
            </form>
            {%includeview pages%}
        {%else%}
        	<h4 class="not_found">{%lang Данные отсутствуют%}</h4>
        {%endif%}
    {%end%} 
{%end%}

{%include footer_free_style%}

{%js crud_items%}