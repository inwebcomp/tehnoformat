$.fn.loaderBackend = function(){
	var loader = $('<div class="cms_loader animated"><div class="loader"></div></div>');
	$("#header").append(loader);
	$(".cms_loader").show();
}


$.fn.loaderFancybox = function(){
	var loader = $('<div class="loader animated"></div>');
	$("#fancybox_content").append(loader);
	$("#fancybox_content .loader").show();
}
$.fn.loaderFrontend = function(){
	var loader = $('<div class="loader"><div class="object"></div></div>');
	$("body").append(loader);
	$(".loader").show();
}
$.fn.loaderCatalog = function(){
	if($('.loader').length == 0){
		var loader = $('<div class="loader content-loader"><div class="object"></div></div>');
		$("#content").append(loader);
		$(".content-loader").show();
	}
}
	
var ajax = {};

jQuery.fn.SetControls = function()
{            

	$(this).find('.ajax-form-request').bind('submit', function()
	{    
		
		if($(this).hasClass('ajax-form-sending'))
			return false;

		var form = $(this);

		$(this).addClass('ajax-form-sending');
		
		var rel = 'admin-content';
		if($(this).attr('rel')) rel = $(this).attr('rel');
		var dt = $(this).attr('action').split('/');
		
		var object = '';
		if(dt[2] !== ''){
			object = 'object=' + dt[2] + '&';    
		}
		if(dt[3] !== ''){
			object += 'object2=' + dt[3] + '&';    
		}

		
		if(!$(this).attr('dont-save-data') && section == "backend"){
			$(this).attr("target", "save_data");
			$(this).attr("action", "/scripts/empty.txt");
			$(this).attr("method", "post");
		}

		
		if($(this).attr('data-loader')){
			var loader = $(this).attr('data-loader');
		}else{
			var loader = "global";	
		}
		
		var goback = $(this.submit_and_back).val();

		$('#' + rel).Request({ controller: dt[0], action: dt[1], data: object + $(this).serialize(), loader: loader, complete: function(r){ form.removeClass('ajax-form-sending'); if(goback == 1){ if($.trim(r.backhref).length > 10){ window.location.href = r.backhref; } } } });

		if($(this).attr('target') == "save_data"){
			return true;
		}else{
			return false;	
		}
	});

	$(this).find('.ajax-request').bind('click', function()
	{ 
		var rel = 'admin-content';
		if ($(this).attr('rel')) rel = $(this).attr('rel');
		var action = $(this).attr('action');
		var dt = action.split('/');
		var button = $(this);
		
		if(dt[2] !== ""){
			var object = "object=" + dt[2];    
		}
		
		if(button.hasClass("fancybox") || button.attr('type') == "fancybox"){
			rel = "fancybox_content";
			//$.fancybox.showActivity();
		}
		
		if($(this).attr('data-loader')){
			var loader = $(this).attr('data-loader');
		}else{
			var loader = "global";	
		}
	
		return $('#' + rel).Request({ controller: dt[0], action: dt[1], data: object, loader: loader, complete: function(data){ 

			if(button.hasClass("fancybox") || button.attr('type') == "fancybox"){
				$("#fancybox-open").click();    
			}
				
		}});
	});
	

	jQuery(this).find('.filter-int').bind('keyup', function()
	{
		FilterInt(this);
	});

	jQuery(this).find('.filter-num').bind('keyup', function()
	{
		FilterNum(this);
	});

	if(section == "backend"){
		// $(this).find('.edit').mousemove(function(){
		// 	if($(this).find('#ckeditor').attr('name') != null) { 
		// 		var text1 = CKEDITOR.instances['ckeditor'].getData();
		// 		$(this).find('#ckeditor').html(text1);
		// 	}
		// 	if($(this).find('#ckeditor2').attr('name') != null) { 
		// 		var text1 = CKEDITOR.instances['ckeditor2'].getData();
		// 		$(this).find('#ckeditor2').html(text1);
		// 	}
		// 	if($(this).find('#ckeditor3').attr('name') != null) { 
		// 		var text1 = CKEDITOR.instances['ckeditor3'].getData();
		// 		$(this).find('#ckeditor3').html(text1);
		// 	}
		// });   
	
		// if($(this).find('#ckeditor').length > 0){ var editor1 = CKEDITOR.replace('ckeditor', { allowedContent: true }); CKFinder.setupCKEditor( editor1, '/ckfinder/' ); }
		// if($(this).find('#ckeditor2').length > 0){ var editor2 = CKEDITOR.replace('ckeditor2',{ allowedContent: true }); CKFinder.setupCKEditor( editor2, '/ckfinder/' ); }
		// if($(this).find('#ckeditor3').length > 0){ var editor3 = CKEDITOR.replace('ckeditor3',{ allowedContent: true }); CKFinder.setupCKEditor( editor3, '/ckfinder/' ); }


		
		$(this).find('.text-editor').froalaEditor({
			language: 'ru',
			imageUploadURL: '/scripts/upload_editor_image.php',
			imageAllowedTypes: ['jpeg', 'jpg', 'png', 'svg'],
			fileUpload: false,
			videoUpload: false,
            pastePlain: true
        }).on('froalaEditor.image.error', function (e, editor, error, response) {
			if (response !== undefined)
				console.log(response);
		});
	}
	
	
	if(section == "frontend"){
		/*$(this).find(".lightbox").fancybox({
			'centerOnScroll' : true,
			'overlayShow'	: true,
			'transitionIn'	: 'fade',
			'transitionOut'	: 'fade',
			'autoDimensions' : true,
			'autoCenter' : true,
			'type' :"iframe",
			'iframe' :{
				scrolling : 'auto',
				preload   : true
			},
			padding: 0
		});
		
		$(this).find(".fancybox").fancybox({
			'padding' : 0,
			'centerOnScroll' : true,
			'overlayShow'	: true,
			'transitionIn'	: 'fade',
			'transitionOut'	: 'fade',
			'autoDimensions' : true,
			'autoCenter' : true,
			'iframe' :{
				scrolling : 'auto',
				preload   : true
			}
		});*/

		$(this).find(".fancybox").fancybox({
			// Space around image, ignored if zoomed-in or viewport smaller than 800px
			margin : [0, 0],

			// What buttons should appear in the toolbar
			slideShow  : false,
			fullScreen : false,
			thumbs     : false,
			opacity : true,
			touch : false,
			beforeShow: function(){
				$("body").css({'overflow-y':'hidden'});
			},
			afterClose: function(){
				$("body").css({'overflow-y':'visible'});
			}
		});
		$(this).find(".lightbox").fancybox({
			// Space around image, ignored if zoomed-in or viewport smaller than 800px
			margin : [0, 0],

			slideShow  : false,
			fullScreen : false,
			thumbs     : false,
			opacity : true,
			touch : true,
			arrows: true,
			infobar : true,
			toolbar : true,
			buttons : [
				'close'
			],
			loop: true
		});
		
		//$(this).preloadImages();
	}else{
		
		$(this).find(".alert:not(.inline) .close").bind("click", function(){ 
			$(".alert:not(.inline)").disappearAtOnce();
		});	
			
	}

}

jQuery.fn.SetControls.ajaxProcesses = 0;

$(function(){
 
    window.areas = new Array();

    var standartEffect = 'none';
    var standartEffectSpeed = 'fast';
        

    jQuery.fn.Request = function(options)
    {  
        var defaults = {
            controller: '',
            action: 'index',
            loader: 'global',
            lang: language,
            complete: null,
            type: 'ajax',
            section: section,
            data: ''
          }; 
        
          var opts = jQuery.extend(defaults, options);

        if (this.length == 0)
        {
            console.log('Элемент не найден, запрос не возможен.');
            return false;
        }
        if (this.length > 1)
        {
            console.log('В выборке больше 1 элемента, запрос не возможен.');
            return false;
        }

        opts['oc'] = this.attr('id');

        if (opts['section'] == 'backend')
        {
			if(opts.loader == "global"){
				this.loaderBackend();   
			}
			return Request(opts);
        }
        else{ 
			if(opts.loader == "fancybox"){
				this.loaderFancybox();   
			}else if(opts.loader == "global"){
				this.loaderFrontend();   
			}else if(opts.loader == "catalog"){
				this.loaderCatalog();   
			}
            return Request(opts);
        }
    }

    jQuery.Request = function(options)
    {  
        var defaults = {
            controller: '',
            action: 'index',
            loader: 'global',
            lang: language,
            complete: null,
            type: 'ajax',
            section: section,
            data: ''
          };

          var opts = jQuery.extend(defaults, options);

        if (opts['type'] == 'json')
            opts['oc'] = 'json';

        if (opts['section'] == 'backend')
        {
			if(opts.loader == "global"){
				$("body").loaderBackend();   
			} 
			return Request(opts);
        }
        else{ 
			if(opts.loader == "fancybox"){
				$("body").loaderFancybox();   
			}else if(opts.loader == "frontend"){
				$("body").loaderFrontend();   
			}else if(opts.loader == "catalog"){
				$("body").loaderCatalog();   
			}
            return Request(opts);
        }
    }

    Request = function(opts)
    {  
        jQuery('#' + opts['oc']).data('callbacks', { complete: opts['complete'] });
        jQuery('#' + opts['oc']).data('loader', { type: opts['loader'] });
        jQuery('#' + opts['oc']).data('effect', { type: opts['effect'], speed: opts['effectSpeed'] });

        opts['data'] += '&oc=' + opts['oc'];
        opts['data'] += '&csrf-token=' + csrf;

		jQuery.fn.SetControls.ajaxProcesses++;

        if (opts['url'])
        {
            var dt = opts['url'].split('/');
            opts['controller'] = dt[0];
            opts['action'] = dt[1];
        }
		if(ajax[opts["oc"]] && ajax[opts["oc"]].readystate != 4){ ajax[opts["oc"]].abort(); }
		ajax[opts["oc"]] = jQuery.ajax({
			type: "POST",
			url: '/' + opts['type'] + '/' + opts['section'] + '/' + opts['lang'] + '/' + opts['controller'] + '/' + opts['action'],
			data: opts['data'],
			success: jQuery.RequestComplete
		});

        return false;
    }

    jQuery.RequestComplete = function(responce)
    {
        jQuery.fn.SetControls.ajaxProcesses--;

        var jsonObj = new Array();
        try
        {
            jsonObj = eval('(' + responce + ')'); 
        }
        catch(ex)
        {
			console.clear();
			console.log(responce);
            jsonObj = new Array();
            jsonObj['oc'] = 'admin-content';
            jsonObj['content'] = '<div id="' + jsonObj['oc'] + '">' + responce + '</div>';
        }
        try
        { 
                var block = '#' + jsonObj['oc'];
                var complete = jQuery(block).data('callbacks').complete;
                var loader = jQuery(block).data('loader').type;

                if (jsonObj['oc'] != 'json')
                {
                    var admin = (jsonObj['oc'] == 'admin-content') ? 1 : 0;

                    if (admin)
                        $('#admin-side').show();

                  	if(block == "#fancybox_content"){ 
                        $(block).html(jsonObj['content']);
                    }else{
						$(block).replaceWith(jsonObj['content']);
                    }

                    if (jsonObj['scripts'] !== undefined)
                        eval(jsonObj['scripts']);

					if($(".alert:not(.inline)").length > 0 && parseInt($('.alert:not(.inline)').css("opacity")) == 0)
						$('.alert:not(.inline)').appear();	
					 
                    jQuery(block).SetControls();
                }
				
				if(loader !== false){
					if($('.loader').length > 0){
						$('.loader').replaceWith(" ");
					}
				}
				
				if($('.cms_loader').length > 0)
					$('.cms_loader').replaceWith(" ");

                if (complete)
                { 
                    (complete)(jsonObj);
                }
        }
        catch (ex) { }
        
    }

    var FilterNum = function(inputElement)
    {
        inputElement.value = inputElement.value.replace(/[^\d\.\,]+/, '');
        inputElement.value = inputElement.value.replace(/,|[\,\.]{2,}/, '.');
        inputElement.value = inputElement.value.replace(/^[\.\,]/, '');
    }

    var FilterInt = function(inputElement)
    {
        inputElement.value = inputElement.value.replace(/[^\d]+/, '');
    }

    var FilterLiteralString = function(inputElement)
    {
        inputElement.value = inputElement.value.toLowerCase();
        inputElement.value = inputElement.value.replace(/[^a-z0-9_\-\.\@]+/, '');
    }

    jQuery.fn.center = function()
    {
        var w = jQuery(window);
        this.css("position", "absolute");
        this.css("top", (w.height() - this.height()) / 2 + w.scrollTop() + "px");
        this.css("left", (w.width() - this.width()) / 2 + w.scrollLeft() + "px");

        return this;
    }

});