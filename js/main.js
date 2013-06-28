(function ($) {
    $(document).ready(function () {
        if ($('#banners').find('.banner').length > 1) {
            var nav = $('<div />').attr({ id: "banner-nav" });
            $('#banners').find('.banner').each(function (i, b) { nav.append('<a href="#" data-bannerid="' + $(b).data('id') + '">' + (i + 1) + '</a>'); });
            $('#banners').append(nav != undefined ? nav : '')
        }

        var timeout = $('#banners').data('timeout');

        $('.active-banner')
            .cycle({
                fx: 'scrollLeft',
                timeout: timeout,
                slideExpr: '.banner',
                pager: '#banner-nav',
                pagerAnchorBuilder: function (index, slide) {
                    return '#banner-nav A:eq(' + (index) + ')';
                }
            })
            .hover(
                function () { $(this).cycle('pause'); },
                function () { $(this).cycle('resume'); }
            );

        $('.inactive-banner').on('click', '#banner-nav A', function (e) {
            e.preventDefault();

            var self = $(this), banners = $(this).closest('#banners');
            banners.find('.banner').hide();
            banners.find('.banner[data-id='+ self.data('bannerid') +']').show();
        });

        $('BODY').on('click', '#book-a-tee', function (e) {
            e.preventDefault();

            $('<DIV />')
                .load('/cms/bookatee.php')
                .modal();
        });

        $('#navigation').on('click', 'A', function (e) {
            var menu = $(this).parent();
            var children = menu.find('UL');

            if (children.length) {
                e.preventDefault();

                if (children.is(':visible')) {
                    children.slideUp();
                } else {
                    children.slideDown();

                    $(children).bind('mouseleave', function (e) {
                        var timeout = setTimeout(function () { children.slideUp(); }, 2000);
                        $(this).bind('mouseenter', function () { clearTimeout(timeout); });
                    });
                }
            }

            if ($(this).hasClass('admin')) {
                e.preventDefault();

                switch ($(this).data('action')) {
                    case 'add_page':
                        {
                            $.get($(this).attr('href'), {}, function (result) {
                                $('<DIV />').html(result).dialog({ modal: true, minHeight: 400, minWidth: 500 });
                            });
                        } break;
                }
            }
        });


    });
})(jQuery);