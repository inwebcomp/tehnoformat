(function(jQuery) {

	jQuery.fn.reportprogress = function(val) {
		var max=100;

		return this.each(
			function(){

				var div=jQuery(this);
				var innerdiv=div.find(".progressbar");

				if(innerdiv.length!=1){
					innerdiv=jQuery("<div class='progressbar'></div>");
					div.append("<div class='text'>&nbsp;</div>");
					jQuery("<span class='text'>&nbsp;</span>").css("width",div.width()).appendTo(innerdiv);
					div.append(innerdiv);
				}
				var width=Math.round(val/max*100);
				innerdiv.css("width",width+"%");
				div.find(".text").html(width+" %");

			}
		);
	};

	jQuery.flashMovie = function(movieName)
	{
		return (jQuery.browser.msie) ? window[movieName] : document[movieName];
	}

	jQuery.uploader = new Object();
	jQuery.uploader.instance = 0;
	jQuery.uploader.nextInstance = function()
    { return ++ jQuery.uploader.instance; }

	jQuery.fn.uploader = function(options)
	{
		var defaults = {
			ID: '1',
			type: '*.jpg; *.jpeg; *.gif; *.png; *.swf',
		    url: '/scripts/upload.php',
		    infoUrl: '/scripts/fileinfo.php',
		    uplPath: '/flash/uploader.swf',
		    eiPath: '/flash/expressInstall.swf',
		    complete: null,
		    hash: null
		};
  		var opts = jQuery.extend(defaults, options);

        return this.each(
			function()
			{
				var index = jQuery.uploader.nextInstance();
        		var name = jQuery(this).attr('name');
				var ID = jQuery.uploader.nextInstance();

       			if (opts['hash'] != null)
       				index = 'i' + index;
       			else
       				index = 'n' + index;

       			jQuery(this).attr('id', 'upl_' + index)
       				.after(
       					jQuery('<div></div>')
       						.attr('id', 'fileinfo_' + index)
       						.attr('class', 'tcmf_uploader_fileinfo')
       						.append(jQuery('<div style="display: none" class="tcmf_uploader_status" id="uplForm_' + index + '"><div><strong>Имя файла: </strong><span id="uplName_' + index + '"></span></div><div><strong>Отправлено: </strong><span id="uplSent_' + index + '">неизвестно</span></div><div><strong>Скорость: </strong><span id="uplSpeed_' + index + '">неизвестно</span></div><div><strong>Время до завершения: </strong><span id="uplTime_' + index + '">неизвестно</span></div><div id="uplProgress_' + index + '" class="progress"></div><div class="upl_actions"><a href="javascript:void(0)" class="tcmf_uploader_button_remove" id="uplCancel_' + index + '">Отмена</a> <a href="javascript:void(0)" class="tcmf_uploader_button_upload" id="uplUpload_' + index + '">Загрузить</a></div></div>'))
       					);

       			jQuery('#fileinfo_' + index).data('params', { name: name, complete: opts['complete'] });


        		var flashvars = { index: index, ID: opts['ID'], url: opts['url'], infoUrl: opts['infoUrl'], type: opts['type'] };
				var attributes = { id: 'upl_' + index, name: 'upl_' + index };
        		var params = { allowScriptAccess: 'always', wmode: 'opaque' };


				swfobject.embedSWF(opts['uplPath'], 'upl_' + index, "72", "23", "9.0.0", opts['eiPath'],  flashvars, params, attributes);

        		if (opts['hash'] != null)
        		{
        			jQuery.uploader.CompleteHash(index, opts['hash'], opts['infoUrl']);
				}
			}
		);
	};

	jQuery.uploader.loaded = function(index)
	{
		//Fix for IE
		if (jQuery.browser.msie)
			window['upl_' + index] = document.getElementById('upl_' + index);
		////////////

		if (index.charAt(0) == 'i')
			jQuery('#upl_' + index).css('width', '0px').css('height', '0px');
    }

	jQuery.uploader.upload = function(index)
	{ jQuery.flashMovie('upl_' + index).upload(); };

	jQuery.uploader.hide = function(index)
	{
		jQuery('#upl_' + index).css('width', '0px').css('height', '0px');
		jQuery('#uplUpload_' + index).show();
		jQuery('#uplForm_' + index).show();
	}

	jQuery.uploader.show = function(index)
	{
		jQuery('#upl_' + index).css('width', '72px').css('height', '23px');
		jQuery('#uplForm_' + index).hide();
	}

	jQuery.uploader.remove = function(index)
	{
		jQuery.uploader.show(index);
		jQuery.flashMovie('upl_' + index).remove();

	};

	jQuery.uploader.onProgress = function(index, name, bytesLoaded, bytesTotal, elapsedSeconds)
	{
		var procent = parseInt(bytesLoaded / bytesTotal * 100);
		var speed = parseInt((bytesLoaded / elapsedSeconds) / 1000);
		var sec = (!speed) ? 0 : parseInt(((bytesTotal - bytesLoaded) / (speed * 1000)));
		speed += ' KB/s';

		var tm = parseInt(sec / 60) + ' min ' + parseInt(sec % 60) + ' sec';

		jQuery('#uplName_' + index).html(name);
		jQuery('#uplSent_' + index).html(parseInt(bytesLoaded / 1000) + '/' + parseInt(bytesTotal / 1000) + ' KB (' + procent + '%)');
		jQuery('#uplSpeed_' + index).html(speed);
		jQuery('#uplTime_' + index).html(tm);
		jQuery("#uplProgress_" + index).reportprogress(procent);
	};

	jQuery.uploader.onError = function(index, name, size, error)
	{ alert(error); };

	jQuery.uploader.onSelect = function(index, name, size)
	{
        jQuery('#uplName_' + index).html(name);
        jQuery('#uplSent_' + index).html('0/' + parseInt(size / 1000) + ' KB (0%)');
        jQuery('#uplSpeed_' + index).html('неизвестно');
        jQuery('#uplTime_' + index).html('неизвестно');
        jQuery('#uplProgress_' + index).reportprogress(0);

        jQuery('#uplUpload_' + index).bind('click', { 'index': index }, function(event) { jQuery(this).hide(); jQuery.uploader.upload(event.data.index); });
        jQuery('#uplCancel_' + index).bind('click', { 'index': index }, function(event) { jQuery.uploader.remove(event.data.index); });

		jQuery.uploader.hide(index);
	};


	jQuery.uploader.CompleteHash = function(index, hash, infoUrl)
	{
		jQuery.getJSON(infoUrl,
  				{ "hash": hash, "index": index },
  				function(json)
  				{
					
					jQuery('#fileinfo_' + json.index)
					.append(jQuery('<input name="' + jQuery('#fileinfo_' + index).data('params').name + '" type="hidden" value="' + json['md5'] + '" /> <span class="tcmf_uploader_done" id="upl_done_' + index + '">' + json.name + '</span>'))
					.append(
						jQuery('<a></a>')
							.text('[X]')
							.attr('href', 'javascript:void(0)')
							.attr('class', 'tcmf_uploader_button_remove')
							.bind('click', { 'index': index }, function(event) { jQuery(this).remove(); jQuery('#upl_done_' + index).remove(); jQuery.uploader.remove(event.data.index); })
					);


					jQuery('#uplForm_' + index).hide();
					if (jQuery('#fileinfo_' + index).data('params').complete)
						jQuery('#fileinfo_' + index).data('params').complete(json.md5);
				}
		);
	};


	jQuery.uploader.onComplete = function(index, name, size, infoUrl)
	{
		jQuery.getJSON(infoUrl,
  				{ "name": name, "size": size, "index": index },
  				function(json)
  				{ 
					$('input[name=params(banner)]').val(name);
					$('input[name=params(banner)]').attr('second_type', 'file');
					
					jQuery('#fileinfo_' + json.index)
					.append(jQuery('<input id="upl_hidden_input_' + index + '" name="' + jQuery('#fileinfo_' + index).data('params').name + '" type="hidden" value="' + json['md5'] + '" /> <span class="tcmf_uploader_done" id="upl_done_' + index + '">' + json.name + '</span>'))
					.append(
						jQuery('<a></a>')
							.text('[X]')
							.attr('href', 'javascript:void(0)')
							.attr('class', 'tcmf_uploader_button_remove')
							.bind('click', { 'index': index }, function(event) { jQuery(this).remove(); jQuery('#upl_hidden_input_' + index + ', #upl_done_' + index).remove(); jQuery.uploader.remove(event.data.index); })
					);
					jQuery('#uplForm_' + index).hide();
					if (jQuery('#fileinfo_' + index).data('params').complete)
						jQuery('#fileinfo_' + index).data('params').complete(json.md5);
				}
		);
	};


})(jQuery);

