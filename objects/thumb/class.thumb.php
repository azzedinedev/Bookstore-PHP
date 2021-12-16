<?php
//+---------------------------------------------------------------------+
//|	class.thumb.php														|
//+---------------------------------------------------------------------+
//|	Thumbs manager lass													|
//+---------------------------------------------------------------------+

class thumb
{
	var $imageNotFound;
	
	function thumb()
	{
		$this->ImageNotFound = "files/system/warning.jpg";	
	}
	
	/**
	 * CroppedThumbnail($srcimg,$tn_w,$tn_h)
	 * @param string $imgSrc			: Image source
	 * @param string $thumbnail_width 	: largeur de la vignette
	 * @param string $thumbnail_height 	: hauteur de la vignette
	 * Fonction de renvoi d'une image miniature
	 */
	function CroppedThumbnail($imgSrc,$thumbnail_width,$thumbnail_height) { //$imgSrc is a FILE - Returns an image resource.
	 //getting the image dimensions  
		list($width_orig, $height_orig) = getimagesize($imgSrc);   
		$myImage = imagecreatefromjpeg($imgSrc);
		$ratio_orig = $width_orig/$height_orig;
		
		if ($thumbnail_width/$thumbnail_height > $ratio_orig) {
		   $new_height = $thumbnail_width/$ratio_orig;
		   $new_width = $thumbnail_width;
		} else {
		   $new_width = $thumbnail_height*$ratio_orig;
		   $new_height = $thumbnail_height;
		}
		
		$x_mid = $new_width/2;  //horizontal middle
		$y_mid = $new_height/2; //vertical middle
		
		$process = imagecreatetruecolor(round($new_width), round($new_height)); 
		
		imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
		$thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height); 
		imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);
	
		imagedestroy($process);
		imagedestroy($myImage);
		return $thumb;
	}
	
	/**
	 * watermark(FichierSource, FichierWatemark, FichierDestination)
	 * @param string $SourceFile	: Image source
	 * @param string $WatermarkFile : Image du watermark
	 * @param string $SaveToFile 	: Sauvegarder l'image dans cette chaine, Sinon : Retourne l'image
	 * @param string $TypeSrcIsFile : Default -> true 		=> Type de l'image source : (true -> Fichier , false -> php)
	 * @param string $placeWatermark: Default -> "center"	=> Emplacement du Watermark : (centre , autres -> droite)
	 * Fonction d’ajout d’un watermark sur un objet de type image
	**/
	function watermark($SourceFile, $WatermarkFile, $SaveToFile = NULL, $TypeSrcIsFile = true, $placeWatermark = "center"){
		$watermark = @imagecreatefrompng($WatermarkFile) or exit('Impossible d\'ouvrir le fichier PNG.');

		$bg_color = imagecolorat($watermark,1,1);
		imagecolortransparent($watermark, $bg_color);

		imageAlphaBlending($watermark, false);
		imageSaveAlpha($watermark, true);

		//$image_string = @file_get_contents($SourceFile) or exit('Impossible d\'ouvrir le fichier');
		//$image = @imagecreatefromstring($image_string) or exit('Format d\'image incorrecte.');
		$image = $SourceFile;
		$imageWidth=imageSX($image);
		$imageHeight=imageSY($image);
		$watermarkWidth=imageSX($watermark);
		$watermarkHeight=imageSY($watermark);
		if( $placeWatermark != "center"){
			// Place le logo en bas a droite de la photo
			$coordinate_X = ( $imageWidth - 5) - ( $watermarkWidth);
			$coordinate_Y = ( $imageHeight - 5) - ( $watermarkHeight);
		}else{
			// Place le logo au centre de la photo
			$coordinate_X = ( $imageWidth - $watermarkWidth) / 2;
			$coordinate_Y = ( $imageHeight - $watermarkHeight) / 2;
		}

		imagecopymerge($image, $watermark, $coordinate_X, $coordinate_Y, 0, 0, $watermarkWidth, $watermarkHeight,35);
		 /*
		 if(!($SaveToFile)) header('Content-Type: image/jpeg');
		 imagejpeg ($image, $SaveToFile, 100);
		 imagedestroy($image);
		 imagedestroy($watermark);
		 */
		return $image;
		//if(!($SaveToFile)) exit;

	}

	/**
	 * process($img,$width,$height,$path= NULL,$quality=80,$WatermarkFile = NULL)
	 * @param string $img		: Image source
	 * @param string $width		: Largeur de l'image
	 * @param string $height	: Longueur de l'image
	 * @param string $path		: Sauvegarder  l'image dans cette chaine
	 * @param string $quality	: Qualité d'image jpeg
	 * @param string $WatermarkFile : Image du watermark
	 * Fonction de creation d'une image miniature avec possiblité de waermark
	 */
	function process($img,$width,$height,$path= NULL,$quality=80,$WatermarkFile = NULL){

		if( file_exists($img) ){
			//$this->thumbnail($img,$width,$height,$path);
			//--------------------------------------------
			//Creation de la vignette
			$thumb = $this->CroppedThumbnail($img,$width,$height);

			//--------------------------------------------
			//Watermark

			if( !($WatermarkFile) or ( !file_exists($WatermarkFile) ) ){
				if( !($path) ){
					header('Content-Type: image/jpeg');
					imagejpeg($thumb,"",$quality);
				}else{
					//Sauvgarder la vignette dans : $path
					imagejpeg($thumb,$path,$quality);
				}
			}else{
				//créer un watermark
				$imgWatermark = $this->watermark($thumb, $WatermarkFile, $path, false,"center");
				if( !($path) ){
					header('Content-Type: image/jpeg');
					imagejpeg($imgWatermark,"",$quality);
				}else{
					//Sauvgarder la vignette dans : $path
					imagejpeg($imgWatermark,$path,$quality);


				}

			}


		}

	}

	/**
	 *return_path($img,$width,$height,$path,$quality=80,$WatermarkFile = NULL)
	 * @param string $img		: Image source
	 * @param string $width		: Largeur de l'image
	 * @param string $height	: Longueur de l'image
	 * @param string $path		: Sauvegarder  l'image dans cette chaine
	 * @param string $quality	: Qualité d'image jpeg
	 * @param string $WatermarkFile : Image du watermark
	 * Fonction de retour du chemin de l'image miniature avec réation en cas de non existance
	 */
	function return_path($img,$width,$height,$path,$quality=80,$WatermarkFile = NULL){
		if( file_exists($path) ){
			//On recréera la vignette si les tailles sont difirentes
			$sizeWH 	= GetImageSize($path);
			$sizeW		= $sizeWH[0];
			$sizeH		= $sizeWH[1];
			if( ( $sizeW != $width ) or ( $sizeH != $height ) ){
				$this->process($img,$width,$height,$path,$quality,$WatermarkFile);
			}
		}else{
			//On recréera la vignette si le fichier n'existe pas
			$this->process($img,$width,$height,$path,$quality,$WatermarkFile);
		}

		return $path;
	}

	/**
	 * image_from_text($text,$path)
	 * @param string $text		: Text à transformer dans l'image
	 * @param string $path		: Sauvegarder  l'image dans cette chaine
	 * Fonction de transformation d'un text en image en retournat le chemin $path ou envoyer l'aperçu de l'image si $path est vide
	 */
	function image_from_text($text,$path){
        if( !($path) ){
            header("Content-type: image/png");
        	$im 	= 	@imagecreate(150, 20) or die("Cannot Initialize new GD image stream");
        	$background_color 	= 	imagecolorallocate($im,  190, 190, 190);//couleur du background ici gris
        	$text_color 		= 	imagecolorallocate($im, 0, 0, 0);//couleur du text ici gris
        	imagestring($im, 1, 5, 5,  $text, $text_color);//construire l'image
        	imagepng($im);//afficher l'image
        	imagedestroy($im);
        }else{
        	//Sauvgarder la vignette dans : $path
            $im 	= 	@imagecreate(150, 20) or die("Cannot Initialize new GD image stream");
        	$background_color 	= 	imagecolorallocate($im,  190, 190, 190);//couleur du background ici gris
        	$text_color 		= 	imagecolorallocate($im, 0, 0, 0);//couleur du text ici gris
        	imagestring($im, 1, 5, 5,  $text, $text_color);//construire l'image
            imagejpeg($im,$path,"80");

            return $path;
        }

	}

}
?>