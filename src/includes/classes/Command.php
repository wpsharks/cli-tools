<?php
namespace WebSharks\CliTools;

/**
 * Command.
 *
 * @since 15xxxx Initial release.
 */
class Command extends AbsBase
{
    /**
     * @type \stdClass Opts.
     */
    protected $opts;

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $short Short options.
     * @param array  $long  Long options.
     */
    public function __construct($short, array $long)
    {
        parent::__construct();

        $this->opts = $this->getOpts($short, $long);
    }

    /**
     * Get options.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $short Short options.
     * @param array  $long  Long options.
     *
     * @return \stdClass Options.
     */
    protected function getOpts($short, array $long)
    {
        $short = (string) $short;
        if (!is_array($opts = getopt($short, $long))) {
            $opts = array();
        }
        return (object) $opts;
    }

    /**
     * Get input line.
     *
     * @since 15xxxx Initial release.
     */
    protected function getLine()
    {
        return fgets(STDIN);
    }
}
