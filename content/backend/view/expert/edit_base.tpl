{%ifset .ID%}
	<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
		<div class="page-header">
			<h3 class="row">
				<div class="col-sm-12">{%lang Базовая информация%}</div>
			</h3>
		</div>
		
		<div class="panel-body">
			<form role="form" rel="{%oc%}" action="{%controllerName%}/save" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
				<input value="{%.ID%}" type="hidden" name="object" />
		
				{%field text/expert/"Эксперт"/4%}

				{%field post/post_ID/"Запись в блоге"/4%}

				<div class="form-group adm-buttons animated_all"> 
					<div class="col-md-offset-2 col-md-10">
						{%includeview button_save%}
					</div>
				</div>
			</form> 
		</div>
	</div>
{%endif%}