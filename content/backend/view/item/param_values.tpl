{%block param_values%}
	{%ifcount .params > 0%}
		<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default" id="param_values">

			{%ifset mess%}
				<div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
					<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>{%mess%}</strong>
				</div>
			{%endif%}

			<div class="page-header">
				<h3 class="row">
					<div class="col-sm-8">{%lang Параметры%}</div>
				</h3>
			</div>
			
			<div class="panel-body">

				<form id="param_values_form" class="form-horizontal">
					<div class="list">
						<table class="table table-hover">
							<thead>
								{%list .params%}
									{%ifset ._first%}
										<tr>
											{%ifset .name%}<th>{%lang Параметр%}</th>{%endif%}
											{%ifset .value%}<th>{%lang Значение%}</th>{%endif%}
											<th></th>
										</tr>
									{%endif%}
								{%end%}
							</thead>
							<tbody>
								{%list .params%}
									<tr class="vertical-align">
										<th class="col-sm-3">{%.title%}{%if .num_in !== ""%} ({%.num_in%}){%endif%}</th>
										{%ifset .value%}
										
											{%if .type == "String"%}
												<th class="col-sm-5"> 
													<div class="input-group input-group-sm col-sm-12">
														<input id="value_{%.name%}" name="params[{%.name%}]" type="text" value="{%.value%}" class="form-control" />
													</div>
												</th>
												<th class="col-sm-4"> 
													<div class="input-group input-group-sm col-sm-12">
														<select size="1" onchange="$('#value_{%.name%}').val($(this).val())" class="form-control">
														  <option value="">{%lang Выберите значение%}</option>
															{%list .items%}
																<option value="{%.value%}" {%if .value == params.value%}selected="selected"{%endif%}>{%.value%}</option>
															{%end%}
														</select>
													</div>
												</th>
											{%endif%}
											{%if .type == "Int"%}
												<th class="col-sm-2"> 
													<div class="input-group input-group-sm col-sm-6">
														<input name="params[{%.name%}]" type="number" value="{%.value%}" class="form-control" />
													</div>
												</th>
												<th></th>
											{%endif%}
											{%if .type == "Double"%}
												<th class="col-sm-2"> 
													<div class="input-group input-group-sm col-sm-6">
														<input name="params[{%.name%}]" type="number" value="{%.value%}" class="form-control" />
													</div>
												</th>
												<th></th>
											{%endif%}
											{%if .type == "Bool"%}
												<th class="col-sm-2"> 
													<div class="input-group input-group-sm col-sm-6">
														<div class="checkbox"><input{%if .value == 1%} checked{%endif%} value="1" type="checkbox" name="params[{%.name%}]" class="form-control" /><label></label></div>
													</div>
												</th>
												<th></th>
											{%endif%}
											{%if .type == "Text"%}
												<th class="col-sm-5"> 
													<div class="input-group input-group-sm col-sm-12">
														<textarea name="params[{%.name%}]" class="form-control">{%.value%}</textarea>
													</div>
												</th>
												<th></th>
											{%endif%}

										{%endif%}
									</tr>
								{%end%}
							</tbody>
						</table>
					</div>
					<div class="form-group adm-buttons animated_all"> 
						<div class="col-md-10">
							{%includeview button_save%}
						</div>
					</div>
				</form> 
				
				<script type="text/javascript">
					$("#param_values .btn.save").bind('click', function() {
						$("#param_values").Request({ controller: '{%controllerName%}', action: 'param_values_save', data: $('#param_values_form').serialize() + '&object={%ID%}' });
						return false;
					});
				</script>  
			</div>
		</div>
	{%endif%}
{%end%}