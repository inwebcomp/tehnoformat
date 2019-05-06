        <form class="actions_values">
			
        	{%block select%}
            
                {%if .num > 0%}
                    <div class="cms_filters">
                        <div class="col">
                        	<label for="cmf_sort_{%oc%}">{%lang Сортировка%}:</label>
                            <select id="cmf_sort_{%oc%}" size="1" name="params[order]" class="cmf_select2 cmf_order">
                                {%ifset items.pos%}<option value="pos" {%if .order == "pos"%}selected="selected"{%endif%}>{%lang Позиция%}</option>{%endif%}
                                {%ifset items.ID%}<option value="ID" {%if .order == "ID"%}selected="selected"{%endif%}>ID</option>{%endif%}
                                {%ifset items.title%}<option value="title" {%if .order == "title"%}selected="selected"{%endif%}>{%lang Заголовок%}</option>{%endif%}
                                {%ifset items.price%}<option value="price" {%if .order == "price"%}selected="selected"{%endif%}>{%lang Цене%}</option>{%endif%}
                                {%ifset items.created%}<option value="created" {%if .order == "created"%}selected="selected"{%endif%}>{%lang Создан%}</option>{%endif%}
                                {%ifset items.updated%}<option value="updated" {%if .order == "updated"%}selected="selected"{%endif%}>{%lang Изменен%}</option>{%endif%}
                                {%ifset items.checked%}<option value="checked" {%if .order == "checked"%}selected="selected"{%endif%}>{%lang Проверен%}</option>{%endif%}
                            </select>
                            <select size="1" name="params[orderDirection]" class="cmf_select2 cmf_orderDirection">
                                <option value="ASC" {%if .orderDirection == "ASC"%}selected="selected"{%endif%}>{%lang По возрастанию%}</option>
                                <option value="DESC" {%if .orderDirection == "DESC"%}selected="selected"{%endif%}>{%lang По убыванию%}</option>
                            </select>
                        </div>
                    </div>
                
                    <div class="cms_filters">
                        <div class="col">
                        	<label for="cmf_onPage_{%oc%}">{%lang На странице%}:</label>
                            <select id="cmf_onPage_{%oc%}" size="1" name="params[onPage]" class="cmf_select2 cms_onPage">
                                <option value="10" {%if .onPage == 10%}selected="selected"{%endif%}>10</option>
                                <option value="30" {%if .onPage == 30%}selected="selected"{%endif%}>30</option>
                                <option value="50" {%if .onPage == 50%}selected="selected"{%endif%}>50</option>
                                <option value="100" {%if .onPage == 100%}selected="selected"{%endif%}>100</option>
                                <option value="500" {%if .onPage == 500%}selected="selected"{%endif%}>500</option>
                                <option value="1000" {%if .onPage == 1000%}selected="selected"{%endif%}>1000</option>
                            </select>
                        </div>
                        <div class="col">
                            <label>Найдено: <strong>{%.num%}</strong></label>
                        </div>
                    </div>
        
                {%endif%}
    
                <!--<div class="cms_filters">
                	<div class="row title">
                    	<label for="where_block_{%oc%}" style="display: inline">{%lang Блокировка%}:</label>
                    </div>
                    <div class="row">
                        <select size="1" id="where_block_{%oc%}" name="params[where][block]" class="cmf_select2 cmf_filter">
                            <option value="_NULL">{%lang Все%}</option>
                            <option value="1" {%block select.where%}{%ifset .block%}{%if .block == 1%}selected="selected"{%endif%}{%endif%}{%end%}>{%lang Заблокированные%}</option>
                            <option value="0" {%block select.where%}{%ifset .block%}{%if .block == 0%}selected="selected"{%endif%}{%endif%}{%end%}>{%lang Разблокированные%}</option>
                        </select>
                    </div>
                </div>-->
         
	{%end%}
    	</form>
        
        