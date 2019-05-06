<div class="form-group{%@ifset "err_"._field_name%} has-error{%endif%}">
    <label class="control-label col-md-2" for="{%_field_name%}">{%@ifset "_field_"._field_name."_required"%}<span class="required_icon">*</span> {%endif%}{%@ifset "_field_"._field_name."_description"%}<a rel="{%_field_name%}" title="{%@ "_field_"._field_name."_description"%}">{%endif%}{%_field_title%}{%@ifset "_field_"._field_name."_description"%}</a>{%endif%}</label>
    <div class="col-md-{%_field_width%}">
        <input value="{%@ _field_name%}" type="number" name="params[{%_field_name%}]" class="form-control" id="{%_field_name%}"{%if _field_name == "pos"%} min="0" step="10"{%endif%}{%@ifset "_field_"._field_name."_max_length"%} maxlength="{%@ "_field_"._field_name."_max_length"%}"{%endif%}{%@ifset "_field_"._field_name."_default"%} placeholder="{%@ "_field_"._field_name."_default"%}"{%endif%}>
        {%@ifset "err_"._field_name%}<span class="help-block">{%@ "err_"._field_name."_mess"%}</span>{%endif%}
    </div>
	<div class="col-md-2 inline-column">
        <select name="params[currency]" class="form-control small" id="currency">
            {%block currency_block%}
                {%list .items%}
                    <option {%if currency == .name%}selected{%endif%} value="{%.name%}">{%.symbol%}</option>
                {%end%}
            {%end%}                  
        </select>
    </div>
</div>