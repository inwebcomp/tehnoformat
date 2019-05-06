<script type="text/javascript">
	$("#{%oc%} #param").bind("change", function(){
		$("#{%oc%} #name").val($("#{%oc%} #param option[value='"+$(this).val()+"']").text().replace(/\s\([\s\S]+?\)$/, ""));
		$("#{%oc%} #urlid").val($("#{%oc%} #param").val().replace(/^[\s\S]+\./, ""));
		$("#{%oc%} #type").show();
		if($(this).val() == "price"){
			$("#{%oc%} #type").hide();
			$("#{%oc%} #type select").val("slider");
		}else{	
			{%if type == "slider"%}
				$("#{%oc%} #type select").val("list");	
			{%else%}
				$("#{%oc%} #type select").val("{%type%}");	
			{%endif%}
		}
	});
	
	if("{%param%}" == "price"){
		$("#{%oc%} #type").hide();	
	}
	
	{%ifset ID%}
		/* Checking item ID */ 
		var urlParams = window.location.href.split('/');
		if(urlParams[9] == "0"){
			urlParams[9] = "{%ID%}";
			var src = urlParams.join('/');
			history.replaceState(3, "Title 2", src);
		}
	{%endif%}
</script>