<?php
$app = App::getInstance();
$url = $app->request()->getBaseUrl();
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

<!-- Слайдер с Админскими кнопками -->
<div class="content slider close">
    <ul class="nav nav-pills nav-justified">
        <li<?= $app->router()->getQuery(0) == false ? ' class="active"' : '' ?>>
            <a href="<?= $url ?>/online/"><i class="user fw"></i><?= _s('Users') ?></a>
        </li>
        <li<?= $app->router()->getQuery(0) == 'history' ? ' class="active"' : '' ?>>
            <a href="<?= $url ?>/online/history/"><i class="sort-amount-desc fw"></i><?= _s('History') ?></i></a>
        </li>
        <!-- Показываем только для администрации -->
        <?php if ($app->user()->get()->rights): ?>
            <li>
                <a href="<?= $url ?>/online/guests/"><i class="group fw"></i><?= _s('Guests') ?></a>
            </li>
            <li>
                <a href="<?= $url ?>/online/ip/"><i class="bolt fw"></i><?= _s('IP Activity') ?></a>
            </li>
        <?php endif; ?>
    </ul>
</div>

<!-- Список онлайн -->
<div class="content box m-list">
    <?php if ($app->user()->isValid() || Config\Users::$allowGuestsOnlineLists): ?>
        <h2><?= $this->list_header ?></h2>
        <ul class="striped">
            <?php if (isset($this->list)): ?>
                <?php foreach ($this->list as $profile): ?>
                    <?php include $this->getPath('include.user.php') ?>
                <?php endforeach ?>
            <?php else: ?>
                <li style="text-align: center; padding: 27px"><?= _s('List is empty') ?></li>
            <?php endif ?>
        </ul>
        <h3><?= _s('Total') ?>:&#160;<?= $this->total ?></h3>
    <?php else: ?>
        <div class="content box padding text-center">
            <?= _s('Only for registered users') ?>
        </div>
    <?php endif ?>
</div>
