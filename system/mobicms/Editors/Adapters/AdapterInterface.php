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

namespace Mobicms\Editors\Adapters;

/**
 * Interface AdapterInterface
 *
 * @package Mobicms\Editors\Adapters
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-02-12
 */
interface AdapterInterface
{
    public function __construct();

    public function display();

    public function getHelp();

    public function getStyle();

    public function setLanguage($iso);
}
