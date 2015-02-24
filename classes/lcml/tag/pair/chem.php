<?php

/*
	Используется https://github.com/Gregwar/Tex2png
	composer require gregwar/tex2png=*
*/

class lcml_tag_pair_chem extends bors_lcml_tag_pair
{
	var $root_src_dir = '/var/www/www.balancer.ru/bors-site/webroot/_cg';
	var $root_cache_dir = '/var/www/www.balancer.ru/htdocs/cache-static/_cg';
	var $root_dir = '/var/www/www.balancer.ru/htdocs/_cg';
	var $root_url = 'http://www.balancer.ru/_cg';

	function html($text, &$params)
	{
		$size = defval($params, 'size', 100);
		$text = trim($text);
		$hash = md5('s='.$size.'t='.$text);
		$src_file = $this->root_src_dir.'/'.date('Y-m').'/'.$hash.'.json';

		mkpath(dirname($src_file));

		file_put_contents($src_file, json_encode(array(
			'generator_class' => 'airbase_cg_chem',
			'data' => $text,
			'size' => $size,
		), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ));

		$this->generator = bors_load('airbase_cg_chem', $text);

//		r($text);

		$cache_file = $this->root_cache_dir.'/'.date('Y-m').'/'.$hash.'.png';
		mkpath(dirname($cache_file));
		$this->generator->set_attr('save_to', $cache_file);
		$this->generator->set_attr('size', $size);

		try {
			$this->generator->content();
		} catch(Exception $e)
		{
			return $e->getMessage();
		}

		return "<img src=\"".$this->root_url.'/'.date('Y-m').'/'.$hash.'.png'."\" />";
	}
}
