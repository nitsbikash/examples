<?php
$valid_exts = array('jpeg', 'jpg', 'png', 'gif');
$max_file_size = 1024 * 1024; #200kb
$nw = $nh = 400; # image with # height

echo "<pre>"; print_r($_POST); 
echo "<pre>"; print_r($_FILES); 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ( isset($_FILES['image']) ) {
		if (! $_FILES['image']['error'] && $_FILES['image']['size'] < $max_file_size) {
			$ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
			if (in_array($ext, $valid_exts)) {
					$path = 'uploads/' . uniqid() . '.' . $ext;
					$size = getimagesize($_FILES['image']['tmp_name']);

					$x = (int) $_POST['x'];
					$y = (int) $_POST['y'];
					$w = (int) $_POST['w'] ? $_POST['w'] : $size[0];
					$h = (int) $_POST['h'] ? $_POST['h'] : $size[1];

					$data = file_get_contents($_FILES['image']['tmp_name']);
					$vImg = imagecreatefromstring($data);
					$dstImg = imagecreatetruecolor($nw, $nh);
					imagecopyresampled($dstImg, $vImg, 0, 0, $x, $y, $nw, $nh, $w, $h);
					imagejpeg($dstImg, $path);
					imagedestroy($dstImg);
					echo "<img src='$path' />";
					
				} else {
					echo 'unknown problem!';
				} 
		} else {
			echo 'file is too small or large';
		}
	} else {
		echo 'file not set';
	}
} else {
	echo 'bad request!';
}

?>