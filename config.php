<?php
/**
 * Global Configuration and Environment Parser
 * "Another Tangent with Toby" - Nerve Radio
 */

// Custom .env Parser Function
function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        return false;
    }
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value, " \t\n\r\0\x0B\"'");
    }
    return true;
}
loadEnv(__DIR__ . '/.env');

// Core Variables
$stationName = $_ENV['STATION_NAME'] ?? "Nerve Radio";
$showName = $_ENV['SHOW_NAME'] ?? "Another Tangent with Toby";
$showSlogan = $_ENV['SHOW_SLOGAN'] ?? "No script. No plan. Just another detour.";
$hostName = $_ENV['HOST_NAME'] ?? "Toby";
$broadcastTime = $_ENV['BROADCAST_TIME'] ?? "Wednesdays at 7:00 PM";
$studioLoc = $_ENV['STUDIO_LOCATION'] ?? "Weymouth House, Talbot Campus";

$streamUrl = $_ENV['STREAM_URL'] ?? "#";
$stationUrl = $_ENV['STATION_URL'] ?? '#';

$studioPhone = $_ENV['STUDIO_PHONE'] ?? '<no phone number yet>';
$emailAddr = $_ENV['SUBMISSION_EMAIL'] ?? '<no email yet>';

// Social Channels
$instagramLink = $_ENV['SOCIAL_INSTAGRAM'] ?? "https://instagram.com/nerveradio";
$tiktokLink = $_ENV['SOCIAL_TIKTOK'] ?? "https://tiktok.com/@nerveradio";
$twitterLink = $_ENV['SOCIAL_TWITTER'] ?? "https://twitter.com/nerveradio";
$youtubeLink = $_ENV['SOCIAL_YOUTUBE'] ?? "https://youtube.com/nerveradio";

/**
 * Dynamic Dynamic Catch Up Locator
 * Scans the /episodes directory for .url shortcuts and loads the latest one
 */
$catchUpUrl = $_ENV['SOCIAL_YOUTUBE'] ?? "https://mixcloud.com/nerveradio"; // Fallback
$episodesDir = __DIR__ . '/episodes';

if (is_dir($episodesDir)) {
    // Scan directory for files ending in .url
    $files = glob($episodesDir . '/*.url');

    if (!empty($files)) {
        sort($files);
        $latestEpisodeFile = end($files);

        // Parse the .url INI file format
        $iniData = parse_ini_file($latestEpisodeFile, true, INI_SCANNER_RAW);
        if (isset($iniData['InternetShortcut']['URL'])) {
            $catchUpUrl = $iniData['InternetShortcut']['URL'];
        }
    }
}

// Form Submission handling
$successMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senderName = filter_input(INPUT_POST, 'sender_name', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($senderName) {
        $successMessage = "Awesome, " . htmlspecialchars($senderName) . "! Your data reached Toby's dashboard screen!";
    }
}


// Complete 15-Segment Database from the Production Bible
$segments = [
    [
        "num" => "1",
        "title" => "The Campus Theme Song",
        "desc" => "Chaining music tastes across the student body. We ask a student to rate the previous song, and get their favourite track to continue the loop."
    ],
    [
        "num" => "2",
        "title" => "The Bus Stop Diaries",
        "desc" => "Quick-fire chats with students waiting in the queue at Talbot Campus for the bus. Raw, funny, and unedited morning thoughts."
    ],
    [
        "num" => "3",
        "title" => "Lost in the Triangle",
        "desc" => "Exploring local independent spots, vintage stores, and quirky cafes across the local area."
    ],
    [
        "num" => "4",
        "title" => "In Another World",
        "desc" => "The ultimate panel game solving completely useless, hilarious hypothetical arguments."
    ],
    [
        "num" => "5",
        "title" => "Mr. vs. Mrs. of the Flat",
        "desc" => "Testing flatmates' knowledge of each other live on air. Correct answers win minor, funny dorm treats!"
    ],
    [
        "num" => "6",
        "title" => "Society Showdown",
        "desc" => "Pitting two wildly different university groups against each other in a fast-paced 60-second debate on trivial topics."
    ],
    [
        "num" => "7",
        "title" => "The Headline Game",
        "desc" => "Guess the real bizarre local headlines versus one made up by Toby, rotating to a different UK region each show."
    ],
    [
        "num" => "8",
        "title" => "Campus Trivia Challenge",
        "desc" => "A high-speed, 5-question micro-quiz between an in-studio guest and a live phone-in challenger."
    ],
    [
        "num" => "9",
        "title" => "Live in the Studio",
        "desc" => "Stripped-down, energetic live acoustic sessions showcasing local student singers, bands, and creators."
    ],
    [
        "num" => "10",
        "title" => "Bournemouth Bucket List",
        "desc" => "Tackling epic local challenges like trekking from Bournemouth Pier to Boscombe Pier only on one leg."
    ],
    [
        "num" => "11",
        "title" => "Local Legends",
        "desc" => "Spotlighting the everyday heroes of Bournemouth who make the student experience memorable, from friendly campus baristas to legendary bus drivers."
    ],
    [
        "num" => "12",
        "title" => "Lost in the Archives",
        "desc" => "An unashamed throwback music segment celebrating massive radio hits from 10 to 15 years ago."
    ],
    [
        "num" => "13",
        "title" => "The Great Student Bake-Off",
        "desc" => "Celebrating weird, wonderful, and highly budget-conscious recipes and high-heat competitions."
    ],
    [
        "num" => "14",
        "title" => "Spotlight Section",
        "desc" => "Giving our weekly guest an uninterrupted, passionate 3-to-5-minute platform to talk about their niche hobby, study, or creative project."
    ],
];