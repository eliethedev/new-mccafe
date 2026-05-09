<?php

echo "<h2>PHP Extensions Check</h2>";

// Check required extensions
$required_extensions = [
    'openssl' => 'Required for SMTP SSL/TLS',
    'mbstring' => 'Required for PHPMailer',
    'sockets' => 'Required for SMTP connections'
];

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Extension</th><th>Status</th><th>Purpose</th></tr>";

$all_good = true;
foreach ($required_extensions as $ext => $purpose) {
    $loaded = extension_loaded($ext);
    $status = $loaded ? "<span style='color: green;'>✅ Loaded</span>" : "<span style='color: red;'>❌ Missing</span>";
    echo "<tr><td>$ext</td><td>$status</td><td>$purpose</td></tr>";
    if (!$loaded) {
        $all_good = false;
    }
}

echo "</table>";

if ($all_good) {
    echo "<h3 style='color: green;'>✅ All required extensions are loaded!</h3>";
} else {
    echo "<h3 style='color: red;'>❌ Some required extensions are missing!</h3>";
    echo "<p>Please enable the missing extensions in your php.ini file and restart Apache.</p>";
}

// Check PHP version
echo "<h2>PHP Version</h2>";
echo "<p>Current PHP Version: " . PHP_VERSION . "</p>";
if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
    echo "<p style='color: green;'>✅ PHP version is compatible with PHPMailer</p>";
} else {
    echo "<p style='color: red;'>❌ PHP version is too old. PHPMailer requires PHP 7.0 or higher</p>";
}

// Check if we can make network connections
echo "<h2>Network Connection Test</h2>";
$host = 'smtp.gmail.com';
$port = 587;
$timeout = 5;

$socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
if ($socket) {
    echo "<p style='color: green;'>✅ Successfully connected to $host:$port</p>";
    fclose($socket);
} else {
    echo "<p style='color: red;'>❌ Cannot connect to $host:$port</p>";
    echo "<p>Error: $errstr ($errno)</p>";
    echo "<p>This could indicate a firewall or network issue.</p>";
}
?>
