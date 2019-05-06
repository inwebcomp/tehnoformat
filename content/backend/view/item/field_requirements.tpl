<div class="{%_field_name%}-field{%@ifset "err_"._field_name%} has-error{%endif%}">
    <div class="form-group">
        <label class="control-label col-md-2">{%_field_title%}</label>
        <div class="col-md-6">
            {%list requirements%}
                <div class="array-field-line">
                    <input value="{%.text%}" type="text" name="params[{%_field_name%}][][text]" class="form-control" id="{%_field_name%}-{%._index%}" />

                    <a rel="{%._index%}" class="remove-{%_field_name%}-field animated nodecoration" onclick="$('#{%_field_name%}-'+$(this).attr('rel')).parent().replaceWith('');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                </div>
            {%end%}
            
            <div class="array-field-line">
                <input value="{%.text%}" type="text" name="params[{%_field_name%}][][text]" class="form-control" id="{%_field_name%}-0" />

                <a rel="0" class="remove-{%_field_name%}-field animated nodecoration" onclick="$('#{%_field_name%}-'+$(this).attr('rel')).parent().replaceWith('');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
            </div>

            <a class="animated nodecoration" id="add-{%_field_name%}-field"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {%lang Добавить%}</a>

            {%@ifset "err_"._field_name%}<span class="help-block">{%@ "err_"._field_name."_mess"%}</span>{%endif%}
        </div>
    </div>
    
</div>

<script type="text/javascript">
    $("#add-{%_field_name%}-field").bind("click", function(){
        var line = $(".{%_field_name%}-field .array-field-line:last").clone();
        line.find("input").val('');
        line.find("input").attr('id', '{%_field_name%}-' + ($(".{%_field_name%}-field .array-field-line").length));
        line.find("a").attr('rel', $(".{%_field_name%}-field .array-field-line").length);

        $(this).before(line);
    });
</script>

<style>
    .{%_field_name%}-field input {
        margin-bottom: 10px;
    }
    .{%_field_name%}-field .array-field-line {
        position: relative;
    }
    .{%_field_name%}-field a {
        cursor: pointer;
    }
    .remove-{%_field_name%}-field {
        position: absolute;
        top: 0;
        left: 100%;
        padding: 8px;
        cursor: pointer;
    }
</style>