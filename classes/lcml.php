<?php

class lcml
{
	static function parse($bb_code)
	{
		return bors_lcml::lcml($bb_code);
	}

	static function __unit_test($suite)
	{
		$suite->assertEquals('<strong>Test</strong>', lcml::parse('[b]Test[/b]'));

		//TODO: надо подумать на тему экранирования.
		$suite->assertEquals('После знака < сообщение прерывается', lcml::parse('После знака < сообщение прерывается'));
	}
}
