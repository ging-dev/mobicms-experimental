<?php
$app = App::getInstance();
$user = $app->user()->get()
?>
<!-- Заголовок раздела -->
<div class="titlebar admin">
    <div><h1><?= _s('Admin Panel') ?></h1></div>
</div>

<!-- Меню -->
<ul class="nav nav-pills nav-stacked">
    <li class="title"><?= _m('Modules') ?></li>
    <?php if ($user->rights == 9): ?>
        <li><a href="counters/"><i class="dashboard lg fw"></i><?= _m('Counters') ?></a></li>
        <li><a href="sitemap/"><i class="sitemap lg fw"></i><?= _m('Site Map') ?></a></li>
    <?php endif ?>
    <li class="title"><?= _m('System') ?></li>
    <?php if ($user->rights == 9): ?>
        <li><a href="system_settings/"><i class="settings lg fw"></i><?= _m('System Settings') ?></a></li>
        <li><a href="users_settings/"><i class="settings lg fw"></i><?= _m('Users Settings') ?></a></li>
        <li><a href="languages/"><i class="language lg fw"></i><?= _m('Languages') ?></a></li>
        <li><a href="smilies/"><i class="smile lg fw"></i><?= _s('Smilies') ?></a></li>
    <?php endif ?>
    <li class="title"><?= _m('Security') ?></li>
    <?php if ($user->rights == 9) : ?>
        <li><a href="firewall/"><i class="shield lg fw"></i><?= _m('Firewall') ?></a></li>
        <li><a href="scanner/"><i class="bug lg fw"></i><?= _m('Anti-Spyware') ?></a></li>
    <?php endif ?>
    <li><a href="<?= $app->request()->getBaseUrl() ?>/whois/"><i class="info-circle lg fw"></i>WHOIS</a></li>
</ul>
