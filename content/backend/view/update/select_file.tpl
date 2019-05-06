<div class="page-header">
    <h3 class="row">
        <div class="col-sm-8">{%lang Установка обновлений%} <small>{%block engine%}{%lang Текущая версия%}: {%.version%}{%end%}</small></div>
    </h3>
</div>

<div class="panel-body">

    <form role="form" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
        <div class="form-group file-input-{%oc%}">
            <label class="control-label col-md-2" for="{%oc%}_file">{%lang Архив%}:</label>
            <div class="col-md-6">
                <input name="file" id="{%oc%}_file" class="file-upload-static" type="file" accept=".zip">
            </div>
        </div>
        <script type="text/javascript">
            var fileUploaderContainer = $(".file-input-{%oc%}");
            var file_types = ["zip"];
        </script>
        {%js upload_file_static%}

        <div class="form-group adm-buttons animated_all"> 
            <div class="col-md-offset-2 col-md-10">
                <button type="submit" name="submit" class="btn btn-success save dont-change animated effect-touch">{%lang Импорт%}</button>
            </div>
        </div>
    </form> 
    
</div>