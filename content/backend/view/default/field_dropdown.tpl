<div class="form-group{%@ifset "err_"._field_name%} has-error{%endif%}">
    <label class="control-label col-md-2" for="{%_field_name%}">{%@ifset "_field_"._field_name."_required"%}<span class="required_icon">*</span> {%endif%}{%@ifset "_field_"._field_name."_description"%}<a rel="{%_field_name%}" title="{%@ "_field_"._field_name."_description"%}">{%endif%}{%_field_title%}{%@ifset "_field_"._field_name."_description"%}</a>{%endif%}</label>
    <div class="col-md-{%_field_width%}">
        <input value="{%@ _field_name%}" type="hidden" name="params[{%_field_name%}]" id="field-{%_field_name%}">
        
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdown-{%_field_name%}" rel="{%_field_name%}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <span class="dropdown-title">{%_field_title%}</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            	{%block colors_block%}
                	{%list .items%}
                		<li{%if .ID == color%} class="selected"{%endif%}><a rel="{%.ID%}"><div class="color-block" style="background-color:{%.color%};"></div><span>{%.title%}</span></a></li>
                    {%end%}
                {%end%}
            </ul>
        </div>
        
        {%@ifset "err_"._field_name%}<span class="help-block">{%@ "err_"._field_name."_mess"%}</span>{%endif%}
    </div>
</div>