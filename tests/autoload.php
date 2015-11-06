<?php
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'jimtonic\\redislockhandler\\mylockable' => '/_files/MyLockable.php',
                'jimtonic\\redislockhandler\\myblamedlockable' => '/_files/MyBlamedLockable.php',
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
);