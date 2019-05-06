<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Базовая информация%}{%ifset .ID%} <small>{%lang ID%}: {%.ID%}</small>{%endif%}</div>
        </h3>
    </div>
    
	<div class="panel-body">

		<form role="form" rel="{%oc%}" class="form-horizontal">
 
 			<div class="form-group">
                <label class="control-label col-md-2" for="from">{%lang От кого%}:</label>
                <div class="col-md-6">
                	<span class="help-block as_value">{%.from%}</span>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-md-2" for="to">{%lang Кому%}:</label>
                <div class="col-md-6">
                	<span class="help-block as_value">{%.to%}</span>
                </div>
            </div>
            
            <hr class="separator" />
 
 			<div class="form-group">
                <label class="control-label col-md-2" for="subject">{%lang Тема%}:</label>
                <div class="col-md-6">
                	<span class="help-block as_value">{%.subject%}</span>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-md-2" for="text">{%lang Текст%}:</label>
                <div class="col-md-6">
                	<span class="help-block as_value">{%.text%}</span>
                </div>
            </div>
                
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                	{%includeview buttons_utils%}
                </div>
            </div>
        </form> 
        
	</div>
</div>