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

<?php include_once("banners.php") ?>

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
</div>

<?php if(!$pageHelper->IsEditable()) { ?>
<div id="rightcol" class="shadow-content">
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

    <?php $news = $eventHelper->GetEvents("Competitions", 7, "ASC"); ?>
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

    <div class="shadow-box"><div class="shadow-left"></div><div class="shadow-right"></div></div>
</div>
<?php } ?>

<?php include("./includes/footer.php"); ?>