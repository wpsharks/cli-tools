<?php
namespace WebSharks\CliTools;

use WebSharks\Core\CliTools\Traits;

/**
 * Delegation handler.
 *
 * @since 15xxxx Initial release.
 */
class Ws extends AbsBase
{
    use Traits\CliColorUtils;
    use Traits\CliExceptionUtils;
    use Traits\CliStreamUtils;
    use Traits\TrimUtils;

    /**
     * @type \stdClass Command.
     *
     * @since 15xxxx Initial release.
     */
    public $command;

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();

        $this->cliExceptionsHandle();

        $this->command = (object) [
            'slug'       => '',
            'class'      => '',
            'class_path' => '',
        ];
        if (!empty($GLOBALS['argv'][1])) {
            $this->command->slug       = (string) $GLOBALS['argv'][1];
            $this->command->slug       = strtolower($this->command->slug);
            $this->command->slug       = preg_replace('/[^a-z0-9]+/', '-', $this->command->slug);
            $this->command->slug       = $this->trim($this->command->slug, '', '-');
            $this->command->class      = $this->commandClass($this->command->slug);
            $this->command->class_path = $this->commandClassPath($this->command->slug);
        }
        if (class_exists($command = $this->command->class_path)) {
            new $command(); // Delegate.
        } else {
            throw new \Exception('Unknown command: `'.$this->command->slug.'`');
            exit(1); // Error exit status.
        }
    }

    /**
     * Class slug from path.
     *
     * @since 15xxxx Initial release.
     */
    public function commandSlug($class)
    {
        $class = (string) $class;
        $class = basename(str_replace('\\', '/', $class));

        $slug  = preg_replace('/([A-Z])/', '-${1}', $class);
        $slug  = $this->trim(strtolower($slug), '', '-');
        $slug  = preg_replace('/[^a-z0-9]+/', '-', $slug);

        return $slug;
    }

    /**
     * Class path from slug.
     *
     * @since 15xxxx Initial release.
     */
    public function commandClass($slug)
    {
        $slug  = (string) $slug;
        $parts = preg_split('/\-/', $slug, null, PREG_SPLIT_NO_EMPTY);
        $parts = array_map('ucfirst', array_map('strtolower', $parts));
        $class = implode('', $parts); // e.g., CommandClass

        return $class;
    }

    /**
     * Class path from slug.
     *
     * @since 15xxxx Initial release.
     */
    public function commandClassPath($slug)
    {
        $slug  = (string) $slug;
        $class = $this->commandClass($slug);

        return $class ? __NAMESPACE__.'\\Commands\\'.$class : '';
    }
}
