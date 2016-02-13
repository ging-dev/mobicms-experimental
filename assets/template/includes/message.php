<?php
// $this->title      string|array   Заголовок страницы
// $this->error      string|array   Уведомление "alert" на красном фоне (стиль "alert-danger")
// $this->warning    string|array   Уведомление "alert" на желтом фоне  (стиль "alert-warning")
// $this->info       string|array   Уведомление "alert" на голубом фоне (стиль "alert-info")
// $this->success    string|array   Уведомление "alert" на зеленом фоне (стиль "alert-success")
// $this->message    string         Текст, который выводится под уведомлениями в обычном блоке <div>

// $this->slider     Содержимое выводится в раскрывающемся слайдере
// $this->buttonText Текст, который выводится на кнопке слайдера
?>
<!-- Заголовок раздела -->
<?php if (isset($this->title)): ?>
    <div class="titlebar">
        <div><h1><?= $this->title ?></h1></div>
    </div>
<?php endif ?>

<div class="content box padding">
    <?php if (isset($this->error)): ?>
        <?php if (is_array($this->error)): ?>
            <?php foreach ($this->error as $val): ?>
                <div class="alert alert-danger"><?= $val ?></div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="alert alert-danger"><?= $this->error ?></div>
        <?php endif ?>
    <?php endif ?>

    <?php if (isset($this->warning)): ?>
        <?php if (is_array($this->warning)): ?>
            <?php foreach ($this->warning as $val): ?>
                <div class="alert alert-warning"><?= $val ?></div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="alert alert-warning"><?= $this->warning ?></div>
        <?php endif ?>
    <?php endif ?>

    <?php if (isset($this->info)): ?>
        <?php if (is_array($this->info)): ?>
            <?php foreach ($this->info as $val): ?>
                <div class="alert alert-info"><?= $val ?></div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="alert alert-info"><?= $this->info ?></div>
        <?php endif ?>
    <?php endif ?>

    <?php if (isset($this->success)): ?>
        <?php if (is_array($this->success)): ?>
            <?php foreach ($this->success as $val): ?>
                <div class="alert alert-success"><?= $val ?></div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="alert alert-success"><?= $this->success ?></div>
        <?php endif ?>
    <?php endif ?>

    <?php if (isset($this->message)): ?>
        <div><?= $this->message ?></div>
    <?php endif ?>

    <?php if (isset($this->slider)): ?>
        <?php $buttonText = isset($this->buttonText) ? $this->buttonText : '===' ?>
        <a href="#" class="btn btn-info btn-block slider-button"><?= $buttonText ?></a>
        <div class="slider close"><?= $this->slider ?></div>
    <?php endif ?>
</div>
