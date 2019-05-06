<div class="form-group{%@ifset "err_"._field_name%} has-error{%endif%}">
    <label class="control-label col-md-2" for="{%_field_name%}">{%@ifset "_field_"._field_name."_required"%}<span class="required_icon">*</span> {%endif%}{%@ifset "_field_"._field_name."_description"%}<a rel="{%_field_name%}" title="{%@ "_field_"._field_name."_description"%}">{%endif%}{%_field_title%}{%@ifset "_field_"._field_name."_description"%}</a>{%endif%}</label>
    <div class="col-md-{%_field_width%}">
        <select name="params[{%_field_name%}]" field="{%_field_name%}" class="form-control">
            <option value="0" {%if status == "0"%}selected{%endif%}>{%lang Ожидает проверки%}</option>
            <option value="1" {%if status == "1"%}selected{%endif%}>{%lang Ожидание оплаты%}</option>
            <option value="2" {%if status == "2"%}selected{%endif%}>{%lang В работе%}</option>
            <option value="3" {%if status == "3"%}selected{%endif%}>{%lang Завершён%}</option>
            <option value="4" {%if status == "4"%}selected{%endif%}>{%lang Отклонён%}</option>
        </select>
        {%@ifset "err_"._field_name%}<span class="help-block">{%@ "err_"._field_name."_mess"%}</span>{%endif%}
    </div>
</div>