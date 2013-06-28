$(function () {
    $('BODY').on('click', 'A.admin-action', function (e) {
        e.preventDefault();

        var link = $(this), banner = $(this).closest('.banner');

        switch (link.data('action').toLowerCase()) {
            case 'add-banner': { AddBanner(banner); } break;
            case 'change-banner': { ChangeBannerImage(banner); } break;
            case 'delete-banner': { DeleteBanner(banner); } break;
        }
    });

    function AddBanner(banner) {
        FindFile('banners', function (url) {
            $.post('/cms/banner.php',
                { action: 'add', pageId: $('INPUT[name=pageId]').val(), path: url },
                function () {
                    location.reload();
                });
        });
    }

    function ChangeBannerImage(banner) {
        FindFile('banners', function (url) {
            if (url.length > 0) {
                $.post('/cms/banner.php', { action: 'delete', id: banner.data('id'), path: url });

                banner.find('IMG.banner-img').attr('src', url);
            }
        });
    }

    function DeleteBanner(banner) {
        if (confirm('Are you sure you want to delete this banner?\n\nNote. the associated image will still be available for use elsewhere.')) {
            $.post('/cms/banner.php', { id: banner.data('id'), action: 'delete' }, function () {
                location.reload();
            });
        }
    }

    function FindFile(type, callback) {
        window.KCFinder = {};
        window.KCFinder.callBack = callback;
        window.open('/kcfinder/browse.php?type=' + type, 'kcfinder_single', 'width=800,height=600');
    }

    CKEDITOR.disableAutoInline = true;

    $('DIV[contenteditable=true]').each(function () {
        var elem = this;
        var content_id = $(elem).attr('id');
        var bannerId = $(elem).closest('.banner').data('id');

        CKEDITOR.inline(content_id, {
            on: {
                blur: function (event) {
                    var data = event.editor.getData();

                    var request = jQuery.ajax({
                        url: "/cms/banner.php",
                        type: "POST",
                        data: {
                            id: bannerId,
                            action: 'updatecontent',
                            content: data
                        },
                        dataType: "html"
                    });
                }
            }
        });
    });




    $('#page_title').on('change, keyup', function () {
        $('#page_name').val(encodeURIComponent($(this).val().toLowerCase().replace(new RegExp(' ', 'g'), '_')));
    });

    $('BODY').on('click', 'BUTTON[name=step]', function (e) {
        e.preventDefault();

        var fieldset = $(this).closest('FIELDSET');
        var direction = $(this).val();
        var form = fieldset.closest('FORM');

        switch (direction) {
            case 'next':
                {
                    if (form.validate().subset(fieldset)) {
                        $(fieldset).find('.fieldset_content').slideUp('slow');
                        $(fieldset).find('.fieldset_summary').show();
                        $(fieldset).next().find('.fieldset_content').slideDown('slow');
                    }
                } break;
            case 'prev':
                {
                    $(fieldset).find('.fieldset_content').slideUp('slow');
                    $(fieldset).find('.fieldset_summary').show();
                    $(fieldset).prev().css('border', '1px solid blue').find('.fieldset_content').slideDown('slow');
                } break;
            case 'save': { form.submit(); } break;
        }
    });
});

jQuery.validator.prototype.subset = function (container) {
    var ok = true;
    var self = this;
    $(container).find(':input').each(function () {
        if (!self.element($(this))) ok = false;
    });
    return ok;
}