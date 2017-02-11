<?php

defined('MOBICMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$catalog = [];
$names = [
    'animals'  => _m('Animals'),
    'anime'    => _m('Anime'),
    'girls'    => _m('Girls'),
    'love'     => _m('Love'),
    'misc'     => _m('Miscellaneous'),
    'monsters' => _m('Monsters'),
    'phrases'  => _m('Phrases'),
];

foreach (glob(ROOT_PATH . 'assets' . DS . 'avatars' . DS . '*', GLOB_ONLYDIR) as $val) {
    $dir = basename($val);
    $catalog[$dir] = (isset($names[$dir]) ? $names[$dir] : $dir);
}

asort($catalog);

$app = App::getInstance();
$uri = $app->uri();
$homeUrl = $app->request()->getBaseUrl();
$query = $app->router()->getQuery();
$view->pagesize = 40;
$user = $app->user()->get();

if ($app->user()->isValid() && isset($query[1], $query[2], $catalog[$query[2]]) && $query[1] == 'set') {
    // Устанавливаем аватар в анкету
    $image = $homeUrl . '/assets/avatars/' . urlencode($query[2]) . '/' . urlencode($query[3]);
    $form = new Mobicms\Form\Form(['action' => $uri . '/' . $query[3]]);
    $form
        ->title(_s('Set Avatar'))
        ->html('<br/><div class="avatars-list" style="float: left; margin-right: 12px"><img src="' . $image . '" alt=""/></div><br/>' . _s('Are you sure you want to set yourself this avatar?'))
        ->divider()
        ->element('submit', 'submit',
            [
                'value' => _s('Save'),
                'class' => 'btn btn-primary'
            ]
        )
        ->html('<a class="btn btn-link" href="' . $uri . 'list/' . $app->router()->getQuery(2) . '">' . _s('Back') . '</a>');

    if ($form->isValid()) {
        $user->avatar = $image;
        $user->save();

        foreach (['gif', 'jpg', 'png'] as $ext) {
            $file = FILES_PATH . 'users' . DS . 'avatar' . DS . $user->id . '.' . $ext;

            if (is_file($file)) {
                unlink($file);
            }
        }

        $form->continueLink = $homeUrl . '/profile/' . $user->id . '/option/avatar/';
        $form->successMessage = _s('Avatar is installed');
        $form->confirmation = true;
        $view->hideuser = true;
    }

    $view->form = $form->display();
    $view->setTemplate('avatars_set.php');
} elseif (isset($query[1], $query[2], $catalog[$query[2]]) && $query[1] == 'list') {
    // Показываем список аватаров в выбранной категории
    $avatars = glob(ROOT_PATH . 'assets' . DS . 'avatars' . DS . $query[2] . DS . '*.{gif,jpg,png}', GLOB_BRACE);

    $view->total = count($avatars);
    $view->start = $app->vars()->page * $view->pagesize - $view->pagesize;
    $end = $app->vars()->page * $view->pagesize;
    if ($end > $view->total) {
        $end = $view->total;
    }

    if ($view->total) {
        $view->list = [];
        for ($i = $view->start; $i < $end; $i++) {
            $view->list[$i] =
                [
                    'image' => $homeUrl . '/assets/avatars/' . urlencode($query[2]) . '/' . basename($avatars[$i]),
                    'link'  => ($app->user()->isValid() ? '../../set/' . urlencode($query[2]) . '/' . urlencode(basename($avatars[$i])) : '#')
                ];
        }
    }

    $view->cat = $query[2];
    $view->setTemplate('avatars_list.php');
} else {
    // Показываем каталог аватаров (список категорий)
    $view->list = [];
    foreach ($catalog as $key => $val) {
        $view->list[] =
            [
                'link'  => $uri . 'list/' . urlencode($key) . '/',
                'name'  => $val,
                'count' => count(glob(ROOT_PATH . 'assets' . DS . 'avatars' . DS . $key . DS . '*.{gif,jpg,png}', GLOB_BRACE))
            ];
    }

    $view->setTemplate('avatars_index.php');
}
