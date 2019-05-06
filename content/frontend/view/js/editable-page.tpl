
<!-- Include external CSS. -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css">

<!-- Include Editor style. -->
<link href="https://cdn.jsdelivr.net/npm/froala-editor@2.9.3/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/froala-editor@2.9.3/css/froala_style.min.css" rel="stylesheet" type="text/css" />

<!-- Include external JS libs. -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>

<!-- Include Editor JS files. -->
<script type="text/javascript" src="/jslib/froala/froala_editor.pkgd.min.js"></script>
<script>
    $(function() {
        $("#editor,#editor2").froalaEditor({
            language: 'ru',
            imageUploadURL: '/scripts/upload_editor_image.php',
            imageAllowedTypes: ['jpeg', 'jpg', 'png', 'svg'],
            pastePlain: true,
            fileUpload: false,
            videoUpload: false,
            toolbarInline: true,
            charCounterCount: false,
            imageStyles: {
                'fr-class-margin-right': 'Отступ справа',
                'fr-class-margin-left': 'Отступ слева',
                'fr-class-margin-top': 'Отступ сверху',
                'fr-class-margin-bottom': 'Отступ снизу',
            },
        }).on('froalaEditor.image.error', function (e, editor, error, response) {
            if (response !== undefined)
                console.log(response);
        });

        // toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', 'color', '-', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', '-', 'insertImage', 'insertLink', 'insertFile', 'insertVideo', 'undo', 'redo']
    });
</script>