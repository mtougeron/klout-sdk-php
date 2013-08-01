<?php

namespace Klout\Exception;

use Guzzle\Http\Exception\ClientErrorResponseException;

class ResourceNotFoundException extends ClientErrorResponseException implements KloutException {}
