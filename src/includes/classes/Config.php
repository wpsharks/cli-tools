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
            throw new \Execption(
                'Env. variables `HOME` and `WEBSHARK_HOME` are both missing.'.
                ' Must have one or the other. Please edit your ~/.profile'
            );
        }
        $this->overload['file'] = $this->home.'/.websharks.json';

        if (!is_file($this->file)) {
            throw new \Execption(
                '`'.$this->file.'` is missing.'.
                ' Please see example: <http://bit.ly/1zt2n32>'
            );
        }
        if (!is_object($this->json = json_decode(file_get_contents($this->file)))) {
            throw new \Execption(
                '`'.$this->file.'` is corrupt.'.
                ' Please see example: <http://bit.ly/1zt2n32>'
            );
        }
        if (empty($this->json->config)) {
            throw new \Execption(
                '`'.$this->file.'` is missing a `config` object.'.
                ' Please see example: <http://bit.ly/1zt2n32>'
            );
        }
        if (empty($this->json->config->user->name)) {
            throw new \Execption(
                '`'.$this->file.'` is missing `config->user->name`.'.
                ' Please see example: <http://bit.ly/1zt2n32>'
            );
        }
        if (empty($this->json->config->user->projects_dir)) {
            throw new \Execption(
                '`'.$this->file.'` is missing `config->user->projects_dir`.'.
                ' Please see example: <http://bit.ly/1zt2n32>'
            );
        }
        if (empty($this->json->config->github->username)) {
            throw new \Execption(
                '`'.$this->file.'` is missing `config->github->username`.'.
                ' Please see example: <http://bit.ly/1zt2n32>'
            );
        }
        if (empty($this->json->config->github->api_key)) {
            throw new \Execption(
                '`'.$this->file.'` is missing `config->github->api_key`.'.
                ' Please see example: <http://bit.ly/1zt2n32>'
            );
        }
        if (empty($this->json->config->slack->username)) {
            throw new \Execption(
                '`'.$this->file.'` is missing `config->slack->username`.'.
                ' Please see example: <http://bit.ly/1zt2n32>'
            );
        }
        if (empty($this->json->config->slack->api_key)) {
            throw new \Execption(
                '`'.$this->file.'` is missing `config->slack->api_key`.'.
                ' Please see example: <http://bit.ly/1zt2n32>'
            );
        }
        if (empty($this->json->config->idonethis->username)) {
            throw new \Execption(
                '`'.$this->file.'` is missing `config->idonethis->username`.'.
                ' Please see example: <http://bit.ly/1zt2n32>'
            );
        }
        if (empty($this->json->config->idonethis->api_key)) {
            throw new \Execption(
                '`'.$this->file.'` is missing `config->idonethis->api_key`.'.
                ' Please see example: <http://bit.ly/1zt2n32>'
            );
        }
        $this->json->config->user->projects_dir = // Resolve HOME directory.
            str_replace('~', $this->home, $this->json->config->user->projects_dir);
        $this->json->config = $this->varTypify($this->json->config, 'string');

        foreach ($this->json->config as $_property => $_value) {
            $this->overload[$_property] = $_value;
        }
        unset($_property, $_value); // Housekeeping.
    }
}
