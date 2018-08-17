<?php

/* Seo Functions */
function seoShuffle(&$items, $string)
{
    mt_srand(crc32($string));
    for ($i = count($items) - 1; $i > 0; $i--) {
        $j = @mt_rand(0, $i);
        $tmp = $items[$i];
        $items[$i] = $items[$j];
        $items[$j] = $tmp;
    }
}

function grammarFix($content) {
    $content = preg_replace("/^a ([aeiouAEIOU])/", " an $1", $content);
    $content = preg_replace("/^A ([aeiouAEIOU])/", " An $1", $content);
    $content = preg_replace("/ a ([aeiouAEIOU])/", " an $1", $content);
    $content = preg_replace("/ A ([aeiouAEIOU])/", " An $1", $content);
    $content = preg_replace("/\.a ([aeiouAEIOU])/", ". An $1", $content);
    $content = preg_replace("/\.A ([aeiouAEIOU])/", " An $1", $content);
    $content = preg_replace("/>a ([aeiouAEIOU])/", ">an $1", $content);
    $content = preg_replace("/>A ([aeiouAEIOU])/", ">An $1", $content);

    $content = preg_replace("/^an ([bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ])/", " a $1", $content);
    $content = preg_replace("/^An ([bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ])/", " A $1", $content);
    $content = preg_replace("/ an ([bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ])/", " a $1", $content);
    $content = preg_replace("/ An ([bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ])/", " A $1", $content);
    $content = preg_replace("/\.an ([bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ])/", ". A $1", $content);
    $content = preg_replace("/\.An ([bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ])/", ". A $1", $content);
    $content = preg_replace("/>an ([bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ])/", ">a $1", $content);
    $content = preg_replace("/>An ([bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ])/", ">A $1", $content);

    return $content;
}


function smartReplace($content, $replacement, $bold=false)
{
    $words = explode(" ", $content);
    $i = array();
    //Sort replacements by longest replacement tags not shortest
    foreach ($replacement as $k=>$v) {
        $remap[$k] = strlen($k);
    }
    arsort($remap);
    $t = $replacement;
    $replacement = array();
    foreach ($remap as $k=>$v) {
        $replacement[$k] = $t[$k];
    }
    foreach ($replacement as $k=>$v) {
        $i[$k] = 0;
    }
    foreach ($words as $word) {
        foreach ($replacement as $k=>$v) {
            $counter = count($v);
            if (preg_match("/$k/", $word)) {
                if ($i[$k] >= $counter) {
                    $i[$k] = 0;
                }
                if ($bold) {
                    $word = preg_replace("/$k/", "<B>".strtotitlex($v[$i[$k]])."</B>", $word);
                } else {
                    $word = preg_replace("/$k/", strtotitlex($v[$i[$k]]), $word);
                }
                $i[$k]++;
            }
        }
        $string[] = $word;
    }
    return implode(" ", $string);
}

function strtotitlex($title)
{
    $smallwordsarray = array(
        'of','a','the','and','an','or','nor','but','is','if','then','else','when','at','from','by','on','off','for','out','over','to','into','with'
    );
    $ewords = explode(' ', $title);
    foreach ($ewords as $key => $eword) {
        $eword = trim($eword);
        if (!in_array(strtolower($eword), $smallwordsarray)) {
            $ewords[$key] = ucfirst($eword);
        } else {
            $ewords[$key] = $eword;
        }
    }
    $newtitle = implode(' ', $ewords);
    return $newtitle;
}

/* Content Spinner Class */
class Spinner{
    # Detects whether to use the nested or flat version of the spinner (costs some speed)
    public static function detect($text, $seedPageName = true, $openingConstruct = '{{', $closingConstruct = '}}', $separator = '|'){
        if(preg_match('~'.$openingConstruct.'(?:(?!'.$closingConstruct.').)*'.$openingConstruct.'~s', $text)){
            return self::nested($text, $seedPageName, $openingConstruct, $closingConstruct, $separator);
        }else{  
            return self::flat($text, $seedPageName, false, $openingConstruct, $closingConstruct, $separator);
        }
    }       
                    
    # The flat version does not allow nested spin blocks, but is much faster (~2x)
    public static function flat($text, $seedPageName = true, $calculate = false, $openingConstruct = '{{', $closingConstruct = '}}', $separator = '|'){
        # Choose whether to return the string or the number of permutations
        $return = 'text';
        if($calculate){ 
            $permutations   = 1;
            $return  = 'permutations';
        }               

        # If we have nothing to spin just exit (don't use a regexp)
        if(strpos($text, stripslashes($openingConstruct)) === false){
            return $$return;
        }
        
        if(preg_match_all('!'.$openingConstruct.'(.*?)'.$closingConstruct.'!s', $text, $matches)){
            # Optional, always show a particular combination on the page
            self::checkSeed($seedPageName);
    
            $find       = array();
            $replace    = array();
                    
            foreach($matches[0] as $key => $match){
                $choices = explode($separator, $matches[1][$key]);
                
                if($calculate){
                    $permutations *= count($choices);
                }else{
                    $find[]     = $match;
                    $replace[]  = $choices[mt_rand(0, count($choices) - 1)];
                }
            }
            
            if(!$calculate){
                # Ensure multiple instances of the same spinning combinations will spin differently
                $text = self::str_replace_first($find, $replace, $text);
            }       
        }       
                
        return $$return;
    }               
                    
                    
    # The nested version allows nested spin blocks, but is slower
    public static function nested($text, $seedPageName = true, $openingConstruct = '{{', $closingConstruct = '}}', $separator = '|'){
        # If we have nothing to spin just exit (don't use a regexp)
        if(strpos($text, $openingConstruct) === false){
            return $text;
        }       
                        
        # Find the first whole match
        if(preg_match('!'.$openingConstruct.'(.+?)'.$closingConstruct.'!s', $text, $matches)){
            # Optional, always show a particular combination on the page
            self::checkSeed($seedPageName);
            
            # Only take the last block
            if(($pos = mb_strrpos($matches[1], $openingConstruct)) !== false){
                $matches[1] = mb_substr($matches[1], $pos + mb_strlen($openingConstruct));
            }

            # And spin it
            $parts  = explode($separator, $matches[1]);
            $text   = self::str_replace_first($openingConstruct.$matches[1].$closingConstruct, $parts[mt_rand(0, count($parts) - 1)], $text);

            # We need to continue until there is nothing left to spin
            return self::nested($text, $seedPageName, $openingConstruct, $closingConstruct, $separator);
        }else{
            # If we have nothing to spin just exit
            return $text;
        }
    }

    # Similar to str_replace, but only replaces the first instance of the needle
    private static function str_replace_first($find, $replace, $string){
        # Ensure we are dealing with arrays
        if(!is_array($find)){
            $find = array($find);
        }

        if(!is_array($replace)){
            $replace = array($replace);
        }

        foreach($find as $key => $value){
            if(($pos = mb_strpos($string, $value)) !== false){
                # If we have no replacement make it empty
                if(!isset($replace[$key])){
                    $replace[$key] = '';
                }

                $string = mb_substr($string, 0, $pos).$replace[$key].mb_substr($string, $pos + mb_strlen($value));
            }
        }

        return $string;
    }


    private static function checkSeed($seedPageName){
        # Don't do the check if we are using random seeds
        if($seedPageName){
            if($seedPageName === true){
                mt_srand(crc32($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
            }
            elseif($seedPageName == 'every second'){
                mt_srand(crc32($_SERVER['REQUEST_URI'].date('Y-m-d-H-i-s')));
            }
            elseif($seedPageName == 'every minute'){
                mt_srand(crc32($_SERVER['REQUEST_URI'].date('Y-m-d-H-i')));
            }
            elseif($seedPageName == 'hourly' OR $seedPageName == 'every hour'){
                mt_srand(crc32($_SERVER['REQUEST_URI'].date('Y-m-d-H')));
            }
            elseif($seedPageName == 'daily' OR $seedPageName == 'every day'){
                mt_srand(crc32($_SERVER['REQUEST_URI'].date('Y-m-d')));
            }
            elseif($seedPageName == 'weekly' OR $seedPageName == 'every week'){
                mt_srand(crc32($_SERVER['REQUEST_URI'].date('Y-W')));
            }
            elseif($seedPageName == 'monthly' OR $seedPageName == 'every month'){
                mt_srand(crc32($_SERVER['REQUEST_URI'].date('Y-m')));
            }
            elseif($seedPageName == 'annually' OR $seedPageName == 'every year'){
                mt_srand(crc32($_SERVER['REQUEST_URI'].date('Y')));
            }
            elseif(preg_match('!every ([0-9.]+) seconds!', $seedPageName, $matches)){
                mt_srand(crc32($_SERVER['REQUEST_URI'].floor(time() / $matches[1])));
            }
            elseif(preg_match('!every ([0-9.]+) minutes!', $seedPageName, $matches)){
                mt_srand(crc32($_SERVER['REQUEST_URI'].floor(time() / ($matches[1] * 60))));
            }
            elseif(preg_match('!every ([0-9.]+) hours!', $seedPageName, $matches)){
                mt_srand(crc32($_SERVER['REQUEST_URI'].floor(time() / ($matches[1] * 3600))));
            }
            elseif(preg_match('!every ([0-9.]+) days!', $seedPageName, $matches)){
                mt_srand(crc32($_SERVER['REQUEST_URI'].floor(time() / ($matches[1] * 86400))));
            }
            elseif(preg_match('!every ([0-9.]+) weeks!', $seedPageName, $matches)){
                mt_srand(crc32($_SERVER['REQUEST_URI'].floor(time() / ($matches[1] * 604800))));
            }
            elseif(preg_match('!every ([0-9.]+) months!', $seedPageName, $matches)){
                mt_srand(crc32($_SERVER['REQUEST_URI'].floor(time() / ($matches[1] * 2620800))));
            }
            elseif(preg_match('!every ([0-9.]+) years!', $seedPageName, $matches)){
                mt_srand(crc32($_SERVER['REQUEST_URI'].floor(time() / ($matches[1] * 31449600))));
            }else{
                throw new Exception($seedPageName. ' Was not a valid spin time option!');
            }
        }
    }
}