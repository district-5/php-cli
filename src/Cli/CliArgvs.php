<?php
/**
 * @noinspection SpellCheckingInspection
 */

namespace District5\Cli;

/**
 * Class CliArgvs
 */
class CliArgvs
{
    /**
     * Holds the static instance of this object.
     *
     * @var CliArgvs|null
     */
    protected static ?CliArgvs $_instance = null;

    /**
     * Holds the arguments, minus the script name.
     *
     * @example [
     *      ['name' => 'Joe'],
     *      ['age' => '24'],
     *      // etc...
     * ]
     *
     * @var array
     */
    protected array $args = [];

    /**
     * Holds previously established values. These differ depending
     * on whether values have been deduplicated. And the keys are
     * appended with `|CliArgvs|0` or `|CliArgvs|1` depending on whether
     * the argument requested was also requested to be deduplicated
     * or not.
     *
     * @example [
     *      'age|CliArgvs|0' => '24',
     *      'favFood|CliArgvs|1' => ['Rice', 'Chips', 'Curry']
     * ]
     * @var array
     */
    protected array $tmp = [];

    /**
     * The name of the script that's been executed.
     *
     * @example `my-script.php`
     * @var string
     */
    protected ?string $script = null;

    /**
     * Has the parameter of `--help`, `-help` or just `help` been passed
     * into the parameters?
     *
     * @var bool
     */
    protected bool $help = false;

    /**
     * When accepting parameters, should `--` or `-` be removed?
     * @example `--name=Joe` would be interpreted as `name=Joe` if
     * this is true.
     *
     * @var bool
     */
    protected bool $stripDashes = true;

    /**
     * @var array
     */
    protected array $flags = [];

    /**
     * Construct the instance, protected to avoid `new` instances. Passing in
     * the raw $args, $num of $args and whether leading dashes should also be
     * removed.
     *
     * @param array|null $args
     * @param int|null $num
     * @param bool $stripLeadingDashes (optional) default true.
     */
    protected function __construct(array $args = null, int $num = null, bool $stripLeadingDashes = true)
    {
        if (is_array($args) && count($args) === $num) {
            $this->stripDashes = $stripLeadingDashes;
            $this->processArguments($args);
        }
    }

    /**
     * Process the array of arguments into the stack.
     *
     * @param array $args
     * @return int
     */
    protected function processArguments(array $args): int
    {
        if (count($args) > 0) {
            $this->script = $args[0];
            array_shift($args);
        }

        foreach ($args as $arg) {
            if (strstr($arg, '=') === false) {
                if (in_array($arg, ['help', '--help', '-help'])) {
                    $this->help = true;
                }
                if ($this->stripDashes === true) {
                    $arg = ltrim($arg, '-');
                }
                if (!in_array($arg, $this->flags)) {
                    $this->flags[] = $arg;
                }
                continue;
            }
            $pieces = explode('=', $arg);
            if (count($pieces) === 2) {
                $this->addArg($pieces[0], $pieces[1]);
            }
        }
        return count($this->args);
    }

    /**
     * Remove the leading `--` or `-` from a string (in that order).
     *
     * @example $this->removeLeadingDash('--foo'); // returns 'foo'
     * @param string $val
     * @return string
     */
    protected function removeLeadingDash(string $val): string
    {
        if (str_starts_with($val, '--')) {
            return substr($val, 2);
        } elseif (str_starts_with($val, '-')) {
            return substr($val, 1);
        }

        return $val;
    }

    /**
     * Add a single argument to the stack.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     * @example $inst->addArg('foo', 'bar');
     */
    public function addArg(string $key, mixed $value): CliArgvs
    {
        if ($this->stripDashes === true) {
            $key = $this->removeLeadingDash($key);
        }
        $this->tmp = [];
        $this->args[] = [$key => $value];
        return $this;
    }

    /**
     * Get an array of arrays holding the argument.
     *
     * @return array
     * @example [
     *      ['name' => 'Joe'],
     *      ['age' => '24'],
     *      // etc...
     * ]
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * Get the name of the script.
     *
     * @return string|null
     * @example 'my-script.php'
     */
    public function getScript(): ?string
    {
        return $this->script;
    }

    /**
     * Has help been passed as a parameter?
     *
     * @return bool
     */
    public function hasHelp(): bool
    {
        return $this->help;
    }

    /**
     * @param string $flag
     * @return bool
     */
    public function hasFlag(string $flag): bool
    {
        if ($this->stripDashes === true) {
            $flag = ltrim($flag, '-');
        }
        return in_array($flag, $this->flags);
    }

    /**
     * Get an argument. If multiple matches are found, return an array of the
     * values, otherwise return the string of the value. If none were found,
     * just returns null.
     *
     * @param string $key
     * @param bool $uniqueValues (optional) whether to remove duplicate values
     * @return string|array|null
     */
    public function getArg(string $key, bool $uniqueValues = false): array|string|null
    {
        if ($this->stripDashes === true) {
            $key = $this->removeLeadingDash($key);
        }

        $cacheKey = $key . '|CliArgvs|1';
        if ($uniqueValues === true) {
            $cacheKey = $key . '|CliArgvs|0';
        }
        if (array_key_exists($cacheKey, $this->tmp)) {
            return $this->tmp[$cacheKey];
        }
        $matches = [];
        foreach ($this->getArgs() as $argument) {
            if (!array_key_exists($key, $argument)) {
                continue;
            }

            if ($uniqueValues === false || ($uniqueValues === true && !in_array($argument[$key], $matches))) {
                $matches[] = $argument[$key];
            }
        }
        if (count($matches) === 1) {
            $this->tmp[$cacheKey] = $matches[0];
            return $matches[0];
        } elseif (count($matches) > 1) {
            $this->tmp[$cacheKey] = $matches;
            return $matches;
        }
        $this->tmp[$cacheKey] = null;

        return null;
    }

    /**
     * Reset the instance with default values.
     *
     * @param array|null $args
     * @param int|null $num
     * @param bool $stripLeadingDashes (optional) default false.
     */
    public function clear(array $args = null, int $num = null, bool $stripLeadingDashes = true): void
    {
        $this->args = [];
        $this->tmp = [];
        $this->script = null;
        $this->help = false;
        $this->stripDashes = $stripLeadingDashes;
        if (is_array($args) && count($args) === $num) {
            $this->processArguments($args);
        }
    }

    /**
     * Retrieve an instance of this object, passing an array of argument, the
     * number of arguments, and whether `--` and `-` should be removed from
     * the front of the parameter keys.
     *
     * New instances of this object must contain the parameters, whereas when
     * retrieving an existing instance, these should be omitted entirely.
     *
     * @param array|null $args
     * @param int|null $num
     * @param bool $stripLeadingDashes (optional) default true.
     * @return CliArgvs
     * @noinspection PhpDocSignatureInspection
     */
    public static function getInstance(array $args = null, int $num = null, bool $stripLeadingDashes = true): ?CliArgvs
    {
        if (null === static::$_instance) {
            static::$_instance = new static($args, $num, $stripLeadingDashes);
        }
        if (null !== $args && null !== $num) {
            static::$_instance->clear($args, $num, $stripLeadingDashes);
        }
        return static::$_instance;
    }
}
