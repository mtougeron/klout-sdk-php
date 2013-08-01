<?php

namespace Klout\Exception;

use Guzzle\Http\Exception\ServerErrorResponseException;

class ServiceUnavailableException extends ServerErrorResponseException implements KloutException {}
