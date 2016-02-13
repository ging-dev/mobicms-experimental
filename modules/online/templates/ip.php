<?php
$app = App::getInstance();
?>
<!-- Заголовок раздела -->
<div class="titlebar">
    <div class="button"></div>
    <div><h1><?= _s('Who is online') ?></h1></div>
    <div class="separator"></div>
    <div class="button">
        <!-- Кнопка меню -->
        <button type="button" class="slider-button" title="<?= _s('Control') ?>">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
</div>

<!-- Слайдер с кнопками -->
<div class="content slider close">
    <ul class="nav nav-pills nav-justified">
        <li>
            <a href="../"><i class="user fw"></i><?= _s('Users') ?></a>
        </li>
        <li>
            <a href="../history/"><i class="sort-amount-desc fw"></i><?= _s('History') ?></i></a>
        </li>
        <li>
            <a href="../guests/"><i class="group fw"></i><?= _s('Guests') ?></a>
        </li>
        <li class="active">
            <a href="../ip/"><i class="bolt fw"></i><?= _s('IP Activity') ?></a>
        </li>
    </ul>
</div>

<!-- Список онлайн -->
<div class="content box m-list">
    <h2><?= _s('IP Activity') ?></h2>
    <ul class="striped">
        <?php if (isset($this->list)): ?>
            <?php foreach ($this->list as $var): ?>
                <?php $result = explode('::', $var) ?>
                <li>
                    <a href="<?= $app->request()->getBaseUrl() ?>/whois/<?= $result[1] ?>" class="mlink">
                        <span class="danger"><i class="refresh fw"></i><strong><?= $result[0] ?></strong></span>&#160;&#160;
                        <i class="bolt fw"></i><?= $result[1] ?></a>
                </li>
            <?php endforeach ?>
        <?php else: ?>
            <li style="text-align: center; padding: 27px"><?= _s('List is empty') ?></li>
        <?php endif ?>
    </ul>
    <h3><?= _s('Total') ?>:&#160;<?= $this->total ?></h3>
</div>
