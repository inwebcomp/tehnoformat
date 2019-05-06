{%if type == "Default"%}
	<div id="fast-edit-{%object%}-{%fieldname%}">{%value%}</div>
{%endif%}
{%if type == "Bool"%}
	{%if fieldname == "block"%}
    	<div id="fast-edit-{%object%}-{%fieldname%}">
        	<div class="checkbox show_only_label"><input class="styled focus-input" type="checkbox" value="1"{%if value !== "1"%} checked{%endif%} id="{%object%}_checkbox_block" /><label for="{%object%}_checkbox_block"><span class="glyphicon glyphicon-eye-open{%if value !== "1"%} active{%endif%}" aria-hidden="true"></span></label><input type="hidden" value="{%value%}" /></div>
        </div>
    {%else%}
        {%if fieldname == "popular"%}
            <div id="fast-edit-{%object%}-{%fieldname%}">
                <div class="checkbox show_only_label"><input class="styled focus-input" type="checkbox" value="1"{%if value == "1"%} checked{%endif%} id="{%object%}_checkbox_popular" /><label for="{%object%}_checkbox_popular"><span class="glyphicon glyphicon-ok{%if value == "1"%} active{%endif%}" aria-hidden="true"></span></label><input type="hidden" value="{%value%}" /></div>
            </div>
        {%else%}
            {%if reverse == "1"%}
                <div id="fast-edit-{%object%}-{%fieldname%}"><div class="checkbox"><input class="styled focus-input" type="checkbox" value="1"{%if value !== "1"%} checked{%endif%} /><label></label><input type="hidden" value="{%value%}" /></div></div>
            {%else%}
                <div id="fast-edit-{%object%}-{%fieldname%}"><div class="checkbox"><input class="styled focus-input" type="checkbox" value="1"{%if value == "1"%} checked{%endif%} /><label></label><input type="hidden" value="{%value%}" /></div></div>
            {%endif%}
        {%endif%}
    {%endif%}
{%endif%}