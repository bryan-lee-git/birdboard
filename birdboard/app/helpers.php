<?php

function gravatarUrl($email)
{
    $email = md5($email);
    return "https://gravatar.com/avatar/$email?s=60";
}
