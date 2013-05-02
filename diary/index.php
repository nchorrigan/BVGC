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

    <div class="event_section">
        <?php $news = $eventHelper->GetEvents("News", 99); ?>
        <h3>Latest news</h3>
        <ul>
            <?php foreach ($news as $item) { ?>
            <li>
                <a href="/diary/info.php?id=<?php echo $item->id; ?>"><?php echo $item->title; ?></a>
                <br />
                <span><?php echo $item->summary; ?></span>
            </li>
            <?php } ?>
        </ul>
    </div>

    <div class="event_section">
        <h3>Club Events</h3>
        <?php $news = $eventHelper->GetEvents("Events", 999); ?>
        <ul>
            <?php foreach ($news as $item) { ?>
            <li>
                <a href="/diary/info.php?id=<?php echo $item->id; ?>" class="event_title"><?php echo $item->title; ?></a> <span class="event_date"><?php echo $item->formattedDate(); ?></span>
                <br />
                <div class="event_desc"><?php echo $item->summary; ?></div>
            </li>
            <?php } ?>
        </ul>
    </div>

    <div class="event_section">
        <h3>Open Competitions</h3>
        <?php $news = $eventHelper->GetEvents("Competitions", 999); ?>
        <ul>
            <?php foreach ($news as $item) { ?>
            <li>
                <a href="/diary/info.php?id=<?php echo $item->id; ?>" class="event_title"><?php echo $item->title; ?></a> <span class="event_date"><?php echo $item->formattedDate(); ?></span>
                <br />
                <div class="event_desc"><?php echo $item->summary; ?></div>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('.event_section').on('click', 'A', function (e) {
            e.preventDefault();

            $('<DIV />').load($(this).attr('href')).modal({ minHeight: 300 });            
        });

    });
</script>

<?php include("../includes/footer.php"); ?>
