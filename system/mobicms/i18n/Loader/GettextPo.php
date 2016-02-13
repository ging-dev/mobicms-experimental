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

namespace Mobicms\i18n\Loader;

/**
 * Class GettextPo
 *
 * @package   Mobicms\i18n\Loader
 * @author    Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @copyright Copyright (c) 2010, Union of RAD http://union-of-rad.org (http://lithify.me/)
 * @copyright Copyright (c) 2012, Clemens Tolboom
 * @version   v.1.0.0 2015-07-07
 */
class GettextPo
{
    private $item = [];
    private $messages = [];
    private $defaults = [
        'ids'        => [],
        'translated' => null,
    ];

    /**
     * Parses portable object (PO) format
     *
     * @param string $file
     * @return array
     */
    public function parse($file)
    {
        if (!file_exists($file) || ($stream = fopen($file, 'r')) === false) {
            return [];
        }

        $stream = fopen($file, 'r');
        $this->item = $this->defaults;

        while ($line = fgets($stream)) {
            $line = trim($line);
            $this->collate($line);
        }

        // save last item
        $this->addMessage();
        fclose($stream);

        return $this->messages;
    }

    private function collate($line)
    {
        if ($line === '') {
            $this->addMessage();
            $this->item = $this->defaults;

            return;
        }

        $this->collateMsgId($line);
    }

    private function collateMsgId($line)
    {
        if (substr($line, 0, 7) === 'msgid "') {
            $this->addMessage();
            $this->item = $this->defaults;
            $this->item['ids']['singular'] = substr($line, 7, -1);

            return;
        }

        $this->collateMsgtr($line);
    }

    private function collateMsgtr($line)
    {
        if (substr($line, 0, 8) === 'msgstr "') {
            $this->item['translated'] = substr($line, 8, -1);

            return;
        }

        $this->collateQuote($line);
    }

    private function collateQuote($line)
    {
        if ($line[0] === '"') {
            $continues = isset($this->item['translated']) ? 'translated' : 'ids';

            if (is_array($this->item[$continues])) {
                end($this->item[$continues]);
                $this->item[$continues][key($this->item[$continues])] .= substr($line, 1, -1);
            } else {
                $this->item[$continues] .= substr($line, 1, -1);
            }

            return;
        }

        $this->collatePlural($line);
    }

    private function collatePlural($line)
    {
        if (substr($line, 0, 14) === 'msgid_plural "') {
            $this->item['ids']['plural'] = substr($line, 14, -1);
        } elseif (substr($line, 0, 7) === 'msgstr[') {
            $size = strpos($line, ']');
            $this->item['translated'][(int)substr($line, 7, 1)] = substr($line, $size + 3, -1);
        }
    }

    /**
     * Save a translation item to the messages.
     *
     * A .po file could contain by error missing plural indexes. We need to
     * fix these before saving them.
     */
    private function addMessage()
    {
        if (is_array($this->item['translated'])) {
            $this->messages[stripcslashes($this->item['ids']['singular'])] = stripcslashes($this->item['translated'][0]);

            if (isset($this->item['ids']['plural'])) {
                $plurals = $this->item['translated'];
                ksort($plurals);
                end($plurals);
                $count = key($plurals);
                $empties = array_fill(0, $count + 1, '-');
                $plurals += $empties;
                ksort($plurals);
                $this->messages[stripcslashes($this->item['ids']['plural'])] = stripcslashes(implode(chr(0), $plurals));
            }
        } elseif (!empty($this->item['ids']['singular'])) {
            $this->messages[stripcslashes($this->item['ids']['singular'])] = stripcslashes($this->item['translated']);
        }
    }
}
