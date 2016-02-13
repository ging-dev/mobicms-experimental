<?php
$user = App::getInstance()->user()->get();
$profile = $app->profile();
$owner = $profile->id == $user->id;
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

<!-- Форма установки аватара -->
<div class="content box padding">
    <?= $this->form ?>
</div>
