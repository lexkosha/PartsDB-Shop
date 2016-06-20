<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Number Helper
 *
 * -
 *
 * @package		2find
 * @subpackage	Helpers
 * @author		runcore
 */

// --------------------------------------------------------------------
	
/**
 * Num2Str
 *
 * Produces the russian string from number. Adds russian currency descriptor.
 *
 * @param	double
 * @return	string
 */	
function num2str($num, $currency = 'RUR') {
    $nul='ноль';
    $ten=array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
    );
    $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
    $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
    $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
    
		// RUR
		$unit=array
		(
			array('копейка' ,'копейки' ,'копеек',	 1),
			array('рубль'   ,'рубля'   ,'рублей'    ,0),
			array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
			array('миллион' ,'миллиона','миллионов' ,0),
			array('миллиард','милиарда','миллиардов',0),
    );
		
		// USD
		if ($currency == 'USD')
		{
			$unit[0] = array('цент' ,'цент' ,'центов',	 1);
			$unit[1] = array('доллар' ,'доллара' ,'долларов',	 0);
		}
		
    //
    list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub)>0) {
        foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1; // unit key
            $gender = $unit[$uk][3];
            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
            // units without rub & kop
            if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        }
    }
    else $out[] = $nul;
    $out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
    $out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
    
		return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

// --------------------------------------------------------------------
	
/**
 * Morph
 *
 * Morphs the words generated by num2str
 *
 * @return	string
 */	
function morph($n, $f1, $f2, $f5) {
    $n = abs(intval($n)) % 100;
    if ($n>10 && $n<20) return $f5;
    $n = $n % 10;
    if ($n>1 && $n<5) return $f2;
    if ($n==1) return $f1;
    return $f5;
}

// --------------------------------------------------------------------
	
/**
 * PHP Shorthand Value to Bytes
 *
 * Converts PHP-shorthand values to bytes.
 * An example of PHP shorthand value - 64M
 *
 * @return	int
 */	
function php_shorthand_val_to_bytes($val)
{
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
	
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return (int) $val;
}

/* End of file EXT_number_helper.php */
/* Location: ./application/helpers/EXT_number_helper.php */