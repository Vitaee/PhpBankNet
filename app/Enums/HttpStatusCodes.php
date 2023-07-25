<?php

namespace App\Enums;


final class HttpStatusCodes {
    const Ok = 200;
    const Created = 201;
    const Accepted = 202;
    const NoContent = 204;
    const BadRequest = 400;
    const NotAuthorized = 401;
    const RequestedAreaForbidden = 403;
    const NotFound = 404;
    const UnprocessableEnttiy = 422;
    const GeneralServeError  = 500;
}
