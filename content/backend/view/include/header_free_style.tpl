<div class="cms_main{%if menu_state == "1"%} notFullWidth{%endif%}" id="admin-content">
	<div class="cms_left"> 	
        {%controller admmenu/left admmenu_left%}
	</div>
    
    {%controller admmenu/top admmenu_top%}
    
    <div class="cms_content{%if actionName == "edit"%} edit_page{%endif%}">
    