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
    $os_version = "Unknown OS";
    if (preg_match('/windows|win32/i', $user_agent)) {
        $os_version = "Windows";
    } elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
        $os_version = "Mac OS";
    } elseif (preg_match('/linux/i', $user_agent)) {
        $os_version = "Linux";
    } elseif (preg_match('/android/i', $user_agent)) {
        $os_version = "Android";
    } elseif (preg_match('/iphone|ipad/i', $user_agent)) {
        $os_version = "iOS";
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

    // Get Processor from Form Data
    $processor = $_POST['processor'] ?? "Unknown Processor";

    // Get Location using cURL
    $location = "Unknown";
    $api_url = "http://ip-api.com/json/{$ip_address}?fields=city,region,country";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $geo_data = curl_exec($ch);
    curl_close($ch);

    if ($geo_data) {
        $geo_json = json_decode($geo_data, true);
        if (isset($geo_json['city'], $geo_json['region'], $geo_json['country'])) {
            $location = "{$geo_json['city']}, {$geo_json['region']}, {$geo_json['country']}";
        }
    }

    return [$ip_address, $os_version, $browser, $processor, $location,$user_agent];
}
?>
