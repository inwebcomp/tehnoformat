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
			
			<input value="1" type="hidden" name="params[some_field]" />
        
            {%field text/title/"Название"/6%}
            
            {%field text/name/"URL ID"/6%}
            
            <hr class="separator" />
            
			{%field price/price/"Цена"/2%}

            {%field number/old_price/"Старая цена"/2%}
            
            <hr class="separator" />
            
            {%controllerdynamic parent_category_select%}
            
            <hr class="separator" />

			{%field textarea/description_min/"Краткое описание"/6%}
		
            {%field textarea/description/"Описание"/6%}
            
            <hr class="separator" />

            {%field results/results/"Чему я научусь"/6%}
            
            <hr class="separator" />

            {%field requirements/requirements/"Требования"/6%}
            
            <hr class="separator" />

			{%field auditory/auditory/"Целевая аудитория"/6%}

            <hr class="separator" />

			{%field plan/plan/"План"/6%}
            
            <hr class="separator" />

            {%field textarea/start/"Начало обучения"/6%}
            {%field date/start_date/"Дата начала обучения"/2%}
            
            <hr class="separator" />
    
            {%includeview images%}

            <hr class="separator" />

            {%field location/location_ID/"Город"/2%}

            {%field district/district_ID/"Район"/2%}

            {%field language/language_ID/"Язык"/3%}

            {%field person/person_ID/"Автор"/2%}
            
            <hr class="separator" />
            
            {%field display_at_lang/display_at_lang/"Показывать в переводе"/3%}

            <hr class="separator" />

            {%field text/address/"Адрес"/6%}
            {%field text/phone/"Телефон"/6%}
            {%field text/email/"Email"/6%}
            {%field text/worktime/"График работы"/6%}
            {%field text/map_location/"Позиция на карте"/6%}

            <hr class="separator" />
            
            {%field checkbox/online/"Онлайн курс"/2%}

            {%field checkbox/training/"Тренинг"/2%}
            
            <hr class="separator" />
            
            {%field text/rating/"Рейтинг"/2%}
            {%field text/rating_num/"Кол-во голосов"/2%}
            {%field text/rating_sum/"Сумма оценок"/2%}

            <hr class="separator" />

            {%field status/status/"Статус"/2%}

            {%field number/pos/"Позиция"/2%}

			{%field checkbox/expired/"Просрочен"/2%}

			{%field checkbox/block/"Заблокирован"/2%}
            
            <hr class="separator" />
            
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                	{%includeview buttons_default%}
                	
                    &nbsp; &nbsp; &nbsp;<button overwrite="true" type="button" class="btn btn-default repost animated effect-touch">{%lang Переопубликовать%}</button>
                    
                    <script type="text/javascript">
						$("#{%oc%} .repost").bind('click', function(){
							if(! confirm("{%lang Переопубликовать?%}"))
								return false;

							$("#{%oc%}").Request({ controller: '{%controllerName%}', action: 'repost', data: "object={%.ID%}", loader: "global" });
							
							return false;
						});
					</script>  
                </div>
            </div>
        </form> 
        
	</div>
</div>