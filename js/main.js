(function ($) {
    $(document).ready(function () {
        if ($('#banners').find('.banner').length > 1) {
            var nav = $('<div />').attr({ id: "banner-nav" });
            $('#banners').find('.banner').each(function (i, b) { nav.append('<a href="#">' + (i + 1) + '</a>'); });
        }

        $('#banners')
            .append(nav != undefined ? nav : '')
            .cycle({
                fx: 'scrollLeft',
                timeout: 10000,
                slideExpr: '.banner',
                pager: '#banner-nav',
                pagerAnchorBuilder: function (index, slide) {
                    return '#banner-nav A:eq(' + (index) + ')';
                }
            });

        $('#banners').hover(
            function () { $(this).cycle('pause'); },
            function () { $(this).cycle('resume'); }
        );

        $('BODY').on('click', '#book-a-tee', function (e) {
            e.preventDefault();

            $('<DIV />')
                .load('/diary/bookatee.php')
                .modal();
        });
    });
})(jQuery);