<?php

class lcml_tag_pair_csv extends bors_lcml_tag_pair
{
	function html($text, $params)
	{
		require_once('engines/lcml/bcsTable.php');
		require_once('inc/csv.php');
		$lcml_parse_cells = bors_strlen($text) < 8192;

		$bcs_args = array();

		if($object = @$params['self'])
		{
			if($layout = $object->get('layoutr'))
			{
				$bcs_args['table_class'] = $layout->table_class();
			}
		}

		$tab = new bcsTable($bcs_args);


		if(!empty($params['width']))
			$tab->table_width($params['width']);

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
            	            $tab->setColSpan($tab->cols-1 - $tab->col);
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

	static function __dev()
	{
		echo bors_lcml::lcml("[csv]a;b;x\n1;2;3[/csv]");
	}
}
