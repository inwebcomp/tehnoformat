{%if type == "String"%}
	<div id="fast-edit-{%object%}-{%fieldname%}" class="input-group"><input name="{%fieldname%}" type="text" value="{%value%}" class="form-control focus-input" /></div>
{%endif%}
{%if type == "Int"%}
	<div id="fast-edit-{%object%}-{%fieldname%}" class="input-group"><input name="{%fieldname%}" type="number" value="{%value%}" class="form-control focus-input" /></div>
{%endif%}
{%if type == "Select"%}
	<div id="fast-edit-{%object%}-{%fieldname%}" class="input-group">
        <select name="{%fieldname%}" class="form-control focus-input">
        	<option value="">{%lang Выберите значение%}</option>
        	{%list items%}
            	<option{%if selected == .value%} selected="selected"{%endif%} value="{%.value%}">{%.title%}</option>
            {%end%}
        </select>
    </div>
{%endif%}