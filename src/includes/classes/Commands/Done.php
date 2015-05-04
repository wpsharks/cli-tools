<?php
namespace WebSharks\CliTools\Commands;

/**
 * Done command.
 *
 * @since 15xxxx Initial release.
 */
class Done extends \WebSharks\CliTools\Command
{
    public function __construct()
    {
        parent::__construct(
            'm:',
            [
                'message:',
            ]
        );
        var_dump($this->opts);
    }

    protected function _entry($entry, $type = 'done')
    {
        if (!($entry = trim((string) $entry))) {
            return ''; // Not possible.
        }
        $type = $type === 'todo' ? 'todo' : 'done';

        $entry = $type === 'todo' ? '[ ] '.$entry : $entry;
        if ($type === 'todo' && stripos($entry, '#'.$this->config->username) === false) {
            $entry .= ' #'.$this->config->username;
        } // Force user tag.

        $headers   = array(
            'Authorization: Token '.$this->config->idonethis_api_key,
            'Content-Type: application/json',
            'Accept: application/json',
        );
        $post_vars = array(
            'raw_text'  => $entry,
            'team'      => $this->config->idonethis_api_team,
            'meta_data' => json_encode(array(
                                           'via' => str_replace('_', '-', __NAMESPACE__),
                                       )),
        );
        $endpoint  = $this->config->idonethis_api_endpoint.'/dones/';

        if (!is_object($http_response = json_decode($this->curl('POST::'.$endpoint, json_encode($post_vars), compact('headers'))))
           || empty($http_response->ok) || $http_response->ok !== true || empty($http_response->result->permalink)
        ) {
            throw new \exception('Unable to create '.strtoupper($type).' item. Got: '.print_r($http_response, true));
        }

        return $http_response->result->permalink;
    }
}
