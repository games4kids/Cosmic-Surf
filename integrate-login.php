<?php
/**
 * AUTO-INTEGRATION SCRIPT
 * 
 * This script automatically integrates the login system into index.html
 * 
 * Usage: php integrate-login.php
 */

$indexFile = __DIR__ . '/index.html';
$backupFile = __DIR__ . '/index.backup.html';

if (!file_exists($indexFile)) {
    die("Error: index.html not found!\n");
}

// Backup original file
copy($indexFile, $backupFile);
echo "✓ Backup created: index.backup.html\n";

// Read the file
$content = file_get_contents($indexFile);

// 1. Add CSS after line 384 (before </style>)
$cssToAdd = file_get_contents(__DIR__ . '/css-addition.txt');
$content = str_replace('</style>', $cssToAdd . "\n</style>", $content);
echo "✓ CSS styles added\n";

// 2. Add HTML after ui-overlay opening
$htmlToAdd = file_get_contents(__DIR__ . '/html-addition.txt');
$content = str_replace('<div class="ui-overlay">', '<div class="ui-overlay">' . "\n" . $htmlToAdd, $content);
echo "✓ HTML elements added\n";

// 3. Add JavaScript before </script>
$jsToAdd = file_get_contents(__DIR__ . '/js-addition.txt');
$content = str_replace('</script>', $jsToAdd . "\n</script>", $content);
echo "✓ JavaScript code added\n";

// 4. Modify endGame() to save score
$content = preg_replace(
    '/(function endGame\(\) \{[^}]+\})/s',
    "$1\n      saveScore(score);",
    $content
);
echo "✓ endGame() modified to save score\n";

// Save the modified file
file_put_contents($indexFile, $content);
echo "✓ Integration complete!\n\n";
echo "Original file backed up to: index.backup.html\n";
echo "Modified file saved to: index.html\n";
?>