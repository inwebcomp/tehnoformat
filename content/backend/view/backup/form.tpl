<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default">
	<div class="page-header">
        <h3 class="row">
            <div class="col-sm-8">{%lang Установка резервной копии%}</div>
        </h3>
    </div>
    
    <div class="panel-body">
    
        <form role="form" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            
            <div class="form-group">
                <label class="control-label col-md-2">{%lang Файл%}:</label>
                <div class="col-md-6">
                    <span class="help-block as_value">{%.file%}</span>
                </div>
            </div>
    
    		<div class="form-group">
                <label class="control-label col-md-2">{%lang Создан%}:</label>
                <div class="col-md-6">
                    <span class="help-block as_value">{%.created%}</span>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-md-2">{%lang Размер%}:</label>
                <div class="col-md-6">
                    <span class="help-block as_value">{%.sizeMB%} MB</span>
                </div>
            </div>
    
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-2 col-md-10">
                    <button type="submit" name="submit" class="btn btn-success save dont-change animated effect-touch">{%lang Установить резервную копию%}</button>
                </div>
            </div>
            
        </form> 
        
    </div>
</div>