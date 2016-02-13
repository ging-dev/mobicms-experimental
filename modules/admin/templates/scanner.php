<!-- Заголовок раздела -->
<div class="titlebar admin">
    <div class="button"><a href="../"><i class="arrow-circle-left lg"></i></a></div>
    <div class="separator"></div>
    <div><h1><?= _s('Admin Panel') ?></h1></div>
    <div class="button"></div>
</div>

<div class="content box padding">
    <?php if (isset($this->errormsg)): ?>
        <div class="alert alert-danger">
            <?= $this->errormsg ?>
        </div>
    <?php elseif (isset($this->ok)): ?>
        <div class="alert alert-success">
            <?= $this->ok ?>
        </div>
    <?php endif ?>

    <?= $this->form ?>
    <br/>
    <?php if (!empty($this->modifiedFiles)): ?>
        <div class="alert alert-danger">
            <h4>(<?= count($this->modifiedFiles) ?>) <?= _m('Modified files') ?></h4>
            <?= _m('These files have been modified. If you do not make modifications to these files, it is strongly recommended to replace them with the original distributive of the correct version.') ?><br/><br/>
            <?php foreach ($this->modifiedFiles as $file): ?>
                <div style="font-size: small; font-weight: bold; padding-top: 2px; padding-bottom: 2px; border-top: 1px dotted #ec8583;">
                    <?= htmlspecialchars($file['file_path']) ?>
                    <span style="font-size: small; font-weight: normal; color: #696969">
                        - <?= $file['file_date'] ?>
                    </span>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>
    <?php if (!empty($this->missingFiles)): ?>
        <div class="alert alert-danger">
            <h4>(<?= count($this->missingFiles) ?>) <?= _m('Missing files') ?></h4>
            <?= _m('These files were in the distribution, but if you lack. Without them, can not be guaranteed and normal system operation. It is recommended to restore all missing files from correct version distributive.') ?><br/><br/>
            <?php foreach ($this->missingFiles as $file): ?>
                <div style="font-size: small; font-weight: bold; padding-top: 2px; padding-bottom: 2px; border-top: 1px dotted #ec8583;">
                    <?= htmlspecialchars($file) ?>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>
    <?php if (!empty($this->extraFiles)): ?>
        <div class="alert alert-danger">
            <h4>(<?= count($this->extraFiles) ?>) <?= _m('New files') ?></h4>
            <?= _m('If the files listed below does not pertain to your additional modules and you are not assured of their safety, remove them. They can be dangerous for your site.') ?><br/><br/>
            <?php foreach ($this->extraFiles as $file): ?>
                <div style="font-size: small; font-weight: bold; padding-top: 2px; padding-bottom: 2px; border-top: 1px dotted #ec8583;">
                    <?= htmlspecialchars($file['file_path']) ?>
                    <span style="font-size: small; font-weight: normal; color: #696969">
                        - <?= $file['file_date'] ?>
                    </span>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>