<?php

class lcml_tag_pair_csv extends bors_lcml_tag_pair
{
	function html($text, &$params)
	{
		require_once('engines/lcml/bcsTable.php');
		require_once('inc/csv.php');
		$lcml_parse_cells = bors_strlen($text) < 8192;

		$bcs_args = array();

		if($container = @$params['container'])
			if($layout = $container->get('layout'))
				$bcs_args['layout'] = $layout;

		$tab = new bcsTable($bcs_args);

		$tab->table_width(defval_ne($params, 'width', 'auto'));

		$delim = defval($params, 'delim', ';');

	    foreach(explode("\n", $text) as $s)
    	{
	            if($s = trim($s))
    	        {
        	        foreach(csv_explode($s, $delim) as $d)
            	    {
                	    if(preg_match("!^\*(.+)$!", $d, $m))
                    	{
                        	$d = trim($m[1]);
	                        $tab->setHead();
    	                }

        	            if(preg_match("!^\|(\d+)(.+)$!", $d, $m))
            	        {
	                        $d = trim($m[2]);
    	                    $tab->setColSpan($m[1]);
	                    }

    	                if(preg_match("!^\[cs=(\d+)\](.+)$!", $d, $m))
        	            {
            	            $d = trim($m[2]);
                	        $tab->setColSpan($m[1]);
	                    }

	                    if(preg_match("!^\[cs=max\](.+)$!", $d, $m))
    	                {
        	                $d = trim($m[1]);
            	            $tab->setColSpan($tab->cols - $tab->col);
                	    }

	                    if(preg_match("!^\[rs=(\d+)\](.+)$!", $d, $m))
    	                {
        	                $d = trim($m[2]);
            	            $tab->setRowSpan($m[1]);
	                    }

	                    if($d == '')
    	                    $d = '&nbsp;';
						elseif($lcml_parse_cells and !preg_match('!^[\w,\-\+\.]+$!', $d))
							$d = lcml($d);
						else
							$d = str_replace("[br]", "<br/>", $d);

	                    $tab->append($d);
    	            }

        	        $tab->new_row();
            	}
	    }

		return remove_format($tab->get_html());
	}

	static function __unit_test($suite)
	{
		$bbcode = "[csv]
*hhh;*[cs=2]ggg
aaa;bbb;[rs=2]ccc
[cs=2]111
[cs=max]333
444;[cs=max]555
[/csv]";

		$html = bors_lcml::lcml($bbcode);

		$suite->assertEquals('<table><tr><th>hhh</th><th colspan="2">ggg</th></tr><tr><td>aaa</td><td>bbb</td><td rowspan="2">ccc</td></tr><tr><td colspan="2">111</td></tr><tr><td colspan="3">333</td></tr><tr><td>444</td><td colspan="2">555</td></tr></table>', $html);

		$bbcode = "[csv]
*[cs=2]Параметр;*Су-27;*МиГ-29
[cs=2]Нормальная взлётная масса, кг;22500;[red]15180[/red]
[rs=2]Скорость, км/ч;максимальная;2500;2450
;у земли;1400;1500
[/csv]";

		$html = bors_lcml::lcml($bbcode);

		$suite->assertEquals('<table><tr><th colspan="2">Параметр</th><th>Су-27</th><th>МиГ-29</th></tr><tr><td colspan="2">Нормальная взлётная масса, кг</td><td>22500</td><td><span style="color: red;">15180</span></td></tr><tr><td rowspan="2">Скорость, км/ч</td><td>максимальная</td><td>2500</td><td>2450</td></tr><tr><td>у земли</td><td>1400</td><td>1500</td></tr></table>', $html);
		$suite->assertRegExp('!<td>.+red.+15180.*</td>!', $html);
	}
}
