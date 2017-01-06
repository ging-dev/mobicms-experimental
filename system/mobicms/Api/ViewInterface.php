<?php

namespace Mobicms\Api;

interface ViewInterface
{
    public function embedJs($js = '', $footer = true);

    public function render();

    public function setCss($cssFile, $media = '');

    public function setLayout($templateFile, $module = false);

    public function setTemplate($templateFile, $key = null, $module = true);
}
