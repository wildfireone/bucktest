$('button').on('click tap', function (e) {
    console.log('<a href="' + $(this).data("value1") + '"></a>');

    switch ($(this).data("value0")) {
        case 0:
            var string = '';
            tinymce.activeEditor.execCommand('mceInsertContent', false, '<a href="'+ $(this).data("value1")+'"><img style="width:260px; height:260px;" src="' + $(this).data("value1") + '"/></a>');
            break;
        case 1:
            tinymce.activeEditor.execCommand('mceInsertContent', false, '<a class="button" href="' + $(this).data("value1") + '">Download Here</a>');
            break;
    }

});