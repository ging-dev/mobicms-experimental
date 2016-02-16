<?php
$app = App::getInstance();
$config = $app->config();
$url = $app->request()->getBaseUrl();
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
            <a class="navbar-brand" href="<?= $url ?>/"><?= $app->image('logo.png', ['alt' => 'mobiCMS']) ?></a>
        </div>

        <!-- Ссылки слева -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="visible-xs-inline-block"><i class="sitemap fw"></i><?= _s('Go To') ?>&nbsp;</span><span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php if ($app->router()->getCurrentModule() != 'home'): ?>
                            <li><a href="<?= $url ?>/"><i class="home fw"></i><?= _s('Home') ?></a></li>
                        <?php endif ?>
                        <li><a href="<?= $url ?>/news/"><i class="rss fw"></i><?= _s('News') ?></a></li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="comments fw"></i><?= _s('Guestbook') ?></a></li>
                        <li><a href="<?= $url ?>/forum/"><i class="comment fw"></i><?= _s('Forum') ?></a></li>
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
                        <li<?= ($app->router()->getCurrentModule() == 'admin' ? ' class="active"' : '') ?>>
                            <a href="<?= $url ?>/admin/"><i class="cogs fw"></i><?= _s('Admin Panel') ?></a>
                        </li>
                    <?php endif ?>
                    <li<?= ($app->router()->getCurrentModule() == 'mail' ? ' class="active"' : '') ?>>
                        <a href="#"><i class="envelope fw"></i><?= _s('Mail') ?></a>
                    </li>
                    <li class="dropdown<?= ($app->router()->getCurrentModule() == 'profile' ? ' active' : '') ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="user fw"></i><?= $user->nickname ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?= $url ?>/profile/<?= $user->id ?>/"><i class="user fw"></i><?= _s('Profile') ?></a></li>
                            <li class="divider"></li>
                            <li><a href="<?= $url ?>/profile/<?= $user->id ?>/option/"><i class="cogs fw"></i><?= _s('Settings') ?></a></li>
                            <li class="divider"></li>
                            <li><a href="<?= $url ?>/login/"><i class="sign-out fw"></i><?= _s('Exit') ?></a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <?php if ($config['reg']['allow']): ?>
                        <li<?= ($app->router()->getCurrentModule() == 'registration' ? ' class="active"' : '') ?>>
                            <a href="<?= $url ?>/registration/"><i class="pencil fw"></i><?= _s('Registration') ?></a>
                        </li>
                    <?php endif ?>
                    <li<?= ($app->router()->getCurrentModule() == 'login' ? ' class="active"' : '') ?>>
                        <a href="<?= $url ?>/login/"><i class="sign-in fw"></i><?= _s('Login') ?></a>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>
