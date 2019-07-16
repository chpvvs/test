<?php 

namespace Demos\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Config;

class AdminLib {


	function __construct() {
        $this->middleware('auth:account');
	}

	public static function checkAccess($id)	{
		if ( !in_array(Auth::id(), Config::get('admin.admin_ids'))) {
			if (\Request::ajax()) {
				\Response::make('no_rights', 401)->send();
				die();
			} else {
				return \Redirect::to('admin')->send();
			}			
		}
	}

	public static function checkAccessUnits($id, $cat_id)	{
		if ( !in_array($id, Config::get('admin.admin_ids'))) {
			$ancestors = array_merge(\Demos\Units\Cat::ancestors($cat_id), [$cat_id]);
			if ( !in_array(2, $ancestors)){
				if (\Request::ajax()) {
					\Response::make('no_rights', 401)->send();
					die();
				} else {
					return \Redirect::to('admin')->send();
				}
			}			
		}
	}


	public static function upload($file, $path, $old_file_name = NULL, $aspect = NULL, $fit_in = FALSE) {
		$new_file_name = $file->getClientOriginalName();
		$file_name = cyrllic_make(preg_replace('/(.*)\\.[^\\.]*/', '$1', $new_file_name));
		$extension = $file->getClientOriginalExtension();
		$new_file_name = $file_name.".".$extension;
		$new_file_name = self::_checkFileName($path."/big/", $new_file_name);
		return self::_process_image($file, $path, $new_file_name, $old_file_name, $aspect, $fit_in);
	}

	public static function simple_upload($file, $path, $old_file_name = NULL) {
		if (!is_dir($path)) mkdir($path);
		$new_file_name = $file->getClientOriginalName();
		$file_name = cyrllic_make(preg_replace('/(.*)\\.[^\\.]*/', '$1', $new_file_name));
		$extension = $file->getClientOriginalExtension();
		$new_file_name = $file_name.".".$extension;
		$new_file_name = self::_checkFileName($path, $new_file_name);

		if ($old_file_name && $new_file_name != $old_file_name) {
			self::clearFolders($path, $old_file_name, ['self']);
		}



        if (in_array($extension, ['gif', 'mp3', 'wav', 'ogg'])) {
            copy($file->getRealPath(), $path."/".$new_file_name);
        }
        else {
            $img = \Image::make($file);
            $img->save($path."/".$new_file_name);
            $img->destroy();
        }

		return $new_file_name;
	}

	public static function _checkFileName($path, $filename) {
		return (is_file($path.'/'.$filename)) ? self::_checkFileName($path, str_random(2)."_".$filename) : $filename;
	}

	public static function clearFolders($path, $filename, $special=array()) {
		if (empty($special)) {
			$files = array(
				$path."/clean/".$filename,
				$path."/big/".$filename,
				$path."/small/".$filename,
				$path."/thumb/".$filename
			);
		} elseif ($special[0] == 'self') {
			$files = array($path."/".$filename);
		} else {
			$files = array();
			foreach ($special as $folder) {
				$files[] = $path."/".$folder."/".$filename;
			}
		}

		foreach ($files as $file) {
			if (is_file($file))
				unlink($file);
		}
	}

	public function checkFile($filename, $path) {
		return (is_file($path."/".$filename)) ? $this->checkFile(str_random(2)."_".$filename, $path) : $filename;
	}

	protected static function _process_image($file, $path, $new_file_name, $old_file_name = NULL, $aspect = NULL, $fit_in = FALSE) {


		if (!is_dir('resources')) mkdir('resources');
		if (!is_dir('resources/assets')) mkdir('resources/assets');
		if (!is_dir('resources/assets/img')) mkdir('resources/assets/img');
		if (!is_dir($path)) mkdir($path);
		if (!is_dir($path.'/clean')) mkdir($path.'/clean');
		if (!is_dir($path.'/big')) mkdir($path.'/big');
		if (!is_dir($path.'/small')) mkdir($path.'/small');
		if (!is_dir($path.'/thumb')) mkdir($path.'/thumb');



		if ($old_file_name && $new_file_name != $old_file_name) {
			self::clearFolders($path, $old_file_name, []);
		}

		$options = \Config::get('admin.images');


		$big_size              = $options['big_size'];
		$small_size            = $options['small_size'];
		$thumb_size            = $options['thumb_size'];
		$default_color         = $options['default_color'];

		if ( ! $aspect ) {
			$default_aspect_width  = $options['aspects_default']['width'];
			$default_aspect_height = $options['aspects_default']['height'];
		} else {
			$aspect_sizes = explode(":", $aspect);
			$default_aspect_width = $aspect_sizes[0];
			$default_aspect_height = $aspect_sizes[1];
		}

		$img = \Image::make($file);


		if ($img->width() > $big_size OR $img->height() > $big_size) {
			$img->resize($big_size, NULL, function ($constraint) {
				$constraint->aspectRatio();
			});
		}


		$big_img = clone $img;
		$big_img->save($path."/clean/".$new_file_name);
		$big_img->insert('resources/assets/img/watermark_big.png', 'bottom-right', 10, 10);
		$big_img->save($path."/big/".$new_file_name);


		if ($fit_in) {
			if ($img->height() >= $img->width()) {
				$height = $small_size;
				$width = round(($small_size*$default_aspect_width)/$default_aspect_height);
			} else {
				$width = $small_size;
				$height = round(($small_size*$default_aspect_height)/$default_aspect_width);
			}
			if ($img->height() >= $img->width()) {
				$img->resize(NULL, $small_size, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
			} else {
				$img->resize($small_size, NULL, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});

				if ($img->height() > $small_size)
					$img->resize(NULL, $small_size, function($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					});
			}

		
			$img->resizeCanvas($width, $height, 'center', false, $default_color);
			$small_img = clone $img;
			$small_img->insert('resources/assets/img/watermark_small.png', 'bottom-right', 10, 10);		
			$small_img->save($path."/small/".$new_file_name);
			$img = \Image::make($file);
			if ($width <= $height) {
				$img->resize(NULL, $thumb_size, function($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
			} else {
				$img->resize($thumb_size, NULL, function($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
			}

			// $img->resizeCanvas($thumb_size, $thumb_size, 'center', false, $default_color);
			$img->insert('resources/assets/img/watermark_thumb.png', 'bottom-right', 10, 10);	
			$img->save($path."/thumb/".$new_file_name);
		} else {
			if ($img->height() <= $img->width()) {
				$height = $img->height();
				$width = round(($height*$default_aspect_width)/$default_aspect_height);
			} else {
				$width = $img->width();
				$height = round(($width*$default_aspect_height)/$default_aspect_width);
			}

			$img->resizeCanvas($width, $height, 'center', FALSE, $default_color);


			if ( ($img->height() >= $img->width()) && ($img->height() > $small_size) ) {
				$img->resize(NULL, $small_size, function ($constraint) {
					$constraint->aspectRatio();
				});
			} elseif ($img->width() > $small_size) {
				$img->resize($small_size, NULL, function ($constraint) {
					$constraint->aspectRatio();
				});		
			}

			$small_img = clone $img;
			$small_img->insert('resources/assets/img/watermark_small.png', 'bottom-right', 10, 10);	
			$small_img->save($path."/small/".$new_file_name);

			if ($height <= $width) {
				$img->resize(NULL, $thumb_size, function ($constraint) {
					$constraint->aspectRatio();
				});
			} else {
				$img->resize($thumb_size, NULL, function ($constraint) {
					$constraint->aspectRatio();
				});		
			}
			$img->insert('resources/assets/img/watermark_thumb.png', 'bottom-right', 10, 10);	
			$img->save($path."/thumb/".$new_file_name);
		}

		$big_img->destroy();
		$small_img->destroy();
		$img->destroy();

		return $new_file_name;
	}

	public static function doCrop($data){
		if ($data['img_small'] != '') {
			if (!\Image::make($data['img_big'])->crop($data['out_width'], $data['out_height'], $data['x1'], $data['y1'])->resize($data['small_width'], $data['small_height'])->insert('resources/assets/img/watermark_small.png', 'bottom-right', 10, 10)->save($data['img_small']))
			$errors[] = $data['img_small'];
		}
		if ($data['img_thumb'] != '') {
			if (!\Image::make($data['img_big'])->crop($data['out_width'], $data['out_height'], $data['x1'], $data['y1'])->resize($data['thumb_width'], $data['thumb_height'])->insert('resources/assets/img/watermark_thumb.png', 'bottom-right', 10, 10)->save($data['img_thumb']))
			$errors[] = $data['img_thumb'];
		}
		return (empty($errors)) ? TRUE : FALSE;
	}

	public static function prepareUnitsJSTree(&$data, $unit_cats = array()) {
		foreach ($data as &$row) {
			$row['title']    = $row['lang']['name'].' ('.$row['units'].')';
			$row['folder']   = true;
			$row['selected'] = (bool)(in_array($row['id'], $unit_cats));
			$row['key']      = $row['id'];
			$row['expand']   = true;
			$row['table']    = 'units_categories';
			$row['class']    = 'fancytree-ico-cf';

			unset($row['id'], $row['lang']['name'], $row['units'], $row['units_count_relation']);

			if (!empty($row['children']))
				self::prepareUnitsJSTree($row['children'], $unit_cats);
		}

		$out[0]['title']    = \Lang::get('units::main.root_name');
		$out[0]['folder']   = true;
		$out[0]['key']      = 'root';
		$out[0]['table']    = 'units_categories';
		$out[0]['expand']   = true;
		$out[0]['expanded'] = true;
		$out[0]['selected'] = true;
		$out[0]['children'] = $data;

		return $out;
	}

	public static function prepareBusJSTree(&$data, $bus_cats = array()) {
		foreach ($data as &$row) {
			$row['title']    = $row['lang']['name'].' ('.$row['goods'].')';
			$row['folder']   = true;
			$row['selected'] = (bool)(in_array($row['id'], $bus_cats));
			$row['key']      = $row['id'];
			$row['expand']   = true;
			$row['table']    = 'busshop_categories';
			$row['class']    = 'fancytree-ico-cf';

			unset($row['id'], $row['lang']['name'], $row['goods'], $row['goods_count_relation']);

			if (!empty($row['children']))
				self::prepareBusJSTree($row['children'], $bus_cats);
		}

		$out[0]['title']    = \Lang::get('busshop::main.root_name');
		$out[0]['folder']   = true;
		$out[0]['key']      = 'root';
		$out[0]['table']    = 'busshop_categories';
		$out[0]['expand']   = true;
		$out[0]['expanded'] = true;
		$out[0]['selected'] = true;
		$out[0]['children'] = $data;

		return $out;
	}

	public static function checkUnitsAlias($alias) {
		$alias = self::translateSlug(trim($alias));
		$units = \Demos\Units\Unit::where("alias", "=", $alias)->withTrashed()->get();
		$cats = \Demos\Units\Cat::where("alias", "=", $alias)->withTrashed()->get();
		return ($units->count()>0 || $cats->count()>0) ? self::checkUnitsAlias($alias."_".str_random(2)) : $alias;
	}

	public static function checkBusAlias($alias) {
		$alias = self::translateSlug(trim($alias));
		$goods = \Demos\Busshop\BusUnit::where("alias", "=", $alias)->withTrashed()->get();
		$cats = \Demos\Busshop\BusCat::where("alias", "=", $alias)->withTrashed()->get();
		return ($goods->count()>0 || $cats->count()>0) ? self::checkBusAlias($alias."_".str_random(2)) : $alias;
	}

	public static function translateSlug($text, $from_to='ru-en') {
		$rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
		$lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
		$response = str_replace($rus, $lat, $text);
		return Str::slug($response);
	}



}
