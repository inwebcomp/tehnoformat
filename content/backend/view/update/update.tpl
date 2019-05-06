<form id="update-content" class="form-horizontal">
   
	<div class="progress">
    	<div step="{%next_step%}" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="{%progress%}" aria-valuemin="0" aria-valuemax="100" style="width:{%progress%}%">
            <span class="sr-only"></span>
        </div>
    </div>
    
    <ul class="list-unstyled">
    	{%list actions%}
    		<li step="{%._index%}"><span class="text-primary-muted text">{%.value%} <span class="glyphicon" aria-hidden="true"></span></span></li>
        {%end%}
    </ul>
    
    <div class="result-block">
    	<hr class="separator" />
    </div>
    
    <script type="text/javascript">
		var nextAction = $("#update-content ul li[step=1]");
		nextAction.find(".text").removeClass("text-mute").addClass("text-primary");
		nextAction.find(".glyphicon").addClass("glyphicon-{%icon_process%}");
	
		var DoUpdateRequest = function(step){
       		$.Request({ type: 'json', controller: 'update', action: 'update', loader: 'none', data: "step="+step, complete: function(r){ if(r.link){ window.location.href = r.link; } UpdateStep(r); } });
		}
		
		DoUpdateRequest("{%next_step%}");
		
		var CalculateResult = function(){
       		var result = "success";
			var actionsBlock = $("#update-content ul");
			var okActions = actionsBlock.find(".text-success");
			var warningActions = actionsBlock.find(".text-warning");
			var errorActions = actionsBlock.find(".text-danger");
			
			if(warningActions.length > 0)
				result = "warning";
			if(errorActions.length > 0)
				result = "danger";
				
			if(result == "success"){
				var text = "{%lang Обновление прошло успешно%}";
				var icon = "ok";
			}else if(result == "warning"){
				var text = "{%lang Обновление прошло с незначительными ошибками. Обратитесь к автору обновления%}";
				var icon = "warning-sign";
			}else if(result == "danger"){
				var text = "{%lang Произошла ошибка при обновлении. Обратитесь к автору обновления%}";
				var icon = "remove";
			}
			
			$("#update-content .result-block").append('<span class="text-'+result+' result">'+text+' <span class="glyphicon glyphicon-'+icon+'" aria-hidden="true"></span></span>');
			$("#update-content .result-block").show();
		}
		
		var UpdateStep = function(r){
			var progressBar = $("#update-content .progress");
			var actions = $("#update-content ul");
			var progressLine = progressBar.find("[step="+r.step+"]");
			var action = actions.find("li[step="+r.step+"]");
			var nextAction = actions.find("li[step="+r.next_step+"]");
			
			progressLine.addClass("progress-bar-"+r.state).removeClass("progress-bar-striped");
			action.find(".text").removeClass("text-primary").addClass("text-"+r.state);
			action.find(".glyphicon").removeClass("glyphicon-{%icon_process%}").addClass("glyphicon-"+r.icon);
			if(r.mess){
				action.find(".text").after("&nbsp; <small>"+r.mess+"</small>");
			}
			
			if(r.finish !== 1 && r.err !== 1){
				nextAction.find(".text").removeClass("text-mute").addClass("text-primary");
				nextAction.find(".glyphicon").addClass("glyphicon-{%icon_process%}");
				
				var newProgressLine = '<div step="'+r.next_step+'" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="'+r.progress+'" aria-valuemin="0" aria-valuemax="100" style="width:'+r.progress+'%"><span class="sr-only"></span></div>';
				
				//var newAction = '<li step="'+r.next_step+'"><span class="text-primary text">'+r.action+' <span class="glyphicon glyphicon-{%icon_process%}" aria-hidden="true"></span></span></li>';
				
				progressBar.append(newProgressLine);
				//actions.append(newAction);
				
				DoUpdateRequest(r.next_step);
			}else{
				CalculateResult();
			}
		}
    </script>
</form> 