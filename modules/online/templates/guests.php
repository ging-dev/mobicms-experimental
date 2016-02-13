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

<!-- Слайдер с Админскими кнопками -->
<div class="content slider close">
    <ul class="nav nav-pills nav-justified">
        <li>
            <a href="../"><i class="user fw"></i><?= _s('Users') ?></a>
        </li>
        <li>
            <a href="../history/"><i class="sort-amount-desc fw"></i><?= _s('History') ?></i></a>
        </li>
        <li class="active">
            <a href="../guests/"><i class="group fw"></i><?= _s('Guests') ?></a>
        </li>
        <li>
            <a href="../ip/"><i class="bolt fw"></i><?= _s('IP Activity') ?></a>
        </li>
    </ul>
</div>

<!-- Список онлайн -->
<div class="content box m-list">
    <?php if ($app->user()->isValid() || Config\Users::$allowGuestsOnlineLists): ?>
        <h2><?= _s('Guests') ?></h2>
        <ul class="striped">
            <?php if (isset($this->list)): ?>
                <?php foreach ($this->list as $guest): ?>
                    <li>
                        <!-- Кнопка выпадающего меню -->
                        <div>
                            <a href="#" class="lbtn dropdown dropdown-toggle" data-toggle="dropdown"></a>
                            <ul class="dropdown-menu" role="menu">
                                <li class="dropdown-header">IP Whois</li>
                                <li>
                                    <a href="<?= $app->request()->getBaseUrl() ?>/whois/<?= long2ip($guest['ip']) ?>"><i class="search fw"></i>IP</a>
                                </li>
                                <?php if (isset($guest['ip_via_proxy']) && !empty($guest['ip_via_proxy'])): ?>
                                    <li>
                                        <a href="<?= $app->request()->getBaseUrl() ?>/whois/<?= long2ip($guest['ip_via_proxy']) ?>"><i class="search fw"></i>IP via Proxy</a>
                                    </li>
                                <?php endif ?>
                            </ul>
                        </div>
                        <a href="" class="mlink has-lbtn">
                            <dl class="description">
                                <dt class="wide">
                                    V: <?= $guest['views'] ?><br>
                                    M: <?= $guest['movings'] ?>
                                </dt>
                                <dd>
                                    <div class="small inline margin"><?= $guest['userAgent'] ?></div>
                                    <div class="small">
                                        <?php if (isset($guest['ip_via_proxy']) && !empty($guest['ip_via_proxy'])): ?>
                                            <span class="danger"><?= long2ip($guest['ip']) ?></span> &raquo; <?= long2ip($guest['ip_via_proxy']) ?>
                                        <?php else: ?>
                                            <?= long2ip($guest['ip']) ?>
                                        <?php endif ?>
                                    </div>
                                </dd>
                            </dl>
                        </a>
                    </li>
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
