<?php

namespace App\Response;

/**
 * Interface ResponseData
 * @package App\Response
 */
interface ResponseData
{
    /**
     * generating JSON API array for response
     *
     * @return array
     */
    public function getResponseArray(): array;
}
