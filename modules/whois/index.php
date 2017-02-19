<?php

defined('MOBICMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\RouterInterface $router */
$router = $container->get(Mobicms\Api\RouterInterface::class);

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();
$form = new Mobicms\Form\Form(['action' => $app->uri()]);
$form->infoMessages = false;
$query = $router->getQuery();

if (isset($query[0])) {
    $form->input['ip'] = $query[0];
    $form->isSubmitted = true;
    $form->isValid = true; //TODO: Доработать
}

$form
    ->title('IP WHOIS')
    ->element('text', 'ip',
        [
            'label'    => _s('IP address'),
            'required' => true
        ]
    )
    ->divider()
    ->element('submit', 'submit',
        [
            'value' => _s('Search'),
            'class' => 'btn btn-primary'
        ]
    )
    ->html('<a class="btn btn-link" href="../">' . _s('Back') . '</a>');//TODO: разобраться с обратной ссылкой

$form->validate('ip', 'ip');

if ($form->isValid()) {
    include_once(__DIR__ . '/classes/WhoisClient.php');
    include_once(__DIR__ . '/classes/Whois.php');
    include_once(__DIR__ . '/classes/IpTools.php');

    $result = (new Whois)->lookup($form->output['ip']);
    $whois = nl2br(implode("\n", $result['rawdata']));

    // Выделяем цветом важные параметры
    $whois = strtr($whois,
        [
            '%'         => '#',
            'inetnum:'  => '<span style="color: #c81237"><strong>inetnum:</strong></span>',
            'netname:'  => '<span style="color: #c81237"><strong>netname:</strong></span>',
            'country:'  => '<span style="color: #c81237"><strong>country:</strong></span>',
            'route:'    => '<span style="color: #c81237"><strong>route:</strong></span>',
            'org-name:' => '<span style="color: #c81237"><strong>org-name:</strong></span>',
            'descr:'    => '<span style="color: #26a51d"><strong>descr:</strong></span>',
            'address:'  => '<span style="color: #26a51d"><strong>address:</strong></span>'
        ]
    );

    $form
        ->divider()
        ->html('<div class="alert alert-neytral"><small>' . $whois . '</small></div>');
}

$view->form = $form->display();
$view->setTemplate('index.php');
