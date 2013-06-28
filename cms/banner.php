<?php
    include("../includes/_init.php"); 
    
    is_admin();
    
    $pageHelper = new PageHelper();
    $banner = $pageHelper->GetBanner($_REQUEST["id"]);
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["action"])) {
            $update = FALSE;
    
            switch($_POST["action"]) {
                case "add": { $pageHelper->AddBanner($_POST["pageId"], $_POST["path"]); } break;
                case "updateimage": { $banner->imagePath = $_POST["path"]; $update = TRUE; } break;
                case "updatecontent": { $banner->content = $_POST["content"]; $update = TRUE; } break;
                case "delete": { $pageHelper->RemoveBanner($banner->id); } break;
            }
    
            if($update) $pageHelper->UpdateBanner($banner->id, $banner->content, $banner->imagePath, $banner->sortOrder);
        }
    }
    
    include("../includes/_dispose.php");
?>