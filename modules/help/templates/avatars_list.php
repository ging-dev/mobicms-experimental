<!-- Заголовок раздела -->
<div class="titlebar">
    <div class="button">
        <a href="../../" title="<?= _s('Back') ?>">
            <i class="arrow-circle-left lg"></i>
        </a>
    </div>
    <div class="separator"></div>
    <div><h1><?= _s('Avatars') ?></h1></div>
    <div class="button"></div>
</div>

<!-- Список аватаров -->
<div class="content box m-list">
    <h2><?= _s($this->cat) //TODO: доделать мультиязычность ?></h2>

    <div style="text-align: center; padding: 12px">
        <?php if ($this->total): ?>
            <?php foreach ($this->list as $val): ?>
                <a href="<?= $val['link'] ?>"><img src="<?= $val['image'] ?>" alt="" class="avatars-list"/></a>
            <?php endforeach ?>
        <?php else: ?>
            <?= _s('List is empty') ?>
        <?php endif ?>
    </div>
    <h3><?= _s('Total') ?>:&#160;<?= $this->total ?></h3>
</div>
