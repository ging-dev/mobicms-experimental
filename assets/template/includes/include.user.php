<?php
$app = App::getInstance();
$url = $app->request()->getBaseUrl();
$profile = isset($profile) ? $profile : $app->profile();
$rights = $app->user()->get()->rights;
$proxy = $app->network()->isProxyIp();
?>
<li>
    <!-- Кнопка выпадающего меню -->
    <?php if ($rights): ?>
        <div>
            <a href="#" class="lbtn dropdown dropdown-toggle" data-toggle="dropdown"></a>
            <ul class="dropdown-menu" role="menu">
                <li class="dropdown-header"><?= _s('IP Management') ?></li>
                <li><a href="<?= $url ?>/whois/<?= $profile['ip'] ?>"><i class="search fw"></i>IP Whois</a></li>
                <?php if ($proxy): ?>
                    <li><a href="#"><i class="cogs fw"></i><?= _s('Proxy Management') ?></a></li>
                <?php endif ?>
                <?php if (
                    $rights == 9
                    && $profile['ip'] != $app->network()->getClientIp()
                    && !in_array($profile['ip'], $app->network()->getTrustedProxies())
                ): ?>
                    <li><a href="#"><i class="ban fw"></i><?= _s('Block this IP') ?></a></li>
                <?php endif ?>
            </ul>
        </div>
    <?php endif ?>

    <!-- Ссылка на профиль, контейнер -->
    <a href="<?= $url . '/profile/' . $profile['id'] ?>/"
       class="mlink<?= $rights ? ' has-lbtn' : '' ?>">
        <dl class="description">
            <dt>
                <?php if (!empty($profile['avatar'])): ?>
                    <img src="<?= $profile['avatar'] ?>"/>
                <?php else: ?>
                    <?= $app->image('empty_user.png') ?>
                <?php endif ?>
            </dt>
            <dd>
                <div class="header">
                    <span class="sex<?= (time() > $profile['lastVisit'] + 300 ? '' : ' online') ?>"><i
                            class="<?= ($profile['sex'] == 'm' ? '' : 'fe') ?>male lg"></i></span>
                    <?= $profile['nickname'] ?>
                </div>
                <?php if (!empty($profile['status'])): ?>
                    <div class="small bold colored"><?= $profile['status'] ?></div>
                <?php endif ?>
                <?php if ($profile['lastVisit'] < time() - 300): ?>
                    <div><?= _s('Last visit') . ': ' . Includes\Functions::displayDate($profile['lastVisit']) ?></div>
                <?php endif ?>
                <?php if ($rights): ?>
                    <div class="small inline margin"><?= $profile['userAgent'] ?></div>
                    <div class="small">
                        <?php if ($proxy): ?>
                            <span class="label label-danger">Proxy</span>
                        <?php endif ?>
                        <?= $profile['ip'] ?>
                    </div>
                <?php endif ?>
            </dd>
        </dl>
    </a>
</li>
