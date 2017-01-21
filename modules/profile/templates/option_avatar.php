<?php
/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ConfigInterface $config */
$config = $container->get(Mobicms\Api\ConfigInterface::class);

$app = App::getInstance();
$user = $app->user()->get();
$profile = $app->profile();
$owner = $profile->id == $user->id;
//TODO: Добавить права на редактирование
?>
<!-- Заголовок раздела -->
<div class="titlebar <?= $owner ? 'private' : 'admin' ?>">
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
    <li class="title"><?= _s('Set Avatar') ?></li>
    <?php if ($config->userAllowUploadAvatars || $user->rights >= 7): ?>
        <li><a href="image/"><i class="upload lg fw"></i><?= _m('Upload image') ?></a></li>
        <li><a href="animation/"><i class="upload lg fw"></i><?= _m('Upload GIF animation') ?></a></li>
    <?php endif ?>
    <?php if ($config->userAllowUseGravatar || $user->rights >= 7): ?>
        <li><a href="gravatar/"><i class="link lg fw"></i><?= _m('Set Gravatar') ?></a></li>
    <?php endif ?>
    <?php if ($owner) : ?>
        <li><a href="<?= $app->request()->getBaseUrl() ?>/help/avatars/"><i class="picture lg fw"></i><?= _m('Choose in the catalog') ?></a></li>
    <?php endif ?>
    <?php if (!empty($profile->avatar)) : ?>
        <li><a href="delete/"><i class="bin lg fw"></i><?= _m('Delete Avatar') ?></a></li>
    <?php endif ?>
</ul>
