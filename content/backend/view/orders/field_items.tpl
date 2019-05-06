<div class="form-group{%@ifset "err_"._field_name%} has-error{%endif%}">
    <label class="control-label col-md-2" for="{%_field_name%}">{%@ifset "_field_"._field_name."_required"%}<span class="required_icon">*</span> {%endif%}{%@ifset "_field_"._field_name."_description"%}<a rel="{%_field_name%}" title="{%@ "_field_"._field_name."_description"%}">{%endif%}{%_field_title%}{%@ifset "_field_"._field_name."_description"%}</a>{%endif%}</label>
    <div class="col-md-{%_field_width%}">
		<span class="help-block as_items printBlock">
        	{%ifcount items > 0%}
            	<div class="items_table">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr class="titles">
                            <td class="count">{%lang Количество%}</td>
                            <td class="info">{%lang Товар%}</td>
                            <td class="summ">{%lang Сумма%}</td>
                        </tr>
                        {%list items%}
                            {%if .multi == "0"%}
                                <tr class="item{%if .mainitem_ID !== "0"%} multiitem{%endif%}">
                                    <td class="count">{%ifnotset .gift%}{%.count%}{%else%}<div class="is_a_share">{%lang Акция%}</div>{%endif%}</td>
                                    <td class="item-info">
                                        <a target="_blank" href="{%root%}/{%language_name%}/product/info/{%.name%}" class="nolink"><img src="{%root%}/image/Item/{%.ID%}/100x100/{%.base_image%}" width="100" height="100" /></a>
                                        <div class="info">
                                            <a target="_blank" href="{%root%}/{%language_name%}/product/info/{%.name%}" class="title nolink">{%.title%}</a>
                                            <div class="id">{%lang Код товара%}: {%.ID%}</div>
                                            <div class="price">
                                                {%block .price%}
                                                    {%block .default%}
                                                        <div class="main_price"><b>{%.price%}</b>{%if .space == 1%} {%endif%}<span>{%.symbol%}</span></div>
                                                    {%end%}
                                                    {%ifcount items.price > 1%}
                                                        <div class="other_prices">
                                                            ({%list items.price%}{%if .name !== items.price.default.name%}<b rel="{%.value%}" class="{%.name%}">{%.price%}</b>{%if .space == 1%} {%endif%}{%.symbol%}{%ifnotset ._last%}, {%endif%}{%endif%}{%end%})
                                                        </div>
                                                    {%endif%}
                                                {%end%}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="summ">
                                        {%ifnotset .gift%}
                                            {%block .summ%}
                                                <div class="price">
                                                    {%block .default%}
                                                        <div class="main_price"><b>{%.price%}</b>{%if .space == 1%} {%endif%}<span>{%.symbol%}</span></div>
                                                    {%end%}
                                                    {%ifcount items.summ > 1%}
                                                        <div class="other_prices">
                                                            ({%list items.summ%}{%if .name !== items.summ.default.name%}<b rel="{%.value%}" class="{%.name%}">{%.price%}</b>{%if .space == 1%} {%endif%}{%.symbol%}{%ifnotset ._last%}, {%endif%}{%endif%}{%end%})
                                                        </div>
                                                    {%endif%}
                                                </div>
                                            {%end%}
                                        {%else%}
                                            <label class="is_a_gift">{%lang Подарок%}</label>
                                        {%endif%}
                                    </td>
                                </tr>
                            {%endif%}
                            {%if .multi == "1"%}
                                <tr class="item multi" rel="{%.ID%}">
                                    <td class="count"><div class="icon black_arrow_down"></div></td>
                                    <td class="item-info">
                                        <a target="_blank" href="{%root%}/{%language_name%}/product/info/{%.name%}" class="nolink"><img src="{%root%}/image/Item/{%.ID%}/100x100/{%.base_image%}" width="100" height="100" /></a>
                                        <div class="info">
                                            <a target="_blank" href="{%root%}/{%language_name%}/product/info/{%.name%}" class="title nolink">{%.title%}</a>
                                            <div class="id">{%lang Код товара%}: {%.ID%}</div>
                                        </div>
                                    </td>
                                    <td class="summ">
            
                                    </td>
                                </tr>
                            {%endif%}
                        {%end%}
                    </table>
                
                    <div class="all_price">
                        {%block all_price%}
                            <div class="price">
                                {%block .default%}
                                    <div class="main_price"><b>{%.price%}</b>{%if .space == 1%} {%endif%}<span>{%.symbol%}</span></div>
                                {%end%}
                                {%ifcount all_price > 1%}
                                    <div class="other_prices">
                                        ({%list all_price%}{%if .name !== all_price.default.name%}<b rel="{%.value%}" class="{%.name%}">{%.price%}</b>{%if .space == 1%} {%endif%}{%.symbol%}{%ifnotset ._last%}, {%endif%}{%endif%}{%end%})
                                    </div>
                                {%endif%}
                            </div>
                        {%end%}
                    </div>
                </div>
            {%else%}{%lang Нет товаров%}{%endif%}
        </span>
    </div>
</div>