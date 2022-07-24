<?php
$access_token = 'vJdzjrUAxybexlcIhApBv8hS0XZHICcF7poHIcSGaVGgHK4/xiYkWs1FCyl9LQRy3Mwh9MIwmvZg/n/0pRxDZLDsCES76NChzi3eAeEhiP0HczzHv/L2SI6eInqZeSkr68e/zwRVeuwD8EGY5d2yfQdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);

function testMakeFunction($one){
	if($one == 1){
		$tell = 'yes its work' ;
	} else {
		$tell = 'nooooooo' ;
	}
	return $tell;
}

function grab_image($url,$saveto){
	error_log('url = ' . $url,0);
	error_log('saveto = ' . $saveto,0);
    $ch = curl_init ($url);
	error_log('ch = ' . $ch,0);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $raw=curl_exec($ch);
	echo($raw);
	error_log('raw = ' . $raw,0);
    curl_close ($ch);
    if(file_exists($saveto)){
        unlink($saveto);
    }
    $fp = fopen($saveto,'x');
	error_log('fp = ' . $fp,0);
    fwrite($fp, $raw);
    fclose($fp);
	return $saveto;
}

function imageCreateFromAny($filepath,$img) { 
    $type = getimagesize($filepath); // [] if you don't have exif you could use getImageSize() 
	error_log('type = ' . $type . ' url = ' . $filepath . ' img = ' . $img);
    $allowedTypes = array( 
        1,  // [] gif 
        2,  // [] jpg 
        3  // [] png 
    ); 
    if (!in_array($type, $allowedTypes)) { 
        return false; 
    } 
    switch ($type) { 
        case 1 : 
            $im = imageCreateFromGif($img); 
        break; 
        case 2 : 
            $im = imageCreateFromJpeg($img); 
        break; 
        case 3 : 
            $im = imageCreateFromPng($img); 
        break;
    }    
    return $im;  
} 


//from http://php.net/manual/en/function.imagejpeg.php comment 1
function scaleImageFileToBlob($file) {

    $source_pic = $file;
    $max_width = 200;
    $max_height = 200;

    list($width, $height, $image_type) = getimagesize($file);

    switch ($image_type)
    {
        case 1: $src = imagecreatefromgif($file); break;
        case 2: $src = imagecreatefromjpeg($file);  break;
        case 3: $src = imagecreatefrompng($file); break;
        default: return '';  break;
    }

    $x_ratio = $max_width / $width;
    $y_ratio = $max_height / $height;

    if( ($width <= $max_width) && ($height <= $max_height) ){
        $tn_width = $width;
        $tn_height = $height;
        }elseif (($x_ratio * $height) < $max_height){
            $tn_height = ceil($x_ratio * $height);
            $tn_width = $max_width;
        }else{
            $tn_width = ceil($y_ratio * $width);
            $tn_height = $max_height;
    }

    $tmp = imagecreatetruecolor($tn_width,$tn_height);

    /* Check if this image is PNG or GIF, then set if Transparent*/
    if(($image_type == 1) OR ($image_type==3))
    {
        imagealphablending($tmp, false);
        imagesavealpha($tmp,true);
        $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
        imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
    }
    imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);

    /*
     * imageXXX() only has two options, save as a file, or send to the browser.
     * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
     * So I start the output buffering, use imageXXX() to output the data stream to the browser, 
     * get the contents of the stream, and use clean to silently discard the buffered contents.
     */
    ob_start();

    switch ($image_type)
    {
        case 1: imagegif($tmp); break;
        case 2: imagejpeg($tmp, NULL, 100);  break; // best quality
        case 3: imagepng($tmp, NULL, 0); break; // no compression
        default: echo ''; break;
    }

    $final_image = ob_get_contents();

    ob_end_clean();

    return $final_image;
}

function inRangeInnerCircle($r,$g,$b){
	$tf = false;
	if($r >= 242 && $r <= 255 && $g >= 150 && $g <= 255 && $b >= 25 && $b <= 117){
		$tf = true;
	}
	return $tf;
}

function inRangeOuterCircle($r,$g,$b){
	$tf = false;
	if($r >= 200 && $r <= 255 && $g >= 100 && $g <= 150 && $b >= 18 && $b <= 80){
		$tf = true;
	}
	return $tf;
}

function inRangeBloodVessel($r,$g,$b){
	$tf = false;
	if($r >= 70 && $r <= 255 && $g >= 15 && $g <= 95 && $b >= 3 && $b <= 25){
		$tf = true;
	}
	return $tf;
}

function inRangeOuterEye($r,$g,$b){
	$tf = false;
	if($r >= 69 && $r <= 224 && $g >= 29 && $g <= 95 && $b >= 1 && $b <= 30){
		$tf = true;
	}
	return $tf;
}

function inRangeGreyZone($r,$g,$b){
	$tf = false;
	/*if($r >= 129 && $r <= 156 && $g >= 80 && $g <= 120 && $b >= 31 && $b <= 48){
		$tf = true;
	}*/
	if($r >= 129 && $r <= 196 && $g >= 80 && $g <= 135 && $b >= 31 && $b <= 60){
		$tf = true;
	}
	return $tf;
}

function inRangeBloodArea($r,$g,$b){
	$tf = false;
	if($r >= 125 && $r <= 136 && $g >= 1 && $g <= 8 && $b >= 1 && $b <= 8){
		$tf = true;
	}
	return $tf;
}

function checkCircleRatio($img,$width,$height){
	$tf = false;
	$countOut = 0;
	$countOutM = 0;
	//$countOutDown = 0;
	$countIn = 0;
	$countInM = 0;
	$ratio = 0;
	$countBlood = 0;
	for($y = 0; $y < $height; $y++) {
		for($x = 0; $x < $width; $x++) {
			// pixel color at (x, y)
			$rgb = imagecolorat($img, $x, $y);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			//error_log('r g b = ' . $r . ' ' . $g . ' ' . $b . ' rgb = ' . $rgb, 0);
			if(inRangeOuterCircle((int)$r,(int)$g,(int)$b)){
				for($i = $y; $i<($y+($height/2)) ; $i++){
					$rgb = imagecolorat($img, $x, $i);
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;
					if(inRangeOuterCircle((int)$r,(int)$g,(int)$b)){
						$countOut = $countOut + 1;
					} else if(inRangeBloodVessel((int)$r,(int)$g,(int)$b)) {
						$countBlood = 0;
						for($i = $y; $i<($y + 100) ; $i++){
							$rgb = imagecolorat($img, $x, $i);
							$r = ($rgb >> 16) & 0xFF;
							$g = ($rgb >> 8) & 0xFF;
							$b = $rgb & 0xFF;
							$countBlood = $countBlood + 1;
						}
						if($countBlood < 90){
							$countOut = $countOut + 1;
						} else{
							break 1;
						}
					} else if(inRangeInnerCircle((int)$r,(int)$g,(int)$b)){
						break 1;
					}
				//	$countOutM = max($countOutM,$countOut);
				}
		//		error_log('loopppp Out',0);
				$countOutM = max($countOutM,$countOut);
			}
			if(inRangeInnerCircle((int)$r,(int)$g,(int)$b)){
				for($i = $y ;$i<($y+($height/2)) ; $i++){
					$rgb = imagecolorat($img, $x, $i);
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;
					if(inRangeInnerCircle((int)$r,(int)$g,(int)$b)){
						$countIn = $countIn + 1;
					} else if(inRangeBloodVessel((int)$r,(int)$g,(int)$b)) {
						$countBlood = 0;
						for($i = $y; $i<($y + 100) ; $i++){
							$rgb = imagecolorat($img, $x, $i);
							$r = ($rgb >> 16) & 0xFF;
							$g = ($rgb >> 8) & 0xFF;
							$b = $rgb & 0xFF;
							$countBlood = $countBlood + 1;
						}
						if($countBlood < 90){
							$countIn = $countIn + 1;
						} else{
							break 1;
						}
					} else if(inRangeOuterCircle((int)$r,(int)$g,(int)$b)){
						break 1;
					}
					//$countInM = max($countInM,$countIn);
				}
		//		error_log('loopppp In',0);
				$countInM = max($countInM,$countIn);
			}
		}
	}
	$countOuter = 0;
	$countOuter = $countOutM + $countInM;
	$ratio = $countInM/$countOuter;
	if($ratio >= 0.5){
		$tf = true;
	}
	//return $tf;
	return $ratio;
}

function checkSizeCircle($img,$width,$height){
	$tf = false;
	$count = 0;
//	$ratio = 0;
	for($x = 0; $x < $width; $x++) {
		for($y = 0; $y < $height; $y++) {
			// pixel color at (x, y)
			$rgb = imagecolorat($img, $x, $y);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			//error_log('r g b = ' . $r . ' ' . $g . ' ' . $b . ' rgb = ' . $rgb, 0);
			if(inRangeInnerCircle((int)$r,(int)$g,(int)$b) || inRangeOuterCircle((int)$r,(int)$g,(int)$b)){
				$count = $count + 1;
			}
		}
	}
//	$ratio = $height/6;
	if($count <= 30000){
		$tf = true;
	}
	//return $tf;
	return $count;
}

function checkGreyArea($img,$width,$height){
	$tf = false;
	$count = 0;
	for($x = 0; $x < $width; $x++) {
		for($y = 0; $y < $height; $y++) {
			// pixel color at (x, y)
			$rgb = imagecolorat($img, $x, $y);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			//error_log('r g b = ' . $r . ' ' . $g . ' ' . $b . ' rgb = ' . $rgb, 0);
			if(inRangeGreyZone((int)$r,(int)$g,(int)$b)){
				$count = $count + 1;
			}
		}
	}
	if($count >= 30769){
		$tf = true;
	}
	//return $tf;
	return $count;
}

function checkBloodArea($img,$width,$height){
	$tf = false;
	$countX = 0;
	$countY = 0;
	for($x = 0; $x < $width; $x++) {
		for($y = 0; $y < $height; $y++) {
			// pixel color at (x, y)
			$rgb = imagecolorat($img, $x, $y);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			//error_log('r g b = ' . $r . ' ' . $g . ' ' . $b . ' rgb = ' . $rgb, 0);
			if(inRangeBloodArea((int)$r,(int)$g,(int)$b)){
				for($i = $x; $i<($x+190) ; $i++){
					$rgb = imagecolorat($img, $i, $y);
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;
					if(inRangeBloodArea((int)$r,(int)$g,(int)$b)){
						$countX++;
					}
				}
				for($i = $y; $i<($y+200) ; $i++){
					$rgb = imagecolorat($img, $x, $i);
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;
					if(inRangeBloodArea((int)$r,(int)$g,(int)$b)){
						$countY++;
					}
				}
				if($countX >= 188 && $countY >= 195){
					$tf = true;
				}
			}
		}
	}
	return $tf;
}

// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		if ($event['type'] == 'message' && $event['message']['type'] == 'image') {
			// Get text sent
			//$image = $event['message']['image'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			$imageId = $event['message']['id'];

			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$urlIm = 'https://api.line.me/v2/bot/message/' . $imageId . '/content';							
	
			$ch = curl_init ($urlIm);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$raw=curl_exec($ch);
			echo curl_error($ch);
			curl_close ($ch);
			$save_to = ' ';
			if(file_exists($save_to)){
				unlink($save_to);
			}
			$fp = fopen($save_to,'x');
			fwrite($fp, $raw);
			fclose($fp);


			error_log('save_to = ' . $save_to,0);
			$ch = $save_to;

			$img = imagecreatefromjpeg($ch); // resource id = xxx ;
			error_log('ch = ' . $ch . 'img = ' . $img, 0);

			$width = imagesx($img);
			$height = imagesy($img);
			error_log('w = ' . $width . ' h = ' . $height,0);

			$cga = 0;
			$tfg = checkGreyArea($img,$width,$height);
			$cga = $tfg;

			$tfg = false;
			if($cga >= 30000){
				$tfg = true;
			}
			$ba = checkBloodArea($img,$width,$height);
			if(!$ba){
				$say = 'ไม่มีเลือดออกในดวงตา';
			} else {
				$say = 'มีเลือดออกในดวงตา';
			}
			$sc = checkSizeCircle($img,$width,$height);
			$cr = checkCircleRatio($img,$width,$height);
			$tfsc = false;
			$tfcr = false;
			if($sc <= 30000){
				$tfsc = true;
			}
			if($cr >= 0.5){
				$tfcr = true;
			}
			if($cr > 1){
				$cr = 1;
			}
			$talk = 'grey zone' . "\n" . '(อาจเกิดจากเงาในรูปที่ส่ง หากมีเงา กรุณาถ่ายใหม่ ขอบคุณครับ)' . "\n" . '*หากมีสีของดวงตาที่ซีด/เทากว่าปกติ ค่านี้อาจจะออกมาเยอะแม้ว่าจะไม่เป็นโรค เพื่อความมั่นใจกรุณาตรวจสอบกับแพทย์ ขอบคุณครับ' . "\n" . '**หากมากกว่า 30000 มีโอกาสเป็นโรค ครับ' . "\n" . ' = ' . $cga . "\n" . "\n" . 'blood area = ' . $say . "\n" . '*หากมีเลือดออกในดวงตา มักจะเป็นโรคต้อหินครับ' . "\n" . "\n" . 'size of circle(small 30000) = ' . $sc . "\n" . '*หากมีขนาดเล็ก(น้อยกว่า 30000) จะมีโอกาสเป็นโรคครับ' . "\n" . "\n" . 'ratio of inner/outer = ' . $cr . "\n" . '*หากมากกว่าหรือเท่ากับ 0.5 จะมีโอกาสเป็นโรคครับ' . "\n";
			

			if($tfg || $ba || $tfsc || $tfcr){
				$ans = 'มีโอกาสเป็นโรค';
			}else{
				$ans = 'ปกติ';
			}

			$talk = $talk . "\n" . 'จากข้อมูลที่ท่านให้มา คาดว่า ' . $ans;


			if(empty($img)){
				$talk = 'no pic';
			}
			
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $talk
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
				//'messages' => [$image],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
		// Reply only when message sent is in 'text' format
		else if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			if($text == 'functiontest'){
				$number = 1;
				$test1 = testMakeFunction($number);
				$messages = [
				'type' => 'text',
				'text' => $test1
				];
			}
			else if($text == 'normal1'){

			
				$urlIm = 'https://image.ibb.co/jNTDNH/normal1.jpg';
				$data = getimagesize($urlIm);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log($talk , 0);

				$img = imagecreatefromjpeg($urlIm); // resource id = xxx ;
		
				$cga = 0;
				$tfg = checkGreyArea($img,$width,$height);
				$cga = $tfg;

				$tfg = false;
				if($cga >= 30000){
					$tfg = true;
				}
				$ba = checkBloodArea($img,$width,$height);
				if(!$ba){
					$say = 'ไม่มีเลือดออกในดวงตา';
				} else {
					$say = 'มีเลือดออกในดวงตา';
				}
				$sc = checkSizeCircle($img,$width,$height);
				$cr = checkCircleRatio($img,$width,$height);
				$tfsc = false;
				$tfcr = false;
				if($sc <= 30000){
					$tfsc = true;
				}
				if($cr >= 0.5){
					$tfcr = true;
				}
				if($cr > 1){
					$cr = 1;
				}
				$talk = 'grey zone' . "\n" . '(อาจเกิดจากเงาในรูปที่ส่ง หากมีเงา กรุณาถ่ายใหม่ ขอบคุณครับ)' . "\n" . '*หากมีสีของดวงตาที่ซีด/เทากว่าปกติ ค่านี้อาจจะออกมาเยอะแม้ว่าจะไม่เป็นโรค เพื่อความมั่นใจกรุณาตรวจสอบกับแพทย์ ขอบคุณครับ' . "\n" . '**หากมากกว่า 30000 มีโอกาสเป็นโรค ครับ' . "\n" . ' = ' . $cga . "\n" . "\n" . 'blood area = ' . $say . "\n" . '*หากมีเลือดออกในดวงตา มักจะเป็นโรคต้อหินครับ' . "\n" . "\n" . 'size of circle(small 30000) = ' . $sc . "\n" . '*หากมีขนาดเล็ก(น้อยกว่า 30000) จะมีโอกาสเป็นโรคครับ' . "\n" . "\n" . 'ratio of inner/outer = ' . $cr . "\n" . '*หากมากกว่าหรือเท่ากับ 0.5 จะมีโอกาสเป็นโรคครับ' . "\n";
			
				if($tfg || $ba || $tfsc || $tfcr){
					$ans = 'มีโอกาสเป็นโรค';
				}else{
					$ans = 'ปกติ';
				}

				$talk = $talk . "\n" . 'จากข้อมูลที่ท่านให้มา คาดว่า ' . $ans;
					
				$messages = [
				'type' => 'text',
				'text' => $talk
				];
			
			}else if($text == 'normal2'){
				$urlIm = 'https://image.ibb.co/j0sCGc/normal2.jpg';
				$data = getimagesize($urlIm);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log($talk , 0);

				$img = imagecreatefromjpeg($urlIm);
				$cga = 0;
				$tfg = checkGreyArea($img,$width,$height);
				$cga = $tfg;

				$tfg = false;
				if($cga >= 30000){
					$tfg = true;
				}
				$ba = checkBloodArea($img,$width,$height);
				if(!$ba){
					$say = 'ไม่มีเลือดออกในดวงตา';
				} else {
					$say = 'มีเลือดออกในดวงตา';
				}
				$sc = checkSizeCircle($img,$width,$height);
				$cr = checkCircleRatio($img,$width,$height);
				$tfsc = false;
				$tfcr = false;
				if($sc <= 30000){
					$tfsc = true;
				}
				if($cr >= 0.5){
					$tfcr = true;
				}
				if($cr > 1){
					$cr = 1;
				}
				$talk = 'grey zone' . "\n" . '(อาจเกิดจากเงาในรูปที่ส่ง หากมีเงา กรุณาถ่ายใหม่ ขอบคุณครับ)' . "\n" . '*หากมีสีของดวงตาที่ซีด/เทากว่าปกติ ค่านี้อาจจะออกมาเยอะแม้ว่าจะไม่เป็นโรค เพื่อความมั่นใจกรุณาตรวจสอบกับแพทย์ ขอบคุณครับ' . "\n" . '**หากมากกว่า 30000 มีโอกาสเป็นโรค ครับ' . "\n" . ' = ' . $cga . "\n" . "\n" . 'blood area = ' . $say . "\n" . '*หากมีเลือดออกในดวงตา มักจะเป็นโรคต้อหินครับ' . "\n" . "\n" . 'size of circle(small 30000) = ' . $sc . "\n" . '*หากมีขนาดเล็ก(น้อยกว่า 30000) จะมีโอกาสเป็นโรคครับ' . "\n" . "\n" . 'ratio of inner/outer = ' . $cr . "\n" . '*หากมากกว่าหรือเท่ากับ 0.5 จะมีโอกาสเป็นโรคครับ' . "\n";
			
				if($tfg || $ba || $tfsc || $tfcr){
					$ans = 'มีโอกาสเป็นโรค';
				}else{
					$ans = 'ปกติ';
				}

				$talk = $talk . "\n" . 'จากข้อมูลที่ท่านให้มา คาดว่า ' . $ans;

				$messages = [
				'type' => 'text',
				'text' => $talk
				];
			
			}else if($text == 'normal3'){
				$urlIm = 'https://image.ibb.co/jNbuUx/normal3.jpg';
				$data = getimagesize($urlIm);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log($talk , 0);

				$img = imagecreatefromjpeg($urlIm);
				$cga = 0;
				$tfg = checkGreyArea($img,$width,$height);
				$cga = $tfg;

				$tfg = false;
				if($cga >= 30000){
					$tfg = true;
				}
				$ba = checkBloodArea($img,$width,$height);
				if(!$ba){
					$say = 'ไม่มีเลือดออกในดวงตา';
				} else {
					$say = 'มีเลือดออกในดวงตา';
				}
				$sc = checkSizeCircle($img,$width,$height);
				$cr = checkCircleRatio($img,$width,$height);
				$tfsc = false;
				$tfcr = false;
				if($sc <= 30000){
					$tfsc = true;
				}
				if($cr >= 0.5){
					$tfcr = true;
				}
				if($cr > 1){
					$cr = 1;
				}
				$talk = 'grey zone' . "\n" . '(อาจเกิดจากเงาในรูปที่ส่ง หากมีเงา กรุณาถ่ายใหม่ ขอบคุณครับ)' . "\n" . '*หากมีสีของดวงตาที่ซีด/เทากว่าปกติ ค่านี้อาจจะออกมาเยอะแม้ว่าจะไม่เป็นโรค เพื่อความมั่นใจกรุณาตรวจสอบกับแพทย์ ขอบคุณครับ' . "\n" . '**หากมากกว่า 30000 มีโอกาสเป็นโรค ครับ' . "\n" . ' = ' . $cga . "\n" . "\n" . 'blood area = ' . $say . "\n" . '*หากมีเลือดออกในดวงตา มักจะเป็นโรคต้อหินครับ' . "\n" . "\n" . 'size of circle(small 30000) = ' . $sc . "\n" . '*หากมีขนาดเล็ก(น้อยกว่า 30000) จะมีโอกาสเป็นโรคครับ' . "\n" . "\n" . 'ratio of inner/outer = ' . $cr . "\n" . '*หากมากกว่าหรือเท่ากับ 0.5 จะมีโอกาสเป็นโรคครับ' . "\n";
			
				if($tfg || $ba || $tfsc || $tfcr){
					$ans = 'มีโอกาสเป็นโรค';
				}else{
					$ans = 'ปกติ';
				}

				$talk = $talk . "\n" . 'จากข้อมูลที่ท่านให้มา คาดว่า ' . $ans;

				$messages = [
				'type' => 'text',
				'text' => $talk
				];
			
			}else if($text == 'glau1'){
				$urlIm = 'https://image.ibb.co/fasVwn/glau1.jpg';
				$data = getimagesize($urlIm);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log($talk , 0);

				$img = imagecreatefromjpeg($urlIm); // resource id = xxx ;
				error_log('img url = ' . $urlIm , 0);
				
				$cga = 0;
				$tfg = checkGreyArea($img,$width,$height);
				$cga = $tfg;

				$tfg = false;
				if($cga >= 30000){
					$tfg = true;
				}
				$ba = checkBloodArea($img,$width,$height);
				if(!$ba){
					$say = 'ไม่มีเลือดออกในดวงตา';
				} else {
					$say = 'มีเลือดออกในดวงตา';
				}
				$sc = checkSizeCircle($img,$width,$height);
				$cr = checkCircleRatio($img,$width,$height);
				$tfsc = false;
				$tfcr = false;
				if($sc <= 30000){
					$tfsc = true;
				}
				if($cr >= 0.5){
					$tfcr = true;
				}
				if($cr > 1){
					$cr = 1;
				}
				$talk = 'grey zone' . "\n" . '(อาจเกิดจากเงาในรูปที่ส่ง หากมีเงา กรุณาถ่ายใหม่ ขอบคุณครับ)' . "\n" . '*หากมีสีของดวงตาที่ซีด/เทากว่าปกติ ค่านี้อาจจะออกมาเยอะแม้ว่าจะไม่เป็นโรค เพื่อความมั่นใจกรุณาตรวจสอบกับแพทย์ ขอบคุณครับ' . "\n" . '**หากมากกว่า 30000 มีโอกาสเป็นโรค ครับ' . "\n" . ' = ' . $cga . "\n" . "\n" . 'blood area = ' . $say . "\n" . '*หากมีเลือดออกในดวงตา มักจะเป็นโรคต้อหินครับ' . "\n" . "\n" . 'size of circle(small 30000) = ' . $sc . "\n" . '*หากมีขนาดเล็ก(น้อยกว่า 30000) จะมีโอกาสเป็นโรคครับ' . "\n" . "\n" . 'ratio of inner/outer = ' . $cr . "\n" . '*หากมากกว่าหรือเท่ากับ 0.5 จะมีโอกาสเป็นโรคครับ' . "\n";
			
				if($tfg || $ba || $tfsc || $tfcr){
					$ans = 'มีโอกาสเป็นโรค';
				}else{
					$ans = 'ปกติ';
				}

				$talk = $talk . "\n" . 'จากข้อมูลที่ท่านให้มา คาดว่า ' . $ans;

				$messages = [
				'type' => 'text',
				'text' => $talk
				];
			
			}else if($text == 'glau2'){
				$urlIm = 'https://image.ibb.co/n324wc/glau2.jpg';
				$data = getimagesize($urlIm);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log($talk , 0);

				$img = imagecreatefromjpeg($urlIm);
				$cga = 0;
				$tfg = checkGreyArea($img,$width,$height);
				$cga = $tfg;

				$tfg = false;
				if($cga >= 30000){
					$tfg = true;
				}
				$ba = checkBloodArea($img,$width,$height);
				if(!$ba){
					$say = 'ไม่มีเลือดออกในดวงตา';
				} else {
					$say = 'มีเลือดออกในดวงตา';
				}
				$sc = checkSizeCircle($img,$width,$height);
				$cr = checkCircleRatio($img,$width,$height);
				$tfsc = false;
				$tfcr = false;
				if($sc <= 30000){
					$tfsc = true;
				}
				if($cr >= 0.5){
					$tfcr = true;
				}
				if($cr > 1){
					$cr = 1;
				}
				$talk = 'grey zone' . "\n" . '(อาจเกิดจากเงาในรูปที่ส่ง หากมีเงา กรุณาถ่ายใหม่ ขอบคุณครับ)' . "\n" . '*หากมีสีของดวงตาที่ซีด/เทากว่าปกติ ค่านี้อาจจะออกมาเยอะแม้ว่าจะไม่เป็นโรค เพื่อความมั่นใจกรุณาตรวจสอบกับแพทย์ ขอบคุณครับ' . "\n" . '**หากมากกว่า 30000 มีโอกาสเป็นโรค ครับ' . "\n" . ' = ' . $cga . "\n" . "\n" . 'blood area = ' . $say . "\n" . '*หากมีเลือดออกในดวงตา มักจะเป็นโรคต้อหินครับ' . "\n" . "\n" . 'size of circle(small 30000) = ' . $sc . "\n" . '*หากมีขนาดเล็ก(น้อยกว่า 30000) จะมีโอกาสเป็นโรคครับ' . "\n" . "\n" . 'ratio of inner/outer = ' . $cr . "\n" . '*หากมากกว่าหรือเท่ากับ 0.5 จะมีโอกาสเป็นโรคครับ' . "\n";
			
				if($tfg || $ba || $tfsc || $tfcr){
					$ans = 'มีโอกาสเป็นโรค';
				}else{
					$ans = 'ปกติ';
				}

				$talk = $talk . "\n" . 'จากข้อมูลที่ท่านให้มา คาดว่า ' . $ans;

				$messages = [
				'type' => 'text',
				'text' => $talk
				];
			
			}else if($text == 'glau3'){
				$urlIm = 'https://image.ibb.co/e4TQU7/glau3.jpg';
				$data = getimagesize($urlIm);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log($talk , 0);

				$img = imagecreatefromjpeg($urlIm);
				$cga = 0;
				$tfg = checkGreyArea($img,$width,$height);
				$cga = $tfg;

				$tfg = false;
				if($cga >= 30000){
					$tfg = true;
				}
				$ba = checkBloodArea($img,$width,$height);
				if(!$ba){
					$say = 'ไม่มีเลือดออกในดวงตา';
				} else {
					$say = 'มีเลือดออกในดวงตา';
				}
				$sc = checkSizeCircle($img,$width,$height);
				$cr = checkCircleRatio($img,$width,$height);
				$tfsc = false;
				$tfcr = false;
				if($sc <= 30000){
					$tfsc = true;
				}
				if($cr >= 0.5){
					$tfcr = true;
				}
				if($cr > 1){
					$cr = 1;
				}
				$talk = 'grey zone' . "\n" . '(อาจเกิดจากเงาในรูปที่ส่ง หากมีเงา กรุณาถ่ายใหม่ ขอบคุณครับ)' . "\n" . '*หากมีสีของดวงตาที่ซีด/เทากว่าปกติ ค่านี้อาจจะออกมาเยอะแม้ว่าจะไม่เป็นโรค เพื่อความมั่นใจกรุณาตรวจสอบกับแพทย์ ขอบคุณครับ' . "\n" . '**หากมากกว่า 30000 มีโอกาสเป็นโรค ครับ' . "\n" . ' = ' . $cga . "\n" . "\n" . 'blood area = ' . $say . "\n" . '*หากมีเลือดออกในดวงตา มักจะเป็นโรคต้อหินครับ' . "\n" . "\n" . 'size of circle(small 30000) = ' . $sc . "\n" . '*หากมีขนาดเล็ก(น้อยกว่า 30000) จะมีโอกาสเป็นโรคครับ' . "\n" . "\n" . 'ratio of inner/outer = ' . $cr . "\n" . '*หากมากกว่าหรือเท่ากับ 0.5 จะมีโอกาสเป็นโรคครับ' . "\n";
			
				if($tfg || $ba || $tfsc || $tfcr){
					$ans = 'มีโอกาสเป็นโรค';
				}else{
					$ans = 'ปกติ';
				}

				$talk = $talk . "\n" . 'จากข้อมูลที่ท่านให้มา คาดว่า ' . $ans;

				$messages = [
				'type' => 'text',
				'text' => $talk
				];
			
			}
			// Build message to reply back
			else if($text == 'ร้องขอการลงทะเบียน') {
			$messages = [
				'type' => 'text',
				'text' => 'https://goo.gl/forms/gf1EF2X9kVdCjDR32'
			];
			} else if($text == 'ขอรับlinkกรอกข้อมูลอาการ') {
			$messages = [
				'type' => 'text',
				'text' => 'https://goo.gl/forms/Cd0medoTwiKYGgKv2'
			];
			} else if($text == 'ขอดูผลการวินิจฉัย') {
			$messages = [
				'type' => 'text',
				'text' => 'ผลการวินิจฉัย ณ ปัจจุบัน แพทย์จะส่งทางอีเมลล์ที่ท่านลงทะเบียนไว้ ภายใน 7 วันทำการ ขอบคุณครับ'
			];
			} else if($text == 'ขอรายชื่อโรงพยาบาลที่เกี่ยวข้อง') {
			$messages = [
				'type' => 'text',
				'text' => 'โรงพยาบาล A เบอร์ติดต่อ 02-000-0000                   โรงพยาบาล B เบอร์ติดต่อ 02-111-1111                       โรงพยาบาล C เบอร์ติดต่อ 02-456-8795                   โรงพยาบาล D เบอร์ติดต่อ 02-789-4561'
			];
			} else if($text == 'ปัจจัยเสี่ยงของต้อหิน') {
			$messages = [
				'type' => 'text',
				'text' => 'ปัจจัยเสี่ยงของโรคต้อหินนั้น จะแบ่งออกเป็น 2 ลักษณะ คือ ต้อหินเฉียบพลัน กับ ต้อหินเรื้องรัง                                          ต้อหินเฉียบพลันจะมีปัจจัยเสี่ยงโดยคร่าวๆ ดังนี้                                           1. เป็นผู้หญิง                                                                                     2. เป็นผู้มีเชื้อสายเอเซีย                                                              3. อายุมากกว่า40ปี                                                               4. มีสายตายาว                                                               5. ครอบครัวมีประวัติเคยเป็นโรคนี้                                                               ต้อหินเรื้อรังจะมีปัจจัยเสี่ยงโดยคร่าวๆ ดังนี้                                                              1. มีเชื้อสายแอฟริกัน                                                              2. เป็นโรคเรื้อรังบางประเภท เช่น โรคหัวใจ โรคความดันโลหิตสูง                                                               3. เป็นโรคเบาหวาน                                                               4. มีสายตาสั้น                                                               5. ครอบครัวมีประวัติเคยเป็นโรคนี้                                                               6. ความดันลูกตาสูงผิดปกติ                                                               7. กระจกตาบางกว่าปกติ                                                               8. เคยได้รับการผ่าตัดดวงตา                                                               9. เคยได้รับการรักษาโรคเรื้อรังทางดวงตา                                                               10. เคยได้รับอุบัติเหตุทางตา                                                               11. เคยมีประวัติการใช้งานยาหยอดตาและยารับประทานบางชนิด โดยเฉพาะยาสเตียรอยด์                                                                ข้อมูลเพิ่มเติม : https://medthai.com/ต้อหิน/                                                                อ้างอิง : https://medthai.com/ต้อหิน/'
			];
			} 
			 else if($text == 'ขอทราบวิธีการใช้งาน App') {
				$talk = 'ในการใช้งาน Glaucoma checker bot นั้น มีวิธีใช้งาน ดังนี้' . "\n" . "\n" . '1. หากต้องการตรวจสอบเบื้องต้นว่าเป็นโรคต้อหินหรือไม่ กรุณาถ่ายภาพดวงตาของท่านด้วยอุปกรณ์ แล้วอัพโหลดรูปลงในไลน์บอทนี้' . "\n" . "\n" . '2. หากบอทได้ตอบกลับว่า "มีโอกาสเป็นโรค" หรือ ท่านต้องการตรวจเช็คอีกรอบกับแพทย์ กรุณากดที่ปุ่ม "ร้องขอการลงทะเบียน" เพื่อที่บอทจะดำเนินการส่งเข้า google form สำหรับกรอกข้อมูลการลงทะเบียนให้กับท่าน หลังจากนั้นกรุณากดที่ปุ่ม "กรอกอาการ" เพื่อกรอกอาการป่วย หรือรายละเอียดต่างๆ เพื่อส่งให้แพทย์ต่อไป แต่หากท่านเคยลงทะเบียนแล้ว ท่านสามารถกดที่ปุ่ม "กรอกอาการ" แล้วไปบันทึกข้อมูลเพื่อที่จะส่งให้แพทย์ได้เลย ' . "\n" . "\n" . '3. ปุ่ม "ขอดูผลการวินิจฉัย" ยังไม่สามารถใช้งานได้ในขณะนี้ หากท่านต้องการทราบผลการวินิจฉัย แพทย์จะส่งไปทางอีเมลล์ที่ท่านลงทะเบียนไว้ ภายใน 7 วันทำการ' . "\n" . "\n" . '4. หากท่านต้องการทราบรายชื่อและเบอร์ติดต่อโรงพยาบาลที่เกี่ยวข้อง กรุณากดที่ปุ่ม "โรงพยาบาลที่เกี่ยวข้อง" ' . "\n" . "\n" . '5. หากท่านต้องการรายละเอียด ปัจจัยเสี่ยงของต้อหิน กรุณากดที่ปุ่ม "ขอทราบปัจจัยเสี่ยง" ' . "\n" . "\n" . '6. ณ ปัจจุบัน ไลน์บอทนี้ ไม่สามารถตรวจสอบรูปที่มีเงาอยู่มากได้ เพราะข้อมูลอาจจะคลาดเคลื่อน และหากรูป มีสีที่เข้ม หรือ อ่อน กว่าปกติ ข้อมูลอาจจะคลาดเคลื่อนได้ รวมไปถึง ความคลาดเคลื่อนของไลน์บอทเอง จึงต้องขออภัย มา ณ ที่นี้ด้วยครับ';
			$messages = [
				'type' => 'text',
				'text' => $talk
			];
			} else {
			$messages = [
				'type' => 'text',
				'text' => 'หากมีคำถาม หรือต้องการใช้บริการอะไร กรุณากดปุ่มใน App Menu หรือหากต้องการตรวจต้อหินเบื้องต้น กรุณาส่งรูปภาพ ขอบคุณครับ'
			];
			}

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
		
	}
}
echo "OK";