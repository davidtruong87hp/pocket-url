<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Shortener;

/**
 */
class ShortenerServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Shortener\ResolveShortcodeRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function ResolveShortcode(\Shortener\ResolveShortcodeRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/shortener.ShortenerService/ResolveShortcode',
        $argument,
        ['\Shortener\ResolveShortcodeResponse', 'decode'],
        $metadata, $options);
    }

}
