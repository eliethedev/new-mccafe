<?php
/**
 * Temporarily disable email verification for testing
 * This will allow checkout without email verification
 */

echo "=== Disable Email Verification for Testing ===\n\n";

// Read the CheckoutController
$controllerFile = 'controllers/CheckoutController.php';
$backupFile = 'controllers/CheckoutController.php.backup';

// Create backup
if (!file_exists($backupFile)) {
    copy($controllerFile, $backupFile);
    echo "✅ Created backup: $backupFile\n";
} else {
    echo "ℹ️  Backup already exists\n";
}

// Read current content
$content = file_get_contents($controllerFile);

// Comment out email verification checks
$patterns = [
    '/(\s+\/\/ Check if user\'s email is verified[\s\S]+?return \$this->redirect\(\'\/dashboard\'\);)/',
    '/(\s+\/\/ Check if user\'s email is verified[\s\S]+?return \$this->redirect\(\'\/dashboard\'\);)/'
];

$replacements = [
    '/*$1*/',
    '/*$1*/'
];

// Apply changes
$newContent = preg_replace(
    '/(\s+)(\/\/ Check if user\'s email is verified[\s\S]+?)return \$this->redirect\(\'\/dashboard\'\);/',
    '$1/*$2return $this->redirect(\'/dashboard\');*/',
    $content
);

if ($newContent !== $content) {
    file_put_contents($controllerFile, $newContent);
    echo "✅ Email verification checks disabled\n";
    echo "ℹ️  Checkout will now work without email verification\n";
    echo "ℹ️  To restore: copy $backupFile to $controllerFile\n";
} else {
    echo "ℹ️  Email verification already disabled or not found\n";
}

echo "\n=== Instructions ===\n";
echo "1. Visit: http://localhost:8000/checkout\n";
echo "2. Login with: test@maccafe.com / password123\n";
echo "3. Add items to cart if needed\n";
echo "4. Proceed to checkout - should work now!\n";
echo "\nTo restore email verification:\n";
echo "copy controllers/CheckoutController.php.backup controllers/CheckoutController.php\n";

?>
