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
				if ($this->canEscape && substr($string, $pos, 1) == '\\') {
					$pos++;
				}
				
				$currentJob .= substr($string, $pos, 1);
				$pos++;
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
			$quote[] = $decorator->Read($string, $pos, $this->textDecorators);
		}
		return array('type' => $this->type, 'content' => $quote);
	}
}

class PreformatedLineDecorator extends LineDecorator {

	function __construct($type, $identifier, $textDecorators){
		parent::__construct($type, $identifier, $textDecorators);
	}
	
	function ReadLineOrLines(&$string, &$pos, $level = 0) {
		$pos += strlen($this->identifier);
		$quote = array();
	
		$decorator = new TextDecorator('paragraph', 'xxx', '¤¤EOF¤¤', false, true, false, true, false);
		$quote[] = $decorator->Read($string, $pos, $this->textDecorators);
		while (parent::StartHere($string, $pos, $level)) {
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
		
		while (	$this->StartHere($string, $pos, $level) || 
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
			new TextDecorator('italic', '_', '_', true, true, false, true, false),
			new TextDecorator('delete', '-', '-', true, true, false, true, false),
			new TextDecorator('underline', '+', '+', true, true, false, true, false),
			new TextDecorator('monospaced', '$', '$', true, true, false, true, false),
			new TextDecorator('image', '!', '!', false, false, false, false, false),
			new TextDecorator('link', '[', ']', false, false, false, false, false),
		);
		$this->textDecoratorFullSet = array_merge($this->textDecoratorMinimalSet, array(
			new TextDecorator('html', '{html}', '{/html}', false, false, true, false, false),
			new TextDecorator('preformatted', '{pre}', '{/pre}', false, false, true, false, false),
		));
		$this->lineDecorators = array(
			new LineDecorator('head1', 'h1. ', $this->textDecoratorMinimalSet),
			new LineDecorator('head2', 'h2. ', $this->textDecoratorMinimalSet),
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

    function __construct($gallery) {
        $this->gallery = $gallery;
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
                case 'paragraph' :
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
                    $string .= '<span class="cx_bold">'.$this->Encode($item['content']).'</span>';
                    break;
                case 'delete' :
                    $string .= '<span class="cx_linethrough">'.$this->Encode($item['content']).'</span>';
                    break;
                case 'italic' :
                    $string .= '<span class="cx_italic">'.$this->Encode($item['content']).'</span>';
                    break;
                case 'underline' :
                    $string .= '<span class="cx_underline">'.$this->Encode($item['content']).'</span>';
                    break;
                case 'monospaced' :
                    $string .= '<span class="cx_monospace">'.$this->Encode($item['content']).'</span>';
                    break;
                case 'list' :
                    $string .= '<ul>'.$this->Encode($item['content']).'</ul>';
                    break;
                case 'listItem' :
                    $string.= '<li>'.$this->Encode($item['content']).'</li>';
                    break;
                case 'link' :
					//var_dump($item['content']);
                    //$string.= '<a href="'.$item['content'].'">'.htmlentities($item['content']).'</a>';
                    break;
                case 'image' :
                    if ($this->gallery->TryGet($item['content'][0]['content'], $image))
                        $string.= '<img src="'.$image['file'].'">';
                    break;
                case 'separator' :
                    $string.= '<hr />';
                    break;
                case 'preformatted' :
					$string.= '<pre>'.htmlentities($this->Encode($item['content'])).'</pre>';
                    break;
				case 'table' :
                    $string.= '<table>'.$this->Encode($item['content']).'</table>';
                    break;
				case 'table' :
                    $string.= '<table>'.$this->Encode($item['content']).'</table>';
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
            }
        }
        return $string;
    }
}

?>