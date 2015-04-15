<?php
class ControllerTranslationManager {

    var $container = 'container';
    var $textDal;
    var $textShortDal;
    var $translator;
    var $authentication;

    function ControllerTranslationManager($services) {
        $this->textDal = $services['textDal'];
        $this->translator = $services['translator'];
        $this->authentication = $services['authentication'];
        $this->textShortDal = $services['textShortDal'];
    }

    function action_saveText(&$obj) {
        $nextText = $_POST['nextText'];

        $key = isset($_POST['key']) ? $_POST['key'] : die('ERREUR, what are you trying to do ?');
        $lg = isset($_POST['lg']) ? $_POST['lg'] : die('ERREUR, what are you trying to do ?');

        $action = isset($_POST['actionpushed']) ? $_POST['actionpushed'] : die('ERREUR, what are you trying to do ?');

        if ($action == 'validate' && !$this->authentication->CheckRole('Administrator'))
            die('ERREUR, what are you trying to do ?');

        $olds = $this->textDal->GetWhere(array('key' => $key));
        if (count($olds) == 0)
            die('ERREUR, what are you trying to do ?');

        if (!isset($_POST['id']) ||
                trim($_POST['id']) == '' ||
                !$this->textDal->TryGet($_POST['id'], $text)) {
            $text = array();
            $text['key'] = $key;
            $text['language'] = $lg;
            $text['prefetch'] = $olds[0]['prefetch'];
            $text['text'] = $olds[0]['text'];
            $text['usage'] = $olds[0]['usage'];
            $text['textStatus'] = 'notValidated';
        }

        if ($action == 'validate') {
            $text['nextText'] = $nextText;
            $text['text'] = $nextText;
            $text['textStatus'] = 'ready';
        } elseif ($action == 'save') {
            $text['nextText'] = $nextText;
            $text['textStatus'] = 'notTranslated';
        } else {
            $text['nextText'] = $nextText;
            $text['textStatus'] = 'notValidated';
        }

        if (!$this->textDal->TrySave($text)) {
            $obj['text'] = $_POST;
            $obj['errors'][] = ':CANT_SAVE_TRANSLATION';
            return 'editText';
        }

        redirectTo(array('controller' => 'translationManager', 'view' => 'translationList'), $obj['errors']);
    }

    function view_translationList(&$obj, &$view) {
        $obj['texts'] = array();
        $obj['languages'] = $this->translator->languages;
        foreach($this->textShortDal->GetWhere(array()) as $text) {
            if (!isset($obj['texts'][$text['key']]))
                $obj['texts'][$text['key']] = array();
            $obj['texts'][$text['key']][$text['language']] = $text;
        }
    }

    function view_editText(&$obj, &$view) {
        $obj['key'] = $_GET['key'];
        $obj['lg'] = isset($_GET['lg']) && in_array($_GET['lg'], $this->translator->languages) ? $_GET['lg'] : $this->translator->languages[0];

        $texts = $this->textDal->GetWhere(array('key' => $_GET['key'], 'language' => $obj['lg']));
        if (count($texts) == 0) {
            $obj['text'] = array(
                'id' => '',
                'nextText' => '',
                'key' => $_GET['key']
            );
        } else {
            $obj['text'] = $texts[0];
        }
        $obj['texts'] = $texts = $this->textDal->GetWhere(array('key' => $_GET['key']));
        $obj['languages'] = $this->translator->languages;
        $obj['lg'] = isset($_GET['lg']) && in_array($_GET['lg'], $this->translator->languages) ? $_GET['lg'] : $this->translator->languages[0];
    }
}
?>