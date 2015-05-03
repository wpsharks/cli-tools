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
     */
    public function __construct()
    {
        parent::__construct();

        $this->opts = new \stdClass();
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
