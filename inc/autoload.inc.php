<?php
/**
 * An example of a project-specific implementation.
 *
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \Foo\Bar\Baz\Qux class
 * from /path/to/project/src/Baz/Qux.php:
 *
 *      new \Foo\Bar\Baz\Qux;
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
// spl_autoload_register(function ($class) {

//     // project-specific namespace prefix
//     $prefix = 'App\\';

//     // base directory for the namespace prefix
//     $base_dir = __DIR__ . '/../src/';

//     // does the class use the namespace prefix?
//     $len = strlen($prefix);
//     if (strncmp($prefix, $class, $len) !== 0) {
//         // no, move to the next registered autoloader
//         return;
//     }

//     // get the relative class name
//     $relative_class = substr($class, $len);

//     // replace the namespace prefix with the base directory, replace namespace
//     // separators with directory separators in the relative class name, append
//     // with .php
//     $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

//     // if the file exists, require it
//     if (file_exists($file)) {
//         require $file;
//     }
// });



spl_autoload_register(function ($class) {

    // 1. Project-specific namespace prefix
    $prefix = 'App\\';

    // 2. Base directory for the namespace prefix
    // Ensure ROOT_PATH is used if defined, otherwise fallback to relative
    $base_dir = defined('ROOT_PATH') ? ROOT_PATH . '/src/' : __DIR__ . '/../src/';

    // 3. Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // 4. Get the relative class name
    $relative_class = substr($class, $len);

    // 5. Replace backslashes with forward slashes for Linux compatibility
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // 6. If the file exists, require it
    if (file_exists($file)) {
        require $file;
    } else {
        // Optional: Debugging line (remove once fixed)
        // echo "Autoloader failed to find: " . $file . "<br>";
    }
});
