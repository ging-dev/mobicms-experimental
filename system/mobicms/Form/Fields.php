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

use Mobicms\Editors\Editor;

/**
 * Class Fields
 *
 * @package Mobicms\Form
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-02-01
 */
class Fields
{
    private $elements = [
        'checkbox'     => ['<input%s%s type="checkbox" value="1"%s%s/>', 'id,name,class,checked'],
        'description'  => ['<span%s>%s</span>', 'description_class,description'],
        'file'         => ['<input%s%s%s type="file"/>', 'id,name,class'],
        'hidden'       => ['<input%s type="hidden" value="%s"/>', 'name,value'],
        'label'        => ['<label%s%s>%s</label>', 'for,label_class,label'],
        'label_inline' => ['<label%s>%s%s</label>', 'label_inline_class,content,label_inline'],
        'option'       => ['<option value="%s"%s>%s</option>', 'value,selected,label'],
        'password'     => ['<input%s%s type="password" value="%s"%s/>', 'id,name,value,class'],
        'radio'        => ['<input%s type="radio" value="%s"%s%s/>', 'name,value,class,checked'],
        'select'       => ['<select%s%s%s>%s</select>', 'name,class,multiple,content'],
        'submit'       => ['<button%s%s type="submit"%s>%s</button>', 'id,name,class,value'],
        'text'         => ['<input%s%s type="text" value="%s"%s%s%s%s/>', 'id,name,value,class,style,maxlength,readonly'],
        'textarea'     => ['<textarea%s%s%s%s%s>%s</textarea>', 'id,name,rows,class,style,value'],
    ];

    private $attributes = [
        'checked'            => ' checked="checked"',
        'class'              => ' class="%s"',
        'content'            => '%s',
        'description'        => '%s',
        'description_class'  => ' class="%s"',
        'disabled'           => ' disabled="disabled"',
        'for'                => ' for="%s"',
        'id'                 => ' id="%s"',
        'label'              => '%s',
        'label_class'        => ' class="%s"',
        'label_inline'       => '%s',
        'label_inline_class' => ' class="%s"',
        'maxlength'          => ' maxlength="%u"',
        'multiple'           => ' multiple="multiple"',
        'name'               => ' name="%s"',
        'readonly'           => ' readonly="readonly"',
        'rows'               => ' rows="%u"',
        'selected'           => ' selected="selected"',
        'style'              => ' style="%s"',
        'value'              => '%s',
    ];

    private $option;

    public function __construct(array $option)
    {
        if (!isset($option['type'])) {
            throw new \InvalidArgumentException('required argument [type] is missing');
        }

        if (!isset($option['name'])) {
            throw new \InvalidArgumentException('required argument [name] is missing');
        }

        if (isset($option['value']) && !is_numeric($option['value'])) {
            $option['value'] = htmlspecialchars_decode($option['value'], ENT_QUOTES);
            $option['value'] = htmlspecialchars($option['value'], ENT_QUOTES, 'UTF-8');
        }

        if (isset($option['error'])) {
            $option['class'] = isset($option['class']) ? $option['class'] . ' error' : 'error';
        } elseif (isset($option['success']) && $option['success']) {
            $option['class'] = isset($option['class']) ? $option['class'] . ' success' : 'success';
        }

        $this->option = $option;
    }

    /**
     * Сборка готового элемента с декораторами
     *
     * @return string
     */
    public function display()
    {
        $out = [];

        // Добавляем метку LABEL
        if (isset($this->option['label'])) {
            if (!isset($this->option['id'])) {
                $this->option['id'] = $this->option['name'];
            }

            $this->checkRequired($this->option['label']);
            $this->option['for'] = $this->option['id'];
            $out[] = $this->build('label', $this->option);
        }

        // Добавляем сообщение об ошибке
        if (isset($this->option['error'])) {
            if (is_array($this->option['error'])) {
                $out[] = '<span class="error-text">' . implode('<br/>', $this->option['error']) . '</span>';
            } else {
                $out[] = '<span class="error-text">' . $this->option['error'] . '</span>';
            }
        }

        switch ($this->option['type']) {
            case 'radio':
                // Добавляем элемент RADIO
                if (!isset($this->option['items']) || !is_array($this->option['items'])) {
                    return 'ERROR: missing radio element items';
                }

                $out[] = $this->prepareRadio();
                break;

            case 'select':
                $out = $this->prepareSelect();
                break;

            case 'textarea':
                $out[] = $this->prepareTextarea();
                break;

            default:
                // Добавляем простой элемент
                if (isset($this->option['label_inline'])) {
                    if (!isset($this->option['label_inline_class'])) {
                        $this->option['label_inline_class'] = 'inline';
                    }

                    $this->checkRequired($this->option['label_inline']);
                    $this->option['content'] = $this->build($this->option['type'], $this->option);
                    $out[] = $this->build('label_inline', $this->option);
                } else {
                    $out[] = $this->build($this->option['type'], $this->option);
                }
        }

        // Добавляем описание DESCRIPTION
        if (isset($this->option['description'])) {
            if (!isset($this->option['description_class'])) {
                $this->option['description_class'] = 'description';
            }

            $out[] = $this->build('description', $this->option);
        }

        return implode(PHP_EOL, $out);
    }

    /**
     * Add an asterisk to the label
     *
     * @param $label
     */
    private function checkRequired(&$label)
    {
        if (isset($this->option['required']) && $this->option['required'] === true) {
            $label = '* ' . $label;
        }
    }

    /**
     * Создание элемента
     *
     * @param string $type
     * @param array  $option
     * @return string
     */
    private function build($type, array $option)
    {
        $placeholders = [];

        foreach (explode(',', $this->elements[$type][1]) as $val) {
            if (isset($option[$val], $this->attributes[$val]) && (!empty($option[$val]) || ($option[$val] == 0 && $val != 'checked'))) {
                $placeholders[] = sprintf($this->attributes[$val], $option[$val]);
            } else {
                $placeholders[] = '';
            }
        }

        return vsprintf($this->elements[$type][0], $placeholders);
    }

    /**
     * @return string
     */
    private function prepareRadio()
    {
        $out = [];

        foreach ($this->option['items'] as $key => $value) {
            $radio = [];
            $radio['name'] = $this->option['name'];
            $radio['value'] = $key;

            if (isset($this->option['checked']) && $this->option['checked'] == $key) {
                $radio['checked'] = true;
            }

            if (is_array($value)) {
                $label = isset($value['label']) ? $value['label'] : '';
            } else {
                $label = $value;
            }

            if (empty($label)) {
                $out[] = $this->build('radio', $radio);
            } else {
                $radio['label_inline'] = $label;
                $radio['label_inline_class'] = isset($this->option['label_inline_class']) ? $this->option['label_inline_class'] : 'inline';
                $radio['content'] = $this->build('radio', $radio);
                $out[] = $this->build('label_inline', $radio);
            }

            if (isset($value['description']) && !empty($value['description'])) {
                if (!isset($value['description_class'])) {
                    $value['description_class'] = 'description';
                }

                $out[] = $this->build('description', $value);
            }
        }

        return implode(PHP_EOL, $out);
    }

    /**
     * @return string
     */
    private function prepareSelect()
    {
        // Добавляем элемент SELECT
        $multiple = isset($this->option['multiple']) && $this->option['multiple'] ? true : false;
        if (isset($this->option['items']) && is_array($this->option['items'])) {
            $list = [];
            $listElement = [];
            foreach ($this->option['items'] as $value => $label) {
                if (empty($label)) {
                    $listElement['label'] = $value;
                }

                if (isset($this->option['selected'])) {
                    if ($multiple && is_array($this->option['selected'])) {
                        if (in_array($value, $this->option['selected'])) {
                            $listElement['selected'] = true;
                        }
                    } else {
                        if ($this->option['selected'] == $value) {
                            $listElement['selected'] = true;
                        }
                    }
                }

                $listElement['value'] = $value;
                $listElement['label'] = $label;
                $list[] = $this->build('option', $listElement);
            }
            $this->option['content'] = PHP_EOL . implode(PHP_EOL, $list) . PHP_EOL;
        }

        if ($multiple) {
            $this->option['name'] = $this->option['name'] . '[]';
        }

        return $this->build('select', $this->option);
    }

    /**
     * @return string
     */
    private function prepareTextarea()
    {
        if (!empty($this->option['editor'])) {
            $config = \App::getInstance()->user()->get()->getConfig();
            // Initialize editor
            $this->option['id'] = 'editor';
            $editor = new Editor($config->editor);
            $editor->setLanguage(\App::getInstance()->config()['lng']['lng']);
            $this->option['style'] = $editor->getStyle();

            if (empty($this->option['description'])) {
                $this->option['description'] = $editor->getHelp();
            } else {
                $this->option['description'] = $editor->getHelp() . '<br>' . $this->option['description'];
            }

            $editor->display();
        }

        return $this->build($this->option['type'], $this->option);
    }
}
