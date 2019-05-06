<div id="select_item">
	{%block path%}
        <div class="form-group">
            <label class="control-label col-md-2">{%lang Товар в категории%}:</label>
            <div class="col-md-6">
                <ol class="breadcrumb">
                {%list .path%}
                    <li><a href="javascript:void(0)" class="cms_parent" rel="{%.ID%}">{%.title%}</a></li>
                {%end%}
                </ol>              
            </div>
        </div>
        
        {%block .nodes%}
            {%if .count > 0%}
                <div class="form-group">
                    <label class="control-label col-md-2" for="where_category_ID_{%oc%}">{%lang Подкатегория%}:</label>
                    <div class="col-md-3">
                        <select size="1" id="where_category_ID_{%oc%}" name="category_ID" class="form-control">
                            <option value="{%parent_ID%}">{%lang Выберите значение%}</option>
                            {%list .items%}
                                <option value="{%.ID%}">{%.title%}</option>
                            {%end%}
                        </select>
                    </div>
                </div>
            {%endif%}
        {%end%}
    
		{%block products%}
			{%if .count > 0%}
                    <div class="form-group">
                        <label class="control-label col-md-2">{%lang Товар%}:</label>
                        <div class="col-md-6">
                            <select size="1" name="params[object_ID]" class="form-control">
                                {%list .items%}
                                    {%if .mainitem_ID == "0"%}<option{%if .ID == object_ID%} selected{%endif%} value="{%.ID%}">{%.title%}</option>{%endif%}
                                {%end%}
                            </select>
                        </div>
                    </div>
			{%endif%}
		{%end%}
    {%end%}
		
	<script type="text/javascript">
        $('#select_item .cms_parent').bind('click', function() {
            return $('#select_item').Request({ controller: '{%controllerName%}', action: 'select_item', data: 'object={%object_ID%}&category_ID=' + $(this).attr('rel') });
        });

        $('#select_item #where_category_ID_{%oc%}').bind('change', function() {
                return $('#select_item').Request({ controller: '{%controllerName%}', action: 'select_item', data: 'object={%object_ID%}&category_ID=' + $(this).val() });
        });
    </script>
</div>