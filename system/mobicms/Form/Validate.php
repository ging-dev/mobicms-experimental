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

namespace Mobicms\Form;

/**
 * Class Validate
 *
 * @package Mobicms\Form
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-09-25
 */
class Validate
{
    public $error = [];
    public $is = false;

    public function __construct($type, $value, array $option = [])
    {
        if (method_exists($this, $type)) {
            $option['value'] = $value;
            $this->is = call_user_func([$this, $type], $option);
        } else {
            $this->error[] = 'Unknown Validator';
        }
    }

    protected function captcha(array $option)
    {
        $session = \App::getInstance()->session(); //TODO: доработать

        if (isset($option['value'])
            && $session->offsetExists('captcha')
            && strtoupper($session->offsetGet('captcha')) == strtoupper($option['value'])
        ) {
            return true;
        }
        $this->error[] = _s('The security code is not correct');

        return false;
    }

    /**
     * Валидация длины строки
     *
     * @param array $option
     *
     * @return bool
     */
    protected function lenght(array $option)
    {
        if (isset($option['empty']) && $option['empty'] && empty($option['value'])) {
            return true;
        }

        if (isset($option['min']) && mb_strlen($option['value']) < $option['min']) {
            $this->error[] = sprintf(_sp('Min. %d symbol', 'Min. %d symbols', $option['min']), $option['min']);

            return false;
        } elseif (isset($option['max']) && mb_strlen($option['value']) > $option['max']) {
            $this->error[] = sprintf(_sp('Max. %d symbol', 'Max. %d symbols', $option['max']), $option['max']);

            return false;
        }

        return true;
    }

    /**
     * Валидация числового значения
     *
     * @param array $option
     *
     * @return bool
     */
    protected function numeric(array $option)
    {
        if (isset($option['empty']) && $option['empty'] && empty($option['value'])) {
            return true;
        }

        if (!is_numeric($option['value'])) {
            $this->error[] = _s('Must be a number');

            return false;
        }

        if (isset($option['min']) && $option['value'] < $option['min']) {
            $this->error[] = sprintf(_s('Minimum %d'), $option['min']);

            return false;
        } elseif (isset($option['max']) && $option['value'] > $option['max']) {
            $this->error[] = sprintf(_s('Maximum %d'), $option['max']);

            return false;
        }

        return true;
    }

    /**
     * Валидация IPv4 адреса
     *
     * @param array $option
     *
     * @return bool
     */
    protected function ip(array $option)
    {
        if (filter_var($option['value'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return true;
        }

        $this->error[] = _s('IP address is not valid');

        return false;
    }

    /**
     * Сравнение двух значений
     *
     * @param array $option
     *
     * @return bool
     */
    protected function compare(array $option)
    {
        if ($option['value'] == $option['compare_value']) {
            return true;
        }

        $this->error[] = isset($option['error']) ? $option['error'] : _s('The values do not match');

        return false;
    }
}
