<?php
/**
 * District5 - Cli
 *
 * @copyright District5
 *
 * @author District5
 * @author Roger Thomas <roger.thomas@district5.co.uk>
 * @link https://www.district5.co.uk
 *
 * @license This software and associated documentation (the "Software") may not be
 * used, copied, modified, distributed, published or licensed to any 3rd party
 * without the written permission of District5 or its author.
 *
 * The above copyright notice and this permission notice shall be included in
 * all licensed copies of the Software.
 *
 */

namespace District5\Cli;

/**
 * Class CliRouter
 * @noinspection PhpUnused
 * @package District5\Cli
 */
class CliRouter
{
    /**
     * Get the instance of the class to run for the console command.
     *
     * @noinspection PhpUnused
     * @param array $argv
     * @param array $injectables
     * @param string $clzAppend
     * @param string|null $psrNamespacePrefix
     * @return CliCommand|null
     */
    public static function getClassForRoute(array $argv, array $injectables, string $clzAppend, string $psrNamespacePrefix = null): ?CliCommand
    {
        if (!array_key_exists(1, $argv)) {
            return null;
        }

        $newArray = array_merge([], $argv);
        unset($newArray[0]);
        $proposedClassNames = [];
        $rollingNormal = '\\';
        $rollingPrefixed = '\\';
        if ($psrNamespacePrefix !== null) {
            $rollingPrefixed .= $psrNamespacePrefix . '\\';
        }
        $tryLength = 0;
        foreach ($newArray as $segment) {
            $tryLength++;

            $rollingNormal .= self::treatSegment($segment);
            if ($psrNamespacePrefix !== null) {
                $rollingPrefixed .= self::treatSegment($segment);
            }

            if ($tryLength > 0) {
                $normalClassName = $rollingNormal . $clzAppend;
                if ($psrNamespacePrefix !== null) {
                    $normalClassName = $rollingPrefixed . $clzAppend;
                }

                if (class_exists($normalClassName)) {
                    $proposedClassNames[$normalClassName] = $tryLength;
                }
                // else {
                //     /** @noinspection PhpArrayUsedOnlyForWriteInspection */
                //     $attemptedClassNames[] = $normalClassName;
                // }
            }
            $rollingNormal .= '\\';
            $rollingPrefixed .= '\\';
        }

        foreach (array_reverse($proposedClassNames) as $class => $offsetLength) {
            $tmpArgs = array_merge([], $newArray);
            $i = 0;
            while ($i < $offsetLength) {
                if (array_key_exists($i, $tmpArgs)) {
                    unset($tmpArgs[$i]);
                }
                $i++;
            }
            $instance = new $class();
            if ($instance instanceof CliCommand) {
                $instance->setCliArgvs(CliArgvs::getInstance($argv, count($argv)));
                $instance->setArguments(array_values($tmpArgs));
                $instance->setInjectables($injectables);
                return $instance;
            }
        }

        return null;
    }

    /**
     * @param string $segment
     * @return string
     */
    protected static function treatSegment(string $segment): string
    {
        $segmentPiece = explode('-', $segment);
        $s = '';
        foreach ($segmentPiece as $seg) {
            if (strlen($seg) === 0) {
                continue;
            }
            $s .= ucfirst($seg);
        }
        return $s;
    }
}
