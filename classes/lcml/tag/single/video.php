<?php

class lcml_tag_single_video extends bors_lcml_tag_single
{
	function html($text, &$params)
	{
		extract($params);

		$height = round($height * 640/$width, 0);
		$width = 640;

/*
<video name="media" class="animated-gif" data-height="426" data-width="500" loop="" src="https://pbs.twimg.com/tweet_video/B-TPwLyCQAA0zdn.mp4">
<source video-src="https://pbs.twimg.com/tweet_video/B-TPwLyCQAA0zdn.mp4" type="video/mp4" class="source-mp4" src="https://pbs.twimg.com/tweet_video/B-TPwLyCQAA0zdn.mp4">
</video>
*/

		return <<<__EOT__
<video controls="controls" data-height="$width" data-width="$height" loop="" src="$video"><source video-src="$video" type="video/mp4" class="source-mp4" src="$video"></video>
__EOT__;
	}
}
