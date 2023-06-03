<?php

namespace app\core\exceptions;

    class ForbiddenExceptions extends \Exception
    {
        protected $message = 'You don\'t have permission to access this page';
        protected $code = '403';
    }

?>