<?php

namespace Mobicms\Form;

class AssignValues
{
    /**
     * @var Form
     */
    private $form;

    public function __construct(Form $form)
    {
        $this->form = $form;

        foreach ($this->form->fields as &$element) {
            $this->setValues($element);
        }
    }

    /**
     * Set values to the fields
     *
     * @param array $option
     */
    protected function setValues(array &$option)
    {
        if (isset($types[$option['type']])) {
            switch ($option['type']) {
                case 'text':
                case 'password':
                case 'hidden':
                case 'textarea':
                    $this->assignText($option);
                    break;

                case 'radio':
                    $this->assignRadio($option);
                    break;

                case 'select':
                    $this->assignSelect($option);
                    break;

                case 'checkbox':
                    $this->assingCheckbox($option);
                    break;
            }
        }
    }

    /**
     * Assign values to the text fields
     *
     * @param array $option
     */
    protected function assignText(array &$option)
    {
        if (isset($this->form->input[$option['name']])) {
            $option['value'] = trim($this->form->input[$option['name']]);
            unset($this->form->input[$option['name']]);

            // Применяем лимитер
            if (isset($option['limit'])) {
                $this->limiter($option);
            }

            if (isset($option['required']) && $option['required'] && empty($option['value'])) {
                // Проверка на обязательное поле
                $option['error'] = _s('This field is required');
                $this->form->validate = false;
                $this->form->setValid(false);
            }

            $this->form->output[$option['name']] = $this->filter($option);
        } else {
            $this->form->setValid(false);
        }
    }

    /**
     * Filter input variables
     *
     * @param array $option
     * @return mixed
     */
    protected function filter($option)
    {
        $filter = isset($option['filter']) ? $option['filter'] : FILTER_DEFAULT;
        $filterOptions = isset($option['filter_options']) ? $option['filter_options'] : [];

        return filter_var($option['value'], $filter, $filterOptions);
    }

    /**
     * Assign values to the radio fields
     *
     * @param array $option
     */
    protected function assignRadio(array &$option)
    {
        if (isset($this->form->input[$option['name']], $option['items'])) {
            if (array_key_exists($this->form->input[$option['name']], $option['items'])) {
                $option['checked'] = trim($this->form->input[$option['name']]);
                $this->form->output[$option['name']] = $option['checked'];
                unset($this->form->input[$option['name']]);
            } else {
                $this->form->setValid(false);
            }
        }
    }

    /**
     * Assign values to the select fields
     *
     * @param array $option
     */
    protected function assignSelect(array &$option)
    {
        if (isset($this->form->input[$option['name']], $option['items'])) {
            $allow = true;

            if (isset($option['multiple']) && $option['multiple']) {
                foreach ($this->form->input[$option['name']] as $val) {
                    if (!array_key_exists($val, $option['items'])) {
                        $allow = false;
                        break;
                    }
                }
            } else {
                if (!array_key_exists($this->form->input[$option['name']], $option['items'])) {
                    $allow = false;
                }
            }

            if ($allow) {
                $option['selected'] = $this->form->input[$option['name']];
                $this->form->output[$option['name']] = $option['selected'];
                unset($this->form->input[$option['name']]);
            } else {
                $this->form->setValid(false);
            }
        }
    }

    /**
     * Assign values to the checkbox
     *
     * @param array $option
     */
    protected function assingCheckbox(array &$option)
    {
        if (isset($this->form->input[$option['name']])) {
            unset($this->form->input[$option['name']]);
            $option['checked'] = 1;
            $this->form->output[$option['name']] = 1;
        } else {
            unset($option['checked']);
            $this->form->output[$option['name']] = 0;
        }
    }

    /**
     * Filter values
     *
     * @param $option
     */
    protected function limiter(&$option)
    {
        $min = isset($option['limit']['min']) ? intval($option['limit']['min']) : false;
        $max = isset($option['limit']['max']) ? intval($option['limit']['max']) : false;

        switch ($option['limit']['type']) {
            case 'str':
            case 'string':
                if ($max !== false) {
                    $option['value'] = mb_substr($option['value'], 0, $max);
                }

                break;

            case 'int':
            case 'integer':
                $option['value'] = intval($option['value']);

                if ($min !== false && $option['value'] < $min) {
                    $option['value'] = $min;
                }

                if ($max !== false && $option['value'] > $max) {
                    $option['value'] = $max;
                }

                break;

            default:
                $option['error'] = 'Unknown limiter: ' . $option['limit']['type'];
        }
    }
}
