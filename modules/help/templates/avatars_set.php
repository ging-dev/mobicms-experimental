<?php
/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\RouterInterface $router */
$router = $container->get(Mobicms\Api\RouterInterface::class);

$app = App::getInstance();
?>
<!-- Заголовок раздела -->
<div class="titlebar">
    <div class="button">
        <a href="../../list/<?= $router->getQuery(2) ?>" title="<?= _s('Back') ?>">
            <i class="arrow-circle-left lg"></i>
        </a>
    </div>
    <div class="separator"></div>
    <div><h1><?= _s('Avatars') ?></h1></div>
    <div class="button"></div>
</div>

<!-- Информация о пользователе -->
<?php if (!isset($this->hideuser)): ?>
    <?php $profile = $app->user()->get() ?>
    <div class="info-block m-list">
        <ul><?php include_once $this->getPath('include.user.php') ?></ul>
    </div>
<?php endif ?>

<!-- Форма установки аватара -->
<div class="content box padding">
    <?= $this->form ?>
</div>
