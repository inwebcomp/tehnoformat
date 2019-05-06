<?php

require __DIR__.'/../vendor/autoload.php';

include("kernel/lang.php");

include("kernel/database.php");

include("kernel/session.php");
include("kernel/settings.php");

include("kernel/utils.php");
include("kernel/checker.php");

include("kernel/model.php");
include("kernel/psr-autoloader.php");
include("kernel/staticDatabaseObject.php");
include("kernel/databaseObject.php");

include("kernel/view.php");
include("kernel/controller.php");
include("kernel/action.php");

include("kernel/application.php");

include("kernel/crud_controller.php");
include("kernel/crud_controller_tree.php");
include("kernel/parameters.php");