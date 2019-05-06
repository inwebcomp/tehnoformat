<div class="form-group{%@ifset "err_"._field_name%} has-error{%endif%}">
    <label class="control-label col-md-2" for="{%_field_name%}">{%@ifset "_field_"._field_name."_required"%}<span class="required_icon">*</span> {%endif%}{%@ifset "_field_"._field_name."_description"%}<a rel="{%_field_name%}" title="{%@ "_field_"._field_name."_description"%}">{%endif%}{%_field_title%}{%@ifset "_field_"._field_name."_description"%}</a>{%endif%}</label>
    <div class="col-md-{%_field_width%}" style=" margin-top: 7px;">
        <a style="margin-right: 32px;" target="_blank" href="/{%if language_name == config_default_language%}{%else%}{%language_name%}/{%endif%}{%name%}" class="animated"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> {%lang Редактировать на сайте%}</a>

        {%@ifset "err_"._field_name%}<span class="help-block">{%@ "err_"._field_name."_mess"%}</span>{%endif%}
    </div>
</div>