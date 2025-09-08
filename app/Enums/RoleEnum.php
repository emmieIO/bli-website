<?php

namespace App\Enums;

enum RoleEnum:string
{
    case Admin = "admin";
    case Student = "student";
    case Instructor = "instructor";

}
