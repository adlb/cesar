<?php

class Translator {
    var $textDal;
    var $groupedCache;
    var $language;
    var $defaultLanguage;
    var $defaultGroupKey = 'GENERAL';
    var $languages;

    function __construct($config, $textDal, $language, $isAdmin) {
        $this->textDal = $textDal;
        $this->groupedCache = array();
        $this->defaultLanguage = $config->current['Languages'][0];
        
        //can be override with values
        $this->languages = array();
        $this->language = $language; 
        
        if (!$isAdmin) {
            foreach($config->current['ActiveLanguages'] as $lang) {
                $this->languages[] = array('name' => $lang, 'active' => true);
            }
            
            if (!in_array($language, $config->current['ActiveLanguages'])) {
                $this->language = $config->current['ActiveLanguages'][0];
            }
        } else {
            foreach($config->current['Languages'] as $lang) {
                $this->languages[] = array('name' => $lang, 'active' => in_array($lang, $config->current['ActiveLanguages']));
            }
            
            if (!in_array($language, $config->current['Languages'])) {
                $this->language = $config->current['ActiveLanguages'][0];
            }
        }
        

        $this->fetchGroup($this->defaultGroupKey);
        $this->fetchGroup('');
    }

    function fetchGroup($groupKey) {
        if ($groupKey == '') {
            $lines = $this->textDal->GetWhere(array('prefetch' => true, 'language' => $this->language));
            foreach($lines as $line) {
                $goupedCache[$line['key']] = $line['text'];
            }

            if ($this->defaultLanguage != $this->language) {
                $lines = $this->textDal->GetWhere(array('prefetch' => true, 'language' => $this->defaultLanguage));
                foreach($lines as $line) {
                    if (!isset($this->goupedCache[$line['key']]))
                        $this->goupedCache[$line['key']] = $line['text'];
                }
            }
        } else {
            if (!isset($this->groupedCache[$groupKey]))
                $this->groupedCache[$groupKey] = array();
            $lines = $this->textDal->GetWhere(array('key' => $this->defaultGroupKey, 'language' => $this->language));
            if (count($lines) == 1) {
                foreach(explode("\n", $lines[0]['text']) as $line) {
                    $split = explode('|', $line);
                    if (count($split) >= 2)
                        $this->groupedCache[$groupKey][$split[0]] = $split[1];
                }
            }
        }
    }
    
    function GetTranslation($key) {
        if (!is_array($key)) {
            return $this->InternalGetTranslation($key);
        } else {
            $k = array_shift($key);
            $trad = $this->InternalGetTranslation($k);
            for($i = 0; $i < count($key); $i++) {
                $trad = str_replace('{'.$i.'}', $key[$i], $trad);
            }
            return $trad;
        }
    }

    //update translation and save with 'ready' : case where you write the article directly
    //  * update the text field + nextText field.
    //  * change other language to :
    //              'don't exists' => do nothing
    //              ready => translationToBeUpdated
    //              translationToBeValidated => translationToBeUpdated
    //              translationToBeUpdated => translationToBeUpdated
    //              firstTranslationToBeValidated => toBeTranslated
    //              toBeTranslated => toBeTranslated
    //update translation and just save it : case where you want to save translation work:
    //  * update the nextText field but do not change status.
    //  * If no previous text => toBeTranslated
    //update translation and ask to validate it : case 'submitForValidation'
    //  * update the nextText field and change status.
    //              'don't exists' => firstTranslationToBeValidated
    //              ready => translationToBeValidated
    //              translationToBeValidated => translationToBeValidated
    //              translationToBeUpdated => translationToBeValidated
    //              firstTranslationToBeValidated => firstTranslationToBeValidated
    //              toBeTranslated => firstTranslationToBeValidated
    //update translation and ask to validate it : case 'Validate'
    //  * update the text and nextText field and change status.
    //              'don't exists' => 'ready'
    //              ready => 'ready'
    //              translationToBeValidated => 'ready'
    //              translationToBeUpdated => 'ready'
    //              firstTranslationToBeValidated => 'ready'
    //              toBeTranslated => 'ready'
    
    private function Validate(&$text) {
        $text['text'] = $text['nextText'];
        $text['textStatus'] = 'ready';
    }
    
    private function Save(&$text) {
    }
    
    private function SubmitForValidation(&$text) {
        if (in_array($text['textStatus'], array('ready', 'translationToBeValidated', 'translationToBeUpdated')))
            $text['textStatus'] = 'translationToBeValidated';
        else
            $text['textStatus'] = 'firstTranslationToBeValidated';
    }
    
    private function NeedUpdate(&$text) {
        if (in_array($text['textStatus'], array('ready', 'translationToBeValidated', 'translationToBeUpdated')))
            $text['textStatus'] = 'translationToBeUpdated';
        else
            $text['textStatus'] = 'toBeTranslated';
    }
    
    function DirectUpdate($language, $key, $value, $prefetch, $usage) {
        if ($value == '') {
            $this->textDal->DeleteWhere(array('key' => $key));
            return '';
        }
        
        $key = $key == '' ? $this->CreateNewKey($value) : $key;
        
        $text = array(
                'key' => $key,
                'language' => $language,
                'prefetch' => $prefetch,
                'text' => $value,
                'nextText' => $value,
                'usage' => $usage,
                'textStatus' => 'ready',
            );
        
        $oldTexts = $this->textDal->GetWhere(array('key' => $key));
        
        foreach($oldTexts as $oldText) {
            if ($oldText['language'] == $language) {
                $text['id'] = $oldText['id'];
            } else {
                $this->NeedUpdate($oldText);
                $this->textDal->TrySave($oldText);
            }
        }
        
        $this->textDal->TrySave($text);
        return $key;
    }
    
    function UpdateTranslation($action, $language, $key, $value, $prefetch, $usage) {
        if ($key == '')
            return;

        $oldTexts = $this->textDal->GetWhere(array('key' => $key));
        
        if (count($oldTexts) == 0) {
            $oldText = array(
                'id' => '',
                'key' => $key,
                'language' => $language,
                'prefetch' => $prefetch,
                'text' => '',
                'nextText' => $value,
                'usage' => $usage,
                'textStatus' => 'notTranslated',
            );
        } else {
            $oldText = $oldTexts[0];
        }
        
        $text = array(
            'key' => $key,
            'language' => $language,
            'prefetch' => $oldText['prefetch'],
            'text' => '',
            'nextText' => $value,
            'usage' => $oldText['usage'],
            'textStatus' => 'toBeTranslated',
        );

        $oldTexts = $this->textDal->GetWhere(array('key' => $key, 'language' => $language));
        if (count($oldTexts) != 0) {
            $text['id'] = $oldTexts[0]['id'];
            $text['text'] = $oldTexts[0]['text'];
            $text['textStatus'] = $oldTexts[0]['textStatus'];
        }
        
        switch ($action) {
            case 'save':
                $this->Save($text);
                break;
            case 'validate':
                $this->Validate($text);
                break;
            case 'submitForValidation':
                $this->SubmitForValidation($text);
                break;
            default:
                break;
        }

        $this->textDal->TrySave($text);
        return;
    }
    
    //if key starts with : that means that it is a grouped translation
    private function InternalGetTranslation($key) {
        if ($key == '')
            return $key;
        $split = explode(':', $key);
        if (count($split) == 2 && $split[0] == 'file' && file_exists('fixedArticles/'.$split[1].'.txt')) {
            return file_get_contents('fixedArticles/'.$split[1].'.txt');
        }
        
        if (count($split) == 2) {
            $group = $split[0] == '' ? $this->defaultGroupKey : $split[0];
            return htmlspecialchars($this->GetGroupedTranslation($group, substr($key, 1)));
        }
        
        $lines = $this->textDal->GetWhere(array('key' => $key, 'language' => $this->language));
        if (count($lines) == 0) {
            $lines = $this->textDal->GetWhere(array('key' => $key, 'language' => $this->defaultLanguage));
            if (count($lines) == 0)
                return '['.$key.']';
        }
        return $lines[0]['text'];
    }

    private function CreateNewKey($string) {
        $alphabet = array(
            'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
        );
        $newKey = strtr(trim($string), $alphabet);
        $newKey = strtolower(preg_replace("/\W+/", "-", $newKey));
        $newKey = substr($newKey, 0, 20);

        $new = $newKey;
        $result = $newKey;
        do {
            $exists = $this->textDal->GetWhere(array('key' => $newKey));
            $result = $newKey;
            $newKey = $new.substr(md5(uniqid(mt_rand(), true)), 0, 3);
        } while (count($exists) > 0);

        return $result;
    }

    private function GetGroupedTranslation($groupedKey, $key) {
        if (!isset($this->groupedCache[$groupedKey]))
            $this->groupedCache[$groupedKey] = array();
        
        if (isset($this->groupedCache[$groupedKey][$key]))
            return $this->groupedCache[$groupedKey][$key];
        
        //Not in cache then add it in cache and DB
        $this->groupedCache[$groupedKey][$key] = $key;
        $lines = array();
        foreach($this->groupedCache[$groupedKey] as $k => $v)
            $lines[] = $k.'|'.$v;
        $dbLines = $this->textDal->GetWhere(array('key' => $groupedKey, 'language' => $this->language));
        if (count($dbLines) == 0) {
            $new = array(
                'key' => $groupedKey,
                'language' => $this->language,
                'prefetch' => false,
                'usage' => 'grouped',
                'textStatus' => 'notTranslated',
            );
        } else {
            $new = $dbLines[0];
        }
        $new['text'] = join("\n", $lines);
        $new['nextText'] = join("\n", $lines);
        $this->textDal->TrySave($new);
        return $this->groupedCache[$groupedKey][$key];
    }
}

?>