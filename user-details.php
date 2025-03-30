<?php

function getUserDetails() {
    // Get Real IP Address
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip_address = trim($ip_list[0]); // Get the first valid IP
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }

    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    // Get OS Version
  // Get OS Version
$os_version = "Unknown OS";

// Define an array mapping user agent patterns to OS versions
$os_patterns = [
    '/Windows NT 10.0;.*\b(22|23|24)[0-9]{3}\b/i' => 'Windows 11', // Detect Windows 11 builds
    '/Windows NT 10.0/i' => function ($matches) {
        // Windows NT 10.0 can be either Windows 10 or 11
        return detectWindowsVersion();
    },
    '/Windows NT 6.3/i' => 'Windows 8.1',
    '/Windows NT 6.2/i' => 'Windows 8',
    '/Windows NT 6.1/i' => 'Windows 7',
    '/Mac OS X ([\d_]+)/i' => function ($matches) {
        return 'Mac OS X ' . str_replace('_', '.', $matches[1]);
    },
    '/Linux/i' => 'Linux',
    '/Android ([\d.]+)/i' => function ($matches) {
        return 'Android ' . $matches[1];
    },
];

function detectWindowsVersion() {
    // Check if it's Windows 11 based on modern browser features
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Edg/') !== false || 
        strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome/') !== false) {
        return 'Windows 11'; // More likely to be Windows 11
    }
    return 'Windows 10'; // Default to Windows 10 if unsure
}

// Iterate through the patterns to find a match
$user_agent = $_SERVER['HTTP_USER_AGENT']; // Example: Get user agent
foreach ($os_patterns as $pattern => $result) {
    if (preg_match($pattern, $user_agent, $matches)) {
        $os_version = is_callable($result) ? $result($matches) : $result;
        break;
    }
}       

    // Improved Browser Detection
    $browsers = [
        'Opera GX' => '/OPR\/.*GX/i',
        'Edge' => '/Edg/i',
        'Brave' => '/Chrome.*Brave/i',
        'Vivaldi' => '/Vivaldi/i',
        'Chrome' => '/Chrome/i',
        'Firefox' => '/Firefox/i',
        'Safari' => '/Safari/i',
        'Opera' => '/Opera|OPR/i',
        'Samsung Internet' => '/SamsungBrowser/i',
        'Internet Explorer' => '/MSIE|Trident/i'
    ];

    $browser = "Unknown Browser";
    foreach ($browsers as $name => $regex) {
        if (preg_match($regex, $user_agent)) {
            $browser = $name;
            break;
        }
    }

    // Special case: Avoid detecting Chrome when it's actually Edge, Brave, or Opera GX
    if ($browser === 'Chrome' && preg_match('/Edg/i', $user_agent)) {
        $browser = 'Edge';
    }
    if ($browser === 'Chrome' && preg_match('/Brave/i', $user_agent)) {
        $browser = 'Brave';
    }
    if ($browser === 'Chrome' && preg_match('/OPR/i', $user_agent)) {
        $browser = 'Opera'; // Fallback if Opera GX wasn't detected first
    }


   // Get Processor Information
    $processor = "Unknown Processor";

    // Parse the User-Agent string for processor-related keywords
    if (preg_match('/x86_64|Win64|WOW64|amd64/i', $user_agent)) {
        $processor = "x86_64 (64-bit)";
    } elseif (preg_match('/i386|i686|x86/i', $user_agent)) {
        $processor = "x86 (32-bit)";
    } elseif (preg_match('/arm|aarch64/i', $user_agent)) {
        $processor = "ARM/ARM64 (Mobile/Embedded)";
    } elseif (preg_match('/PPC|PowerPC/i', $user_agent)) {
        $processor = "PowerPC (RISC)";
    } elseif (preg_match('/sparc/i', $user_agent)) {
        $processor = "SPARC (Enterprise)";
    } elseif (preg_match('/mips/i', $user_agent)) {
        $processor = "MIPS (Embedded Systems)";
    }

// Fallback if no match is found
if ($processor === "Unknown Processor") {
    if (stripos($user_agent, 'Macintosh') !== false) {
        $processor = "Apple Silicon (M1/M2) or Intel";
    } elseif (stripos($user_agent, 'CrOS') !== false) {
        $processor = "Chrome OS (ARM or x86_64)";
    }
}
    // Get Location using cURL
    $location = "Unknown";
    $api_url = "http://ip-api.com/json/{$ip_address}?fields=city,regionName,country,zip,lat,lon";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $geo_data = curl_exec($ch);
    curl_close($ch);

    if ($geo_data) {
        $geo_json = json_decode($geo_data, true);
        if (isset($geo_json['city'], $geo_json['regionName'], $geo_json['country'], $geo_json['zip'], $geo_json['lat'], $geo_json['lon'])) {
            $location = "{$geo_json['city']}, {$geo_json['regionName']}, {$geo_json['country']} (ZIP: {$geo_json['zip']}, Lat: {$geo_json['lat']}, Lon: {$geo_json['lon']})";
        }
    }

    return [$ip_address, $os_version, $browser, $processor, $location, $user_agent];
}
?>
