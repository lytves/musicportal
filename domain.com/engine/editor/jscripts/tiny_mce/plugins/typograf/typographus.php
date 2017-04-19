<?php
# ��������! ������ ���� ������ ���������� � ��������� WINDOWS-1251! #

setlocale(LC_ALL, 'ru_RU.CP1251');

/**
 * ��������, ������ 2.2.0 (PHP5)
 * ------------------------------------------------------------
 * ���������: http://rmcreative.ru/article/programming/typograph/  
 *
 * ������:
 * � �������� ������ ( http://smee-again.livejournal.com/ )
 *    �������������� ��� � �������, ������������.
 * � ������� ��������� ( http://rmcreative.ru/ )
 *    ���, ������������, �������, ����, ���������� ���������.
 *
 * �������:
 * � faZeful, Naruvi, Shaman, Eagle, grasshopper, Max, Reki, 
 *   Zav (�� ����������� � ��������� ���������� ��������� ������ ��� ������ 2.0.7)
 *
 * ��� �������� ������ ��������� ������ ������� ����� ��������������:
 * � http://philigon.ru/
 * � http://artlebedev.ru/kovodstvo/
 * � http://pvt.livejournal.com/
 * ------------------------------------------------------------
 */
class typographus{

    var $_options = array(
        'CONVERT_E' => false,
        'HTML_ENTITIES' => false
    );
    
    var $_encoding;
	var $safe_blocks = array();
    
	var $_sym = array(
		'nbsp'    => '&nbsp;',
		'lnowrap' => '<span style="white-space:nowrap">',
		'rnowrap' => '</span>',

		'lquote'  => '�',
		'rquote'  => '�',
		'lquote2' => '�',
		'rquote2' => '�',
		'mdash'   => '�',
		'ndash'   => '�',
		'minus'   => '�', // �����. �� ������ ������� +, ���� �� ���� �������

		'hellip'  => '�',
		'copy'    => '�',
		'trade'   => '<sup>�</sup>',
		'apos'    => '&#39;',   // ��. http://fishbowl.pastiche.org/2003/07/01/the_curse_of_apos
		'reg'     => '<sup><small>�</small></sup>',
		'multiply' => '&times;',
		'1/2' => '&frac12;',
		'1/4' => '&frac14;',
		'3/4' => '&frac34;',
		'plusmn' => '&plusmn;',
		'rarr' => '&rarr;',
		'larr' => '&larr;',
	    'rsquo' => '&rsquo;'
	);

	var $_safeBlocks = array(
		'<pre[^>]*>' => '<\/pre>',
		'<style[^>]*>' => '<\/style>',
		'<script[^>]*>' => '<\/script>',
		'<!--' => '-->',
	    '<code[^>]*>' => '<\/code>',
	    '\[code[^\]]*]' => '\[\/code\]',

	);


	/**
	 * ������� ��������� ��� ��������� ������ � ���������, �������� �� WINDOWS-1251.
	 * @param String $encoding
	 */
	function typographus($encoding = null){
	   $this->_encoding = $encoding;
	}

	/**
	 * ��������� ���������� ����, ������� �� ����� �������������� ����������.
	 *
	 * @param String $openTag
	 * @param String $closeTag
	 */
	function addSafeBlock($openTag, $closeTag){
		$this->_safeBlocks[$openTag] = $closeTag;
	}
	
	/**
	 * ������� ��� ���������� ����� ���������.
	 * �������, ���� ���������� ������ ��������� ���� �����.
	 */
	function removeAllSafeBlocks(){
		$this->_safeBlocks = array();
	}

	/**
	 * ������������� ������������ ����� �������� � ��� ��������������.
	 *
	 * @param String $sym
	 * @param String $entity
	 */
	function setSym($sym, $entity){
		$this->_sym[$sym] = $entity;
	}
	
	/**
	 * ������������� ����� ���������.
	 * ��. ������ ����� ����.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	function setOpt($key, $value){
		$this->_options[$key] = $value;
	}
	

	/**
	 * �������� ��������, ������ html-����� � ���������� �����
	 * ��� ��������� ������ � ���������, �������� �� WINDOWS-1251, ������� �
	 * ������ ����������.
	 *
	 * @param string $str
	 * @return string
	 */
	function process($str){
		$str = str_replace( "<br>", "_BRLINE_", $str );
		$str = str_replace( "<br />", "_BRLINE_", $str );
		$str = str_replace( "<BR>", "_BRLINE_", $str );
		$str = str_replace( "<BR />", "_BRLINE_", $str );
		if($this->_encoding!=null){
			$str = iconv($this->_encoding, 'WINDOWS-1251', $str);	 
		}
		
		$pattern = '(';
		foreach ($this->_safeBlocks as $start => $end){
			$pattern .= "$start.*$end|";
		}
		$pattern .= '<[^>]*[\s][^>]*>)';
		$str = preg_replace_callback("~$pattern~isU", array(&$this, '_stack'), $str);

		$str = html_entity_decode($str);
		$str = str_replace( ']"', '_LINE_] "', $str );
		$str = str_replace( '"[', '" _LINE_[', $str );
		$str = $this->typo_text($str);
		$str = str_replace( "_LINE_] ", "]", $str );
		$str = str_replace( " _LINE_[", "[", $str );
		$str = strtr($str, $this->_stack());
		if($this->_encoding!=null){
			$str = iconv('WINDOWS-1251', $this->_encoding, $str);
		}
		$str = str_replace( "_BRLINE_", "<br />", $str );
		return $str;
	}

	/**
	 * ����������� �������� ��� ���������� ������ ��� ������������� � ��������
	 * ��������� ������. ��� ��������� ������������� ���������� �����������
	 * ������.
	 *
	 * @param array $matches
	 * @return array
	 */
	function _stack($matches = false){


		if ($matches !== false){
			$key = '<'.count($this->safe_blocks).'>';
			$this->safe_blocks[$key] = $matches[0];
			return $key;
		}
		else{
			$tmp = $this->safe_blocks;
			$this->safe_blocks = array();
			return $tmp;
		}
	}
	
	/**
	 * ��������� ��� ���������� ������� � ������
	 * @param array
	 * @return String
	 */
	function apply_rules($rules, $str){
      return preg_replace(array_keys($rules), array_values($rules), $str);
	}

	/**
	 * ������� ������� ���������.
	 * @param String $str
	 * @return String
	 */
	function typo_text($str){
		$sym = $this->_sym;
		if (trim($str) == '') return '';

		$html_tag = '(?:(?U)<.*>)';
		$hellip = '\.{3,5}';
		
		//�����
		$word = '[a-zA-Z�-��-�_]';
		
		//������ �����
		$phrase_begin = "(?:$hellip|$word|\n)";
		//����� �����
        $phrase_end   = '(?:[)!?.:;#*\\\]|$|'.$word.'|'.$sym['rquote'].'|'.$sym['rquote2'].'|&quot;|"|'.$sym['hellip'].'|'.$sym['copy'].'|'.$sym['trade'].'|'.$sym['apos'].'|'.$sym['reg'].'|\')';
        //����� ���������� (��������� � ����� - ��������� ������!)
        $punctuation = '[?!:,;]';
		//������������
        $abbr = '���|���|���|��|��|���|���';
        //�������� � �����
        $prepos = '�|�|��|���|�|���|�|�|�|�|�|��|��|���|��|���|��|��|��|��|��|��|���|��|��|��|��|���|����|����|�����|���|���|����|���|���|����|���|��|���|��|���|���|��|���|��|���|�����|�����|������|�����|�����|������|���|���|�';
        
        $metrics = '��|��|�|��|�|��|�|��|��|��|dpi|px';
        
        $shortages = '�|��|���|���|c|��|�|���|�';

        $money = '���\.|����\.|����|�\.�\.';
        $counts = '���\.|���\.';
        
		$any_quote = "(?:$sym[lquote]|$sym[rquote]|$sym[lquote2]|$sym[rquote2]|&quot;|\")";

		$rules_strict = array(
		  // ����� �������� ��� ��������� -> ���� ������
		  '~( |\t)+~' => ' ',
		  // ������� ����� �� � ���. ���� ��� ���� � �� ������.
		  '~([^,])\s(�|��)\s~' => '$1, $2 ',
		);
		
        $rules_symbols = array(
            //������ �����.
            //TODO: ������� �������
            '~([^!])!!([^!])~' => '$1!$2',        
            '~([^?])\?\?([^?])~' => '$1?$2',
            '~(\w);;(\s)~' => '$1;$2',
            '~(\w)\.\.(\s)~' => '$1.$2',
            '~(\w),,(\s)~' => '$1,$2',
            '~(\w)::(\s)~' => '$1:$2',
        
            '~(!!!)!+~' => '$1',
            '~(\?\?\?)\?+~' => '$1',
            '~(;;;);+~' => '$1',
            '~(\.\.\.)\.+~' => '$1',
            '~(,,,),+~' => '$1',
            '~(:::):+\s~' => '$1',
        
            //�������� ����������
            '~!\?~' => '?!',
        
            // ����� (c), (r), (tm)
			'~\((c|�)\)~i' 	=> $sym['copy'],
			'~\(r\)~i' 	=>	$sym['reg'],
			'~\(tm\)~i'	=>	$sym['trade'],
        
            // �� 2 �� 5 ����� ����� ������ - �� ���� ���������� (������ - �� ��������� ��������).
			"~$hellip~" => $sym['hellip'],

			// ����������� ��� 1/2 1/4 3/4
			'~\b1/2\b~'	=> $sym['1/2'],
			'~\b1/4\b~' => $sym['1/4'],
			'~\b3/4\b~' => $sym['3/4'],

            //L�'����
            "~([a-zA-Z])'([�-��-�])~i" => '$1'.$sym['rsquo'].'$2',
        
			"~'~" => $sym['apos'], //str_replace?
		
			// ������� 10x10, ���������� ���� + ������� ������ �������
			'~(\d+)\s{0,}?[x|X|�|�|*]\s{0,}(\d+)~' => '$1'.$sym['multiply'].'$2',
        
        	//+-
			'~([^\+]|^)\+-~' => '$1'.$sym['plusmn'],
        
			//�������
			'~([^-]|^)->~' => '$1'.$sym['rarr'],
			'~<-([^-]|$)~' => $sym['larr'].'$1',
        );
        
        $rules_quotes = array(
             // �������� ������������ �������
		     '~([^"]\w+)"(\w+)"~' => '$1 "$2"',
		     '~"(\w+)"(\w+)~' => '"$1" $2',

             // ���������� ������� � ������. ������� ������� ���������.
             "~(?<=\\s|^|[>(])($html_tag*)($any_quote)($html_tag*$phrase_begin$html_tag*)~"	=> '$1'.$sym['lquote'].'$3',
             "~($html_tag*(?:$phrase_end|[0-9]+)$html_tag*)($any_quote)($html_tag*$phrase_end$html_tag*|\\s|[,<-])~"	=> '$1'.$sym['rquote'].'$3',
        );
        
        $rules_braces = array(
		  // �������� ������ �� �����
			'~(\w)\(~' => '$1 (',
          //�������� ������ �� �������
		     '~\( ~s' => '(',
			 '~ \)~s' => ')',
		);

		$rules_main = array(
		    // �������� � �����- � �������������
			// �������� ���� �� �����
			//'~(\w)- ~' => '$1 - ',
		
            //����� � �������������� �������� ��������!
			'~('.$phrase_end.') +('.$punctuation.'|'.$sym['hellip'].')~' => '$1$2',
			'~('.$punctuation.')('.$phrase_begin.')~' => '$1 $2',
		
		    //��� ����� ��������
		    '~(\w)\s(?:\.)(\s|$)~' => '$1.$2',

		     //����������� �������� ����������� � ����������� ���� �������������
		     // ~ ������ �� ���� &nbsp;?
             // ! �������� ����������� ���� ����� ��������� ������ !
			'~('.$abbr.')\s+(�.*�)~' => $sym['lnowrap'].'$1 $2'.$sym['rnowrap'],

			 //������ �������� ���������� �� ������������ � ���� �����.
			 //��������: ���. ������, �. �������
 			 //������ ������, ���� ��� ���.
 			 '~(^|[^a-zA-Z�-��-�])('.$shortages.')\.\s?([�-�0-9]+)~s' => '$1$2.'.$sym['nbsp'].'$3',

			 //�� �������� ���., �. � �.�. �� ������.
			 '~(���|�|����|���|���)\.\s*(\d+)~si' => '$1.'.$sym['nbsp'].'$2',

			 //�� ��������� 2007 �., ������� ������, ���� ��� ���. ������ �����, ���� � ���.
			'~([0-9]+)\s*([��])\.\s~s' => '$1'.$sym['nbsp'].'$2. ',
			
			 //����������� ������ ����� ������ � �������� ���������
			 '~([0-9]+)\s*('.$metrics.')~s' => '$1'.$sym['nbsp'].'$2',
			
             //��������� � ������ ��. ��������� � ��������, ���� � �.�.
             '~(\s'.$metrics.')(\d+)~' => '$1<sup>$2</sup>',	

			// ���� ������ ��� ��� ����� ������ ������ � �� ���� �������� ����.
			// + ������ ��������� ������ ����� ����, ��������: ������ � ����, ������ � �������� �������.
			'~ +(?:--?|�|&mdash;)(?=\s)~' => $sym['nbsp'].$sym['mdash'],
		    '~^(?:--?|�|&mdash;)(?=\s)~' => $sym['mdash'],
		
    		//������ ����
		    '~(?:^|\s+)(?:--?|�|&mdash;)(?=\s)~' => "\n".$sym['nbsp'].$sym['mdash'],

			// ���� ������, ������������ � ����� ������ ������� � �� ���� ��������� ����.
//			'~(?<=\d)-(?=\d)~' => $sym['ndash'],

			// ������ ��������� � ����� ������ �������� � �����
			'~(?<=\s|^|\W)('.$prepos.')(\s+)~i' => '$1'.$sym['nbsp'],

			// ������ �������� ������� ��, ��, �� �� ��������������� �����, ��������: ��� ��, ���� ��, ��� ��.
			"~(?<=\\S)(\\s+)(�|��|�|��|��|��|����|���)(?=$html_tag*[\\s)!?.])~i" => $sym['nbsp'].'$2',

			// ����������� ������ ����� ���������.
			'~([�-�A-Z]\.)\s?([�-�A-Z]\.)\s?([�-��-�A-Za-z]+)~s' => '$1$2'.$sym['nbsp'].'$3',

            // ���������� ���� �� ���������� �� �����.			
			'~(\d+)\s?('.$counts.')~s'	=>	'$1'.$sym['nbsp'].'$2',
		
		    //��� � �������� ������
		    '~(\d+|'.$counts.')\s?��~s'	=>	'$1'.$sym['nbsp'].'�.�.',
		    
     		// �������� �����, ���������� ������� � ������ ������.
			'~(\d+|'.$counts.')\s?('.$money.')~s'	=>	'$1'.$sym['nbsp'].'$2',

			// ����������� ������� � ��������
			//"/($sym[lquote]\S*)(\s+)(\S*$sym[rquote])/U" => '$1'.$sym["nbsp"].'$3',
			
			//��������
            //'~(?:���\.?/?����:?\s?\((\d+)\))~i' => '���./����:'.$sym['nbsp'].'($1)',
			
			//'~���[:.] ?(\d+)~ie' => "'<span style=\"white-space:nowrap\">���: '.self::_phone('$1').'</span>'",
			
			//����� ������ ��������� ����� ���������� � �������� v.
//			'~([v�]\.) ?([0-9])~i' => '$1'.$sym['nbsp'].'$2',
			'~(\w) ([v�]\.)~i' => '$1'.$sym['nbsp'].'$2',
		
		    //% �� ���������� �� �����
		    '~([0-9]+)\s+%~' => '$1%',
		
			//IP-������ ����� ��������
			'~(1\d{0,2}|2(\d|[0-5]\d)?)\.(0|1\d{0,2}|2(\d|[0-5]\d)?)\.(0|1\d{0,2}|2(\d|[0-5]\d)?)\.(0|1\d{0,2}|2(\d|[0-5]\d)?)~' =>
			$sym['lnowrap'].'$0'.$sym['rnowrap'],
		);

		
		$r = preg_split( '((>)|(<))', $str, - 1, PREG_SPLIT_DELIM_CAPTURE );
					
		for($i = 0; $i < count( $r ); $i ++) {
			if( $r[$i] == "<" ) {
				$i ++;
				continue;
			}
			$r[$i] = $this->apply_rules($rules_quotes, $r[$i]);
		}

		$str = join( "", $r );

	
				
		// ��������� ������.
        $i=0; $lev = 5;
        while (($i<$lev) && preg_match('~�(?:[^�]*?)�~', $str)){
			$i++;
            $str = preg_replace('~�([^�]*?)�(.*?)�~s', '�$1'.$sym['lquote2'].'$2'.$sym['rquote2'], $str);
		}

        $i=0;
        while (($i++<$lev) && preg_match('~�(?:[^�]*?)�~', $str)){
		  $i++;
          $str = preg_replace('~�([^�]*?)�~', $sym['rquote2'].'$1�', $str);
		}
        
        $str = $this->apply_rules($rules_strict, $str);        
		$str = $this->apply_rules($rules_main, $str);
		$str = $this->apply_rules($rules_symbols, $str);
		$str = $this->apply_rules($rules_braces, $str);
		
        if($this->_options['CONVERT_E']){
            $str = str_replace(array('�', '�'), array('�', '�'), $str);
        }
        if($this->_options['HTML_ENTITIES']){
          $str = htmlentities($str);
        }
		return $str;
	}
}