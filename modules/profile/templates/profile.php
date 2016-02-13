<?php
$app = App::getInstance();
$homeUrl = $app->request()->getBaseUrl();
// Владелец профиля
$profile = $app->profile();
// Системный пользователь
$user = $app->user()->get();
// Является ли системный пользователь владельцем профиля?
$owner = $profile->id == $user->id;
// Разрешена ли модерация? (включает модерские кнопки и меню)
$moderate = (!$owner && ($user->rights == 9 || $user->rights > $profile->rights));
?>
<!-- Заголовок раздела -->
<div class="titlebar<?= ($owner ? ' private' : '') . ($moderate ? ' toogle-admin' : '') ?>">
    <div class="button"></div>
    <div><h1><?= ($owner ? _m('My Profile') : _m('User Profile')) ?></h1></div>
    <?php if ($moderate): ?>
        <div class="separator"></div>
        <div class="button"><a class="slider-button" href="#" title="<?= _s('Control') ?>"><i class="cog lg"></i></a></div>
    <?php else: ?>
        <div class="button"></div>
    <?php endif ?>
</div>

<!-- Слайдер с Админскими кнопками -->
<?php if ($moderate): ?>
    <div class="content slider close">
        <ul class="nav nav-pills nav-justified">
            <li><a href="option/"><i class="settings fw"></i> <?= _s('Settings') ?></a></li>
            <?php if ($moderate): ?>
                <li><a href="#"><i class="sign-out fw"></i> <?= _m('Kick') ?></a></li>
                <li><a href="#"><i class="ban fw"></i> <?= _m('Ban') ?></a></li>
                <li><a href="#"><i class="bin fw"></i> <?= _m('Delete') ?></a></li>
            <?php endif ?>
        </ul>
    </div>
<?php endif ?>

<!-- Информация о пользователе -->
<div class="info-block m-list">
    <ul><?php include_once $this->getPath('include.user.php') ?></ul>
</div>

<!-- Навигация -->
<ul class="nav nav-pills nav-stacked">
    <li class="title"><?= _m('Reputation') ?></li>
    <li>
        <a href="reputation/">
            <table style="width: 100%">
                <tr>
                    <td class="progress-counter"><?= $this->reputation_total ?></td>
                    <td style="width: 100%">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" style="width: <?= $this->reputation['a'] ?>%;"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info" style="width: <?= $this->reputation['b'] ?>%;"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-neytral" style="width: <?= $this->reputation['c'] ?>%;"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning" style="width: <?= $this->reputation['d'] ?>%;"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger" style="width: <?= $this->reputation['e'] ?>%;"></div>
                        </div>
                    </td>
                </tr>
            </table>
        </a>
    </li>

    <li class="title"><?= _s('Information') ?></li>
    <li><a href="#"><i class="info-circle lg fw"></i><?= _m('Personal Data') ?> <span class="label label-warning">planned</span></a></li>
    <li><a href="#"><i class="stats-bars lg fw"></i><?= _m('Activity') ?> <span class="label label-warning">planned</span></a></li>

    <li class="title"><?= _m('Assets') ?></li>
    <li><a href="#"><i class="pictures lg fw"></i><?= _s('Photo Album') ?> <span class="label label-warning">planned</span> <span class="badge badge-right">0</span></a></li>
    <li><a href="#"><i class="comments lg fw"></i><?= _s('Guestbook') ?> <span class="label label-warning">planned</span> <span class="badge badge-right">0</span></a></li>
    <li><a href="#"><i class="group lg fw"></i><?= _m('Friends') ?> <span class="label label-warning">planned</span> <span class="badge badge-right">0</span></a></li>

    <?php if ($app->user()->isValid() && $user->id != $profile->id): ?>
        <li class="title"><?= _s('Mail') ?></li>
        <?php if (empty($this->banned)): ?>
            <li><a href="<?= $homeUrl ?>/mail/?act=messages&amp;id=<?= $profile->id ?>"><i class="envelope lg fw"></i><?= _s('Write') ?></a></li>
            <li><a href="<?= $homeUrl ?>/contacts/?act=select&amp;mod=contact&amp;id=<?= $profile->id ?>"><i class="adress_book lg fw"></i><?= ($this->num_cont ? _m('Remove from Contacts') : _m('Add to Contacts')) ?></a>
            </li>
        <?php endif ?>
        <li><a href="<?= $homeUrl ?>/contacts/?act=select&amp;mod=banned&amp;id=<?= $profile->id ?>">
                <i class="ban lg fw"></i><?= (isset($this->banned) && $this->banned == 1 ? _m('Remove from Ignore List') : _m('Add to Ignore List')) ?></a></li>
    <?php endif ?>
</ul>
