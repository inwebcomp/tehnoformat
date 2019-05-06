<div class="form-group{%@ifset "err_"._field_name%} has-error{%endif%}">
    <label class="control-label col-md-2" for="{%_field_name%}">{%@ifset "_field_"._field_name."_required"%}<span class="required_icon">*</span> {%endif%}{%@ifset "_field_"._field_name."_description"%}<a rel="{%_field_name%}" title="{%@ "_field_"._field_name."_description"%}">{%endif%}{%_field_title%}{%@ifset "_field_"._field_name."_description"%}</a>{%endif%}</label>
    <div class="col-md-{%_field_width%}">
        <input value="{%@ _field_name%}" type="datetime-local" name="params[{%_field_name%}]" class="form-control" id="{%_field_name%}"{%if _field_name == "pos"%} min="0" step="10"{%endif%}{%@ifset "_field_"._field_name."_max_length"%} maxlength="{%@ "_field_"._field_name."_max_length"%}"{%endif%}{%@ifset "_field_"._field_name."_default"%} placeholder="{%@ "_field_"._field_name."_default"%}"{%endif%}>
        {%@ifset "err_"._field_name%}<span class="help-block">{%@ "err_"._field_name."_mess"%}</span>{%endif%}
        <script type="text/javascript">
			var value = "{%@ _field_name%}".split(" "); 
			$("#{%_field_name%}").val(value[0]+"T"+value[1].substring(0, value[1].length - 3));
		</script>
    </div>
</div>