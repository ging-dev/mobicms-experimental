<!-- Заголовок раздела -->
<div class="titlebar admin">
    <div class="button"><a href="../"><i class="arrow-circle-left lg"></i></a></div>
    <div class="separator"></div>
    <div><h1><?= _s('Admin Panel') ?></h1></div>
    <div class="button"></div>
</div>

<div class="content box padding">
    <?php if (isset($_GET['save'])): ?>
        <div class="alert alert-success"><?= _s('Data saved successfully') ?></div>
    <?php elseif (isset($_GET['default'])): ?>
        <div class="alert"><?= _m('Options set to default') ?></div>
    <?php elseif (isset($_GET['cache'])): ?>
        <div class="alert alert-success"><?= _m('The Cache cleared successfully') ?></div>
    <?php endif ?>
    <?= $this->form ?>
</div>
