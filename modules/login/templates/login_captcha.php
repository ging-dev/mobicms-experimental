<?php
$app = App::getInstance();
?>
<ul class="title">
    <li class="center"><h1><?= _s('Login') ?></h1></li>
</ul>
<div class="content form-container">
    <div style="text-align: center">
        <form action="<?= $app->request()->getUri() ?>" method="post">
            <fieldset>
                <legend><?= _s('Symbols on the picture') ?></legend>
                <br/>
                <?php
                $captcha = new Mobicms\Captcha\Captcha;
                $code = $captcha->generateCode();
                $app->session()->offsetSet('captcha', $code);
                echo '<img alt="' . _s('If you do not see the picture with the code, turn the graphics support in your browser and refresh the page') .
                    '" width="' . $captcha->width . '" height="' . $captcha->height . '" src="' . $captcha->generateImage($code) . '"/>';

                if (isset($this->error)) {
                    echo '<span class="error-text">' . $this->error . '<br/></span>';
                }
                ?>
                <br/>
                <input id="captcha" type="text" style="width: 100px; text-align: center" maxlength="5"
                       name="captcha" <?= (isset($this->error['captcha']) ? 'class="error"' : '') ?>/>
            </fieldset>
            <fieldset>
                <input type="submit" name="submit" class="btn btn-primary" value="<?= _s('Continue') ?>"/>
                <input type="hidden" name="login" value="<?= htmlspecialchars($this->data['login']) ?>"/>
                <input type="hidden" name="password" value="<?= htmlspecialchars($this->data['password']) ?>"/>
                <?php if (isset($this->data['remember']) && $this->data['remember']) : ?>
                    <input type="hidden" name="remember" value="1"/>
                <?php endif ?>
                <input type="hidden" name="form_token" value="<?= $this->form_token ?>"/>
            </fieldset>
        </form>
    </div>
</div>
