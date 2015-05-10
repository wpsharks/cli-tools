<?php
namespace WebSharks\CliTools;

use WebSharks\Core\CliTools\Traits;

/**
 * Command.
 *
 * @since 15xxxx Initial release.
 */
class Config extends AbsBase
{
    use Traits\OverloadMembers;
    use Traits\VarTypeUtils;

    /**
     * Config file data.
     *
     * @since 15xxxx Initial release.
     *
     * @type \stdClass Config. data.
     */
    protected $json;

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();

        if (!empty($_SERVER['HOME'])) {
            $this->overload['home'] = (string) $_SERVER['HOME'];
        } elseif (!empty($_SERVER['WEBSHARK_HOME'])) {
            $this->overload['home'] = (string) $_SERVER['WEBSHARK_HOME'];
        }
        if (empty($this->overload['home'])) {
            throw new \Exception(
                'Env. variables `HOME` and `WEBSHARK_HOME` are both missing.'.
                ' Must have one or the other. Please edit your ~/.profile'
            );
        }
        $this->overload['file'] = $this->home.'/.websharks.json';

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
        $this->json->config                     = $this->varTypify($this->json->config, 'string');
        $this->json->config->user->projects_dir = str_replace('~', $this->home, $this->json->config->user->projects_dir);

        foreach ($this->json->config as $_property => $_value) {
            $this->overload[$_property] = $_value;
        }
        unset($_property, $_value); // Housekeeping.
    }
}
