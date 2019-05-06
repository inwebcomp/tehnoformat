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
			},
			start: function(e, ui){
				$("#{%oc%} .sortable .ui-sortable-placeholder").children().each(function(i, element){
					$(this).height(ui.helper.find("th:eq("+i+")").height());
					if(ui.helper.find("th:eq("+i+")").hasClass('pos')) $(this).hide();
				});
			}
		});
		$("#{%oc%} .sortable").disableSelection();
	}

	
	/* Actions */
	$('#{%oc%} .cms_action').bind('click', function() {

        if ($(this).attr('rel') == "fast_delete")
        { 
        	if (confirm('{%lang Вы действительно желаете удалить выделенные элементы?%}'))
    			return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'fast_delete', data: $('#cms_actions_form_{%oc%}').serialize()+"&"+$('#{%oc%}_request_params').serialize() });
    	}
    	else if ($(this).attr('rel') == "fast_save")
    	{
    		if (confirm('{%lang Вы действительно желаете сохранить данные?%}'))
    			return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'fast_save', data: $('#cms_actions_form_{%oc%}').serialize()+"&"+$('#{%oc%}_request_params').serialize() });
    	}
    	else if ($(this).attr('rel') == "fast_block")
    	{ 
    		if (confirm('{%lang Вы действительно желаете заблокировать выделенные элементы?%}'))
    			return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'fast_block', data: $('#cms_actions_form_{%oc%}').serialize()+"&"+$('#{%oc%}_request_params').serialize() });
    	}
    	else if ($(this).attr('rel') == "fast_unblock")
    	{
    		if (confirm('{%lang Вы действительно желаете разблокировать выделенные элементы?%}'))
    			return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'fast_unblock', data: $('#cms_actions_form_{%oc%}').serialize()+"&"+$('#{%oc%}_request_params').serialize() });
    	}

    });
	
	
	/* Pagination */
	$('#{%oc%} .cms_pagination li:not(.disabled,.active) a').bind('click', function() {
		return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: '{%actionName%}', data: 'params[page_is]=' + $(this).attr('rel') + $('#{%oc%} .actions_values').serialize(), loader: "global" });
	});
	
	
	/* Select Actions */
	$('#{%oc%} .actions_values select').bind('change', function(){
		if($('#{%oc%} .actions_values').attr("rel") !== undefined){
			var av_action = $('#{%oc%} .actions_values').attr("rel");
		}else{
			var av_action = "{%actionName%}";
		}
	
    	return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: av_action, data: 'params[page_is]=1&' + $('#{%oc%} .actions_values').serialize() }); 
    });
	
	/* Search */
	$('#{%oc%} .actions_values .search').bind('blur', function(){
    	return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: '{%actionName%}', data: 'params[page_is]=1&' + $('.actions_values').serialize() }); 
    }).bind('keypress', function(e){
		if(e.keyCode == 13){
    		return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: '{%actionName%}', data: 'params[page_is]=1&' + $('.actions_values').serialize() }); 
		}
    });
	
	/* Parent Checkbox */
    $('#{%oc%} .parent_checkbox').bind('click', function(){ 
		var checkboxes = $(this).parent().parent().parent().parent().parent().find('.checkbox_field:not(.free) input[type=checkbox]');
		checkboxes.prop("checked", $(this).is(':checked'));
    });	

	/* Tree select */
	$('#{%oc%} .table .cms_parent:not(.last_level)').bind('click', function(event){
		var self = $(this).parent();
		
		if(event.target.tagName !== "A"){
			
			$('#{%oc%}').Request({ controller: '{%controllerName%}', action: '{%actionName%}', data: 'params[page_is]={%select.page_is%}&' + $(".actions_values").serialize() + '&params[where][parent_ID]=' + self.attr('rel')/*, complete: function(){ window.history.pushState(window.history.state+1, "Parent", {%root%}/backend/ru/index/link/{%controllerName%}/{%actionName%}/"+self.attr('rel')); }*/ });
			
			return false;
		}
    });
	
	/* Parent category filter select */
	$('#{%oc%} .cms_path .cms_parent:not(.last_level)').bind('click', function(){
		var self = $(this);
		
		$('#{%oc%}').Request({ controller: '{%controllerName%}', action: '{%actionName%}', data: 'params[page_is]={%select.page_is%}&' + $(".actions_values").serialize() + '&params[where][parent_ID]=' + self.attr('rel') });

		return false;
    });
	
	/* Category filter select */
	$('#{%oc%} .cms_path .cms_category:not(.last_level)').bind('click', function(){
		var self = $(this);
		if(self.attr('rel') == "1"){
			self.attr('rel', '_NULL');
		}
		$('#{%oc%}').Request({ controller: '{%controllerName%}', action: '{%actionName%}', data: 'params[page_is]={%select.page_is%}&' + $(".actions_values").serialize() + '&params[where][category_ID]=' + self.attr('rel') });
				
		return false;
    });
	
	/* Checkbox Check */ 
	$('#{%oc%} .checkbox_field.free input[type=checkbox]').bind('change', function(){

		var l = $(this).parent().find(':checked').length;
		if(l !== 1){
			var val = "NULL";	
		}else{
			var val = 1;
		}
				
		return $(this).parent().find('input').val(val);
    });
	
	/* Radio Select */
    $("#{%oc%} tr .radio input[type=radio]").bind("change", function(){
		$("#{%oc%} tr .radio input[type=radio]").prop("checked", false);
		$(this).prop("checked", true);
		
		$("#{%oc%} tr .radio input[type=radio]").parent().find("input").val("NULL");
		$(this).parent().find('input').val(1);
    });
	
	/* Export */
	$('#{%oc%} .cms_action.export').bind('click', function() {

        return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'export', data: $('#cms_actions_form_{%oc%}').serialize()+"&"+$('#{%oc%}_request_params').serialize(), complete: function(responce){ if(responce.href !== ""){ window.location.href = responce.href; } } });

    });
	
	/* Export */
	$('#{%oc%} .cms_action.export2').bind('click', function() {

        return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'export2', data: $('#cms_actions_form_{%oc%}').serialize()+"&"+$('#{%oc%}_request_params').serialize(), complete: function(responce){ if(responce.href !== ""){ window.location.href = responce.href; } } });

    });
	
	/* Fast Edit */
	var fastEditFields = $("#{%oc%} tbody [fast-edit]:not(.checkbox_field)");
	if(fastEditFields.length > 0){
		
		var showField = function(r, field){
			$(field).find(".focus-input").focus();
			$(field).parent().addClass("opened");
			initShow();
			initEdit();
		}
		
		var hideField = function(r, field){
			$(field).parent().removeClass("opened");
			initShow();
			initEdit();
		}
		
		fastEditFields.parent().each(function(i, element){
			if(parseInt($(element).attr("rel")) > 0){
				$(element).addClass("fast-edit");
			}
		});

		fastEditFields.each(function(i, element){
			var tHtml = $(element).html();
			var objectID = (parseInt($(this).parent().attr("rel")) > 0) ? parseInt($(this).parent().attr("rel")) : false;
			$(element).html("<div>"+tHtml+"</div>").find("> div").attr("id", "fast-edit-"+objectID+"-"+$(element).attr("fast-edit"));
		});
		
		var initShow = function(){
			var fastEditFields = $("#{%oc%} tbody [fast-edit]:not(.checkbox_field)");
			fastEditFields.unbind().bind("click", function(){ 
				if($(this).hasClass("opened") == false){
					var objectID = (parseInt($(this).parent().attr("rel")) > 0) ? parseInt($(this).parent().attr("rel")) : false;
					var field = $(this).attr("fast-edit"); 
					if(objectID){
						var fastField = "#fast-edit-"+objectID+"-"+field;
						$(fastField).Request({ controller: "{%controllerName%}", action: "fast_edit_open", data: "object="+objectID+"&field="+field, complete: function(r){ showField(r, fastField); } });
					}
				}
			});
		}
		
		var initEdit = function(){
			var fastEditFields = $("#{%oc%} tbody [fast-edit].opened:not(.checkbox_field) .focus-input");
			fastEditFields.unbind().bind("blur", function(){ 
				var td = $(this).parent().parent();
				var objectID = (parseInt(td.parent().attr("rel")) > 0) ? parseInt(td.parent().attr("rel")) : false;
				var field = td.attr("fast-edit");
				var value = td.find(".focus-input").val();
				if(objectID){
					var fastField = "#fast-edit-"+objectID+"-"+field;
					$(fastField).Request({ controller: "{%controllerName%}", action: "fast_edit_save", data: "object="+objectID+"&field="+field+"&value="+value, complete: function(r){ hideField(r,  fastField); } });
				}
			});
		}
		
		initShow();
		initEdit();
		
	}
	
	
	var fastEditFields = $("#{%oc%} tbody [fast-edit].checkbox_field");
	if(fastEditFields.length > 0){
		
		var hideFieldCB = function(r, field){
			initEditCB();
			if(r.fieldname == "block" && $(field).parent().parent().hasClass("without_highlights") == false){
				//$(field).parent().parent().toggleClass("warning");	
			}
		}
		
		fastEditFields.parent().each(function(i, element){
			if(parseInt($(element).attr("rel")) > 0 && $(element).hasClass("fast-edit") == false){
				$(element).addClass("fast-edit");
			}
		});
		
		fastEditFields.each(function(i, element){
			var tHtml = $(element).html();
			var objectID = (parseInt($(this).parent().attr("rel")) > 0) ? parseInt($(this).parent().attr("rel")) : false;
			$(element).html("<div>"+tHtml+"</div>").find("> div").attr("id", "fast-edit-"+objectID+"-"+$(element).attr("fast-edit"));
		});
		
		var initEditCB = function(){
			var fastEditFields = $("#{%oc%} tbody [fast-edit].checkbox_field input");
			fastEditFields.unbind().bind("change", function(){ 
				var td = $(this).parent().parent().parent();
				var objectID = (parseInt(td.parent().attr("rel")) > 0) ? parseInt(td.parent().attr("rel")) : false;
				var field = td.attr("fast-edit");
				var value = td.find(".focus-input").prop("checked");
				if(objectID){
					var fastField = "#fast-edit-"+objectID+"-"+field;
					$(fastField).Request({ controller: "{%controllerName%}", action: "fast_edit_save", data: "object="+objectID+"&field="+field+"&value="+value, complete: function(r){ hideFieldCB(r,  fastField); } });
				}
			});
		}
	
		initEditCB();
	}
</script>
