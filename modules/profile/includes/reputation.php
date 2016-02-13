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

defined('MOBICMS') or die('Error: restricted access');

$app = App::getInstance();
$db = $app->db();
$user = $app->user()->get();
$profile = $app->profile();

$reputation = !empty($profile->reputation)
    ? unserialize($profile->reputation)
    : ['a' => 0, 'b' => 0, 'c' => 0, 'd' => 0, 'e' => 0];

if ($app->user()->isValid() && $profile->id != $user->id) {
    $checked = '0';
    $update = false;

    // Поиск имеющегося голосования
    $voteStmt = $db->prepare('SELECT * FROM `usr__reputation` WHERE `from` = ? AND `to` = ? LIMIT 1');
    $voteStmt->execute([$user->id, $profile->id]);
    $voteResult = $voteStmt->fetch();

    if ($voteResult !== false) {
        $checked = $voteResult['value'];
        $update = true;
    }

    $form = new Mobicms\Form\Form(['action' => $app->uri()]);
    $form
        ->title(_m('Vote'))
        ->html('<span class="description">' . _m('You can express your relationship to the person, what is its reputation in your opinion. You can change your attitude when you want, no restrictions.') . '</span>')
        ->element('radio', 'vote',
            [
                'checked' => $checked,
                'items'   =>
                    [
                        '2'  => _m('Excellent'),
                        '1'  => _m('Good'),
                        '0'  => _m('Neutrally'),
                        '-1' => _m('Bad'),
                        '-2' => _m('Very Bad')
                    ]
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
        if ($update) {
            $writeStmt = $db->prepare('UPDATE `usr__reputation` SET `value` = ? WHERE `from` = ? AND `to` = ?');
        } else {
            $writeStmt = $db->prepare('INSERT INTO `usr__reputation` SET `value` = ?, `from` = ?, `to` = ?');
        }

        $writeStmt->execute([$form->output['vote'], $user->id, $profile->id]);

        // Обновляем кэш пользователя
        $repStmt = $db->prepare('
            SELECT
            COUNT(IF(`value` =  2, 1, NULL)) AS `a`,
            COUNT(IF(`value` =  1, 1, NULL)) AS `b`,
            COUNT(IF(`value` =  0, 1, NULL)) AS `c`,
            COUNT(IF(`value` = -1, 1, NULL)) AS `d`,
            COUNT(IF(`value` = -2, 1, NULL)) AS `e`
            FROM `usr__reputation`
            WHERE `to` = ?
        ');
        $repStmt->execute([$profile->id]);
        $reputation = $repStmt->fetch();

        $profile->reputation = serialize($reputation);
        $profile->save();
    }

    $app->view()->form = $form->display();
}

$app->view()->counters = $reputation;
$app->view()->reputation = [];
$app->view()->reputation_total = array_sum($reputation);
foreach ($reputation as $key => $val) {
    $app->view()->reputation[$key] = $app->view()->reputation_total
        ? 100 / $app->view()->reputation_total * $val
        : 0;
}

$app->view()->setTemplate('reputation.php');
