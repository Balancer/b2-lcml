<?php

/**
	Оформление вставок кода Facebook. Видео и т.п.
*/

// Для проверки: http://www.balancer.ru/g/p3655919

class lcml_parser_pre_facebook extends bors_lcml_parser
{
	function parse($code)
	{
		// Обрабатываем только если в тексте есть подстрока facebook
		if(stripos($code, 'facebook') === false)
			return $code;

/*
Пример кода:

<div id="fb-root"></div> <script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-post" data-href="https://www.facebook.com/video.php?v=806324122767529" data-width="640"><div class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/video.php?v=806324122767529">публикация</a> <a href="https://www.facebook.com/bizimyolinfo">Bizim Yol</a>.</div></div>
*/

		$code = preg_replace('!<div id="fb-root">.+?data-href="https://www\.facebook\.com/video\.php\?v=(\d+).+?</div></div>!siu', '[facebook_video=$1]', $code);

// Если зевнули кусок кода, как на http://www.balancer.ru/g/p3639710
		$code = preg_replace('!<div id="fb-root">.+?data-href="https://www\.facebook\.com/video\.php\?v=(\d+).+$!sium', '[facebook_video=$1]', $code);

		$code = preg_replace('!^\s*https://www\.facebook\.com/video\.php\?v=(\d+)\s*$!m', '[facebook_video=$1]', $code);
		$code = preg_replace('!^\s*https://www\.facebook\.com/video\.php\?v=(\d+)&\S*$!m', '[facebook_video=$1]', $code);

		return $code;
	}

	function __unit_test($suite)
	{
		$bb_code = <<< __EOT__
			<div id="fb-root"></div> <script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-post" data-href="https://www.facebook.com/video.php?v=806324122767529" data-width="640"><div class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/video.php?v=806324122767529">публикация</a> <a href="https://www.facebook.com/bizimyolinfo">Bizim Yol</a>.</div></div>
__EOT__;

		$suite->assertRegexp('!^\[facebook_video=806324122767529\]$!', trim(self::parse($bb_code)));

		$suite->assertRegexp('!^\[facebook_video=806324122767529\]$!', trim(self::parse('https://www.facebook.com/video.php?v=806324122767529')));

		$bb_code = "...
https://www.facebook.com/video.php?v=768973713156693
...";

		$suite->assertRegexp('!\[facebook_video=768973713156693\]!', trim(self::parse($bb_code)));

		$bb_code = "...
https://www.facebook.com/video.php?v=721535437924757&set=vb.125210247557282&type=2&theater
...";

		$suite->assertRegexp('!\[facebook_video=721535437924757\]!', trim(self::parse($bb_code)));
	}
}
