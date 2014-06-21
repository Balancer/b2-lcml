<?php

class lcml
{
	static function parse($bb_code)
	{
		return bors_lcml::lcml($bb_code);
	}

	static function __dev()
	{
		echo lcml::parse('[b]Test[/b]');
	}
}
