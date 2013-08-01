<?php

namespace Klout\Exception;

use Guzzle\Http\Exception\ClientErrorResponseException;

class NotAuthorizedException extends ClientErrorResponseException implements KloutException {}
