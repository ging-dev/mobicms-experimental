<?php
$user = App::getInstance()->user()->get();
$profile = App::getInstance()->profile();
$owner = $profile->id == $user->id;
?>
<!-- Заголовок раздела -->
<div class="titlebar <?= $owner ? 'private' : '' ?>">
    <div class="button"><a href="../"><i class="arrow-circle-left lg"></i></a></div>
    <div class="separator"></div>
    <div><h1><?= ($owner ? _m('My Profile') : _m('User Profile')) ?></h1></div>
    <div class="button"></div>
</div>

<!-- информация о пользователе -->
<?php if (!isset($this->hideuser)): ?>
    <!-- Информация о пользователе -->
    <div class="info-block m-list">
        <ul><?php include_once $this->getPath('include.user.php') ?></ul>
    </div>
<?php endif ?>

<!-- График репутации -->
<ul class="nav nav-pills nav-stacked">
    <li class="title"><?= _m('Reputation') ?></li>
    <li>
        <a href="../">
            <table style="width: 100%">
                <tr>
                    <td class="progress-counter"><?= $this->reputation_total ?></td>
                    <td style="width: 100%; padding-bottom: 6px">
                        <!-- Отлично -->
                        <div class="reputation-desc">
                            <?= _m('Excellent') ?>: <?= $this->counters['a'] ?>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" style="width: <?= $this->reputation['a'] ?>%;"></div>
                        </div>

                        <!-- Хорошо -->
                        <div class="reputation-desc">
                            <?= _m('Good') ?>: <?= $this->counters['b'] ?>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info" style="width: <?= $this->reputation['b'] ?>%;"></div>
                        </div>

                        <!-- Нейтрально -->
                        <div class="reputation-desc">
                            <?= _m('Neutrally') ?>: <?= $this->counters['c'] ?>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-neytral" style="width: <?= $this->reputation['c'] ?>%;"></div>
                        </div>

                        <!-- Плохо -->
                        <div class="reputation-desc">
                            <?= _m('Bad') ?>: <?= $this->counters['d'] ?>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning" style="width: <?= $this->reputation['d'] ?>%;"></div>
                        </div>

                        <!-- Очень плохо -->
                        <div class="reputation-desc">
                            <?= _m('Very Bad') ?>: <?= $this->counters['e'] ?>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger" style="width: <?= $this->reputation['e'] ?>%;"></div>
                        </div>
                    </td>
                </tr>
            </table>
        </a>
    </li>
</ul>

<!-- Форма -->
<?php if (isset($this->form)): ?>
    <div class="content box padding">
        <?= $this->form ?>
    </div>
<?php endif ?>
