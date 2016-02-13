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

namespace Mobicms\Exception\Handler;

use Exception;

/**
 * Class Handler
 *
 * @package Mobicms\Exceptions\Handler
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @author  DE BONA Vivien <debona.vivien@gmail.com>
 * @version v.1.0.0 2015-07-18
 */
class Handler
{
    private $additionnalLines = 5;
    private $geshiInstance;
    private $counter = 1;

    protected $handlerType;
    protected $message;
    protected $code = '';
    protected $file;
    protected $line = 0;
    protected $type;

    public function __construct()
    {
        set_error_handler([$this, 'errors']);
        set_exception_handler([$this, 'exceptions']);
    }

    public function errors($errNum, $errMsg, $errFile, $errLine)
    {
        $this->handlerType = 'error';
        $this->message = $errMsg;
        $this->file = $errFile;
        $this->type = $this->getErrorType($errNum);
        $this->line = $errLine;
        $this->display([]);
    }

    public function exceptions(Exception $e)
    {
        $this->handlerType = 'exception';
        $this->message = $e->getMessage();
        $this->file = $e->getFile();
        $this->type = get_class($e);
        $this->line = $e->getLine();
        $this->display($e->getTrace());
    }

    /**
     * Handles exception/error and display them in a beautiful way
     *
     * @param array $trace
     */
    private function display(array $trace)
    {
        ob_end_clean();
        ob_start();
        $this->code = self::trace($this->line, $this->file);

        include 'templates/header.php';
        include 'templates/content.php';

        if (!empty($trace)) {
            foreach ($trace as $e) {
                $e = (object)$e;
                $this->message = '';
                $this->file = $e->file;
                $this->line = $e->line;
                $this->code = $this->trace($this->line, $this->file);
                $this->counter++;
                include 'templates/content.php';
            }
        }

        include 'templates/footer.php';
        ob_end_flush();
        \App::getInstance()->view()->setLayout(false); //TODO: improve!
        exit;
    }

    /**
     * Traces the exception/error
     *
     * @param   int    $line The line concerned
     * @param   string $file The file concerned
     * @return  string  The colored code
     */
    private function trace($line, $file)
    {
        try {
            $fileContents = file($file);
            $source = '';

            // Get 5 lines before and after the flawed line
            for ($x = ($line - $this->additionnalLines - 1); $x < ($line + $this->additionnalLines); $x++) {
                if (!empty($fileContents[$x])) {
                    $source .= $fileContents[$x];
                }
            }

            return $this->highlight($line, $source);
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Configures and launches GeSHi
     *
     * @param int    $line
     * @param string $source
     * @return string
     */
    private function highlight($line, $source)
    {
        if (null === $this->geshiInstance) {
            $this->geshiInstance = new \GeSHi;
            $this->geshiInstance->set_language('php');
            $this->geshiInstance->set_header_type(GESHI_HEADER_NONE);
            $this->geshiInstance->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
        }

        $this->geshiInstance->set_source($source);
        $this->geshiInstance->highlight_lines_extra([$this->additionnalLines + 1]);
        $this->geshiInstance->set_highlight_lines_extra_style('background-color: #FCFFBF; border: 1px solid red;');
        $this->geshiInstance->start_line_numbers_at($line - $this->additionnalLines);

        return $this->geshiInstance->parse_code();
    }

    /**
     * Returns the type of the error
     *
     * @param int $errNum
     * @return string
     */
    protected function getErrorType($errNum)
    {
        $types = [
            1     => 'E_ERROR',
            2     => 'E_WARNING',
            4     => 'E_PARSE',
            8     => 'E_NOTICE',
            16    => 'E_CORE_ERROR',
            32    => 'E_CORE_WARNING',
            64    => 'E_COMPILE_ERROR',
            128   => 'E_COMPILE_WARNING',
            256   => 'E_USER_ERROR',
            512   => 'E_USER_WARNING',
            1024  => 'E_USER_NOTICE',
            2048  => 'E_STRICT',
            4096  => 'E_RECOVERABLE_ERROR',
            8192  => 'E_DEPRECATED',
            16384 => 'E_USER_DEPRECATED',
            32767 => 'E_ALL',
        ];

        return isset($types[$errNum]) ? $types[$errNum] : 'unknown error';
    }
}
