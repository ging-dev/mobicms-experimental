<?php

defined('MOBICMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form
    ->title(_m('Set Gravatar'))
    ->element('text', 'email',
        [
            'label'       => 'Email',
            'description' => _m('Gravatar (an abbreviation for globally recognized avatar) is a service for providing globally unique avatars. On Gravatar, users can <a href="http://gravatar.com">register an account</a> based on their email address, and upload an avatar to be associated with the account. Next, in your profile when you install avatar, just specify your Email address and will be used as your global avatar.'),
            'required'    => true
        ]
    )
    ->divider()
    ->element('submit', 'submit',
        [
            'value' => _s('Save'),
            'class' => 'btn btn-primary'
        ]
    )
    ->html('<a class="btn btn-link" href="../">' . _s('Back') . '</a>');

if ($form->isValid()) {
    $profile = $app->profile();
    $default = 'http://johncms.com/images/empty.png'; //TODO: Установить изображение по умолчанию
    $profile->avatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($form->output['email']))) . '?d=' . urlencode($default) . '&s=48';
    $profile->save();

    $ext = ['.jpg', '.gif', '.png'];
    $file = FILES_PATH . 'users' . DS . 'avatar' . DS . $profile->id;

    foreach ($ext as $val) {
        if (is_file($file . $val)) {
            unlink($file . $val);
        }
    }

    $form->continueLink = '../';
    $form->successMessage = _m('Avatar is established');
    $form->confirmation = true;
    $view->hideuser = true;
}

$view->form = $form->display();
$view->setTemplate('edit_form.php');
