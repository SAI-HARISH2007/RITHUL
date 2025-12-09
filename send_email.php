<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['type'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$type = $input['type'];
$timestamp = date('Y-m-d H:i:s');
$user_ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

$to = 'saiharishanand2007@gmail.com';
$from = 'noreply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost');

if ($type === 'visit') {
    $subject = '🔔 Chinni opened your site!';
    $message = "
🎉 SITE VISIT ALERT! 🎉

Chinni just opened your special \"For Chinni\" website!

🕐 EXACT TIME SHE OPENED IT:
- Date: {$input['visitDate']}
- Time: {$input['visitTime']}
- Full DateTime: {$input['fullDateTime']}
- Timezone: {$input['timezone']}
- Server Time: {$timestamp}

📱 DEVICE INFO:
- Screen Size: {$input['screenWidth']}x{$input['screenHeight']}
- Device Screen: {$input['deviceWidth']}x{$input['deviceHeight']}
- IP Address: {$user_ip}

📊 BROWSER INFO:
- " . substr($input['userAgent'], 0, 200) . "

Hope it's your Chinni! 💕

---
Automated notification from your \"For Chinni\" site
    ";

} else if ($type === 'meeting') {
    $subject = '💝 AMAZING! Chinni wants to meet you!';
    $message = "
🎉 CONGRATULATIONS! 🎉

Chinni has accepted your meeting invitation!

📍 MEETING DETAILS:
- Place: {$input['place']}
- When: {$input['when']}
- Note: " . ($input['note'] ?: 'No additional note') . "

📊 INTERACTION DATA:
- Total Clicks: {$input['clicks']}
- Total Hovers: {$input['hovers']}
- Time Spent: {$input['timeSpent']} seconds
- Scratch Card Progress: {$input['scratchProgress']}%
- Screenshot Attempts: {$input['screenshotAttempts']}

🌐 DEVICE INFO:
- Screen Size: {$input['screenSize']}
- IP Address: {$user_ip}
- Response Time: {$timestamp}

💗 She said YES to meeting you! Don't miss this! 💗

---
Response from your \"For Chinni\" site
    ";

} else if ($type === 'screenshot') {
    $subject = '📸 Screenshot Alert!';
    $message = "
⚠️ SCREENSHOT DETECTED! ⚠️

Someone attempted to take a screenshot of your site!

🕐 TIME:
- {$input['timestamp']}

📊 DETAILS:
- Total Screenshot Attempts: {$input['screenshotCount']}

This could mean Chinni is sharing it with friends or saving it! 💕

---
Alert from your \"For Chinni\" site
    ";

} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid type']);
    exit;
}

$headers = [
    'From: ' . $from,
    'Reply-To: ' . $from,
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
    'X-Mailer: PHP/' . phpversion()
];

$success = mail($to, $subject, $message, implode("\r\n", $headers));

// Log to file with detailed information
$log_entry = "[$timestamp] Type: $type | IP: $user_ip";
if ($type === 'visit') {
    $log_entry .= " | Visit Time: {$input['visitTime']}";
} else if ($type === 'meeting') {
    $log_entry .= " | Place: {$input['place']} | Clicks: {$input['clicks']}";
} else if ($type === 'screenshot') {
    $log_entry .= " | Screenshot Count: {$input['screenshotCount']}";
}
$log_entry .= " | Success: " . ($success ? 'YES' : 'NO') . "\n";

file_put_contents('chinni_activity.log', $log_entry, FILE_APPEND | LOCK_EX);

echo json_encode([
    'success' => $success, 
    'message' => $success ? 'Email sent successfully!' : 'Failed to send email',
    'timestamp' => $timestamp
]);

?>
```

---

## 📁 **Complete Project Structure:**
```
your-project/
├── index.html          ✅ (Complete - ready to use!)
├── send_email.php      ✅ (Complete - save the code above)
└── chinni_activity.log (Auto-created when site is used)