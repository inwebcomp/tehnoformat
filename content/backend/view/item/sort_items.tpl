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
    {%includeview filters_base_sort%}
    
	{%block select%}
        <form id="{%oc%}_request_params" class="request_params">
    		{%includeview request_params%}
        </form>
    	{%if .num > 0%}
        	<form id="cms_actions_form_{%oc%}" method="post" action="{%controllerName%}/fast_save" class="ajax-form-request">
                {%includeview sort_items_list%}
            </form>
            {%includeview pages%}
        {%else%}
        	<h4 class="not_found">{%lang Данные отсутствуют%}</h4>
        {%endif%}
    {%end%} 
{%end%}

<style>
    .grid-sort { overflow:hidden; position:relative; }
    .grid-sort .item { margin:10px; border:1px solid #CCC; float:left; padding:5px; background-color:#FFF; cursor:pointer; width:82px; height:82px; }
    .grid-sort .item.selected { background-color:#999; }
    .grid-sort .item .discount {
        display: inline-block;
        background: #FFF;
        padding: 3px 5px;
        border-radius: 3px;
        font-size: 11px;
        border: 1px solid #CCC;
        color: #D00B0B;
        line-height: 12px;
        margin-bottom: 8px;
        position: relative;
        top: -6px;
        left: 50%;
        margin-left: -20px;
        width: 40px;
        text-align: center;
    }
</style>

{%include footer_free_style%}