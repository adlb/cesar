<?php
ini_set('xdebug.var_display_max_depth', 35);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

class TextDecorator {
    var $type;
    var $identifierIn;
    var $identifierOut;
    var $needSpace;
    var $canIncludeDecorator;
    var $canIncludeNewLine;
    var $canEscape;
    var $keepIdentifierOut;
    
    function __construct($type, $identifierIn, $identifierOut, $needSpace, $canIncludeDecorator, $canIncludeNewLine, $canEscape, $keepIdentifierOut) {
        $this->type = $type;
        $this->identifierIn = $identifierIn;
        $this->identifierOut = $identifierOut;
        $this->needSpace = $needSpace;
        $this->canIncludeDecorator = $canIncludeDecorator;
        $this->canIncludeNewLine = $canIncludeNewLine;
        $this->canEscape = $canEscape;
        $this->keepIdentifierOut = $keepIdentifierOut;
    }
    
    function StartHere(&$string, &$pos, &$currentJob) {
        if (
            $this->needSpace &&
            !(strlen($currentJob) == 0) &&
            substr($currentJob, -1, 1) != ' ')
            return false;
        
        if (
            !substr($string, $pos + strlen($this->identifierIn), 1) ||
            substr($string, $pos + strlen($this->identifierIn), 1) == " " ||
            substr($string, $pos + strlen($this->identifierIn), strlen($this->identifierIn)) == $this->identifierIn)
            return false;
        
        if (substr($string, $pos, strlen($this->identifierIn)) != $this->identifierIn)
            return false;
        
        $pos += strlen($this->identifierIn);
        return true;
    }
    
    function StopHere(&$string, &$pos, &$currentJob, &$type) {
        if ($this->identifierOut == '')
            return false;
        
        if ($this->needSpace && substr($currentJob, -1, 1) == ' ')
            return false;
        
        if (!$this->canIncludeNewLine && substr($string, $pos, 1) == "\n")
            $id = "\n";
        else
            $id = $this->identifierOut;
        
        if (substr($string, $pos, strlen($id)) != $id)
            return false;
        
        if (!$this->keepIdentifierOut)
            $pos += strlen($id);
        
        $type = $this->type;
        return true;
    }

    function Read(&$string, &$pos, $decorators) {
        $content = array();
        $currentJob = '';
        $type = '';
        
        while (
            (strlen($string) > $pos) && 
            !$this->StopHere($string, $pos, $currentJob, $type)
            ) {
            $break = false;
            if ($this->canIncludeDecorator) {
                for($index = 0; $index < count($decorators); $index++) {
                    if ($decorators[$index]->StartHere($string, $pos, $currentJob)) {
                        if ($currentJob != '') {
                            $content[] = array('type' => 'text', 'content' => $currentJob);
                            $currentJob = '';
                        }
                        $content[] = $decorators[$index]->Read($string, $pos, $decorators);
                        $break = true;
                        break;
                    }
                }
            }
            
            if (!$break) {
                if ($this->canEscape && substr($string, $pos, 2) == '\\n') {
                    $pos++;
                    $currentJob .= "\n";
                    $pos++;
                } elseif ($this->canEscape && substr($string, $pos, 1) == '\\') {
                    $pos++;
                    $currentJob .= substr($string, $pos, 1);
                    $pos++;
                } else {
                    $currentJob .= substr($string, $pos, 1);
                    $pos++;
                }
            }
        }
        
        if ($currentJob != '') {
            $content[] = array('type' => 'text', 'content' => $currentJob);
            $currentJob = '';
        }
        
        return array('type' => $this->type, 'content' => $content);
    }
}

class LineDecorator {
    var $type;
    var $identifier;
    var $textDecorators;
    
    function __construct($type, $identifier, $textDecorators) {
        $this->type = $type;
        $this->identifier = $identifier;
        $this->textDecorators = $textDecorators;
    }
    
    function StartHere(&$string, &$pos, $level = 0) {
        if (strlen($string) <= $pos)
            return false;
            
        if (substr($string, $pos, strlen($this->identifier)) != $this->identifier)
            return false;
        
        return true;
    }
    
    function ReadLineOrLines(&$string, &$pos, $level = 0) {
        $pos += strlen($this->identifier);
        
        $decorator = new TextDecorator($this->type, 'xxx', '¤¤EOF¤¤', false, true, false, true, false);
        
        return $decorator->Read($string, $pos, $this->textDecorators);
    }
}

class ArrayLineDecorator extends LineDecorator {
    var $headReader;
    var $rowReader;
    var $textDecoratorsHead;
    var $textDecoratorsRow;
    
    function ArrayLineDecorator($type, $identifier, $textDecorators) {
        $this->textDecoratorsHead = array_merge($textDecorators, array(new TextDecorator('cellhead', $identifier, $identifier, false, true, false, true, true)));
        $this->textDecoratorsRow = array_merge($textDecorators, array(new TextDecorator('cellrow', $identifier, $identifier, false, true, false, true, true)));
        
        parent::__construct($type, $identifier, null);
        $this->headReader = new TextDecorator('head', 'xxx', '¤¤¤EOF¤¤¤', false, true, false, true, false);
        $this->rowReader = new TextDecorator('row', 'xxx', '¤¤¤EOF¤¤¤', false, true, false, true, false);
    }
    
    function ReadLineOrLines(&$string, &$pos, $level = 0) {
        $table = array();
    
        $table[] = $this->headReader->Read($string, $pos, $this->textDecoratorsHead);
        while (parent::StartHere($string, $pos, $level)) {
            $table[] = $this->rowReader->Read($string, $pos, $this->textDecoratorsRow);
        }
        
        return array('type' => $this->type, 'content' => $table);
    }
}

class QuoteLineDecorator extends LineDecorator {

    function __construct($type, $identifier, $textDecorators){
        parent::__construct($type, $identifier, $textDecorators);
    }
    
    function ReadLineOrLines(&$string, &$pos, $level = 0) {
        $pos += strlen($this->identifier);
        $quote = array();
    
        $decorator = new TextDecorator('paragraph', 'xxx', '¤¤EOF¤¤', false, true, false, true, false);
        $quote[] = $decorator->Read($string, $pos, $this->textDecorators);
        while (parent::StartHere($string, $pos, $level)) {
            $pos += strlen($this->identifier);
            $quote[] = $decorator->Read($string, $pos, $this->textDecorators);
        }
        return array('type' => $this->type, 'content' => $quote);
    }
}

class BulletLineDecorator extends LineDecorator {
    var $decorator;
    
    function __construct($type, $identifier, $textDecorators){
        parent::__construct($type, $identifier, $textDecorators);
        $this->decorator = new TextDecorator('listItem', 'xxx', '¤¤EOF¤¤', false, true, false, true, false);
    }
    
    function StartHere(&$string, &$pos, $level = 0) {
        if (strlen($string) <= $pos)
            return false;
        $id = str_repeat($this->identifier, $level + 1).' ';            
        if (substr($string, $pos, strlen($id)) != $id)
            return false;
        
        return true;
    }
    
    function ReadLineOrLines(&$string, &$pos, $level = 0) {
        $list = array();
        
        while (    $this->StartHere($string, $pos, $level) || 
                $this->StartHere($string, $pos, $level + 1)
                ) {
            if ($this->StartHere($string, $pos, $level + 1)) {
                $list[] = $this->ReadLineOrLines($string, $pos, $level + 1);
            } else {
                $id = str_repeat($this->identifier, $level + 1).' ';
                $pos += strlen($id);
                $list[] = $this->decorator->Read($string, $pos, $this->textDecorators);
            }
        }
        
        return array('type' => $this->type, 'content' => $list);
    }
}

class LexerParser {
    var $textDecoratorMinimalSet;
    var $textDecoratorFullSet;
    var $lineDecorators;

    function __construct() {
        $this->textDecoratorMinimalSet = array(
            new TextDecorator('strong', '*', '*', true, true, false, true, false),
            new TextDecorator('italic', '+', '+', true, true, false, true, false),
            new TextDecorator('delete', '-', '-', true, true, false, true, false),
            new TextDecorator('underline', '_', '_', true, true, false, true, false),
            new TextDecorator('monospaced', '$', '$', true, true, false, true, false),
            new TextDecorator('image', '!', '!', true, false, false, false, false),
            new TextDecorator('link', '[', ']', true, false, false, false, false),
            new TextDecorator('icon', '¤', '¤', true, false, false, false, false),
        );
        $this->textDecoratorFullSet = array_merge($this->textDecoratorMinimalSet, array(
            new TextDecorator('html', '{html}', '{/html}', false, false, true, false, false),
            new TextDecorator('preformatted', '{pre}', '{/pre}', false, false, true, false, false),
        ));
        $this->lineDecorators = array(
            new LineDecorator('head1', 'h1. ', $this->textDecoratorMinimalSet),
            new LineDecorator('head2', 'h2. ', $this->textDecoratorMinimalSet),
            new LineDecorator('head3', 'h3. ', $this->textDecoratorMinimalSet),
            new LineDecorator('head4', 'h4. ', $this->textDecoratorMinimalSet),
            new LineDecorator('center', 'c. ', $this->textDecoratorFullSet),
            new LineDecorator('right', 'r. ', $this->textDecoratorFullSet),
            new LineDecorator('separator', '----', $this->textDecoratorMinimalSet),
            new ArrayLineDecorator('table', '|', $this->textDecoratorMinimalSet),
            new QuoteLineDecorator('quote', 'q. ', $this->textDecoratorFullSet),
            new BulletLineDecorator('list', '*', $this->textDecoratorMinimalSet),
            new LineDecorator('paragraph', '', $this->textDecoratorFullSet)
        );
    }
    
    function lex(&$string) {
        $result = array();
        $pos = 0;
        while (strlen($string)>$pos) {
            foreach($this->lineDecorators as $decorator) {
                if ($decorator->StartHere($string, $pos)) {
                    $result[] = $decorator->ReadLineOrLines($string, $pos);
                    break;
                }
            }
        }
        return $result;
    }
}

class Transformer {
    var $lexerParser;
    var $gallery;
    var $articleDal;
    var $translator;
    
    function __construct($gallery, $articleDal, $translator) {
        $this->gallery = $gallery;
        $this->articleDal = $articleDal;
        $this->translator = $translator;
        $this->lexerParser = new LexerParser();
    }

    function ToHtml($string) {
        $decoded = $this->lexerParser->lex($string);
        return $this->Encode($decoded);
    }

    function ToText($string) {
        $decoded = $this->lexerParser->lex($string);
        return $this->EncodeText($decoded);
    }

    function Encode($decoded) {
        $string = '';
        foreach($decoded as $item) {
            if (!isset($item['type']))
                die("can't find type in array. (".json_encode($item).")");
            switch ($item['type']) {
                case 'text' :
                    $string .= nl2br(htmlspecialchars($item['content'], 0, "UTF-8"));
                    break;
                case 'paragraph' :
                    if (count($item['content'])==0) { 
                        $string .= '<P class="text-justify">&nbsp;</P>';
                    } elseif (isset($item['content'][0]['type']) && 
                        ($item['content'][0]['type'] == 'text' ||
                        in_array($item['content'][0]['type'], array_map(array('Transformer', 'GetKeys'), $this->lexerParser->textDecoratorMinimalSet))))
                        $string .= '<P class="text-justify">'.$this->Encode($item['content']).'</P>';
                    else
                        $string .= $this->Encode($item['content']);
                    break;
                case 'head1' :
                    $string .= '<H1>'.$this->Encode($item['content']).'</H1>';
                    break;
                case 'head2' :
                    $string .= '<H2>'.$this->Encode($item['content']).'</H2>';
                    break;
                case 'head3' :
                    $string .= '<H3>'.$this->Encode($item['content']).'</H3>';
                    break;
                case 'head4' :
                    $string .= '<H4>'.$this->Encode($item['content']).'</H4>';
                    break;
                case 'center' :
                    $string .= '<p style="text-align: center;">'.$this->Encode($item['content']).'</p>';
                    break;
                case 'right' :
                    $string .= '<p style="text-align: right;">'.$this->Encode($item['content']).'</p>';
                    break;
                case 'html' :
                    $string .= $item['content'][0]['content'];
                    break;
                case 'strong' :
                    $string .= '<strong>'.$this->Encode($item['content']).'</strong>';
                    break;
                case 'delete' :
                    $string .= '<s>'.$this->Encode($item['content']).'</s>';
                    break;
                case 'italic' :
                    $string .= '<em>'.$this->Encode($item['content']).'</em>';
                    break;
                case 'underline' :
                    $string .= '<u>'.$this->Encode($item['content']).'</u>';
                    break;
                case 'monospaced' :
                    $string .= '<tt>'.$this->Encode($item['content']).'</tt>';
                    break;
                case 'icon' :
                    $string .= '<i class="fa fa-'.$item['content'][0]['content'].'"></i>';
                    break;
                case 'list' :
                    $string .= '<ul>'.$this->Encode($item['content']).'</ul>';
                    break;
                case 'listItem' :
                    $string.= '<li>'.$this->Encode($item['content']).'</li>';
                    break;
                case 'link' :
                    $split = explode('|', $item['content'][0]['content']);
                    if (count($split) == 1) {
                        $string .= $this->CreateLinkTo($split[0], '');
                    } elseif (count($split) == 2) {
                        $string .= $this->CreateLinkTo($split[0], $split[1]);
                    } elseif (count($split) == 3) {
                        $string .= $this->CreateLinkTo($split[0], $split[1], $split[2]);
                    } else {
                        $string .= "???";
                    }
                    break;
                case 'image' :
                    $split = explode('|', $item['content'][0]['content']);
                    if (count($split) == 1) {
                        $string .= $this->CreateLinkTo('', '@'.$split[0]);
                    } elseif (count($split) == 2) {
                        $string .= $this->CreateLinkTo('', '@'.$split[0], $split[1]);
                    } elseif (count($split) > 2) {
                        $string .= $this->CreateLinkTo($split[2], '@'.$split[0], $split[1]);
                    } else {
                        $string .= "???";
                    }
                    break;
                case 'separator' :
                    $string.= '<hr style="clear:both;"/>';
                    break;
                case 'preformatted' :
                    $string.= '<pre>'.htmlentities($item['content'][0]['content']).'</pre>';
                    break;
                case 'table' :
                    $string.= '<table class="table">'.$this->Encode($item['content']).'</table>';
                    break;
                case 'head' :
                case 'row' :
                    $string.= '<tr>'.$this->Encode($item['content']).'</tr>';
                    break;
                case 'cellhead' :
                    $string.= '<th>'.$this->Encode($item['content']).'</th>';
                    break;
                case 'cellrow' :
                    $string.= '<td>'.$this->Encode($item['content']).'</td>';
                    break;
                case 'quote' :
                    $string.= '<blockquote>'.$this->Encode($item['content']).'</blockquote>';
                    break;
            }
        }
        return $string;
    }
    
    function EncodeText($decoded) {
        $string = '';
        foreach($decoded as $item) {
            if (!isset($item['type']))
                die("can't find type in array. (".json_encode($item).")");
            switch ($item['type']) {
                case 'text' :
                    $string .= $item['content'];
                    break;
                case 'paragraph' :
                    $string .= $this->EncodeText($item['content']).PHP_EOL;
                    break;
                case 'head1' :
                    $string .= PHP_EOL.$this->EncodeText($item['content']).PHP_EOL;
                    break;
                case 'head2' :
                    $string .= PHP_EOL."  ".$this->EncodeText($item['content']).PHP_EOL;
                    break;
                case 'head3' :
                    $string .= PHP_EOL."    ".$this->EncodeText($item['content']).PHP_EOL;
                    break;
                case 'head4' :
                    $string .= PHP_EOL."      ".$this->EncodeText($item['content']).PHP_EOL;
                    break;
                case 'center' :
                    $string .= $this->EncodeText($item['content']);
                    break;
                case 'right' :
                    $string .= $this->EncodeText($item['content']);
                    break;
                case 'html' :
                    $string .= ''; //$item['content'][0]['content'];
                    break;
                case 'strong' :
                    $string .= $this->EncodeText($item['content']);
                    break;
                case 'delete' :
                    $string .= $this->EncodeText($item['content']);
                    break;
                case 'italic' :
                    $string .= $this->EncodeText($item['content']);
                    break;
                case 'underline' :
                    $string .= $this->EncodeText($item['content']);
                    break;
                case 'monospaced' :
                    $string .= $this->EncodeText($item['content']);
                    break;
                case 'list' :
                    $string .= $this->EncodeText($item['content']);
                    break;
                case 'listItem' :
                    $string.= '* '.$this->EncodeText($item['content']).PHP_EOL;
                    break;
                case 'link' :
                    $split = explode('|', $item['content'][0]['content']);
                    if (count($split) == 1) {
                        $string .= $this->CreateLinkToTxt($split[0], '');
                    } elseif (count($split) == 2) {
                        $string .= $this->CreateLinkToTxt($split[0], $split[1]);
                    } elseif (count($split) == 3) {
                        $string .= $this->CreateLinkToTxt($split[0], $split[1], $split[2]);
                    } else {
                        $string .= "???";
                    }
                    break;
                case 'image' :
                    $split = explode('|', $item['content'][0]['content']);
                    if (count($split) == 1) {
                        $string .= $this->CreateLinkToTxt('', '@'.$split[0]);
                    } elseif (count($split) == 2) {
                        $string .= $this->CreateLinkToTxt('', '@'.$split[0], $split[1]);
                    } elseif (count($split) > 2) {
                        $string .= $this->CreateLinkToTxt($split[2], '@'.$split[0], $split[1]);
                    } else {
                        $string .= "???";
                    }
                    break;
                case 'separator' :
                    $string.= '-------------------------------'.PHP_EOL;
                    break;
                case 'preformatted' :
                    $string.= $this->EncodeText($item['content']);
                    break;
                case 'table' :
                    $string.= $this->EncodeText($item['content']);
                    break;
                case 'head' :
                case 'row' :
                    $string.= $this->EncodeText($item['content']).PHP_EOL;
                    break;
                case 'cellhead' :
                    $string.= $this->EncodeText($item['content']).' | ';
                    break;
                case 'cellrow' :
                    $string.= $this->EncodeText($item['content']).' | ';
                    break;
                case 'quote' :
                    $string.= $this->EncodeText($item['content']).' | ';
                    break;
            
            }
        }
        return $string;
    }
    
    private function CreateLinkTo($link, $display, $pos='right') {
        if (substr($display,0,1) == '@' && $this->gallery->TryGet(substr($display, 1), $media)) {
            $class = $pos == 'right' ? 'cx_right' : (
                        $pos == 'left' ? 'cx_left' :
                        'cx_center');
            $display = '<img class="'.$class.'" src="'.$media['file'].'">';
            if ($link == '' && $media['original'] != '')
                $link = $media['original'];
        } else {
            $display = htmlspecialchars($display);
        }
        
        if ($link == 'home') {
            if ($display == '')
                $display = $link;
            $link = url(array('controller' => 'site', 'view' => 'home'));
        } elseif ($link == 'donate') {
            if ($display == '')
                $display = $link;
            $link = url(array('controller' => 'donation', 'view' => 'donate'));
        } elseif (intval($link)>0 && $this->articleDal->TryGet(intval($link), $article)) {
            if ($display == '') {
                $display = htmlspecialchars($this->translator->GetTranslation($article['titleKey']));
            }
            $link = url(array('controller' => 'site', 'view' => 'article', 'id' => $article['id']));
        } elseif (substr($link, 0, 1) == '@' && $this->gallery->TryGet(substr($link, 1), $media)) {
            if ($display == '') {
                $display = htmlspecialchars($media['name']);
            }
            if ($media['original'] == '')
                $link = $media['file'];
            else
                $link = $media['original'];
        } elseif ($link != '') {
            if ($display == '') {
                $display = htmlspecialchars($link);
            }
        } else {
            return $display;
        }
        return '<a href="'.$link.'">'.$display.'</a>';
    }
    
    private function CreateLinkToTxt($link, $display, $pos='right') {
        if (substr($display,0,1) == '@' && $this->gallery->TryGet(substr($display, 1), $media)) {
            $display = $media['file'];
        } else {
            $display = $display;
        }
        
        if ($link == 'home') {
            if ($display == '')
                $display = $link;
            $link = url(array('controller' => 'site', 'view' => 'home'));
        } elseif ($link == 'donate') {
            if ($display == '')
                $display = $link;
            $link = url(array('controller' => 'donation', 'view' => 'donate'));
        } elseif (intval($link)>0 && $this->articleDal->TryGet(intval($link), $article)) {
            if ($display == '') {
                $display = $this->translator->GetTranslation($article['titleKey']);
            }
            $link = url(array('controller' => 'site', 'view' => 'article', 'id' => $article['id']));
        } elseif (substr($link, 0, 1) == '@' && $this->gallery->TryGet(substr($link, 1), $media)) {
            if ($display == '') {
                $display = $media['name'];
            }
            $link = $media['file'];
        } elseif ($link != '') {
            if ($display == '') {
                $display = $link;
            }
        } else {
            return $display;
        }
        if ($link != $display) {
            return $display.' ('.$link.')';
        }
        return $link;
    }
    
    private function GetKeys($a) {
        return $a->type;
    }
}

?>