<?php
/**
 * aws-upload - 🌈 A delicious CLI Tool for uploading files to ec2
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 * @author    Marco Buttini <marco.asdman@gmail.com>
 * @copyright 2017 Marco Buttini
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License
 */

namespace AwsUpload\System;

use AwsUpload\Setting\SettingFolder;

class Zshrc
{
    /**
     * @var string
     */
    public $zshrc;


    public function __construct()
    {
        $this->zshrc = SettingFolder::getHomeDir() . '/../.zshrc';
    }

    /**
     * Activate the plugin.
     *
     * @return bool
     */
    public function enablePlugin($plugin_name)
    {
        $zshrc_body = $this->getZshrcContent();
        $zshrc_body = $this->writeInZshrcPluginVariable($zshrc_body, $plugin_name);

        return $this->updateZshrcContent($zshrc_body);
    }

    /**
     * @param string $line
     * @return bool
     */
    private function isValidPluginLine($line, $plugin_name)
    {
        $trimLine = trim($line);
        $isCommentLine = ($trimLine[0] === '#');
        $hasPluginsSyntax = (strpos($line, 'plugins=(') !== false);
        $hasEndParentesys = (strpos($line, ')') !== false);
        $pluginIsNotYetActive = (strpos($line, $plugin_name) === false);

        return (! $isCommentLine &&
                $hasPluginsSyntax &&
                $hasEndParentesys &&
                $pluginIsNotYetActive);
    }

    /**
     * @param string[] $zshrc_body
     * @return bool
     */
    private function hasOnePluginLine($zshrc_body)
    {
        $hasPluginLine = false;
        foreach ($zshrc_body as $key => $line) {
            $trimLine = trim($line);

            if (empty($trimLine)) {
                continue;
            }

            $isCommentLine = ($trimLine[0] === '#');
            $hasPluginsSyntax = (strpos($line, 'plugins=(') !== false);
            $hasEndParentesys = (strpos($line, ')') !== false);

            $hasPluginLine = (! $isCommentLine &&
                                $hasPluginsSyntax &&
                                $hasEndParentesys) ? true : $hasPluginLine;
        }

        return $hasPluginLine;
    }

    /**
     * @param string[] $zshrc_body
     * @param string   $plugin_name
     * @return string[] $zshrc_body
     */
    private function writeInZshrcPluginVariable($zshrc_body, $plugin_name)
    {
        $zshrc_body = $this->attemptCaseBasicInsert($zshrc_body, $plugin_name);
        $zshrc_body = $this->attemptCaseNoPlugin($zshrc_body, $plugin_name);

        return $zshrc_body;
    }

    private function attemptCaseBasicInsert($zshrc_body, $plugin_name)
    {
        // attempt basic insert in one line
        foreach ($zshrc_body as $key => $line) {
            $trimLine = trim($line);

            if (empty($trimLine)) {
                continue;
            }

            if ($this->isValidPluginLine($line, $plugin_name)) {
                $zshrc_body[$key] = str_replace(')', ' ' . $plugin_name . ')', $line);
            }
        }

        return $zshrc_body;
    }

    private function attemptCaseNoPlugin($zshrc_body, $plugin_name)
    {
        // case no plugin line at all
        if (!$this->hasOnePluginLine($zshrc_body)) {
            $zshrc_body[] = "\n" . 'plugins=(' . $plugin_name . ')';
        }

        return $zshrc_body;
    }

    private function getZshrcContent()
    {
        return file($this->zshrc);
    }

    private function updateZshrcContent($zshrc_body)
    {
        return file_put_contents($this->zshrc, implode('', $zshrc_body));
    }
}
