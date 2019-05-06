    	<script type="text/javascript">
			var host = "{%host%}";
	        var page = "{%page%}";
	        var language = "{%language_name%}";
	        var section = "{%section%}";
	        var noLangRequest = "{%noLangRequest%}";
			var cmf_table_open_interval = 400;
			var cmf_tree_open_interval = 200;
			var csrf = "{%csrf%}";

			$(document).ready(function()
			{
				$(document).SetControls();
			});
			
			new WOW({ offset: 0, mobile: false }).init();
		</script>
</body>
</html>