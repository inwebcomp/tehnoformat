<script type="text/javascript">
	$("#{%oc%} #param").bind("change", function(){
		$.Request({ controller: "filters", action: "get_param_name", data: "param=" + $(this).val() + "&category={%category%}", type: "json", complete: function(data){ if(data["param_name"] !== null){ $("#{%oc%} #param_name").val(data["param_name"]).attr("readonly","true"); } } });
		
		$("#val").Request({ controller: "filters", action: "get_val_list", data: "param=" + $(this).val() + "&category={%category%}", complete: function(){ $("#val").delay(300).fadeOut(100).fadeIn(100); $("#{%oc%} #title").val($("#{%oc%} #val").val()); $("#{%oc%} #val").bind("change", function(){	$("#{%oc%} #title").val($(this).val()); }); } });
	
		$("#{%oc%} #param_name").val($("#{%oc%} #param option[value="+$(this).val()+"]").text().replace(/\s\([a-zA-Z_0-9]+?\)$/, ""));
	});

	var Verifity = function(){
		if($("#{%oc%} #type").val() == "checkbox" || $("#type_{%oc%}").val() == "select"){
			
			$(".interval_value").hide(); $(".interval_value").prev().hide();
			$("#val_list").show(); $("#val_list").prev().show();
			
		}else if($("#{%oc%} #type").val() == "interval"){
			
			$(".interval_value").show(); $(".interval_value").prev().show();
			$("#val_list").hide(); $("#val_list").prev().hide();
			
		}
	};
	
	Verifity();
	
	$("#{%oc%} #type").bind("change", function(){
		Verifity();
	});
</script>