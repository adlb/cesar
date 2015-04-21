<?php

class Translator {
    var $textDal;
    var $groupedCache;
    var $language;
    var $defaultLanguage;
    var $defaultGroupKey = 'GROUPED';
    var $languages;

    function Translator($textDal, $language, $languages) { //First element of $languages is default
        $this->textDal = $textDal;
        $this->groupedCache = array();
        $this->language = $language;
        $this->defaultLanguage = $languages[0];
        $this->languages = $languages;

        $lines = $this->textDal->GetWhere(array('key' => $this->defaultGroupKey, 'language' => $this->language));
        if (count($lines) == 1) {
            foreach(explode("\n", $lines[0]['text']) as $line) {
                $split = explode('|', $line);
                if (count($split) >= 2)
                    $this->groupedCache[$split[0]] = $split[1];
            }
        }

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
    }

    function GetTranslation($key) {
        if (!is_array($key)) {
            return $this->InternalGetTranslation($key);
        } else {
            $k = array_shift($key);
            $trad = $this->services['translator']->InternalGetTranslation($k);
            for($i = 0; $i < count($key); $i++) {
                $trad = str_replace('{'.$i.'}', $key[$i], $trad);
            }
            return $trad;
        }
    }

    function UpdateTranslation($language, $key, $value, $prefetch, $usage) { //return the key
        if ($value == '') {
            $this->textDal->DeleteWhere(array('key' => $key));
            return '';
        }
        $key = $key == '' ? $this->CreateNewKey($value) : $key;

        $newValue = array(
                'key' => $key,
                'language' => $language,
                'prefetch' => $prefetch,
                'text' => $value,
                'nextText' => $value,
                'usage' => $usage,
                'textStatus' => 'ready',
            );

        $oldValues = $this->textDal->GetWhere(array('key' => $key, 'language' => $language));
        if (count($oldValues) == 0) {
            $this->textDal->DeleteWhere(array('key' => $key));
            $this->textDal->TrySave($newValue);
            return $key;
        }

        $oldValue = $oldValues[0];
        if ($newValue['text'] == $oldValue['text']) {
            $newValue['id'] = $oldValue['id'];
            $this->textDal->TrySave($newValue);
            return $key;
        }

        $oldValues = $this->textDal->GetWhere(array('key' => $key));
        foreach($oldValues as $oldValue) {
            if ($oldValue['language'] == $language) {
                $newValue['id'] = $oldValue['id'];
                $this->textDal->TrySave($newValue);
            } else {
                $oldValue['nextTextStatus'] = 'notTranslated';
                $this->textDal->TrySave($oldValue);
            }
        }

        return $key;
    }

    //if key starts with : that means that it is a grouped translation
    private function InternalGetTranslation($key) {
        if ($key == '')
            return $key;
        if (substr($key, 0, 1) == ':')
            return htmlspecialchars($this->GetGroupedTranslation(substr($key, 1)));

        $lines = $this->textDal->GetWhere(array('key' => $key, 'language' => $this->language));
        if (count($lines) == 0) {
            $lines = $this->textDal->GetWhere(array('key' => $key, 'language' => $this->defaultLanguage));
            if (count($lines) == 0)
                return '['.$key.']';
        }
        return htmlspecialchars($lines[0]['text']);
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

    private function GetGroupedTranslation($key) {
        if (isset($this->groupedCache[$key]))
            return $this->groupedCache[$key];
        $this->groupedCache[$key] = $key;
        $lines = array();
        foreach($this->groupedCache as $k => $v)
            $lines[] = $k.'|'.$v;
        $dbLines = $this->textDal->GetWhere(array('key' => $this->defaultGroupKey, 'language' => $this->language));
        if (count($dbLines) == 0) {
            $new = array(
                'key' => $this->defaultGroupKey,
                'language' => $this->language,
                'prefetch' => true,
                'usage' => 'grouped',
                'textStatus' => 'notTranslated',
            );
        } else {
            $new = $dbLines[0];
        }
        $new['text'] = join("\n", $lines);
        $new['nextText'] = join("\n", $lines);
        $this->textDal->TrySave($new);
        return $this->groupedCache[$key];
    }
}

?>