<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
		<div class="page-header">
			<h3 class="row">
				<div class="col-sm-8">{%lang Базовая информация%}{%ifset .ID%} <small>{%lang ID%}: {%.ID%}</small>{%endif%}</div>
				<div class="col-sm-4 action">
					{%ifset .ID%}<a href="javascript:void(0)" class="copy-object animated" rel="{%.ID%}"><span class="fa fa-copy" aria-hidden="true"></span> {%lang Создать дубликат%}</a> &nbsp; &nbsp;{%endif%} 
					<a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/" class="animated"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> {%lang Добавить запись%}</a>
				</div>
			</h3>
		</div>
		
		<div class="panel-body">
	
			<form role="form" rel="{%oc%}" action="{%controllerName%}/save" class="ajax-form-request form-horizontal" enctype="multipart/form-data">
				<input value="{%.ID%}" type="hidden" name="object" />
			
				{%field text/title/"Заголовок"/6%}
				
				{%field text/name/"URL ID"/6%}

				{%field category/category_ID/"Раздел"/3%}

				{%field text/author/"Автор"/6%}

				<hr class="separator" />

				{%field textarea/description_min/"Краткое описание"/6%}

				{%field editor/description/"Текст"/6%}
	
				<hr class="separator" />

				<div class="opener" rel="normal video">
					{%includeview image%}
				</div>

				<div class="opener" rel="photo">
            		{%includeview images%}
				</div>

				{%field checkbox/small_image/"Узкое изображение"/2%}
	
				<hr class="separator" />

				{%field type/type/"Тип записи"/2%}

				<div class="opener" rel="video">
					{%field text/video/"Ссылка на видео с Youtube"/6%}
				</div>

				<script type="text/javascript">
					$("#type-selector").bind("change", function(){
						$(".edit .opener:not([rel*="+$(this).val()+"])").hide();
						$(".edit .opener[rel*="+$(this).val()+"]").show();
					}).change();
				</script>

				<hr class="separator" />

				{%field text/views/"Количество просмотров"/6%}

				<hr class="separator" />

				{%ifset ID%}
					{%field datetime/created/"Дата публикации"/3%}
				{%endif%}
				
				<hr class="separator" />
            
				{%field display_at_lang/display_at_lang/"Показывать в переводе"/3%}
				
				<hr class="separator" />
				
				{%field checkbox/popular/"Популярный"/2%}
				
				{%field number/pos/"Позиция"/2%}
				
				{%field checkbox/block/"Заблокирован"/2%}
				   
				<div class="form-group adm-buttons animated_all"> 
					<div class="col-md-offset-2 col-md-10">
						{%includeview buttons_default%}
					</div>
				</div>
			</form> 
			
		</div>
	</div>