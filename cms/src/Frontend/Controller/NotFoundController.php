<?php

class NotFoundController
{
    public function error404()
    {

        http_response_code(404);

        echo "This is page is 404 page";
    }
}
