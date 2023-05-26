<?php

namespace Knapsack\Compass\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Knapsack\Compass\Exceptions\ValidationException;
use Knapsack\Compass\Support\Traits\ForwardsCalls;

/**
 * @method all()
 * @method bool has($key)
 *
 */
class Request
{
    use ForwardsCalls;

    private $params;
    private $headers;

    public function __construct()
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $this->params = Collection::make($_POST)
            ->merge($_GET)
            ->merge($body);

        $this->headers = Collection::make($this->getHeaders());
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->params, $method, $parameters);
    }

    public function input(string $key, $default = null)
    {
        return $this->params->get($key, $default);
    }

    public function validate($rules, $messages = [])
    {
        $validator = vgb_validator();
        $validation = $validator->validate($this->all(), $rules, $messages);

        if ($validation->fails()) {
            throw new ValidationException($validation->errors()->all());
        }

        return $validation->getValidatedData();
    }

    public function expectsJson()
    {
        $isAjax = $this->headers->get('X-REQUESTED-WITH') === 'XMLHttpRequest';
        $wantsJson = Str::contains($this->headers->get('CONTENT_TYPE'), 'json');
        return $isAjax || $wantsJson;
    }

    private function getHeaders(): array
    {
        $headers = [];
        $server = $_SERVER;

        foreach ($server as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $headers[substr($key, 5)] = $value;
            } elseif (\in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5', 'X-REQUESTED-WITH'], true)) {
                $headers[$key] = $value;
            }
        }

        if (isset($server['PHP_AUTH_USER'])) {
            $headers['PHP_AUTH_USER'] = $server['PHP_AUTH_USER'];
            $headers['PHP_AUTH_PW'] = $server['PHP_AUTH_PW'] ?? '';
        } else {
            /*
             * php-cgi under Apache does not pass HTTP Basic user/pass to PHP by default
             * For this workaround to work, add these lines to your .htaccess file:
             * RewriteCond %{HTTP:Authorization} .+
             * RewriteRule ^ - [E=HTTP_AUTHORIZATION:%0]
             *
             * A sample .htaccess file:
             * RewriteEngine On
             * RewriteCond %{HTTP:Authorization} .+
             * RewriteRule ^ - [E=HTTP_AUTHORIZATION:%0]
             * RewriteCond %{REQUEST_FILENAME} !-f
             * RewriteRule ^(.*)$ app.php [QSA,L]
             */

            $authorizationHeader = null;
            if (isset($server['HTTP_AUTHORIZATION'])) {
                $authorizationHeader = $server['HTTP_AUTHORIZATION'];
            } elseif (isset($server['REDIRECT_HTTP_AUTHORIZATION'])) {
                $authorizationHeader = $server['REDIRECT_HTTP_AUTHORIZATION'];
            }

            if (null !== $authorizationHeader) {
                if (0 === stripos($authorizationHeader, 'basic ')) {
                    // Decode AUTHORIZATION header into PHP_AUTH_USER and PHP_AUTH_PW when authorization header is basic
                    $exploded = explode(':', base64_decode(substr($authorizationHeader, 6)), 2);
                    if (2 == \count($exploded)) {
                        [$headers['PHP_AUTH_USER'], $headers['PHP_AUTH_PW']] = $exploded;
                    }
                } elseif (empty($server['PHP_AUTH_DIGEST']) && (0 === stripos($authorizationHeader, 'digest '))) {
                    // In some circumstances PHP_AUTH_DIGEST needs to be set
                    $headers['PHP_AUTH_DIGEST'] = $authorizationHeader;
                    $server['PHP_AUTH_DIGEST'] = $authorizationHeader;
                } elseif (0 === stripos($authorizationHeader, 'bearer ')) {
                    /*
                     * XXX: Since there is no PHP_AUTH_BEARER in PHP predefined variables,
                     *      I'll just set $headers['AUTHORIZATION'] here.
                     *      https://php.net/reserved.variables.server
                     */
                    $headers['AUTHORIZATION'] = $authorizationHeader;
                }
            }
        }

        if (isset($headers['AUTHORIZATION'])) {
            return $headers;
        }

        // PHP_AUTH_USER/PHP_AUTH_PW
        if (isset($headers['PHP_AUTH_USER'])) {
            $headers['AUTHORIZATION'] = 'Basic '.base64_encode($headers['PHP_AUTH_USER'].':'.($headers['PHP_AUTH_PW'] ?? ''));
        } elseif (isset($headers['PHP_AUTH_DIGEST'])) {
            $headers['AUTHORIZATION'] = $headers['PHP_AUTH_DIGEST'];
        }

        return $headers;
    }
}
