<?php
require "app/app.php";
use App\Core\Session;

Session::destroy();
header("location:/expense-tracker1/login");