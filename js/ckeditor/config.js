CKEDITOR.editorConfig = function (config) {
    config.autoGrow_onStartup = true;
    config.extraPlugins = 'autogrow';
    config.autoGrow_minHeight = '500';
    config.removePlugins = 'resize';
    config.forcePasteAsPlainText = true;

    config.contentsCss = '/css/wysiwyg.editor.css';

    config.toolbarGroups = [
		{ name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
		{ name: 'simpleStyles', items: ['Format', 'Font', 'FontSize'] },
        { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align'] },
        '/',
		{ name: 'links' },
		{ name: 'simpleinsert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
		{ name: 'colors' },
        { name: 'editing', groups: ['find', 'selection'] },
        { name: 'clipboard', groups: ['clipboard', 'undo'] }
    ];

    config.removeButtons = 'Underline,Subscript,Superscript';

    config.filebrowserBrowseUrl = '/kcfinder/browse.php?type=files';
    config.filebrowserImageBrowseUrl = '/kcfinder/browse.php?type=images';
    config.filebrowserFlashBrowseUrl = '/kcfinder/browse.php?type=flash';
    config.filebrowserUploadUrl = '/kcfinder/upload.php?type=files';
    config.filebrowserImageUploadUrl = '/kcfinder/upload.php?type=images';
    config.filebrowserFlashUploadUrl = '/kcfinder/upload.php?type=flash';
};
