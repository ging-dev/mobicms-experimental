<?php
/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ConfigInterface $config */
$config = $container->get(Mobicms\Api\ConfigInterface::class);

$app = App::getInstance();
$uri = $app->request()->getUri();
$user = $app->user()->get();
$profile = $app->profile();
?>
<!-- Заголовок раздела -->
<div class="titlebar <?= $profile->id == $user->id ? 'private' : 'admin' ?>">
    <div class="button"><a href="../"><i class="arrow-circle-left lg"></i></a></div>
    <div class="separator"></div>
    <div><h1><?= _s('Settings') ?></h1></div>
    <div class="button"></div>
</div>

<!-- Информация о пользователе -->
<div class="info-block m-list">
    <ul><?php include_once $this->getPath('include.user.php') ?></ul>
</div>

<ul class="nav nav-pills nav-stacked">
    <li class="title"><?= _s('Profile') ?></li>
    <li><a href="<?= $uri ?>edit/"><i class="edit fw lg"></i><?= _m('Edit Profile') ?></a></li>
    <li><a href="<?= $uri ?>avatar/"><i class="picture fw lg"></i><?= _m('Set Avatar') ?></a></li>
    <li><a href="<?= $uri ?>password/"><i class="shield fw lg"></i><?= _m('Change Password') ?></a></li>
    <li><a href="<?= $uri ?>email/"><i class="shield fw lg"></i><?= _m('Change E-mail') ?></a></li>
    <?php if ($config->userAllowChangeNickname || $user->rights >= 7): ?>
        <li><a href="<?= $uri ?>nickname/"><i class="shield fw lg"></i><?= _m('Change Nickname') ?></a></li>
    <?php endif ?>
    <li class="title"><?= _s('Settings') ?></li>
    <li><a href="<?= $uri ?>settings/"><i class="settings fw lg"></i><?= _m('System Settings') ?></a></li>
    <li><a href="<?= $uri ?>theme/"><i class="paint-format fw lg"></i><?= _m('Choose Skin') ?> <span class="label label-danger">draft</span></a></li>
    <?php if ($config->lngSwitch): ?>
        <li><a href="<?= $uri ?>language/"><i class="language fw lg"></i><?= _m('Choose Language') ?></a></li>
    <?php endif ?>
    <?php if ($user->rights == 9 || ($user->rights == 7 && $user->rights > $profile->rights)): ?>
        <li><a href="<?= $uri ?>rank/"><span class="danger"><i class="graduation-cap fw lg"></i><?= _m('Rank') ?></span></a></li>
    <?php endif ?>
</ul>
