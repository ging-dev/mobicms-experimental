<?php

namespace Mobicms\Config;

use Mobicms\Api\ConfigInterface;
use Zend\Stdlib\ArrayObject;

class ConfigContainer extends ArrayObject implements ConfigInterface
{
    public function __construct(array $input)
    {
        parent::__construct($input, parent::ARRAY_AS_PROPS);
    }
}
