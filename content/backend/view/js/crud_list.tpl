<script type="text/javascript">

    /* Hide Alert */
	
	setInterval(function(){ if(parseInt($('.alert:not(.inline)').css("opacity")) > 0){ $('.alert:not(.inline)').disappear(); } }, 2000);

	/* Change Position */
	if($("#{%oc%} tbody .pos").length > 0){
		$("#{%oc%} tbody").addClass("sortable");
		
		$("#{%oc%} thead tr >:first-child").before('<th></th>');
		$("#{%oc%} tbody .pos").each(function(i){
			$(this).parent().find(">:first-child").before('<th class="pos-handle"><div class="handle-grid"></div></th>');
		});
		
		$("#{%oc%} tbody  tr th").each(function(i){
			$(this).width($(this).width());
			
		});
		
		$("#{%oc%} .sortable").sortable({ axis: "y", handle: ".pos-handle",
			change: function(event,ui){ 
				if(ui.helper.attr("position") == undefined){
					var originalPosition = ui.originalPosition.top;
				}else{
					var originalPosition = ui.helper.attr("position");
				}
				if(originalPosition < ui.position.top){ // Down
					var updateElement = ui.placeholder.prev().find(".pos input");
					var updateElementTr = ui.placeholder.prev();
				}else{ // Up
					var updateElement = ui.placeholder.next().find(".pos input");
					var updateElementTr = ui.placeholder.next();
				}
				var currentElement = ui.helper.find(".pos input");
				var updateVal = updateElement.val();
				
				if(!ui.helper.attr("level") || (parseInt(updateElementTr.attr("level")) == parseInt(ui.helper.attr("level")))){
					updateElement.val(currentElement.val());
					currentElement.val(updateVal);
				}
				
				ui.helper.attr("position", ui.position.top);
			},
			update: function(event,ui){
				var next = ui.item.next();	
				var prev = ui.item.prev();
				
				if((parseInt(ui.item.attr("level")) >= parseInt(next.attr("level")) && ui.item.attr("level") == prev.attr("level")) || (parseInt(ui.item.attr("level")) <= parseInt(prev.attr("level")) && ui.item.attr("level") == next.attr("level")) || (parseInt(ui.item.attr("level")) >= parseInt(prev.attr("level")) && ui.item.attr("level") == next.attr("level")) || (prev.attr("level") == undefined && parseInt(ui.item.attr("level")) == parseInt(next.attr("level"))) || (next.attr("level") == undefined)){
					if(ui.item.attr("parent")){
						var items = $("#{%oc%} .sortable tr[parent="+ui.item.attr("rel")+"]");
						if(items.length > 0) ui.item.after(items);
					}
					if(ui.item.prev().attr("parent")){
						var items = $("#{%oc%} .sortable tr[parent="+ui.item.prev().attr("rel")+"]");
						if(items.length > 0) ui.item.prev().after(items);
					}
					if(ui.item.next().attr("parent")){
						var items = $("#{%oc%} .sortable tr[parent="+ui.item.next().attr("rel")+"]");
						if(items.length > 0) ui.item.next().after(items);
					}
				}else{
					$( "#{%oc%} .sortable" ).sortable( "cancel" );
				}
				
				$("#{%oc%} .sortable tr").removeClass("info");
				ui.item.addClass("info");
			}
		});
		$("#{%oc%} .sortable").disableSelection();
	}
	
	$('#{%oc%} .adm-buttons .btn').bind('click', function() {

        if ($(this).hasClass("fast_delete"))
        { 
        	if (confirm('{%lang Вы действительно желаете удалить выделенные элементы?%}'))
    			$('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'fast_delete', data: $('#{%oc%}_form').serialize()+"&"+$('#{%oc%}_request_params').serialize(), loader: "global" });
    	}
    	else if ($(this).hasClass("fast_save"))
    	{
    		if (confirm('{%lang Вы действительно желаете сохранить данные?%}'))
    			$('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'fast_save', data: $('#{%oc%}_form').serialize()+"&"+$('#{%oc%}_request_params').serialize(), loader: "global" });
    	}
    	else if ($(this).hasClass("fast_block"))
    	{ 
    		if (confirm('{%lang Вы действительно желаете заблокировать выделенные элементы?%}'))
    			$('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'fast_block', data: $('#{%oc%}_form').serialize()+"&"+$('#{%oc%}_request_params').serialize(), loader: "global" });
    	}
    	else if ($(this).hasClass("fast_unblock"))
    	{
    		if (confirm('{%lang Вы действительно желаете разблокировать выделенные элементы?%}'))
    			$('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'fast_unblock', data: $('#{%oc%}_form').serialize()+"&"+$('#{%oc%}_request_params').serialize(), loader: "global" });
    	}
	
		return false;
	
    });

	/* Parent Checkbox */
    $('#{%oc%} .parent_checkbox').bind('click', function(){ 
		var checkboxes = $(this).parent().parent().parent().parent().parent().find('input[type=checkbox]');
		checkboxes.prop("checked", $(this).is(':checked'));
    });

</script>
