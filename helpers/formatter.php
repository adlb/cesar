<?php

class LexerParser {
    
    //start line with space or 'end of line' right after
    var $lineTransformers = array(
        '|' => 'array',
        'h1.' => 'head1',
        'h2.' => 'head2',
        '* ' => 'bullet1',
        '** ' => 'bullet2',
        '*** ' => 'bullet3',
        '----' => 'separator',
        'q.' => 'quote',
        'p.' => 'preformatted'
    );
    
    //space before and no space after means start. no space before and space after or EoL means stop.
    var $inlineTransformers = array(
        '*' => 'strong',
        '_' => 'italic',
        '-' => 'delete',
        '+' => 'underline',
        '$' => 'monospaced'
    );
    
    //used like {html}blah blah blah{/html}
    var $blockTransformers = array(
        'html' => 'html'
    );
    
    // link: no specific array but [ksjfqksfjsdfkjh]
    // image: no specific array but !qsjqksjdhqsd.jpg!
    
    function lex(&$string) {
        $result = array();
        $pos = 0;
        while ($pos < strlen($string)) {
            $previousPos = $pos;
            $this->ReadHeadLine($string, $pos, $type);
            if ($type == 'separator' || $type == 'preformatted') {
                $result[] = array('type' => $type, 'content' => $this->ReadEndOfLine($string, $pos));
            } elseif ($type == 'quote') {
                $result[] = array('type' => $type, 'content' => $this->ReadQuote($string, $pos));
            } elseif ($type == 'array') {
                $result[] = array('type' => $type, 'content' => $this->ReadArray($string, $pos));
            } elseif ($type == 'bullet1' || $type == 'bullet2' || $type == 'bullet3') {
                $pos = $previousPos;
                $result[] = $this->ReadBullet($string, $pos, 1);
            } else {
                $result[] = array('type' => $type, 'content' => $this->ReadLine($string, $pos, "\n", "\n"));
            }
        }
        return $result;
    }
    
    private function ReadEndOfLine(&$string, &$pos) {
        $previousChain = '';
        $stop = false;
        while(!$stop && $pos < strlen($string)) {
            $current = substr($string, $pos, 1);
            if ($current != "\n")
                $previousChain .= $current;
            else 
                $stop = true;
            $pos++;
        }
        return $previousChain;
    }
    
    private function ReadQuote(&$string, &$pos) {
        $result = $this->ReadLine($string, $pos, "\n");
        while (TryReadSpecificHeadLine('q.', $string, $pos)) {
            array_merge($result, $this->ReadLine($string, $pos, "\n"));
        }
    }
    
    private function ReadArray(&$string, &$pos) {
        $header = $this->ReadEndOfLine($string, $pos);
        $headers = explode('|', $header);
        $headerSize = array();
        $cells = array();
        $rows = array();
        foreach($headers as $head) {
            $headerSize[] = strlen($head);
            $cells[] = array('type' => 'headCell', 'content' => $head);
        }
        $rows[] = array('type' => 'headRow', 'content' => $cells);
        while(TryReadSpecificHeadLine('|', $string, $pos)) {
            $line = $this->ReadEndOfLine($string, $pos);
            $cells = array();
            $inPos = 0;
            foreach($headerSize as $length) {
                $cells[] = array('type' => 'cell', 'content' => substr($line, $inPos, $length));
                $inPos += $length + 1;
            }
            $rows[] = array('type' => 'row', 'content' => $cells);
        }
        return array('type' => 'array', 'content' => $rows);
    }
    
    private function ReadBullet(&$string, &$pos, $level) {
        $bullet = array('* ' => 'bullet1',
                        '** ' => 'bullet2',
                        '*** ' => 'bullet3');
        $result = array();
        $nextPos = $pos;
        while($this->TryReadSpecificHeadLineArray($bullet, $string, $nextPos, $type)) {
            $newLevel = intval(substr($type, -1));
            if ($newLevel < $level)
                return array('type' => 'list', 'content' => $result);
            if ($newLevel == $level) {
                $pos = $nextPos;
                $result[] = array('type' => 'listElement', 'content' => $this->ReadLine($string, $pos, '', "\n"));
            }
            if ($newLevel > $level)
                $result[] = $this->ReadBullet($string, $pos, $level + 1);
            $nextPos = $pos;
        }
        return array('type' => 'list', 'content' => $result);
    }
    
    private function ReadLine(&$string, &$pos, $previousChar, $stack) {
        $result = array();
        $previousChain = '';
        while($pos < strlen($string)) {
            $current = substr($string, $pos, 1);
            $next = substr($string, $pos + 1, 1);
            if ($current == '\r') {
                $pos++;
                continue;
            }
            if ($current == '\\') {
                $previousChain .= substr($string, $pos+1, 1);
                $previousChar = '';
                $pos += 2;
                continue;
            }
            if (($previousChar == ' ' || $previousChar == "\n") && $next != ' ' && in_array($current, array_keys($this->inlineTransformers))) {
                if ($previousChain != '')
                    $result[] = array('type' => 'text', 'content' => $previousChain);
                $previousChain = '';
                $pos++;
                $result[] = array('type' => $this->inlineTransformers[$current], 'content' => $this->ReadLine($string, $pos, ' ', $current));
                $previousChar = 'a';
                continue;
            }
            if ($previousChar != ' ' && $previousChar != "\n" && 
                    ($next == ' ' || $next == "\n" || $next == '' || in_array($next, array_keys($this->inlineTransformers))) && 
                    in_array($current, array_keys($this->inlineTransformers))) {
                if ($previousChain != '')
                    $result[] = array('type' => 'text', 'content' => $previousChain);
                $pos++;
                if ($current != $stack) {
                    $pos--;
                }
                return $result;
            }
            if (($previousChar == ' ' || $previousChar == "\n") && $current == '[') {
                $pos++;
                if ($this->TryReadLink($string, $pos, $display, $link)) {
                    if ($previousChain != '')
                        $result[] = array('type' => 'text', 'content' => $previousChain);
                    $result[] = array('type' => 'link', 'display' => $display, 'link' => $link);
                    $previousChain = '';
                    $previousChar='a';
                    continue;
                } else {
                    $pos--;
                }
            }
            if (($previousChar == ' ' || $previousChar == "\n") && $current == '!') {
                $pos++;
                if ($this->TryReadPicture($string, $pos, $link)) {
                    if ($previousChain != '')
                        $result[] = array('type' => 'text', 'content' => $previousChain);
                    $result[] = array('type' => 'picture', 'content' => $link);
                    $previousChain = '';
                    $previousChar='a';
                    continue;
                } else {
                    $pos--;
                }
            }
            if ($current == '{') {
                $pos++;
                if ($this->TryReadHTML($string, $pos, $html)) {
                    if ($previousChain != '')
                        $result[] = array('type' => 'text', 'content' => $previousChain);
                    $result[] = array('type' => 'html', 'content' => $html);
                    $previousChain = '';
                    $previousChar='a';
                    continue;
                } else {
                    $pos--;
                }
            }
            
            if ($current == "\n") {
                if ($previousChain != '')
                    $result[] = array('type' => 'text', 'content' => $previousChain);
                if ($stack == '' || $current == $stack) {
                    $pos++;
                }
                return $result;
            }
            $previousChar = $current;
            $previousChain .= $current;
            $pos++;
        }
        if ($previousChain != '')
            $result[] = array('type' => 'text', 'content' => $previousChain);
        return $result;
    }
    
    private function ReadHeadLine(&$string, &$pos, &$type) {
        foreach($this->lineTransformers as $k => $newType) {
            if ($this->TryReadSpecificHeadLine($k, $string, $pos)) {
                $type = $newType;
                return true;
            }
        }
        $type = 'normal';
        return true;
    }
    
    private function TryReadSpecificHeadLine($key, &$string, &$pos) {
        if (substr($string, $pos, strlen($key)) == $key) {
            $pos += strlen($key);
            return true;
        }
        return false;
    }
    
    private function TryReadSpecificHeadLineArray($keyArray, &$string, &$pos, &$type) {
        foreach($keyArray as $key => $t) {
            if (substr($string, $pos, strlen($key)) == $key) {
                $pos += strlen($key);
                $type = $t;
                return true;
            }
        }
        return false;
    }
    
    private function TryReadLink(&$string, &$pos, &$display, &$link) {
        $res = preg_match("/^([^\]]+)\]/", substr($string, $pos), $reg);
        if ($res == 1) {
            $key = $reg[1];
            $separator = strpos($key, '|');
            $display = $separator ? substr($key, 0, $separator) : $key;
            $link = $separator ? substr($key, $separator + 1) : $key;
            $pos = $pos + strlen($key) + 1;
            return true;
        }
        return false;
    }
    
    private function TryReadPicture(&$string, &$pos, &$key) {
        $a = substr($string, $pos);
        $res = preg_match("/^([^\!]+)\!/", substr($string, $pos), $reg);
        if ($res == 1) {
            $key = $reg[1];
            $pos = $pos + strlen($key) + 1;
            return true;
        }
        return false;
    }
    
    private function TryReadHTML(&$string, &$pos, &$key) {
        if (substr($string, $pos, 5) != 'html}')
            return false;
        $pos += 5;
        $previousChain = '';
        while($pos < strlen($string)) {
            if (substr($string, $pos, 7) != '{/html}') {
                $previousChain .= substr($string, $pos, 1);
                $pos++;
            } else {
                $key = $previousChain;
                $pos += 7;
                return true;
            }
        }
        $key = $previousChain;
        return true;
    }
}

class Transformer {
    var $lexerParser;
    
    function Transformer() {
        $this->lexerParser = new LexerParser();
    }
    
    function ToHtml($string) {
        $decoded = $this->lexerParser->lex($string);
        return $this->Encode($decoded);
    }
    
    function Encode($decoded) {
        $string = '';
        foreach($decoded as $item) {
            if (!isset($item['type']))
                die("can't find type in array. (".json_encode($item).")");
            switch ($item['type']) {
                case 'text' :
                    $string .= htmlentities($item['content']);
                    break;
                case 'normal' :
                    $string .= '<P>'.$this->Encode($item['content']).'</P>';
                    break;
                case 'head1' :
                    $string .= '<H1>'.$this->Encode($item['content']).'</H1>';
                    break;
                case 'head2' :
                    $string .= '<H2>'.$this->Encode($item['content']).'</H2>';
                    break;
                case 'html' :
                    $string .= $item['content'];
                    break;
                case 'strong' :
                    $string .= '<span style="font-weight:bold;">'.$this->Encode($item['content']).'</span>';
                    break;
                case 'delete' :
                    $string .= '<span style="text-decoration: line-through;">'.$this->Encode($item['content']).'</span>';
                    break;
                case 'italic' :
                    $string .= '<span style="font-style: italic;">'.$this->Encode($item['content']).'</span>';
                    break;
                case 'underline' :
                    $string .= '<span style="text-decoration: underline;">'.$this->Encode($item['content']).'</span>';
                    break;
                case 'monospaced' :
                    $string .= '<span style="font-family:Courier New, Courier, monospace;">'.$this->Encode($item['content']).'</span>';
                    break;
                case 'list' :
                    $string .= '<ul>'.$this->Encode($item['content']).'</ul>';
                    break;
                case 'listElement' :
                    $string.= '<li>'.$this->Encode($item['content']).'</li>';
                    break;
                case 'link' :
                    $string.= '<a href="'.$item['link'].'">'.htmlentities($item['display']).'</a>';
                    break;
                case 'picture' :
                    $string.= '<img src="'.$item['content'].'">';
                    break;
                case 'separator' :
                    $string.= '<hr />';
                    break;
                case 'preformatted' :
                    $string.= '<pre>'.htmlentities($item['content']).'</pre>';
                    break;
            }
        }
        return $string;
    }
}
/*
$t = new Transformer();
$p = new LexerParser();

$string = "----
p.Ce text à la bonne taille ?
";

var_dump($string);
$t = new Transformer();
echo json_encode($p->lex($string));
echo htmlentities($t->ToHtml($string));
echo $t->ToHtml($string);
echo '<HR/>';
*/



?>