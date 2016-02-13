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

<div class="content box m-list">
    <h2><?= _m('Themes') ?></h2>
    <ul class="striped">
        <?php if (!empty($this->tpl_list)): ?>
            <?php foreach ($this->tpl_list as $key => $val): ?>
                <li>
                    <a href="?act=set&amp;mod=<?= $key ?>" class="mlink">
                        <dl class="description">
                            <dt class="wide">
                                <img src="<?= $val['thumbinal'] ?>"/>
                            </dt>
                            <dd>
                                <div class="header"><?= $val['name'] ?></div>
                                <p>
                                    <?php if (!empty($val['author'])): ?>
                                        <strong><?= _m('Author') ?></strong>: <?= $val['author'] ?>
                                    <?php endif ?>
                                    <?php if (!empty($val['author_url'])): ?>
                                        <br/><strong><?= _m('Site') ?></strong>: <?= $val['author_url'] ?>
                                    <?php endif ?>
                                    <?php if (!empty($val['author_email'])): ?>
                                        <br/><strong>Email</strong>: <?= $val['author_email'] ?>
                                    <?php endif ?>
                                    <?php if (!empty($val['description'])): ?>
                                        <br/><strong><?= _m('Description') ?></strong>: <?= $val['description'] ?>
                                    <?php endif ?>
                                </p>
                            </dd>
                        </dl>
                    </a>
                </li>
            <?php endforeach ?>
        <?php else: ?>
            <li style="text-align: center; padding: 27px"><?= _s('List is empty') ?></li>
        <?php endif ?>
    </ul>
    <h3><?= _s('Total') . ': ' . (!empty($this->tpl_list) ? count($this->tpl_list) : 0) ?></h3>
</div>
