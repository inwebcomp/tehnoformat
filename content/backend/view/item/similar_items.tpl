{%block similar_items%}
<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default" id="similar_items">

	{%ifset mess%}
    	<div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
        	<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<strong>{%mess%}</strong>
        </div>
    {%endif%}

	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Похожие курсы%}</div>
        </h3>
    </div>
    
	<div class="panel-body">

        {%if .count > 0%}
        	<form class="form-horizontal">
                <div class="form-group items-container">
                    <label class="control-label col-md-3">{%lang Курс%}:</label>
                    <div class="col-md-9 without_padding">
                        {%list .items%}
                        <div class="item col-md-6">
                            <button type="button" class="close delete-image" aria-label="{%lang Удалить%}" rel="{%.ID%}"><span aria-hidden="true">&times;</span></button>
                            <!--<div class="checkbox">{%lang Обязательное%}<input class="styled" type="checkbox" value="{%.similar_ID%}"{%if .required == 1%} checked="checked"{%endif%} /><label></label></div>-->
                            <a href="/image/{%modelName%}/{%.ID%}/0x0/{%.base_image%}" class="thumbnail" target="_blank">
                                <img src="/image/{%modelName%}/{%.ID%}/200x150/{%.base_image%}" width="200" height="150" />
                            </a>
                            <a href="/backend/{%language_name%}/index/link/item/edit/{%.ID%}" class="title">{%.title%}</a>
                        </div>   
                        {%end%}
                    </div>
                </div>         
            </form>
        {%endif%}
    
        {%controllerdynamic .select_similar_item%}
        
        <script type="text/javascript">
			$('#similar_items .delete-image').bind('click', function(){ 
				if(confirm('{%lang Вы действительно желаете удалить курс из этого списка?%}')){
					return $('#similar_items').Request({ controller: '{%controllerName%}', action: 'delete_similar', data: 'object=' + $(this).attr("rel") + '&main_object={%.object%}' });
				}
			});
			$('#similar_items .items-container .item .checkbox input').bind('click', function() {
				return $('#similar_items').Request({ controller: '{%controllerName%}', action: 'change_required', data: 'ID=' + $(this).val() });
			});
		</script>
      	
	</div>
</div>
{%end%}