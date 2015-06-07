<?php
namespace WebSharks\CliTools;

use WebSharks\Core\CliTools\Classes as CoreClasses;

/**
 * CLI primary.
 *
 * @since 15xxxx Initial release.
 */
class Ws extends CoreClasses\AbsCliCmdBase
{
    /**
     * Version string.
     *
     * @since 15xxxx Initial release.
     *
     * @return string Version.
     */
    protected function version()
    {
        return VERSION;
    }

    /**
     * Initialize config. values.
     *
     * @since 15xxxx Initial release.
     */
    protected function initConfig()
    {
        $this->config = $this->Dicer->get(Config::class);
    }

    /**
     * Available sub-commands.
     *
     * @since 15xxxx Initial release.
     *
     * @return array Available sub-commands.
     */
    protected function subCommandAliases()
    {
        return [
            'todo' => 'done',
        ]; // All aliases.
    }

    /**
     * Available sub-commands.
     *
     * @since 15xxxx Initial release.
     *
     * @return array Available sub-commands.
     */
    protected function availableSubCommands()
    {
        return [
            'todo'    => 'iDoneThis entry submission.',
            'done'    => 'iDoneThis entry submission.',
            'sales'   => 'Retrieve revenue statistics.',
            'shorten' => 'URL shortener via wsharks.com.',
            'i2kba'   => 'GitHub issue to KB article converter.',
        ]; // All sub-commands.
    }
}
