<?php
    
    include("../includes/header.php"); 
    
    $eventHelper = new EventHelper();
    
    $page = $pageHelper->GetPage($_SERVER["REQUEST_URI"]);
?>
<?php if (!$pageHelper->IsEditable() && $page->banners != null) { ?>
<div id="banners">
    <?php foreach($page->banners as $banner) { ?>
    <div class="banner">
        <div class="banner-dark">
            <div class="banner-desc">
                <?php echo $banner->content; ?>
            </div>
        </div>
        <img src="<?php echo $banner->imagePath; ?>" alt="<?php echo $banner->imageDesc; ?>" />
    </div>
    <?php } ?>
</div>
<div id="banner-base"></div>
<?php } ?>
<div id="fullcol">
    <h2><?php echo $page->title; ?></h2>

    <?php echo $page->content; ?>

    <?php $news = $eventHelper->GetEvents("News", 99, "ASC"); ?>
    <?php if (count($news) > 0 || is_admin()) { ?>
    <div class="event_section" id="news">       
        <h3>Latest news <?php if (is_admin()) { ?><a href="/diary/info.php?type=News" target="_blank" class="popup">[+]</a><?php } ?></h3>
        <ul>
            <?php foreach ($news as $item) { ?>
            <li>
                <a id="event_<?php echo $item->id; ?>" class="<?php echo (is_admin() ? "popup" : "modal")?>" href="/diary/info.php?id=<?php echo $item->id; ?>"><?php echo $item->title; ?></a>
                <br />
                <span><?php echo $item->summary; ?></span>
            </li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>

    <?php $news = $eventHelper->GetEvents("Competitions", 999, "ASC"); ?>
    <?php if (count($news) > 0 || is_admin()) { ?>
    <div class="event_section" id="open">
        <h3>Open Competitions and events <?php if (is_admin()) { ?><a href="/diary/info.php?type=Competitions" target="_blank" class="popup">[+]</a><?php } ?></h3>
        <ul>
            <?php foreach ($news as $item) { ?>
            <li>
                <a id="event_<?php echo $item->id; ?>" class="<?php echo (is_admin() ? "popup" : "modal")?>" href="/diary/info.php?id=<?php echo $item->id; ?>" class="event_title"><?php echo $item->title; ?></a> <span class="event_date"><?php echo $item->formattedDate(); ?></span>
                <br />
                <div class="event_desc"><?php echo $item->summary; ?></div>
            </li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>
</div>

<script type="text/javascript">
    $(function () {
        $('.event_section').on('click', 'A', function (e) {
            e.preventDefault();

            if ($(this).hasClass('modal')) {
                $('<DIV />')
                    .load($(this).attr('href'))
                    .modal({
                        minHeight: 300
                    });
            } else if ($(this).hasClass('popup')) {
                window.open($(this).attr('href'), "", "width=780,height=650,location=no,menubar=no,toolbar=no")
            }
        });

        if (location.hash) {
            if ($(location.hash)) {
                $(location.hash).click();
            }
        }
    });
</script>

<?php include("../includes/footer.php"); ?>
