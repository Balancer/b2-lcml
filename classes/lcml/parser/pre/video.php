<?php

/**
	Препарсинг кода video

*/

// Для проверки: http://www.balancer.ru/g/p3751073

class lcml_parser_pre_video extends bors_lcml_parser
{
	function parse($code)
	{
		// Обрабатываем только если в тексте есть подстрока video
		if(stripos($code, '<video') === false)
			return $code;

/*
Пример кода:

<video name="media" class="animated-gif" data-height="426" data-width="500" loop="" src="https://pbs.twimg.com/tweet_video/B-TPwLyCQAA0zdn.mp4">
<source video-src="https://pbs.twimg.com/tweet_video/B-TPwLyCQAA0zdn.mp4" type="video/mp4" class="source-mp4" src="https://pbs.twimg.com/tweet_video/B-TPwLyCQAA0zdn.mp4">
</video>

*/

		$code = preg_replace_callback('!(<video [^>]+>.*?</video>)!siu', function($m) {
			$dom = new DOMDocument();
			@$dom->loadHTML($m[1]);
			$el = $dom->getElementsByTagName('video')->item(0);
			$src = $el->getAttribute('src');
			$width = $el->getAttribute('data-width');
			$height = $el->getAttribute('data-height');
			return "[video=$src width=$width height=$height]";
		}, $code);

		return $code;
	}

	function __unit_test($suite)
	{
		$bb_code = <<< __EOT__
<video name="media" class="animated-gif" data-height="426" data-width="500" loop="" src="https://pbs.twimg.com/tweet_video/B-TPwLyCQAA0zdn.mp4">
<source video-src="https://pbs.twimg.com/tweet_video/B-TPwLyCQAA0zdn.mp4" type="video/mp4" class="source-mp4" src="https://pbs.twimg.com/tweet_video/B-TPwLyCQAA0zdn.mp4">
</video>
__EOT__;

		$suite->assertRegexp('!^\[video=https://pbs.twimg.com/tweet_video/B-TPwLyCQAA0zdn.mp4 width=500 height=426\]$!', trim(self::parse($bb_code)));
	}
}
