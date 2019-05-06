<div id="select_items">
	{%block path%}
        <div class="form-group">
            <label class="control-label col-md-2">{%lang Товары в категории%}:</label>
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
                        <label class="control-label col-md-2">{%lang Товары%}:</label>
                        <div class="col-md-6">
                            <select size="8" multiple name="params[items][]" class="form-control">
                                {%list .items%}
                                    {%if .mainitem_ID == "0"%}<option{%ifinarray .ID,items_array%} selected{%endif%} value="{%.ID%}">{%.title%}</option>{%endif%}
                                {%end%}
                            </select>
                        </div>
                    </div>
			{%endif%}
		{%end%}
    {%end%}
		
	<script type="text/javascript">
        $('#select_items .cms_parent').bind('click', function() {
            return $('#select_items').Request({ controller: '{%controllerName%}', action: 'select_items', data: 'object={%object_ID%}&category_ID=' + $(this).attr('rel') });
        });

        $('#select_items #where_category_ID_{%oc%}').bind('change', function() {
                return $('#select_items').Request({ controller: '{%controllerName%}', action: 'select_items', data: 'object={%object_ID%}&category_ID=' + $(this).val() });
        });
    </script>
</div>