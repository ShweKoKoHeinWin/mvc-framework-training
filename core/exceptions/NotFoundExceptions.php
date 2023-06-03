<?php

namespace app\core\exceptions;

    class NotFoundExceptions extends \Exception
    {
        protected $code = '404';
        protected $message = "Page Not Found";
    }

?>