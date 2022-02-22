<?php

/**
 * @ PACKAGE  =   DCMS-SOCIAL
 * @ AUTHOR   =   DARIK 
 */
 
class image {
 
/* Глобальные переменные  */
 
    var $image;
    var $image_type;
 
/* Загрузка изображения */
 
	function load($filename) {

		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if ( $this->image_type == IMAGETYPE_JPEG ) {
		$this->image = imagecreatefromjpeg($filename);
		} else if ( $this->image_type == IMAGETYPE_GIF ) {
		$this->image = imagecreatefromgif($filename);
		} else if ( $this->image_type == IMAGETYPE_PNG ) {
		$this->image = imagecreatefrompng($filename);
    }
	
	}

/* Сохранение изображения */

	function save($filename, $image_type=IMAGETYPE_JPEG, $compression=90, $permissions=null) {

		if ( $image_type == IMAGETYPE_JPEG ) {
		imagejpeg($this->image,$filename,$compression);
		} else if ( $image_type == IMAGETYPE_GIF ) {
		imagegif($this->image,$filename);
		} else if ( $image_type == IMAGETYPE_PNG ) {
		imagealphablending($this->image, false); 
		imagesavealpha($this->image, true);
		imagepng($this->image,$filename);
		}
		if ($permissions != null) {
		chmod($filename,$permissions);
		}
			
	}

}

?>