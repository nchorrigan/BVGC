<?php
    
    include("../includes/header.php"); 

    $galleryHelper = new GalleryHelper();

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

    <?php foreach($galleryHelper->getAll() as $gallery) { ?>
    <div class="gallery" data-id="<?php echo $gallery->id; ?>" data-imgs="<?php echo $gallery->JSON(); ?>">
        <div class="gallery-image">
            <img src="<?php echo $gallery->images[0]->imagePath; ?>" alt="<?php echo $gallery->images[0]->imageDesc; ?>" />
        </div>
        <div class="gallery-desc"><span><?php echo $gallery->description; ?></span></div>
    </div>
    <?php } ?>
</div>

<style type="text/css">
    .gallery {
        float: left;
        width: 210px;
        position: relative;
        border: 10px solid #fff;
        cursor: pointer;
    }
    .gallery .gallery-image {
        border: 0;
    }
        .gallery .gallery-image IMG {
            width: 210px;
            max-height: 150px;
        }
    
    .gallery-desc {
        position: absolute;
        bottom: 0;
        margin: 100px 0 0 0;
        width: 100%;
        height: 50px;
        background-color: #fff;
        opacity: 0.85;
        text-align: center;
    }
    
        .gallery-desc SPAN {
            display: block;
            margin: 4px;
        }
</style>
<script type="text/javascript">
    $(function ($) {
        $('.gallery').click(function (e) {
            e.preventDefault();
    
            OpenGallery($(this));
        });
    
        function OpenGallery(gallery) {
            var images = eval(gallery.data('imgs'));
    
            if (images != undefined && images.length > 0) {
                $('<div />').load('gallery.php?id='+ gallery.data('id')).modal({ minWidth: 800, minHeight: 650 });
            } else {
                alert('Work in progress - an update coming soon!');
            }
        }
    });
</script>

<?php include("../includes/footer.php"); ?>
