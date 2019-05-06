{%if type == "Default"%}
	{%if fieldname == "price"%}
		<div id="fast-edit-{%object%}-{%fieldname%}">{%value%} {%currency%}</div>
    {%else%}
    	<div id="fast-edit-{%object%}-{%fieldname%}">{%value%}</div>
    {%endif%}
{%endif%}
{%if type == "Bool"%}
	<div id="fast-edit-{%object%}-{%fieldname%}"><div class="checkbox"><input class="styled focus-input" type="checkbox" value="1"{%if value !== "1"%} checked{%endif%} /><label></label><input type="hidden" value="{%value%}" /></div></div>
{%endif%}