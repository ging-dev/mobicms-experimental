<?php
/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ConfigInterface $config */
$config = $container->get(Mobicms\Api\ConfigInterface::class);

$module = $container->get(Mobicms\Api\RouterInterface::class)->getCurrentModule();

$app = App::getInstance();
$user = $app->user()->get();
?>
<nav class="navbar navbar-inverse" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <!-- Кнопка меню -->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- Логотип -->
            <a class="navbar-brand" href="<?= $config->homeUrl ?>/"><?= $app->image('logo.png', ['alt' => 'mobiCMS']) ?></a>
        </div>

        <!-- Ссылки слева -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="visible-xs-inline-block"><i class="sitemap fw"></i><?= _s('Go To') ?>&nbsp;</span><span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php if ($module != 'home'): ?>
                            <li><a href="<?= $config->homeUrl ?>/"><i class="home fw"></i><?= _s('Home') ?></a></li>
                        <?php endif ?>
                        <li><a href="<?= $config->homeUrl ?>/news/"><i class="rss fw"></i><?= _s('News') ?></a></li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="comments fw"></i><?= _s('Guestbook') ?></a></li>
                        <li><a href="<?= $config->homeUrl ?>/forum/"><i class="comment fw"></i><?= _s('Forum') ?></a></li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="download fw"></i><?= _s('Downloads') ?></a></li>
                        <li><a href="#"><i class="books fw"></i><?= _s('Library') ?></a></li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="group fw"></i><?= _s('Community') ?></a></li>
                        <li><a href="#"><i class="picture fw"></i><?= _s('Photo Album') ?></a></li>
                    </ul>
                </li>
            </ul>

            <!-- Ссылки справа -->
            <ul class="nav navbar-nav navbar-right">
                <?php if ($app->user()->isValid()): ?>
                    <?php if ($user->rights): ?>
                        <li<?= ($module == 'admin' ? ' class="active"' : '') ?>>
                            <a href="<?= $config->homeUrl ?>/admin/"><i class="cogs fw"></i><?= _s('Admin Panel') ?></a>
                        </li>
                    <?php endif ?>
                    <li<?= ($module == 'mail' ? ' class="active"' : '') ?>>
                        <a href="#"><i class="envelope fw"></i><?= _s('Mail') ?></a>
                    </li>
                    <li class="dropdown<?= ($module == 'profile' ? ' active' : '') ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="user fw"></i><?= $user->nickname ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?= $config->homeUrl ?>/profile/<?= $user->id ?>/"><i class="user fw"></i><?= _s('Profile') ?></a></li>
                            <li class="divider"></li>
                            <li><a href="<?= $config->homeUrl ?>/profile/<?= $user->id ?>/option/"><i class="cogs fw"></i><?= _s('Settings') ?></a></li>
                            <li class="divider"></li>
                            <li><a href="<?= $config->homeUrl ?>/login/"><i class="sign-out fw"></i><?= _s('Exit') ?></a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <?php if ($config->registrationAllow): ?>
                        <li<?= ($module == 'registration' ? ' class="active"' : '') ?>>
                            <a href="<?= $config->homeUrl ?>/registration/"><i class="pencil fw"></i><?= _s('Registration') ?></a>
                        </li>
                    <?php endif ?>
                    <li<?= ($module == 'login' ? ' class="active"' : '') ?>>
                        <a href="<?= $config->homeUrl ?>/login/"><i class="sign-in fw"></i><?= _s('Login') ?></a>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>
