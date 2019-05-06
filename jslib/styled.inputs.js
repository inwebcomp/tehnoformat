$.fn.StyledCheckboxes = function(className, height){

	var newClass = (!className) ? 'checkbox' : className;
	var height = (!height) ? 25 : height;

	var checkbox = this.find('input[type=checkbox]');
	
	for(i=0;i<checkbox.length;i++){
		
		if(checkbox.eq(i).attr('styled') == 'checkbox'){ return false; }
		
		if(checkbox.eq(i).prev().attr('class') !== newClass){ 
			checkbox.eq(i).hide().before('<span class="' + newClass + '"></span>');	
			checkbox.eq(i).attr('styled','checkbox');
		}
		
		if(checkbox.eq(i).attr('checked') == true){
			var BGpos = height * 2;
			checkbox.eq(i).prev().css({ backgroundPosition: '0 -' + BGpos + 'px' });
		}else{
			var BGpos = height * 0;
			checkbox.eq(i).prev().css({ backgroundPosition: '0 -' + BGpos + 'px' });
		}
		
	}
	
	this.find('.' + newClass).hover(function(){

		var checkboxNew = $(this);
		
		var BGpos = 0;
		
		if(checkboxNew.next().attr('disabled') !== true && checkboxNew.attr('class') == newClass){
	
			checkboxNew.mousedown(function(){ 
				var state = $(this).next().attr('checked');
				if(state == true){
					BGpos = height * 3;
				}else{
					BGpos = height;
				}
				$(this).css({ backgroundPosition: '0 -' + BGpos + 'px' });
			});	
			
			checkboxNew.bind('mouseup', function(){
				var state = $(this).next().attr('checked');
				if(state == true){
					BGpos = height * 0;
					$(this).next().removeAttr('checked');
				}else{
					BGpos = height * 2;
					$(this).next().attr('checked', 'true');
				}
				$(this).css({ backgroundPosition: '0 -' + BGpos + 'px' });
			});
			
		}
		
	});
	
}


$.fn.StyledRadiobuttons = function(className, height){

	var newClass = (!className) ? 'radio' : className;
	var height = (!height) ? 25 : height;

	var element = this;
	
	var radio = this.find('input[type=radio]');
	
	for(i=0;i<radio.length;i++){
		
		if(radio.eq(i).attr('styled') == 'radio'){ return false; }
		
		if(radio.eq(i).prev().attr('class') !== newClass){ 
			radio.eq(i).hide().before('<span class="' + newClass + '"></span>');	
			radio.eq(i).attr('styled','radio');
		}
		
		if(radio.eq(i).attr('checked') == true){
			var BGpos = height * 2;
			radio.eq(i).prev().css({ backgroundPosition: '0 -' + BGpos + 'px' });
		}
		
	}
	
	var radioStyled = this.find('input[type=radio]').prev();
	
	this.find('.' + newClass).hover(function(){

		var radioNew = $(this);
		
		var BGpos = 0;
		
		if(radioNew.next().attr('disabled') !== true){
	
			radioNew.mousedown(function(){
				BGpos = height;
				$(this).css({ backgroundPosition: '0 -' + BGpos + 'px' });
			});	
			
			radioNew.bind('mouseup', function(){
				var BGpos = height * 0;
				var BGposE = height * 2;
				radio.parent().find('input[type=radio]').prev().css({ backgroundPosition: '0 -' + BGpos + 'px' });
				$(this).css({ backgroundPosition: '0 -' + BGposE + 'px' }).next().attr('checked','checked');
			});
			
		}
		
	}, function(){

		if($(this).next().attr('disabled') !== true){

			if($(this).next().attr('checked') !== true){
				BGpos = 0;
				$(this).css({ backgroundPosition: '0 -' + BGpos + 'px' });
			}
			
		}
		
	});
	
}