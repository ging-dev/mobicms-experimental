<?php
$valid = App::getInstance()->user()->isValid();
?>
<div class="titlebar<?= $valid ? ' private' : '' ?>">
    <div><h1><?= ($valid ? _s('Exit') : _s('Login')) ?></h1></div>
</div>

<div class="content box padding text-center">
    <div style="max-width: 270px; margin: 0 auto">
        <?= $this->form ?>
    </div>
</div>
