<?php
    //Successful responses
    define("OK", 200);
    define("NO_CONTENT", 204);

    //Client error responses
    define("BAD_REQUEST", 400);
    define("UNAUTHORISED", 401);
    define("PAYMENT_REQUIRED", 402);
    define("FORBIDDEN", 403);
    define("FILE_NOT_FOUND", 404);
    define("METHOD_NOT_ALLOWED", 405);
    define("NOT_ACCEPTABLE", 406);

    //Server error responses
    define("INTERNAL_SERVER_ERROR", 500);
    define("NOT_IMPLEMENTED", 501);

?>