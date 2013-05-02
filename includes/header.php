<?php    
    require_once(dirname(__FILE__) . "/_init.php");

    $pageHelper = new PageHelper();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title>Burghill Valley Golf Club</title>
        <link rel="stylesheet" type="text/css" href="/css/site.css" />
        <?php if(is_admin()) { ?>
        <link rel="stylesheet" type="text/css" href="/css/admin.css" />
        <script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
        <?php } ?>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/js/date-en-GB.js"></script>
        <script type="text/javascript" src="/js/jquery.cycle.all.js"></script>
        <script type="text/javascript" src="/js/jquery.simplemodal.js"></script>
        <script type="text/javascript" src="/js/jquery.galleriffic.js"></script>
        <script type="text/javascript" src="/js/main.js"></script>
    </head>
    <body>
        <div id="header">
            <div id="top"></div>
            <div id="heading" class="content">
                <div id="title"><a href="/">Burghill Valley Golf Club</a></div>
                <div id="contact_details">
                    <div id="tel_no"><strong>tel.</strong> 01432 760456</div>
                    <div id="email_addr"><strong>email.</strong> <a href="mailto:info@bvgc.co.uk">info@bvgc.co.uk</a></div>
                </div>
                <div id="social_header">
                    <a href="https://www.facebook.com/burghillvalley" target="_blank" title="like us on facebook"><img src="/imgs/facebook.png" alt="Facebook" height="29" /></a>
                    <a href="http://twitter.com/#!/BurghillValley" target="_blank" title="follow us on twitter"><img src="/imgs/twitter.png" alt="Twitter" height="29" /></a>
                </div>
            </div>
            <div id="navigation">
                <ul class="content"><?php echo $pageHelper->BuildMenu(); ?></ul>
            </div>
        </div>
        <div id="mainbody" class="content">
