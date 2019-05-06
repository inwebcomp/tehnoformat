<div class="form-group{%@ifset "err_"._field_name%} has-error{%endif%}">
    <label class="control-label col-md-2" for="{%_field_name%}">{%@ifset "_field_"._field_name."_description"%}<a rel="{%_field_name%}" title="{%@ "_field_"._field_name."_description"%}">{%endif%}{%_field_title%}{%@ifset "_field_"._field_name."_description"%}</a>{%endif%}</label>
    <div class="col-md-{%_field_width%}">
        <div class="checkbox"><input{%if @_field_name == "1"%} checked{%endif%}{%ifnotset ID%}{%@ifset "_field_"._field_name."_default"%} checked{%endif%}{%endif%} type="checkbox" name="params[{%_field_name%}]" class="form-control" id="{%_field_name%}" /><label></label></div>
        {%@ifset "err_"._field_name%}<span class="help-block">{%@ "err_"._field_name."_mess"%}</span>{%endif%}
    </div>
</div>