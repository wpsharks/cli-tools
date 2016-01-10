<?php
namespace WebSharks\CliTools;

use WebSharks\Core\CliTools\Classes as CoreClasses;

/**
 * Config object.
 *
 * @since 15xxxx Initial release.
 */
class Config extends CoreClasses\AbsBase
{
    protected $VarType;

    /**
     * @type string Home directory.
     *
     * @since 15xxxx Initial release.
     */
    protected $home;

    /**
     * @type string File path.
     *
     * @since 15xxxx Initial release.
     */
    protected $file;

    /**
     * @type \stdClass Config. data.
     *
     * @since 15xxxx Initial release.
     */
    protected $json;

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        CoreClasses\VarType $VarType
    ) {
        parent::__construct();

        $this->VarType = $VarType;

        if (!empty($_SERVER['HOME'])) {
            $this->home = (string) $_SERVER['HOME'];
        } elseif (!empty($_SERVER['WEBSHARK_HOME'])) {
            $this->home = (string) $_SERVER['WEBSHARK_HOME'];
        }
        if (empty($this->home)) {
            throw new \Exception(
                'Env. variables `HOME` and `WEBSHARK_HOME` are both missing.'.
                ' Must have one or the other. Please edit your ~/.profile'
            );
        }
        $this->file = $this->home.'/.ws.json';
        if (!is_file($this->file)) {
            $this->file = $this->home.'/.websharks.json';
        }
        if (!is_file($this->file)) {
            throw new \Exception(
                '`'.$this->file.'` is missing. See: <http://bit.ly/1zt2n32>'
            );
        }
        if (!is_object($this->json = json_decode(file_get_contents($this->file)))) {
            throw new \Exception(
                '`'.$this->file.'` is corrupt. See: <http://bit.ly/1zt2n32>'
            );
        }
        if (!empty($this->json->cli_tools)) {
            $this->json->config = $this->json->cli_tools;
        }
        if (empty($this->json->config) || !is_object($this->json->config)) {
            throw new \Exception(
                '`'.$this->file.'` is missing a `config` object. See: <http://bit.ly/1zt2n32>'
            );
        }
        if (empty($this->json->config->user->name)) {
            throw new \Exception(
                '`'.$this->file.'` is missing required value `config->user->name`. See: <http://bit.ly/1zt2n32>'
            );
        }
        if (empty($this->json->config->user->projects_dir)) {
            throw new \Exception(
                '`'.$this->file.'` is missing required value `config->user->projects_dir`. See: <http://bit.ly/1zt2n32>'
            );
        }
        $this->json->config                     = $this->VarType->ify($this->json->config, 'string');
        $this->json->config->user->projects_dir = str_replace('~', $this->home, $this->json->config->user->projects_dir);

        $this->overload(['home', 'file']); // Setup overloaded properties.

        foreach ($this->json->config as $_property => &$_value) {
            $this->overload->{$_property} = &$_value;
        }
        unset($_property, $_value); // Housekeeping.
    }
}
