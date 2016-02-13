<?php
$user = App::getInstance()->user()->get();
$profile = App::getInstance()->profile();
?>
<!-- Заголовок раздела -->
<div class="titlebar <?= $profile->id == $user->id ? 'private' : 'admin' ?>">
    <div class="button"><a href="../"><i class="arrow-circle-left lg"></i></a></div>
    <div class="separator"></div>
    <div><h1><?= _s('Settings') ?></h1></div>
    <div class="button"></div>
</div>

<?php if (!isset($this->hideuser)): ?>
    <!-- Информация о пользователе -->
    <div class="info-block m-list">
        <ul><?php include_once $this->getPath('include.user.php') ?></ul>
    </div>
<?php endif ?>
<div class="content box padding">
    <?= $this->form ?>
</div>
