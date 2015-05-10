<?php
namespace WebSharks\CliTools;

use WebSharks\Core\CliTools\Traits;

/**
 * Command base.
 *
 * @since 15xxxx Initial release.
 */
abstract class CmdBase extends AbsBase
{
    use Traits\CliColorUtils;
    use Traits\CliOptUtils;
    use Traits\CliStreamUtils;

    /**
     * @type Config Opts.
     */
    protected $config;

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
    public function __construct($short, array $long = array())
    {
        parent::__construct();

        $this->config = new Config();
        $this->opts   = $this->cliOptsGet($short, $long);
    }
}
