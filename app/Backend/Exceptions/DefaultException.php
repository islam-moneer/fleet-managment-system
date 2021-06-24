<?php
/**
 * Created by PhpStorm.
 * User: abdelkader
 * Date: 1/24/19
 * Time: 5:00 AM
 */


namespace App\Backend\Exceptions;


use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class DefaultException extends Exception
{
    public function __construct(string $message = "error occurred", int $code = Response::HTTP_NOT_FOUND, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
