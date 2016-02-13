<div class="stackTrace">
    <?php if (!empty($this->message)): ?>
        <h2>Stack Trace</h2>
    <?php endif ?>

    <div class="trace">
        <div class="info">
            <div class="counter"><?= $this->counter ?></div>
            <div class="fileinfo"><?= $this->file ?>, line <?= $this->line ?></div>
        </div>
        <?php if (DEBUG === true): ?>
            <?= $this->code ?>
        <?php else: ?>
            <p>The code is visible only in debug mode</p>
        <?php endif ?>
    </div>
</div>
