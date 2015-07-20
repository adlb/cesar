<?php

class Viewer {
    var $articleDal;
    var $authentication;
    var $formatter;
    var $translator;
    var $textDal;
    var $textShortDal;
    var $gallery;
    var $config;

    function Viewer($articleDal, $authentication, $formatter, $translator, $textDal, $textShortDal, $gallery, $config) {
        $this->articleDal = $articleDal;
        $this->authentication = $authentication;
        $this->formatter = $formatter;
        $this->translator = $translator;
        $this->textDal = $textDal;
        $this->textShortDal = $textShortDal;
        $this->gallery = $gallery;
        $this->config = $config;
    }

    private function view_container(&$obj) {
        $obj['title'] = $this->config->current['Title'];
        $obj['languages'] = $this->config->current['Languages'];
    }

    private function view_editHelp(&$obj) {
    }
}
?>