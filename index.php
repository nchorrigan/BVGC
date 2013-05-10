<?php require_once("./includes/header.php"); 
    $eventHelper = new EventHelper();
    
    if(is_admin() && $_SERVER["REQUEST_METHOD"] == "POST") {
        $pageId = $_POST["pageId"];

        if (isset($_POST["save"])) {
            if ($pageHelper->UpdatePage($pageId, $_POST["pageTitle"], $_POST["content"])) {
                header ("Location: ". $currentpath ."?updated");
            }               
            else {
                echo 'Failed to edit page';
            }
        }
    }  
    
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

<div id="<?php echo (!$pageHelper->IsEditable() ? "leftcol" : "fullcol"); ?>">
    <form method="POST" action="./">
        <input type="hidden" name="pageId" value="<?php echo $page->id;?>" />
        <input type="hidden" name="pageName" value="<?php echo $page->title ?>" />
        <input type="hidden" name="pagePath" value="<?php echo $page->path ?>" />
        <h2>
            <?php echo $pageHelper->pageHeading($page->title); ?>
            <?php echo $pageHelper->editPageButton(); ?>
        </h2>

        <?php echo $pageHelper->wysiwyg($page->content); ?>
    </form>

    <?php if ($pageHelper->IsEditable()) { ?>
    <br />
    <fieldset>
        <legend>Banners</legend>
        <p>Use this area to administer the banners that will display on this page.</p>
        <table style="width: 100%;margin-left: 2em">
            <?php foreach($page->banners as $banner) { ?>
            <tr>
                <td rowspan="2"><img src="<?php echo $banner->imagePath; ?>" height="70" alt="<?php echo $banner->imageDesc; ?>" /></td>
                <td>
                    <label style="width: 7em;display: inline-block">Image desc:</label>
                    <input type="text" style="width: 350px" value="<?php echo $banner->imageDesc; ?>" />
                </td>
                <td>
                    <a href="#">up</a> | <a href="#">dwn</a> | <a href="#">del</a>
                </td>
            </tr>
            <tr>
                <td>
                    <label style="width: 7em;display: inline-block">Banner content:</label>
                    <textarea style="width: 350px; height: 50px"><?php echo $banner->content; ?></textarea>
                </td>
            </tr>
            <?php } ?>
        </table>

        <p>
            <label for="bannerFile">or upload a new banner <em>(dimensions: 900px 300px)</em></label> <input id="bannerFile" type="file" name="banner" />
        </p>
        <p>
            <button type="submit">Upload</button>
        </p>
    </fieldset>
    <?php } ?>
</div>
<?php if(!$pageHelper->IsEditable()) { ?>
<div id="rightcol">
    <?php $news = $eventHelper->GetEvents("News", 4, "ASC"); ?>
    <?php if (count($news) > 0) { ?>
    <h3>Latest news</h3>
    <ul class="homepage_events">
        <?php foreach ($news as $item) { ?>
        <li>
            <a href="/diary?view=<?php echo $item->id; ?>"><?php echo $item->title; ?></a>
            <br />
            <span><?php echo $item->summary; ?></span>
        </li>
        <?php } ?>
    </ul>
    <?php } ?>

    <?php $news = $eventHelper->GetEvents("Competitions", 5, "ASC"); ?>
    <?php if (count($news) > 0) { ?>
    <h3>Competitions and events</h3>
    <ul class="homepage_events">
        <?php foreach ($news as $item) { ?>
        <li>
            <a href="/diary/#event_<?php echo $item->id; ?>"><?php echo $item->title; ?></a>
            <span class="event_date"><?php echo $item->formattedDate(); ?></span>
            <br />
            <span><?php echo $item->summary; ?></span>
        </li>
        <?php } ?>
    </ul>
    <?php } ?>
</div>
<?php } ?>

<?php include("./includes/footer.php"); ?>