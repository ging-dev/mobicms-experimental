<?php
return array(
    'Mobicms\\Api\\ViewInterface' => array(
        'supertypes' => array(),
        'instantiator' => null,
        'methods' => array(
            'setCss' => 0,
            'setLayout' => 0,
            'setTemplate' => 0,
        ),
        'parameters' => array(
            'setCss' => array(
                'Mobicms\\Api\\ViewInterface::setCss:0' => array(
                    0 => 'cssFile',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Api\\ViewInterface::setCss:1' => array(
                    0 => 'media',
                    1 => null,
                    2 => false,
                    3 => '',
                ),
            ),
            'setLayout' => array(
                'Mobicms\\Api\\ViewInterface::setLayout:0' => array(
                    0 => 'templateFile',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Api\\ViewInterface::setLayout:1' => array(
                    0 => 'module',
                    1 => null,
                    2 => false,
                    3 => false,
                ),
            ),
            'setTemplate' => array(
                'Mobicms\\Api\\ViewInterface::setTemplate:0' => array(
                    0 => 'templateFile',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Api\\ViewInterface::setTemplate:1' => array(
                    0 => 'key',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Api\\ViewInterface::setTemplate:2' => array(
                    0 => 'module',
                    1 => null,
                    2 => false,
                    3 => true,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\Authentication\\AbstractAuth' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\Authentication\\AbstractAuth::__construct:0' => array(
                    0 => 'facade',
                    1 => 'Mobicms\\Checkpoint\\Facade',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Authentication\\AbstractAuth::__construct:1' => array(
                    0 => 'session',
                    1 => 'Zend\\Session\\Container',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Authentication\\AbstractAuth::__construct:2' => array(
                    0 => 'request',
                    1 => 'Zend\\Http\\PhpEnvironment\\Request',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Authentication\\AbstractAuth::__construct:3' => array(
                    0 => 'network',
                    1 => 'Mobicms\\Environment\\Network',
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\Authentication\\Authentication' => array(
        'supertypes' => array(
            0 => 'Mobicms\\Checkpoint\\Authentication\\AbstractAuth',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\Authentication\\Authentication::__construct:0' => array(
                    0 => 'facade',
                    1 => 'Mobicms\\Checkpoint\\Facade',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Authentication\\Authentication::__construct:1' => array(
                    0 => 'session',
                    1 => 'Zend\\Session\\Container',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Authentication\\Authentication::__construct:2' => array(
                    0 => 'request',
                    1 => 'Zend\\Http\\PhpEnvironment\\Request',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Authentication\\Authentication::__construct:3' => array(
                    0 => 'network',
                    1 => 'Mobicms\\Environment\\Network',
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\Authentication\\Identification' => array(
        'supertypes' => array(
            0 => 'Mobicms\\Checkpoint\\Authentication\\AbstractAuth',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\Authentication\\Identification::__construct:0' => array(
                    0 => 'facade',
                    1 => 'Mobicms\\Checkpoint\\Facade',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Authentication\\Identification::__construct:1' => array(
                    0 => 'session',
                    1 => 'Zend\\Session\\Container',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Authentication\\Identification::__construct:2' => array(
                    0 => 'request',
                    1 => 'Zend\\Http\\PhpEnvironment\\Request',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Authentication\\Identification::__construct:3' => array(
                    0 => 'network',
                    1 => 'Mobicms\\Environment\\Network',
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\Authentication\\Logout' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\Authentication\\Logout::__construct:0' => array(
                    0 => 'facade',
                    1 => 'Mobicms\\Checkpoint\\Facade',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Authentication\\Logout::__construct:1' => array(
                    0 => 'clearToken',
                    1 => null,
                    2 => false,
                    3 => false,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\Exceptions\\ExceptionInterface' => array(
        'supertypes' => array(),
        'instantiator' => null,
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\Checkpoint\\Exceptions\\InvalidInputException' => array(
        'supertypes' => array(
            0 => 'Mobicms\\Checkpoint\\Exceptions\\UserExceptionInterface',
            1 => 'Mobicms\\Checkpoint\\Exceptions\\ExceptionInterface',
            2 => 'InvalidArgumentException',
            3 => 'LogicException',
            4 => 'Exception',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\Exceptions\\InvalidInputException::__construct:0' => array(
                    0 => 'message',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Exceptions\\InvalidInputException::__construct:1' => array(
                    0 => 'code',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Exceptions\\InvalidInputException::__construct:2' => array(
                    0 => 'previous',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\Exceptions\\InvalidTokenException' => array(
        'supertypes' => array(
            0 => 'Mobicms\\Checkpoint\\Exceptions\\UserExceptionInterface',
            1 => 'Mobicms\\Checkpoint\\Exceptions\\ExceptionInterface',
            2 => 'Exception',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\Exceptions\\InvalidTokenException::__construct:0' => array(
                    0 => 'message',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Exceptions\\InvalidTokenException::__construct:1' => array(
                    0 => 'code',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Exceptions\\InvalidTokenException::__construct:2' => array(
                    0 => 'previous',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\Exceptions\\UserExceptionInterface' => array(
        'supertypes' => array(
            0 => 'Mobicms\\Checkpoint\\Exceptions\\ExceptionInterface',
        ),
        'instantiator' => null,
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\Checkpoint\\Exceptions\\UserNotFoundException' => array(
        'supertypes' => array(
            0 => 'Mobicms\\Checkpoint\\Exceptions\\UserExceptionInterface',
            1 => 'Mobicms\\Checkpoint\\Exceptions\\ExceptionInterface',
            2 => 'Exception',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\Exceptions\\UserNotFoundException::__construct:0' => array(
                    0 => 'message',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Exceptions\\UserNotFoundException::__construct:1' => array(
                    0 => 'code',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Exceptions\\UserNotFoundException::__construct:2' => array(
                    0 => 'previous',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\Exceptions\\WrongPasswordException' => array(
        'supertypes' => array(
            0 => 'Mobicms\\Checkpoint\\Exceptions\\UserExceptionInterface',
            1 => 'Mobicms\\Checkpoint\\Exceptions\\ExceptionInterface',
            2 => 'Exception',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\Exceptions\\WrongPasswordException::__construct:0' => array(
                    0 => 'message',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Exceptions\\WrongPasswordException::__construct:1' => array(
                    0 => 'code',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Exceptions\\WrongPasswordException::__construct:2' => array(
                    0 => 'previous',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\Facade' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setUser' => 0,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\Facade::__construct:0' => array(
                    0 => 'request',
                    1 => 'Zend\\Http\\PhpEnvironment\\Request',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Facade::__construct:1' => array(
                    0 => 'manager',
                    1 => 'Zend\\Session\\SessionManager',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\Facade::__construct:2' => array(
                    0 => 'network',
                    1 => 'Mobicms\\Environment\\Network',
                    2 => true,
                    3 => null,
                ),
            ),
            'setUser' => array(
                'Mobicms\\Checkpoint\\Facade::setUser:0' => array(
                    0 => 'user',
                    1 => 'Mobicms\\Checkpoint\\User\\AbstractUser',
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\Tools\\FindById' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\Checkpoint\\Tools\\FindByLogin' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\Checkpoint\\Tools\\Validator' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\Tools\\Validator::__construct:0' => array(
                    0 => 'db',
                    1 => 'PDO',
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\User\\AbstractUser' => array(
        'supertypes' => array(
            0 => 'Countable',
            1 => 'Serializable',
            2 => 'ArrayAccess',
            3 => 'Traversable',
            4 => 'IteratorAggregate',
            5 => 'ArrayObject',
            6 => 'IteratorAggregate',
            7 => 'Traversable',
            8 => 'ArrayAccess',
            9 => 'Serializable',
            10 => 'Countable',
        ),
        'instantiator' => null,
        'methods' => array(
            '__construct' => 3,
            'setPassword' => 0,
            'setToken' => 0,
            'setFlags' => 0,
            'setIteratorClass' => 0,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\User\\AbstractUser::__construct:0' => array(
                    0 => 'user',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setPassword' => array(
                'Mobicms\\Checkpoint\\User\\AbstractUser::setPassword:0' => array(
                    0 => 'password',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setToken' => array(
                'Mobicms\\Checkpoint\\User\\AbstractUser::setToken:0' => array(
                    0 => 'token',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setFlags' => array(
                'Mobicms\\Checkpoint\\User\\AbstractUser::setFlags:0' => array(
                    0 => 'flags',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setIteratorClass' => array(
                'Mobicms\\Checkpoint\\User\\AbstractUser::setIteratorClass:0' => array(
                    0 => 'iteratorClass',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\User\\AddUser' => array(
        'supertypes' => array(
            0 => 'IteratorAggregate',
            1 => 'Traversable',
            2 => 'ArrayAccess',
            3 => 'Serializable',
            4 => 'Countable',
            5 => 'Mobicms\\Checkpoint\\User\\AbstractUser',
            6 => 'Countable',
            7 => 'Serializable',
            8 => 'ArrayAccess',
            9 => 'Traversable',
            10 => 'IteratorAggregate',
            11 => 'ArrayObject',
            12 => 'IteratorAggregate',
            13 => 'Traversable',
            14 => 'ArrayAccess',
            15 => 'Serializable',
            16 => 'Countable',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setPassword' => 0,
            'setToken' => 0,
            'setFlags' => 0,
            'setIteratorClass' => 0,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\User\\AddUser::__construct:0' => array(
                    0 => 'user',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setPassword' => array(
                'Mobicms\\Checkpoint\\User\\AddUser::setPassword:0' => array(
                    0 => 'password',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setToken' => array(
                'Mobicms\\Checkpoint\\User\\AddUser::setToken:0' => array(
                    0 => 'token',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setFlags' => array(
                'Mobicms\\Checkpoint\\User\\AddUser::setFlags:0' => array(
                    0 => 'flags',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setIteratorClass' => array(
                'Mobicms\\Checkpoint\\User\\AddUser::setIteratorClass:0' => array(
                    0 => 'iteratorClass',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\User\\Config' => array(
        'supertypes' => array(
            0 => 'Countable',
            1 => 'Serializable',
            2 => 'ArrayAccess',
            3 => 'Traversable',
            4 => 'IteratorAggregate',
            5 => 'ArrayObject',
            6 => 'IteratorAggregate',
            7 => 'Traversable',
            8 => 'ArrayAccess',
            9 => 'Serializable',
            10 => 'Countable',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setFlags' => 0,
            'setIteratorClass' => 0,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\User\\Config::__construct:0' => array(
                    0 => 'user',
                    1 => 'Mobicms\\Checkpoint\\User\\AbstractUser',
                    2 => true,
                    3 => null,
                ),
            ),
            'setFlags' => array(
                'Mobicms\\Checkpoint\\User\\Config::setFlags:0' => array(
                    0 => 'flags',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setIteratorClass' => array(
                'Mobicms\\Checkpoint\\User\\Config::setIteratorClass:0' => array(
                    0 => 'iteratorClass',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\User\\Data' => array(
        'supertypes' => array(
            0 => 'Countable',
            1 => 'Serializable',
            2 => 'ArrayAccess',
            3 => 'Traversable',
            4 => 'IteratorAggregate',
            5 => 'ArrayObject',
            6 => 'IteratorAggregate',
            7 => 'Traversable',
            8 => 'ArrayAccess',
            9 => 'Serializable',
            10 => 'Countable',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setFlags' => 0,
            'setIteratorClass' => 0,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\User\\Data::__construct:0' => array(
                    0 => 'db',
                    1 => 'PDO',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\User\\Data::__construct:1' => array(
                    0 => 'userId',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Checkpoint\\User\\Data::__construct:2' => array(
                    0 => 'section',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setFlags' => array(
                'Mobicms\\Checkpoint\\User\\Data::setFlags:0' => array(
                    0 => 'flags',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setIteratorClass' => array(
                'Mobicms\\Checkpoint\\User\\Data::setIteratorClass:0' => array(
                    0 => 'iteratorClass',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\User\\EmptyUser' => array(
        'supertypes' => array(
            0 => 'IteratorAggregate',
            1 => 'Traversable',
            2 => 'ArrayAccess',
            3 => 'Serializable',
            4 => 'Countable',
            5 => 'Mobicms\\Checkpoint\\User\\AbstractUser',
            6 => 'Countable',
            7 => 'Serializable',
            8 => 'ArrayAccess',
            9 => 'Traversable',
            10 => 'IteratorAggregate',
            11 => 'ArrayObject',
            12 => 'IteratorAggregate',
            13 => 'Traversable',
            14 => 'ArrayAccess',
            15 => 'Serializable',
            16 => 'Countable',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setPassword' => 0,
            'setToken' => 0,
            'setFlags' => 0,
            'setIteratorClass' => 0,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\User\\EmptyUser::__construct:0' => array(
                    0 => 'network',
                    1 => 'Mobicms\\Environment\\Network',
                    2 => true,
                    3 => null,
                ),
            ),
            'setPassword' => array(
                'Mobicms\\Checkpoint\\User\\EmptyUser::setPassword:0' => array(
                    0 => 'password',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setToken' => array(
                'Mobicms\\Checkpoint\\User\\EmptyUser::setToken:0' => array(
                    0 => 'token',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setFlags' => array(
                'Mobicms\\Checkpoint\\User\\EmptyUser::setFlags:0' => array(
                    0 => 'flags',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setIteratorClass' => array(
                'Mobicms\\Checkpoint\\User\\EmptyUser::setIteratorClass:0' => array(
                    0 => 'iteratorClass',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Checkpoint\\User\\User' => array(
        'supertypes' => array(
            0 => 'IteratorAggregate',
            1 => 'Traversable',
            2 => 'ArrayAccess',
            3 => 'Serializable',
            4 => 'Countable',
            5 => 'Mobicms\\Checkpoint\\User\\AbstractUser',
            6 => 'Countable',
            7 => 'Serializable',
            8 => 'ArrayAccess',
            9 => 'Traversable',
            10 => 'IteratorAggregate',
            11 => 'ArrayObject',
            12 => 'IteratorAggregate',
            13 => 'Traversable',
            14 => 'ArrayAccess',
            15 => 'Serializable',
            16 => 'Countable',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setPassword' => 0,
            'setToken' => 0,
            'setFlags' => 0,
            'setIteratorClass' => 0,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Checkpoint\\User\\User::__construct:0' => array(
                    0 => 'user',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setPassword' => array(
                'Mobicms\\Checkpoint\\User\\User::setPassword:0' => array(
                    0 => 'password',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setToken' => array(
                'Mobicms\\Checkpoint\\User\\User::setToken:0' => array(
                    0 => 'token',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setFlags' => array(
                'Mobicms\\Checkpoint\\User\\User::setFlags:0' => array(
                    0 => 'flags',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setIteratorClass' => array(
                'Mobicms\\Checkpoint\\User\\User::setIteratorClass:0' => array(
                    0 => 'iteratorClass',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Database\\PdoFactory' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\Editors\\Adapters\\AdapterInterface' => array(
        'supertypes' => array(),
        'instantiator' => null,
        'methods' => array(
            '__construct' => 3,
            'setLanguage' => 0,
        ),
        'parameters' => array(
            'setLanguage' => array(
                'Mobicms\\Editors\\Adapters\\AdapterInterface::setLanguage:0' => array(
                    0 => 'iso',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Editors\\Adapters\\CodeMirror' => array(
        'supertypes' => array(
            0 => 'Mobicms\\Editors\\Adapters\\AdapterInterface',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setLanguage' => 0,
        ),
        'parameters' => array(
            'setLanguage' => array(
                'Mobicms\\Editors\\Adapters\\CodeMirror::setLanguage:0' => array(
                    0 => 'iso',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Editors\\Adapters\\SCeditor' => array(
        'supertypes' => array(
            0 => 'Mobicms\\Editors\\Adapters\\AdapterInterface',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setLanguage' => 0,
        ),
        'parameters' => array(
            'setLanguage' => array(
                'Mobicms\\Editors\\Adapters\\SCeditor::setLanguage:0' => array(
                    0 => 'iso',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Editors\\Adapters\\Stub' => array(
        'supertypes' => array(
            0 => 'Mobicms\\Editors\\Adapters\\AdapterInterface',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setLanguage' => 0,
        ),
        'parameters' => array(
            'setLanguage' => array(
                'Mobicms\\Editors\\Adapters\\Stub::setLanguage:0' => array(
                    0 => 'iso',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Editors\\Editor' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Editors\\Editor::__construct:0' => array(
                    0 => 'editor',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Environment\\Network' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\Environment\\Vars' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(),
    ),
    'Mobicms\\Exception\\Handler\\Handler' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(),
    ),
    'Mobicms\\Ext\\Session\\PdoSessionHandler' => array(
        'supertypes' => array(
            0 => 'Zend\\Session\\SaveHandler\\SaveHandlerInterface',
            1 => 'SessionHandlerInterface',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Ext\\Session\\PdoSessionHandler::__construct:0' => array(
                    0 => 'router',
                    1 => 'Mobicms\\Routing\\Router',
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Firewall\\Firewall' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\Form\\AssignValues' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Form\\AssignValues::__construct:0' => array(
                    0 => 'form',
                    1 => 'Mobicms\\Form\\Form',
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Form\\Fields' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Form\\Fields::__construct:0' => array(
                    0 => 'option',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Form\\Form' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setError' => 0,
            'setValid' => 0,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Form\\Form::__construct:0' => array(
                    0 => 'option',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setError' => array(
                'Mobicms\\Form\\Form::setError:0' => array(
                    0 => 'field',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Form\\Form::setError:1' => array(
                    0 => 'errorMsg',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setValid' => array(
                'Mobicms\\Form\\Form::setValid:0' => array(
                    0 => 'state',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Form\\Validate' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Form\\Validate::__construct:0' => array(
                    0 => 'type',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Form\\Validate::__construct:1' => array(
                    0 => 'value',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Form\\Validate::__construct:2' => array(
                    0 => 'option',
                    1 => null,
                    2 => false,
                    3 => array(),
                ),
            ),
        ),
    ),
    'Mobicms\\HtmlFilter\\Filter' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\HtmlFilter\\Filter::__construct:0' => array(
                    0 => 'arguments',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\HtmlFilter\\Purify' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\HtmlFilter\\Purify::__construct:0' => array(
                    0 => 'arguments',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\i18n\\Loader\\GettextPo' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Locales' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\i18n\\Locales::__construct:0' => array(
                    0 => 'request',
                    1 => 'Zend\\Http\\PhpEnvironment\\Request',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\i18n\\Locales::__construct:1' => array(
                    0 => 'user',
                    1 => 'Mobicms\\Checkpoint\\Facade',
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\i18n\\Plural\\Pluralization' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule1' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule10' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule11' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule12' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule13' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule14' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule2' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule3' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule4' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule5' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule6' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule7' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule8' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Plural\\Rule9' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\i18n\\Translate' => array(
        'supertypes' => array(
            0 => 'Mobicms\\i18n\\Locales',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setModule' => 0,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\i18n\\Translate::__construct:0' => array(
                    0 => 'request',
                    1 => 'Zend\\Http\\PhpEnvironment\\Request',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\i18n\\Translate::__construct:1' => array(
                    0 => 'session',
                    1 => 'Zend\\Session\\Container',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\i18n\\Translate::__construct:2' => array(
                    0 => 'user',
                    1 => 'Mobicms\\Checkpoint\\Facade',
                    2 => true,
                    3 => null,
                ),
            ),
            'setModule' => array(
                'Mobicms\\i18n\\Translate::setModule:0' => array(
                    0 => 'module',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Ioc\\Container' => array(
        'supertypes' => array(
            0 => 'Interop\\Container\\ContainerInterface',
            1 => 'Psr\\Container\\ContainerInterface',
        ),
        'instantiator' => null,
        'methods' => array(
            '__construct' => 3,
            'setService' => 0,
            'setCallable' => 0,
        ),
        'parameters' => array(
            'setService' => array(
                'Mobicms\\Ioc\\Container::setService:0' => array(
                    0 => 'name',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Ioc\\Container::setService:1' => array(
                    0 => 'service',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setCallable' => array(
                'Mobicms\\Ioc\\Container::setCallable:0' => array(
                    0 => 'name',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Ioc\\Container::setCallable:1' => array(
                    0 => 'callable',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Ioc\\Exception\\NotFoundException' => array(
        'supertypes' => array(
            0 => 'Interop\\Container\\Exception\\NotFoundException',
            1 => 'Psr\\Container\\NotFoundExceptionInterface',
            2 => 'Psr\\Container\\ContainerExceptionInterface',
            3 => 'Interop\\Container\\Exception\\ContainerException',
            4 => 'BadMethodCallException',
            5 => 'BadFunctionCallException',
            6 => 'LogicException',
            7 => 'Exception',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Ioc\\Exception\\NotFoundException::__construct:0' => array(
                    0 => 'message',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Ioc\\Exception\\NotFoundException::__construct:1' => array(
                    0 => 'code',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Ioc\\Exception\\NotFoundException::__construct:2' => array(
                    0 => 'previous',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Routing\\Router' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Routing\\Router::__construct:0' => array(
                    0 => 'request',
                    1 => 'Zend\\Http\\PhpEnvironment\\Request',
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Template\\Traits\\HelpersTrait' => array(
        'supertypes' => array(),
        'instantiator' => null,
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\Template\\Traits\\PathTrait' => array(
        'supertypes' => array(),
        'instantiator' => null,
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\Template\\View' => array(
        'supertypes' => array(
            0 => 'Countable',
            1 => 'Serializable',
            2 => 'ArrayAccess',
            3 => 'Traversable',
            4 => 'IteratorAggregate',
            5 => 'Mobicms\\Api\\ViewInterface',
            6 => 'ArrayObject',
            7 => 'IteratorAggregate',
            8 => 'Traversable',
            9 => 'ArrayAccess',
            10 => 'Serializable',
            11 => 'Countable',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setSanitize' => 0,
            'setLayout' => 0,
            'setTemplate' => 0,
            'setCss' => 0,
            'setJs' => 0,
            'setFlags' => 0,
            'setIteratorClass' => 0,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Template\\View::__construct:0' => array(
                    0 => 'router',
                    1 => 'Mobicms\\Routing\\Router',
                    2 => true,
                    3 => null,
                ),
            ),
            'setSanitize' => array(
                'Mobicms\\Template\\View::setSanitize:0' => array(
                    0 => 'key',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Template\\View::setSanitize:1' => array(
                    0 => 'val',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setLayout' => array(
                'Mobicms\\Template\\View::setLayout:0' => array(
                    0 => 'file',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Template\\View::setLayout:1' => array(
                    0 => 'module',
                    1 => null,
                    2 => false,
                    3 => false,
                ),
            ),
            'setTemplate' => array(
                'Mobicms\\Template\\View::setTemplate:0' => array(
                    0 => 'template',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Template\\View::setTemplate:1' => array(
                    0 => 'key',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Template\\View::setTemplate:2' => array(
                    0 => 'module',
                    1 => null,
                    2 => false,
                    3 => true,
                ),
            ),
            'setCss' => array(
                'Mobicms\\Template\\View::setCss:0' => array(
                    0 => 'file',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Template\\View::setCss:1' => array(
                    0 => 'media',
                    1 => null,
                    2 => false,
                    3 => '',
                ),
            ),
            'setJs' => array(
                'Mobicms\\Template\\View::setJs:0' => array(
                    0 => 'file',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Template\\View::setJs:1' => array(
                    0 => 'args',
                    1 => null,
                    2 => false,
                    3 => array(),
                ),
            ),
            'setFlags' => array(
                'Mobicms\\Template\\View::setFlags:0' => array(
                    0 => 'flags',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setIteratorClass' => array(
                'Mobicms\\Template\\View::setIteratorClass:0' => array(
                    0 => 'iteratorClass',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Template\\ViewFactory' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(),
        'parameters' => array(),
    ),
    'Mobicms\\Utility\\Image' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Utility\\Image::__construct:0' => array(
                    0 => 'request',
                    1 => 'Zend\\Http\\PhpEnvironment\\Request',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Utility\\Image::__construct:1' => array(
                    0 => 'router',
                    1 => 'Mobicms\\Routing\\Router',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Utility\\Image::__construct:2' => array(
                    0 => 'user',
                    1 => 'Mobicms\\Checkpoint\\Facade',
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Utility\\Image::__construct:3' => array(
                    0 => 'arguments',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Utility\\Mail' => array(
        'supertypes' => array(),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Utility\\Mail::__construct:0' => array(
                    0 => 'from',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Utility\\Mail::__construct:1' => array(
                    0 => 'fromName',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Utility\\Mail::__construct:2' => array(
                    0 => 'to',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Utility\\Mail::__construct:3' => array(
                    0 => 'subject',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Utility\\Mail::__construct:4' => array(
                    0 => 'body',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
        ),
    ),
    'Mobicms\\Validator\\Email' => array(
        'supertypes' => array(
            0 => 'Zend\\Validator\\ValidatorInterface',
            1 => 'Zend\\Validator\\Translator\\TranslatorAwareInterface',
            2 => 'Zend\\Validator\\AbstractValidator',
            3 => 'Zend\\Validator\\Translator\\TranslatorAwareInterface',
            4 => 'Zend\\Validator\\ValidatorInterface',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setOptions' => 0,
            'setMessage' => 0,
            'setMessages' => 0,
            'setValueObscured' => 0,
            'setTranslator' => 3,
            'setTranslatorTextDomain' => 3,
            'setTranslatorEnabled' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Validator\\Email::__construct:0' => array(
                    0 => 'options',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
            ),
            'setOptions' => array(
                'Mobicms\\Validator\\Email::setOptions:0' => array(
                    0 => 'options',
                    1 => null,
                    2 => false,
                    3 => array(),
                ),
            ),
            'setMessage' => array(
                'Mobicms\\Validator\\Email::setMessage:0' => array(
                    0 => 'messageString',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Validator\\Email::setMessage:1' => array(
                    0 => 'messageKey',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
            ),
            'setMessages' => array(
                'Mobicms\\Validator\\Email::setMessages:0' => array(
                    0 => 'messages',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setValueObscured' => array(
                'Mobicms\\Validator\\Email::setValueObscured:0' => array(
                    0 => 'flag',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setTranslator' => array(
                'Mobicms\\Validator\\Email::setTranslator:0' => array(
                    0 => 'translator',
                    1 => 'Zend\\Validator\\Translator\\TranslatorInterface',
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Validator\\Email::setTranslator:1' => array(
                    0 => 'textDomain',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
            ),
            'setTranslatorTextDomain' => array(
                'Mobicms\\Validator\\Email::setTranslatorTextDomain:0' => array(
                    0 => 'textDomain',
                    1 => null,
                    2 => false,
                    3 => 'default',
                ),
            ),
            'setTranslatorEnabled' => array(
                'Mobicms\\Validator\\Email::setTranslatorEnabled:0' => array(
                    0 => 'enabled',
                    1 => null,
                    2 => false,
                    3 => true,
                ),
            ),
        ),
    ),
    'Mobicms\\Validator\\Nickname' => array(
        'supertypes' => array(
            0 => 'Zend\\Validator\\ValidatorInterface',
            1 => 'Zend\\Validator\\Translator\\TranslatorAwareInterface',
            2 => 'Zend\\Validator\\AbstractValidator',
            3 => 'Zend\\Validator\\Translator\\TranslatorAwareInterface',
            4 => 'Zend\\Validator\\ValidatorInterface',
        ),
        'instantiator' => '__construct',
        'methods' => array(
            '__construct' => 3,
            'setOptions' => 0,
            'setMessage' => 0,
            'setMessages' => 0,
            'setValueObscured' => 0,
            'setTranslator' => 3,
            'setTranslatorTextDomain' => 3,
            'setTranslatorEnabled' => 3,
        ),
        'parameters' => array(
            '__construct' => array(
                'Mobicms\\Validator\\Nickname::__construct:0' => array(
                    0 => 'options',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
            ),
            'setOptions' => array(
                'Mobicms\\Validator\\Nickname::setOptions:0' => array(
                    0 => 'options',
                    1 => null,
                    2 => false,
                    3 => array(),
                ),
            ),
            'setMessage' => array(
                'Mobicms\\Validator\\Nickname::setMessage:0' => array(
                    0 => 'messageString',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
                'Mobicms\\Validator\\Nickname::setMessage:1' => array(
                    0 => 'messageKey',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
            ),
            'setMessages' => array(
                'Mobicms\\Validator\\Nickname::setMessages:0' => array(
                    0 => 'messages',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setValueObscured' => array(
                'Mobicms\\Validator\\Nickname::setValueObscured:0' => array(
                    0 => 'flag',
                    1 => null,
                    2 => true,
                    3 => null,
                ),
            ),
            'setTranslator' => array(
                'Mobicms\\Validator\\Nickname::setTranslator:0' => array(
                    0 => 'translator',
                    1 => 'Zend\\Validator\\Translator\\TranslatorInterface',
                    2 => false,
                    3 => null,
                ),
                'Mobicms\\Validator\\Nickname::setTranslator:1' => array(
                    0 => 'textDomain',
                    1 => null,
                    2 => false,
                    3 => null,
                ),
            ),
            'setTranslatorTextDomain' => array(
                'Mobicms\\Validator\\Nickname::setTranslatorTextDomain:0' => array(
                    0 => 'textDomain',
                    1 => null,
                    2 => false,
                    3 => 'default',
                ),
            ),
            'setTranslatorEnabled' => array(
                'Mobicms\\Validator\\Nickname::setTranslatorEnabled:0' => array(
                    0 => 'enabled',
                    1 => null,
                    2 => false,
                    3 => true,
                ),
            ),
        ),
    ),
);
