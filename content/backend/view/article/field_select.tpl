<div class="form-group{%@ifset "err_"._field_name%} has-error{%endif%}">
    <label class="control-label col-md-2" for="{%_field_name%}">{%@ifset "_field_"._field_name."_required"%}<span class="required_icon">*</span> {%endif%}{%@ifset "_field_"._field_name."_description"%}<a rel="{%_field_name%}" title="{%@ "_field_"._field_name."_description"%}">{%endif%}{%_field_title%}{%@ifset "_field_"._field_name."_description"%}</a>{%endif%}</label>
    <div class="col-md-{%_field_width%}">
        <select name="params[{%_field_name%}]" class="form-control">
            <option value="0" {%if .category_ID == "0"%}selected{%endif%}>{%lang Без категории%}</option>
            {%block category_block%}
            	{%list .items%}
                	<option value="{%.ID%}" {%if category_ID == .ID%}selected{%endif%}>{%.title%}</option>
                {%end%}
            {%end%}
        </select>
        {%@ifset "err_"._field_name%}<span class="help-block">{%@ "err_"._field_name."_mess"%}</span>{%endif%}
    </div>
</div>