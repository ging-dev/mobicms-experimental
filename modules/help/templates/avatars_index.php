<!-- Заголовок раздела -->
<div class="titlebar">
    <div class="button">
        <a href="../" title="<?= _s('Back') ?>">
            <i class="arrow-circle-left lg"></i>
        </a>
    </div>
    <div class="separator"></div>
    <div><h1><?= _s('Avatars') ?></h1></div>
    <div class="button"></div>
</div>

<!-- Список каталогов -->
<ul class="nav nav-pills nav-stacked">
    <?php foreach ($this->list as $val): ?>
        <li>
            <a href="<?= $val['link'] ?>">
                <i class="picture lg fw"></i>
                <?= $val['name'] ?>
                <span class="badge pull-right"><?= $val['count'] ?></span>
            </a>
        </li>
    <?php endforeach ?>
</ul>
