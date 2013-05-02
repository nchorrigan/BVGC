<?php
    class GalleryHelper {
        function GetGallery($id) {
            global $databaseConnection;
    
            $statement = $databaseConnection->prepare("SELECT g.id, g.name, g.description, g.sortOrder, g.createdAt
                                                       FROM `galleries` g  
                                                       WHERE g.id = ?");
            $statement->bind_param('i', $id);
            $statement->execute();
            $statement->store_result();
    
            $gallery = new Gallery();
    
            if (!$statement->error && !$statement->num_rows != 1) {
    
                $statement->bind_result($gallery->id, $gallery->name, $gallery->description, $gallery->sortOrder, $gallery->createdAt);
                $statement->fetch();

                $gallery->images = $this->GetGalleryImages($gallery->id);
            }
    
            return $gallery;
        }
    
        function GetAll() {
            global $databaseConnection;
    
            $galleries = array();
    
            $statement = $databaseConnection->prepare("SELECT g.id, g.name, g.description, g.sortOrder, g.createdAt
                                                       FROM `galleries` g  
                                                       ORDER BY g.`sortOrder`");
    
            if ($statement->execute()) {
                $statement->bind_result($id, $name, $desc, $sortOrder, $createdAt);
    
                while($row = $statement->fetch()) {
                    $gallery = new Gallery();
    
                    $gallery->id = $id;
                    $gallery->name = $name;
                    $gallery->description = $desc;
                    $gallery->sortOrder = $sortOrder;       
                    $gallery->createdAt = $createdAt;
                   
                    $galleries[] = $gallery;
                }

                foreach($galleries as $gallery) {
                     $gallery->images = $this->GetGalleryImages($gallery->id);
                }
            }
       
            return $galleries;
        }

        function GetGalleryImages($galleryId) {
            global $databaseConnection;
    
            $images = array();
    
            $statement = $databaseConnection->prepare("SELECT gi.id, gi.imagePath, gi.imageDesc, gi.sortOrder
                                                       FROM `galleryimages` gi
                                                       WHERE gi.galleryId = ?            
                                                       ORDER BY gi.`sortOrder` ASC");
            $statement->bind_param('i', $galleryId);

            if ($statement->execute()) {
                $statement->bind_result($id, $path, $desc, $sortOrder);
    
                while($row = $statement->fetch()) {
                    $image = new GalleryImage();
    
                    $image->id = $id;
                    $image->imagePath = $path;
                    $image->imageDesc = $desc;
                    $image->sortOrder = $sortOrder;       
    
                    $images[] = $image;
                }
            }
       
            return $images;
        }
    }
    
    class Gallery {
        public $id = null;
        public $name = null;
        public $description = null;
        public $sortOrder = null;
        public $createdAt = null;

        public $images = array();

        public function JSON() {
            $json = "[";

            for($i = 0; $i < count($this->images); $i++) {
                $image = $this->images[$i];
                $json .= "{'path': '$image->imagePath', 'desc': '$image->imageDesc' }";
                if ($i < (count($this->images) - 1)) $json .= ",";
            }

            $json .= "]";

            return $json;
        }
    }
    
    class GalleryImage {
        public $id = NULL;
        public $imagePath = NULL;
        public $imageDesc = NULL;
        public $sortOrder = NULL;
    } 
?>