<?php
function getUserDetails() {
    $ip_address = getUserIP();
    $os_version = getOS();
    $browser = getBrowser();
    $processor = getProcessor();
    $location = getLocationFromIP($ip_address);
    return [$ip_address, $os_version, $browser, $processor, $location];
}

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($ip === "::1" || $ip === "127.0.0.1") {
            $external_ip = @file_get_contents("https://api64.ipify.org?format=json");
            if ($external_ip) {
                $external_ip = json_decode($external_ip, true);
                return $external_ip['ip'] ?? "Unknown IP";
            }
        }
        return $ip;
    }
    return "Unknown IP";
}

function getOS() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/windows/i', $user_agent)) {
        return "Windows";
    } elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
        return "MacOS";
    } elseif (preg_match('/linux/i', $user_agent)) {
        return "Linux";
    } elseif (preg_match('/ubuntu/i', $user_agent)) {
        return "Ubuntu";
    } elseif (preg_match('/iphone/i', $user_agent)) {
        return "iOS";
    } elseif (preg_match('/android/i', $user_agent)) {
        return "Android";
    }
    return "Unknown OS";
}

function getBrowser() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = "Unknown Browser";
    $browsers = [
        '/brave/i'   => 'Brave Browser',
        '/edg/i'     => 'Microsoft Edge',
        '/chrome/i'  => 'Google Chrome',
        '/firefox/i' => 'Mozilla Firefox',
        '/safari/i'  => 'Safari',
        '/opr/i'     => 'Opera'
    ];
    foreach ($browsers as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser = $value;
            break;
        }
    }
    return $browser;
}

function getProcessor() {
    if (PHP_OS_FAMILY === 'Windows') {
        $cpu = shell_exec("wmic cpu get Name");
        if (!empty($cpu)) {
            $cpu = explode("\n", trim($cpu));
            return trim($cpu[1]);
        }
    } else {
        $cpu = shell_exec("lscpu | grep 'Model name'");
        if (!empty($cpu)) {
            return trim(str_replace("Model name:", "", $cpu));
        }
    }
    return 'Unknown Processor';
}

function getLocationFromIP($ip) {
    if ($ip === "127.0.0.1" || $ip === "::1") {
        return "Localhost";
    }
    $url = "http://ip-api.com/json/" . $ip;
    $json = @file_get_contents($url);
    if ($json) {
        $data = json_decode($json, true);
        if (!empty($data['city']) && !empty($data['country'])) {
            return $data['city'] . ", " . $data['country'];
        } else {
            return "Location not found (API Limit?)";
        }
    }
    return "Unknown Location";
}
?>