<?php

namespace App\Helper;


use App\Events\ActivityLog;
use App\Model\Order;
use App\Model\OrderedProduct;
use App\Model\Setting;
use App\Model\UserPermission;
use App\Model\Withdrawal;
use Auth;
use DataTables;
use phpDocumentor\Reflection\Types\This;
use Request;
use Validator;

class AppHelper {
	
	/**
	 * JSON response for success type response.
	 *
	 * @param string $message
	 * @param array  $data
	 * @param int    $code
	 * @return \Illuminate\Http\JsonResponse
	 */
	public static function RespondWithSuccess($message = '', $data = [], $code = 200) {
		return response()->json([
			'status' => 'success',
			'type' => 'success',
			'status_code' => $code,
			'message' => $message,
			'data' => $data,
		], $code);
	}
	
	/**
	 * JSON response for error type response.
	 *
	 * @param string $message
	 * @param array  $data
	 * @param int    $code
	 * @return \Illuminate\Http\JsonResponse
	 */
	public static function RespondWithError($message = '', $data = [], $code = 401) {
		return response()->json([
			'status' => 'error',
			'type' => 'error',
			'status_code' => $code,
			'message' => $message,
			'data' => $data,
		], $code);
	}
	
	public static function ValidateData($data = [], $rules = [], $messages = []) {
		$validator = Validator::make($data, $rules, $messages);
		
		
		if ($validator->fails()) {
			/*return AppHelper::RespondWithError(
				$validator->errors()->first()
			);*/
			return $validator->errors()->first();
		} else {
			/*return AppHelper::RespondWithSuccess(
				'Data Validated.',
				$data
			);*/
			return TRUE;
		}
	}
	
	public static function getExtension($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; }
		$l = strlen($str) - $i;
		$ext = substr($str, $i+1, $l);
		return $ext;
	}
	
	
	public static function generate_webp($images){
		$images = explode('/',$images);
		$image_name = end($images);
		$removed = array_pop($images);
		$directory = implode('/',$images);

		$extension = self::getExtension($image_name);
		$image_name_without_extension = str_replace('.'.$extension,'',$image_name);
		$image_name_without_extension = str_replace($directory.'/','',$image_name_without_extension);
		
		$destination_image = $directory.'/'.$image_name_without_extension.'.webp';
		$source_image = $directory.'/'.$image_name;
		
		if (file_exists($destination_image) == false) {
			switch ($extension) {
				case "jpg":
				case "jpeg":
				case "JPG":
				case "JPEG":
					$source_image = imagecreatefromjpeg($source_image);
					break;
				case "PNG":
				case "png":
					$source_image = imagecreatefrompng($source_image);
					break;
				case "BMP":
				case "bmp":
					$source_image = imagecreatefrombmp($source_image);
					break;
				case "GIF":
				case "gif":
					$source_image = imageCreateFromGif($source_image);
					break;
				default:
					$source_image = imagecreatefromjpeg($source_image);
			}
			/*if($extension == 'png'){
				$source_image=imagecreatefrompng($image_name);
			}else{
				$source_image=imagecreatefromjpeg($image_name);
			}*/
			$w = imagesx($source_image);
			$h = imagesy($source_image);
			$webp = imagecreatetruecolor($w, $h);
			imagecopy($webp, $source_image, 0, 0, 0, 0, $w, $h);
			imagewebp($webp, $destination_image, 20);
			chmod($destination_image, 0777);
		}
	}
	
	public static function imageExits($img) {
		if (!empty($img)) {
			if (file_exists(public_path($img))) {
				$image = $img;
			} else {
				$image = 'img/ward.jpg';
			}
		} else {
			$image = 'img/ward.jpg';
		}
		$extension = self::getExtension($image);
		$image_name_without_extension = str_replace('.'.$extension,'',$image);
		$image_name_webp = $image_name_without_extension.'.webp';
		
		if (file_exists(public_path($image_name_webp))) {
			$image = $image_name_webp;
		}else{
			$webp = self::generate_webp($image);
		}
		//$webp = self::generate_webp($image);
		
		return asset($image_name_webp);
	}
}