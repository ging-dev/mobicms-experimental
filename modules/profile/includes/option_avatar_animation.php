<?php
/*
 * mobiCMS Content Management System (http://mobicms.net)
 *
 * For copyright and license information, please see the LICENSE.md
 * Installing the system or redistributions of files must retain the above copyright notice.
 *
 * @link        http://mobicms.net mobiCMS Project
 * @copyright   Copyright (C) mobiCMS Community
 * @license     LICENSE.md (see attached file)
 */

defined('JOHNCMS') or die('Error: restricted access');

$app = App::getInstance();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form
    ->title(_m('Upload GIF animation'))
    ->element('hidden', 'MAX_FILE_SIZE', ['value' => (10 * 1024)])
    ->element('file', 'animation',
        [
            'label'       => _m('Image'),
            'description' => _m('Only GIF files allowed for upload, file size should not exceed 20kb.<br>Image size must be 48x48.')
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
    $error = [];
    if ($_FILES['animation']['size'] > 0) {
        // Проверка на допустимый вес файла
        if ($_FILES['animation']['size'] > 20480) {
            $error[] = _m('Weight of the file exceeds 20kb');
        }

        $param = getimagesize($_FILES['animation']['tmp_name']);

        // Проверка на допустимый тип файла
        if ($param == false || $param['mime'] != 'image/gif') {
            $error[] = _m('Invalid file type, are only allowed to upload images in GIF format');
        }

        // Проверка на допустимый размер изображения
        if ($param != false && ($param[0] != 48 || $param[1] != 48)) {
            $error[] = _m('The size of the images must be 48x48');
        }

        if (empty($error)) {
            $profile = $app->profile();

            if ((move_uploaded_file($_FILES['animation']['tmp_name'], FILES_PATH . 'users' . DS . 'avatar' . DS . $profile->id . '.gif')) == true) {
                $profile->avatar = $app->request()->getBaseUrl() . '/uploads/users/avatar/' . $profile->id . '.gif';
                $profile->save();

                if (is_file(FILES_PATH . 'users' . DS . 'avatar' . DS . $profile->id . '.jpg')) {
                    unlink(FILES_PATH . 'users' . DS . 'avatar' . DS . $profile->id . '.jpg');
                }

                $form->continueLink = '../';
                $form->successMessage = _m(_m('Avatar is uploaded'));
                $form->confirmation = true;
                $app->view()->hideuser = true;
            } else {
                $error[] = _m('Error uploading avatar');
            }
        } else {
            echo $error . ' <a href="../">' . _s('Back') . '</a>';
        }
    } else {
        // Если не выбран файл
        $error[] = _m('The file is not selected');
    }

    if (!empty($error)) {
        $app->view()->error = implode('<br/>', $error);
    }
}

$app->view()->form = $form->display();
$app->view()->setTemplate('edit_form.php');
