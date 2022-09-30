<?php
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;

class NamelessOAuthIdentityProviderException extends IdentityProviderException {
    /**
     * Creates client exception from response
     *
     * @param  ResponseInterface $response
     * @param  array $data Parsed response data
     *
     * @return IdentityProviderException
     */
    public static function clientException(ResponseInterface $response, $data) {
        return static::fromResponse(
            $response,
            isset($data['message']) ? $data['message'] : json_encode($data)
        );
    }

    /**
     * Creates identity exception from response
     *
     * @param  ResponseInterface $response
     * @param  string $message
     *
     * @return IdentityProviderException
     */
    protected static function fromResponse(ResponseInterface $response, $message = null) {
        return new static($message, $response->getStatusCode(), (string) $response->getBody());
    }
}
