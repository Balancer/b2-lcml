<?php

class lcml_tag_single_facebook_video extends bors_lcml_tag_single
{
	function html($video_id, &$params = array())
	{

$html = <<< __EOT__
<div id="fb-root"></div> <script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-post" data-href="https://www.facebook.com/video.php?v={$video_id}" data-width="640"><div class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/video.php?v={$video_id}">публикация</a></div></div>
__EOT__;

		return $html;
	}

	function text($video_id, &$params = array())
	{
		return "\nFacebook video: https://www.facebook.com/video.php?v={$video_id}\n";
	}

	function __unit_test($suite)
	{
		$suite->assertRegexp('!^<div id="fb-root">.+<script>.+<div class="fb-post" data-href="https://www\.facebook\.com/video\.php\?v=806324122767529" data-width="640">.*</div></div>$!isu', trim(lcml::parse('[facebook_video=806324122767529]')));
	}
}
