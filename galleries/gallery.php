<?php
    
    include("../includes/_init.php"); 
    
    $galleryHelper = new GalleryHelper();
    
    $gallery = $galleryHelper->GetGallery($_GET["id"]);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
        <h2 style="margin-top: 0"><?php echo $gallery->name; ?></h2>
        <div id="gallery_area">
            <div id="gallery_list">
                <ul>
                    <?php foreach($gallery->images as $image) { ?>
                    <li><img src="<?php echo $image->imagePath; ?>" alt="<?php echo $image->imageDesc; ?>" /></li>
                    <?php } ?>
                </ul>
            </div>
            <div id="gallery_canvas">
                <img src="<?php echo $gallery->images[0]->imagePath; ?>" alt="<?php echo $gallery->images[0]->imageDesc; ?>" />
                <div id="gallery_description">
                    <span><?php echo $gallery->images[0]->imageDesc; ?></span>
                </div>
            </div>
        </div>

        <style type="text/css">  
            #gallery_list {
                float: left;
                overflow-y: scroll;
                padding-right: 5px;
            }
            
                #gallery_list UL {
                    margin: 0;
                    padding: 0;
                }
            
                    #gallery_list UL LI {
                        list-style: none;
                        cursor: pointer;
                        padding: 0;
                    }
            
                        #gallery_list UL LI IMG {
                            width: 100px;
                            max-height: 75px;
                            border: 1px solid #000;
                        }
            
            #gallery_canvas {
                margin-left: 140px;
                position: relative;
            }
            
                #gallery_canvas IMG {
                    width: 100%;
                }
            
            #gallery_description {
                position: absolute;
                bottom: 0;
                width: 100%;
                border-top: 1px solid #000;
                height: 100px;
                background-color: #fff;
                opacity: 0.85;
            }
            
                #gallery_description SPAN {
                    display: block;
                    margin: 10px;
                }
        </style>
    </body>
</html>

<script type="text/javascript">
    $(function ($) {
        $('#gallery_list').on('click', 'LI', function (e) {
            e.preventDefault();

            var img = $(this).find('IMG');

            $('#gallery_canvas')
                .html(img.clone())
                .append($('<div />')
                    .attr({ id: 'gallery_description' })
                    .html($('<span />').append(img.attr('alt'))));
        });
    });
</script>

<?php include("../includes/_dispose.php"); ?>