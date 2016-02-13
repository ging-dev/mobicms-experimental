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

use Mobicms\Captcha\Captcha;

/**
 * Class Form
 *
 * @package Mobicms\Form
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-02-01
 */
class Form
{
    private $session;
    private $handler = false;
    private $isValid = false;
    private $form = [];
    private $rules = [];

    public $validate = true;
    public $fields = [];
    public $input;

    public $submitNames = [];
    public $isSubmitted = false;
    public $output = [];

    public $infoMessages = '<div class="alert %s">%s</div>';
    public $confirmation = false;
    public $continueLink;
    public $successMessage;
    public $errorMessage;

    public function __construct(array $option)
    {
        $this->session = \App::getInstance()->session(); //TODO: доработать
        $this->form = $option;

        if (!isset($this->form['name'])) {
            $this->form['name'] = 'form';
        }

        if (isset($option['method']) && $option['method'] == 'get') {
            $this->form['method'] = 'get';
            $this->input = filter_input_array(INPUT_GET);
        } else {
            $this->form['method'] = 'post';
            $this->input = filter_input_array(INPUT_POST);
        }

        $this->successMessage = _s('Data saved successfully');
        $this->errorMessage = _s('Errors occurred');
    }

    public function __toString()
    {
        return $this->display();
    }

    /**
     * Adding form elements
     *
     * @param string $type   Тип добавляемого элемента
     * @param string $name   Имя элемента
     * @param array  $option Дополнительные параметры
     * @return $this
     */
    public function element($type, $name, array $option = [])
    {
        if ($type == 'submit') {
            $this->submitNames[] = $name;
        } elseif ($type == 'file') {
            $this->form['enctype'] = true;
        } elseif ($type == 'textarea' && !isset($option['rows'])) {
            $option['rows'] = 5;
        }

        $option['type'] = $type;
        $option['name'] = $name;
        $this->fields[$name] = $option;

        return $this;
    }

    /**
     * Assign error to the field
     *
     * @param string $field
     * @param string $errorMsg
     */
    public function setError($field, $errorMsg)
    {
        if (!isset($this->fields[$field])) {
            throw new \InvalidArgumentException('The field [' . $field . '] does not exists');
        }

        $this->fields[$field]['error'] = $errorMsg;
        $this->setValid(false);
    }

    /**
     * Adding Title
     *
     * @param string      $title
     * @param null|string $class
     * @return $this
     */
    public function title($title, $class = null)
    {
        $this->fields[] = [
            'type'    => 'html',
            'content' => '<div class="' . ($class === null ? 'form-title' : $class) . '">' . htmlspecialchars($title) . '</div>',
        ];

        return $this;
    }

    /**
     * Adding HTML code
     * The string is not processed and transmitted in the form as it is.
     *
     * @param string $html
     * @return $this
     */
    public function html($html)
    {
        $this->fields[] = [
            'type'    => 'html',
            'content' => $html,
        ];

        return $this;
    }

    /**
     * Adding a divider
     *
     * @param int $height
     * @return $this
     */
    public function divider($height = 24)
    {
        $this->fields[] = [
            'type'    => 'html',
            'content' => '<div style="height: ' . $height . 'px; clear: both"></div>',
        ];

        return $this;
    }

    /**
     * Adding a CAPTCHA block
     *
     * @return $this
     */
    public function captcha()
    {
        $this->fields[] = ['type' => 'captcha'];

        return $this;
    }

    /**
     * Adding validation rules
     *
     * @param string $field
     * @param string $type
     * @param array  $options
     * @return $this
     */
    public function validate($field, $type, $options = [])
    {
        $options['field'] = $field;
        $options['type'] = $type;
        $this->rules[] = $options;

        return $this;
    }

    public function isValid()
    {
        if (!$this->handler) {
            $this->handler();
        }

        return $this->isValid;
    }

    public function setValid($state)
    {
        $this->isValid = $state === true ? true : false;
    }

    /**
     * Заключительная сборка готовой формы
     *
     * @return string
     */
    public function display()
    {
        // Информационные сообщения об ошибке, или успехе
        $message = '';
        $saved = filter_has_var(INPUT_GET, 'saved');

        if ($this->infoMessages !== false && $this->isSubmitted || $saved) {
            if ($this->isValid || $saved) {
                // Сообщение об удачном сохранении данных
                $message = sprintf($this->infoMessages, 'alert-success', $this->successMessage);

                if ($this->confirmation) {
                    // Если задано отдельное окно подтверждения
                    //TODO: Переделать App
                    $message .= '<a class="btn btn-primary" href="' . ($this->continueLink === null ? \App::getInstance()->request()->getBaseUrl() : $this->continueLink) . '/">' . _s('Continue') . '</a>';

                    return $message;
                }
            } else {
                // Сообщение, что имеются ошибки
                $message = sprintf($this->infoMessages, 'alert-danger', $this->errorMessage);
            }
        }

        $out = [];

        foreach ($this->fields as &$element) {
            // Создаем элемент формы
            switch ($element['type']) {
                case 'html':
                    $out[] = $element['content'];
                    break;

                case 'captcha':
                    $captcha = new Captcha;
                    $code = $captcha->generateCode();
                    $this->session->offsetSet('captcha', $code);
                    $out[] = '<img alt="' . _s('If you do not see the picture with the code, turn the graphics support in your browser and refresh the page') .
                        '" width="' . $captcha->width . '" height="' . $captcha->height . '" src="' . $captcha->generateImage($code) . '"/>';
                    break;

                default:
                    if ($this->isSubmitted && isset($element['reset_value'])) {
                        $element['value'] = $element['reset_value'];
                    }
                    $out[] = (new Fields($element))->display();
            }
        }

        // Добавляем токен валидации
        if (!$this->session->offsetExists('form_token')) {
            $this->session->offsetSet('form_token', md5(rand(1, 1000) . microtime()));
        }

        $out[] = (new Fields([
            'type'  => 'hidden',
            'name'  => 'form_token',
            'value' => $this->session->offsetGet('form_token'),
        ]))->display();

        return sprintf(PHP_EOL . $message . PHP_EOL . '<form role="form" name="%s" method="%s"%s%s%s>%s</form>' . PHP_EOL,
            $this->form['name'],
            $this->form['method'],
            (isset($this->form['action']) ? ' action="' . $this->form['action'] . '"' : ''),
            (isset($this->form['enctype']) ? ' enctype="multipart/form-data"' : ''),
            (isset($this->form['class']) ? ' class="' . $this->form['class'] . '"' : ''),
            PHP_EOL . implode(PHP_EOL, $out) . PHP_EOL
        );
    }

    /**
     * Processing form data
     *
     * @return bool
     */
    private function handler()
    {
        $this->handler = true;

        // Checking whether the form is submitted?
        foreach ($this->submitNames as $submit) {
            if (isset($this->input[$submit])) {
                if (isset($this->input['form_token'])
                    && $this->session->offsetExists('form_token')
                    && $this->input['form_token'] == $this->session->offsetGet('form_token')
                ) {
                    $this->isSubmitted = true;
                    $this->isValid = true;
                }
                break;
            }
        }

        if ($this->isSubmitted) {
            // Assigns the value
            new AssignValues($this);

            // Data Validation
            foreach ($this->rules as $validator) {
                if ($this->validate && array_key_exists($validator['field'], $this->fields)) {
                    $this->validateField($validator, $this->fields[$validator['field']]);
                }
            }
        }
    }

    /**
     * Валидация полей формы
     *
     * @param array $validator
     * @param array $option
     * @uses Validate
     */
    private function validateField(array $validator, array &$option)
    {
        if (isset($validator['valid']) && $validator['valid'] && !$this->isValid) {
            return;
        }

        if ($validator['type'] == 'compare') {
            if (isset($this->output[$validator['compare_field']])) {
                $validator['compare_value'] = $this->output[$validator['compare_field']];
            } else {
                $option['error'] = 'ERROR: compare field does not exist';
            }
        }

        $check = new Validate($validator['type'], $option['value'], $validator);

        if ($check->is !== true) {
            if (isset($option['error']) && !empty($option['error'])) {
                if (is_array($option['error'])) {
                    $option['error'] = array_merge($option['error'], $check->error);
                } else {
                    $option['error'] = $option['error'] . '<br/>' . implode('<br/>', $check->error);
                }
            } else {
                $option['error'] = $check->error;
            }

            $this->isValid = false;

            if (isset($validator['continue']) && !$validator['continue']) {
                $this->validate = false;
            }
        }
        unset($check);
    }
}
