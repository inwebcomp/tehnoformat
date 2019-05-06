<!--<fieldset class="cmf_fieldset">
	<legend>{%lang Общая информация%}</legend>

<form method="post" action="{%controllerName%}/save" class="ajax-form-request">
{%ifset ID%}
	<input name="object" type="hidden" value="{%ID%}" />
{%endif%}

                <div class="cmf_line">
                    <div class="cmf_labelline">
                            <label for="param_{%oc%}">* {%lang Параметр%}:</label>
                    </div>
                    <div class="cmf_inputline">
                        <select size="1" name="params[param]" id="param_{%oc%}" class="cmf_select {%ifset err_param%}cmf_err{%endif%}"> 
                                {%block param_list%}
                                    {%list .items%}
                                        <option value="{%.name%}" {%if param == .name%}selected{%endif%}>{%.title%} ({%.name%})</option>
                                    {%end%}
                                {%end%}
                        </select>
                        {%ifset err_param%}<div class="err">{%lang Ошибка%}! {%err_mess_param%}</div>{%endif%}
                    </div>
            
                    <div class="clear"></div>
                </div>
                
                <div class="cmf_line">
                    <label for="param_name_{%oc%}">* {%lang Заголовок группы параметров%}:</label>
                    <input name="params[param_name]" id="param_name_{%oc%}" type="text" value="{%param_name%}" class="cmf_txtinput {%ifset err_param_name%}cmf_err{%endif%}" />
                    {%ifset err_param_name%}<div class="err">{%lang Ошибка%}! {%err_mess_param_name%}</div>{%endif%}
                </div>
                
                <div class="cmf_line">
                    <div class="cmf_labelline">
                            <label for="val_{%oc%}">* {%lang Значение для поиска%}:</label>
                    </div>
                    <div class="cmf_inputline">
                        <select size="1" name="params[val]" id="val_list" class="cmf_select {%ifset err_val%}cmf_err{%endif%}">
                                {%list .val_list%}
                                    <option value="{%.val%}" {%if val == .val%}selected{%endif%}>{%.val%}</option>
                                {%end%}
                        </select>
                        {%ifset err_val%}<div class="err">{%lang Ошибка%}! {%err_mess_val%}</div>{%endif%}
                    </div>
            
                    <div class="clear"></div>
                </div>
                
                <div class="cmf_line">
                    <label for="title_{%oc%}">* {%lang Заголовок параметра%}:</label>
                    <input name="params[title]" id="title_{%oc%}" type="text" value="{%title%}" class="cmf_txtinput {%ifset err_title%}cmf_err{%endif%}" />
                    {%ifset err_title%}<div class="err">{%lang Ошибка%}! {%err_mess_title%}</div>{%endif%}
                </div>
                
                <input value="{%category%}" type="hidden" name="params[category]" /><br />
                
                <div class="cmf_line">
                    <div class="cmf_labelline">
                            <label for="type_{%oc%}">{%lang Тип%}:</label>
                    </div>
                    <div class="cmf_inputline">
                        <select size="1" name="params[type]" id="type_{%oc%}" class="cmf_select {%ifset err_type%}cmf_err{%endif%}">
                                <option value="list" {%if type == "list"%}selected{%endif%}>{%lang Список%}</option>
                                <option value="select" {%if type == "select"%}selected{%endif%}>{%lang Выпадающий список%}</option>
                                <option value="interval" {%if type == "interval"%}selected{%endif%}>{%lang Интервал%}</option>
                        </select>
                        {%ifset err_type%}<div class="err">{%lang Ошибка%}! {%err_mess_type%}</div>{%endif%}
                    </div>
            
                    <div class="clear"></div>
                </div>
                
                <span class="input_label">{%lang Первое значение%}:</span>
                <input value="{%min%}" class="interval_value short" type="text" name="params[min]" /><br />
                
                <span class="input_label">{%lang Последнее значение%}:</span>
                <input value="{%max%}" class="interval_value short" type="text" name="params[max]" /><br />
            
            	<div class="cmf_line">
                    <label for="pos_{%oc%}">{%lang Позиция%}:</label>
                    <input name="params[pos]" id="pos_{%oc%}" style="width:100px;" type="text" value="{%pos%}" class="cmf_txtinput {%ifset err_pos%}cmf_err{%endif%}" />
                    {%ifset err_pos%}<div class="err">{%lang Ошибка%}! {%err_mess_pos%}</div>{%endif%}
                </div>
            
        
        

        
<div class="clear"></div>


<div class="cmf_actions">
	<input onclick='window.location.href="/backend/{%language%}/index/link/filters/items/{%category%}/";return false;' type="button" class="cmf_button cmf_back disabled" value="{%lang Назад%}" />
	<input type="submit" class="cmf_button" value="{%lang Сохранить%}" />
	<input onclick='window.location.href="/backend/{%language%}/index/link/filters/edit/0/{%category%}/";return false;' type="button" class="cmf_button cmf_add disabled" value="{%lang Добавить новый%}" />
</div>

</form>

</fieldset>-->



<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Фильтр%}{%ifset .ID%} <small>{%lang ID%}: {%.ID%}</small>{%endif%}</div>
        	<div class="col-sm-4 action"><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/" class="animated"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {%lang Добавить запись%}</a></div>
        </h3>
    </div>
    
	<div class="panel-body">

		<form role="form" rel="{%oc%}" action="{%controllerName%}/save" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
        	<input value="{%.ID%}" type="hidden" name="object" />
        	<input value="{%category%}" type="hidden" name="params[category]" />
        
        	<div class="form-group{%ifset .err_param%} has-error{%endif%}">
                <label class="control-label col-md-2" for="param">{%lang Значение%}:</label>
                <div class="col-md-4">
                    {%block param_list%}
                    	<select name="params[param]" class="form-control" id="param">
                        	<option value="" {%if param == ""%}selected{%endif%}>{%lang Выберите значение%}</option>
                            {%list .items%}
                                <option value="{%.name%}" {%if param == .name%}selected{%endif%}>{%.title%} ({%.name%})</option>
                            {%end%}
                        </select>
                    {%end%}
                    {%ifset .err_param%}<span class="help-block">{%.err_param_mess%}</span>{%endif%}
                </div>
            </div>
        
        	<div class="form-group{%ifset .err_param_name%} has-error{%endif%}">
                <label class="control-label col-md-2" for="param_name">{%lang Заголовок группы параметров%}:</label>
                <div class="col-md-4">
                	<input value="{%.param_name%}" type="text" name="params[param_name]" class="form-control" id="param_name">
                    {%ifset .err_param_name%}<span class="help-block">{%.err_param_name_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_val%} has-error{%endif%}">
                <label class="control-label col-md-2" for="val">{%lang Значение для поиска%}:</label>
                <div class="col-md-3">
                    <select name="params[val]" class="form-control" id="val">
                    {%list .val_list%}
                        <option value="{%.val%}" {%if val == .val%}selected{%endif%}>{%.val%}</option>
                    {%end%}
                    </select>
                    {%ifset .err_val%}<span class="help-block">{%.err_val_mess%}</span>{%endif%}
                </div>
            </div>
        
            <div class="form-group{%ifset .err_title%} has-error{%endif%}">
                <label class="control-label col-md-2" for="title">{%lang Заголовок параметра%}:</label>
                <div class="col-md-4">
                	<input value="{%.title%}" type="text" name="params[title]" class="form-control" id="title">
                    {%ifset .err_title%}<span class="help-block">{%.err_title_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_type%} has-error{%endif%}">
                <label class="control-label col-md-2" for="type">{%lang Тип%}:</label>
                <div class="col-md-3">
                    <select name="params[type]" class="form-control">
                        <option value="list" {%if type == "list"%}selected{%endif%}>{%lang Список%}</option>
                    </select>
                    {%ifset .err_type%}<span class="help-block">{%.err_type_mess%}</span>{%endif%}
                </div>
            </div>
            
            <div class="form-group{%ifset .err_pos%} has-error{%endif%}">
                <label class="control-label col-md-2" for="pos">{%lang Позиция%}:</label>
                <div class="col-md-2">
                	<input value="{%.pos%}" type="number" name="params[pos]" class="form-control" id="pos" min="0" step="10">
                    {%ifset .err_pos%}<span class="help-block">{%.err_pos_mess%}</span>{%endif%}
                </div>
            </div>

            <div class="form-group{%ifset .err_block%} has-error{%endif%}">
                <label class="control-label col-md-2" for="block">{%lang Заблокирован%}:</label>
                <div class="col-md-2">
                	<div class="checkbox"><input{%if .block == 1%} checked{%endif%} type="checkbox" name="params[block]" class="form-control" id="block" /><label></label></div>
                    {%ifset .err_block%}<span class="help-block">{%.err_block_mess%}</span>{%endif%}
                </div>
            </div>
                
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                	{%includeview buttons_default%}
                </div>
            </div>
        </form> 
        
        {%js js_params%}
	</div>
</div>
