<?php
include('debug.php');
?>
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <div>Test page for debug class. Open console to see it in action.</div>
        <?php 
            debug::consolelog('log', ['a' => 1, 'b' => 2, 'c' => [31, 32, 33]]); 
            debug::consoleerror('error', ['a' => 1, 'b' => 2, 'c' => [31, 32, 33]]);
            debug::consolewarn('warn', ['a' => 1, 'b' => 2, 'c' => [31, 32, 33]]);
            debug::info('info', ['a' => 1, 'b' => 2, 'c' => [31, 32, 33]]);
            debug::info('info debug', ['a' => 1, 'b' => 2, 'c' => [31, 32, 33]], TRUE);
            debug::alert(['a' => 1, 'b' => 2, 'c' => [31, 32, 33]]);
            debug::dump(['a' => 1, 'b' => 2, 'c' => [31, 32, 33]]);
            debug::dump(['a' => 1, 'b' => 2, 'c' => [31, 32, 33]], TRUE);
            debug::display('test', ['a' => 1, 'b' => 2, 'c' => [31, 32, 33]]);
            debug::display('test', ['a' => 1, 'b' => 2, 'c' => [31, 32, 33]], TRUE);
            debug::log('test', ['a' => 1, 'b' => 2, 'c' => [31, 32, 33]], 'TEST');
        ?>
    </body>
</html>
