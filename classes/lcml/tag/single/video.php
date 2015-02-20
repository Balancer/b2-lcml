<?php

class lcml_tag_single_video extends bors_lcml_tag_single
{
	function html($text, &$params)
	{
		extract($params);
		return <<<__EOT__
...
<video data-height="$width" data-width="$height" loop="" src="$video">
<source video-src="$video" type="video/mp4" class="source-mp4" src="$video">
</video>
__EOT__;
	}
}
