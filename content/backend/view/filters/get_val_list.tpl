<select name="params[val]" class="form-control" id="val">
    {%list .val_list%}
        <option {%if val == .val%}selected{%endif%}>{%.val%}</option>
    {%end%}
</select>