<?php
    include("includes/header.php");
    
    if(is_admin() && $_SERVER["REQUEST_METHOD"] == "POST") {
        $pageId = $_POST["pageId"];
    
        if(isset($_POST["action"])) {
            switch($_POST["action"]) {
                case "addbanner": { $pageHelper->AddBanner($pageId, $_POST["path"]); } break;
                case "updatebanner": { $pageHelper->UpdateBannerContent($pageId, $_POST["bannerId"], $_POST["content"]); } break;
                case "removebanner": { $pageHelper->RemoveBanner($_POST["bannerId"]); } break;
            }

            exit(0);
        }
    
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

<div id="fullcol">
    <form method="POST" action="<?php echo $page->path; ?>">
        <input type="hidden" name="pageId" value="<?php echo $page->id;?>" />
        <input type="hidden" name="pageName" value="<?php echo $page->title ?>" />
        <input type="hidden" name="pagePath" value="<?php echo $page->path ?>" />
        <h1>
            <?php echo $pageHelper->pageHeading($page->title); ?>
            <?php echo $pageHelper->editPageButton(); ?>
        </h1>

        <?php echo $pageHelper->wysiwyg($page->content); ?>
    </form>
</div>

<?php include ("includes/footer.php"); ?>