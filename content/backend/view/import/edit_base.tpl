<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	{%ifset object%}
        <div class="page-header">
            <h3 class="row">
                <div class="col-sm-8">{%lang Импорт%} <small>«{%subtitle%}»</small></div>
            </h3>
        </div>

        <div class="panel-body">
    
            <form role="form" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                <input value="{%object%}" type="hidden" name="object" />
                
                <div class="form-group file-input-{%oc%}">
                    <label class="control-label col-md-2" for="{%oc%}_file">{%lang Файл%}:</label>
                    <div class="col-md-6">
                        <input name="params[file]" id="{%oc%}_file" class="file-upload-static" type="file" accept=".csv,.xls">
                    </div>
                </div>
                <script type="text/javascript">
					var fileUploaderContainer = $(".file-input-{%oc%}");
					var file_types = ["csv", "xls"];
				</script>
				{%js upload_file_static%}
                
                <div class="form-group{%ifset .err_skip%} has-error{%endif%}">
                    <label class="control-label col-md-2" for="skip">{%lang Пропустить строк%}:</label>
                    <div class="col-md-6">
                        <input value="{%ifset skip_rows%}{%skip_rows%}{%else%}1{%endif%}" type="text" name="params[skip]" class="form-control" id="skip">
                        {%ifset .err_skip%}<span class="help-block">{%.err_skip_mess%}</span>{%endif%}
                    </div>
                </div>
                
                <div class="form-group{%ifset .err_columns_id%} has-error{%endif%}">
                    <label class="control-label col-md-2" for="columns_id">{%lang Строка-ид. полей%}:</label>
                    <div class="col-md-6">
                        <input value="{%ifset columns_id%}{%columns_id%}{%else%}0{%endif%}" type="text" name="params[columns_id]" class="form-control" id="columns_id">
                        {%ifset .err_columns_id%}<span class="help-block">{%.err_columns_id_mess%}</span>{%endif%}
                    </div>
                </div>
                
                <div class="form-group{%ifset .err_separator%} has-error{%endif%}">
                    <label class="control-label col-md-2" for="separator">{%lang Разделитель%}:</label>
                    <div class="col-md-2">
                        <input value="{%ifset separator%}{%separator%}{%else%};{%endif%}" type="text" name="params[separator]" class="form-control" id="separator">
                        {%ifset .err_separator%}<span class="help-block">{%.err_separator_mess%}</span>{%endif%}
                    </div>
                </div>
                
                {%if has_images == 1%}
                	<hr class="separator" />
                    
					<div class="form-group{%ifset .err_images_dir%} has-error{%endif%}">
                        <label class="control-label col-md-2" for="images_dir">{%lang Путь к изображениям%}:</label>
                        <div class="col-md-6">
                            <input value="" type="text" name="params[images_dir]" class="form-control" id="images_dir" placeholder="/import/">
                            {%ifset .err_images_dir%}<span class="help-block">{%.err_images_dir_mess%}</span>{%endif%}
                        </div>
                    </div>
				{%endif%}

                <div class="form-group adm-buttons animated_all"> 
                    <div class="col-md-offset-2 col-md-10">
                        <button type="submit" name="submit" class="btn btn-success save dont-change animated effect-touch">{%lang Импорт%}</button>
                    </div>
                </div>
            </form> 
            
        </div>
	{%endif%}
</div>

{%ifcount csv_items > 0%}
	<div class="list wow {%config_animation_effect_list%} panel panel-default overflow-x">
    	<form role="form" method="post" action="" class="form-horizontal">
        	{%if has_images == 1%}
            	<input type="hidden" name="params[images_dir]" value="{%images_dir%}" />
            {%endif%}
            <table class="table table-hover">
                <thead>
                    <tr>
                        {%list csv_items%}
                            {%ifset ._first%}
                                {%list .cols%}
                                    <th>
                                        <!--<select name="fields[col_{%._index%}]" class="form-control">
                                            <option value="">--</option>
                                            {%ifinarray "article",columns%}<option value="article" {%if ._index == 1%}selected="selected"{%endif%}>{%lang Артикул%}</option>{%endif%}
                                            {%ifinarray "price",columns%}<option value="price" {%if ._index == 2%}selected="selected"{%endif%}>{%lang Цена%}</option>{%endif%}
                                            {%ifinarray "price_eur",columns%}<option value="price_eur">{%lang Цена%} $</option>{%endif%}
                                            {%ifinarray "price_usd",columns%}<option value="price_usd">{%lang Цена%} &euro;</option>{%endif%}
                                            {%ifinarray "old_price",columns%}<option value="old_price" {%if ._index == 3%}selected="selected"{%endif%}>{%lang Старая цена%}</option>{%endif%}
                                            {%ifinarray "city_price",columns%}<option value="city_price">{%lang Цена по городу%}</option>{%endif%}
                                            {%ifinarray "discount",columns%}<option value="discount">{%lang Скидка%}</option>{%endif%}         
                                        </select>-->
                                        
                                        <select name="fields[col_{%._index%}]" class="form-control">
                                            <option value="">--</option>
                                            {%list columns%}
                                            	<option value="{%.value%}" {%if csv_items.cols.selected_col == .value%}selected="selected"{%endif%}>{%.value%}</option>
                                            {%end%}        
                                        </select>
                                    </th>
                                {%end%}
                            {%endif%}
                        {%end%}
                    </tr>
                </thead>
                <tbody>
                    {%list csv_items%}
                        <tr class="vertical-align{%if .block == 1%} warning{%endif%}">
                        	{%list .cols%}
                            	<th style="min-width:150px;"><input name="items[{%csv_items._index%}][col_{%._index%}]" class="form-control normal-weight" value="{%.value%}" /></th>
                            {%end%}
                        </tr>
                    {%end%}
                </tbody>
            </table>
            
            <div class="adm-buttons animated_all"> 
                <button type="submit" name="upload" class="btn btn-success save animated effect-touch">{%lang Сохранить%}</button>
            </div>
        </form>
    </div>
{%endif%}