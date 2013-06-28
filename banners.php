<?php if ($page->banners != null) { ?>
<div id="banners" class="<?php echo $pageHelper->IsEditable() ? "inactive-banner" : "active-banner"; ?>" data-timeout="<?php echo $pageHelper->IsEditable() ? 0 : 10000; ?>">
    <?php foreach($page->banners as $banner) { ?>
    <div class="banner" data-id="<?php echo $banner->id; ?>">
        <?php if ($pageHelper->IsEditable()) { ?>
        <div class="banner-admin">
            <a href="#" class="admin-action" data-action="change-banner">change pic</a>
            <a href="#" class="admin-action" data-action="delete-banner">delete</a>
            <a href="#" class="admin-action" data-action="add-banner">add banner</a>
        </div>
        <?php } ?>
        <?php if (strlen($banner->content) > 0 || $pageHelper->IsEditable()) { ?>
        <div class="banner-dark">
            <div class="banner-desc" id="bannercontent_<?php echo $banner->id; ?>" contenteditable="<?php echo $pageHelper->IsEditable() ? "true" : "false"; ?>">
                <?php echo $banner->content; ?>
            </div>
        </div>
        <?php } ?>
        <img class="banner-img" src="<?php echo $banner->imagePath; ?>" alt="<?php echo $banner->imageDesc; ?>" />
    </div>
    <?php } ?>
</div>
<div id="banner-base"></div>
<?php } ?>
<?php if($pageHelper->IsEditable()) { ?>
<a href="#" class="admin-action" data-action="add-banner">add a new banner</a>
<?php } ?>