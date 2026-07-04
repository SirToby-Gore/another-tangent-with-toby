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


$segments = [
    [
        "num" => "1",
        "title" => "The Campus Theme Song",
        "bref" => "I am chaining student music tastes into a continuous loop.",
        "desc" => "My goal is to create an infinite chain of musical tastes across campus; it relies entirely on a continuous loop of student recommendations. The Hand-off: I play the track selected by the previous week's final interviewee. The Interview: I stop a random student on campus, play them a 15-second snippet, and ask for a rating out of 10. The Tangent: I ask for their absolute favorite song right now; that track becomes the start of next week's chain. The Tie-in: The loop always begins or ends with my in-studio guest."
    ],
    [
        "num" => "2",
        "title" => "The Bus Stop Diaries",
        "bref" => "I gather raw, quick-fire chats live from the bus queue.",
        "desc" => "I want to capture raw, unedited student thoughts during the daily commute. The U1/U2 Lineup: I wander down to the Talbot Campus bus hub armed with a microphone. The Quick-Fire Hook: I ask students waiting in line one highly specific question (e.g., 'What is the weirdest thing you've seen in the library?'). The Departure: I cut the package off abruptly with the sound of a revving bus engine."
    ],
    [
        "num" => "3",
        "title" => "Lost in the Triangle",
        "bref" => "My on-the-ground reviews of quirky independent spots across Bournemouth.",
        "desc" => "I explore Bournemouth's independent, alternative cultural hubs; it highlights the unique character of our local area. Independent Showcase: I highlight one quirky indie cafe, vintage store, or record shop in Westbourne or The Triangle. The Micro-Review: I deliver a quick, humorous, on-the-ground review of a local product. The Interactive Map: I post the spot to our social media accounts to build a crowd-sourced guide.",
        "submission" => [
            "value" => "Lost_In_The_Triangle",
            "label" => "I Know A Place That's Nice"
        ]
    ],
    [
        "num" => "4",
        "title" => "In Another World",
        "bref" => "I host a studio panel show solving useless hypothetical debates.",
        "desc" => "I settle completely useless, hilarious hypothetical arguments with my guests. The Dilemma: I pitch a bizarre scenario to the guest panel (e.g., 'You get £10,000 every time you trip, but you can only walk in zig-zags'). The Cross-Examination: I act as the mock-serious judge, questioning the feasibility of their logic. The Verdict: The studio desk or listeners via text decide the final winner.",
        "submission" => [
            "value" => "Another_World_Hypothetical",
            "label" => "Submit an 'In Another World' Question"
        ]
    ],
    [
        "num" => "5",
        "title" => "Mr. vs. Mrs. of the Flat",
        "bref" => "I test flatmates live on air to win funny prizes.",
        "desc" => "I put flatmate relationships to the ultimate test live on air. The Confession: I bring two flatmates into the studio; one is sent out or wears soundproof headphones. The Question: I ask the remaining flatmate about their living habits (e.g., 'Who leaves dirty pans the longest?'). The Match: I bring the second flatmate back to see if their answers align. The Prize: Correct matches win a minor, funny prize like a custom flat trophy.",
        "submission" => [
            "value" => "Mr_Vs_Mrs",
            "label" => "Volunteer Your Flatmates to Come on Air"
        ]
    ],
    [
        "num" => "6",
        "title" => "Society Showdown",
        "bref" => "I pit two wildly different university groups against each other.",
        "desc" => "I pit two wildly different university societies against each other in a battle of wits. The Meet-Cute: Representatives from two distinct groups pitch why their society is superior. The Trivial Debate: I give them a 60-second debate on a completely unrelated, minor topic (e.g., 'Is soup a drink?'). The Decider: I use a quick poll or my own judgment to award the decorated 'Tangent Trophy'.",
        "submission" => [
            "value" => "Society_showdown",
            "label" => "I Want to See My Society Pitted Against"
        ]
    ],
    [
        "num" => "7",
        "title" => "The Headline Game",
        "bref" => "I challenge guests to deduce real local news from my fakes.",
        "desc" => "I sift through the wonderfully bizarre world of local UK journalism. The Regional Spotlight: Each week, I select one specific region of the UK (e.g., Cornwall or Yorkshire). The Lineup: I read out three bizarre local news headlines from that region. The Twist: Two are real local headlines; one is completely fabricated by me. The Guess: The guest or live callers must deduce which headline is the fake."
    ],
    [
        "num" => "8",
        "title" => "Campus Trivia Challenge",
        "bref" => "I run a high-speed micro-quiz between guests and live callers.",
        "desc" => "I host a high-speed, trivia-based duel to keep listeners on edge. The Contenders: My in-studio guest goes head-to-head against a live phone caller. The Rapid-Fire Round: I ask exactly 5 questions with no multiple choice to keep the pace fast. The Penalty: I resolve tie-breakers with a quick-draw guess (e.g., 'How many steps are in Weymouth House?')."
    ],
    [
        "num" => "9",
        "title" => "Live in the Studio",
        "bref" => "I host raw, minimal acoustic sessions tracking local creators.",
        "desc" => "I promote local student musical talent in a raw, acoustic setting. The Acoustic Strip-Down: Bands or solo artists perform 1 or 2 songs live in the studio. Instrumentation is kept minimal to prevent technical lag. The Behind-the-Music Interview: I have a quick chat with them about their songwriting process, influences, and upcoming local gigs.",
        "submission" => [
            "value" => "Live_In_The_Studio",
            "label" => "I'd Love to Perform Live On Air"
        ]
    ],
    [
        "num" => "10",
        "title" => "Bournemouth Bucket List",
        "bref" => "I execute classic local student challenges on a budget.",
        "desc" => "I find out how much of the classic local experience a student can achieve. The Challenge Drop: I announce a weekly challenge (e.g., walking from Bournemouth Pier to Boscombe Pier in fancy dress). The Review: My guest or I recount the experience of trying to execute the challenge, complete with audio logs and photos."
    ],
    [
        "num" => "11",
        "title" => "Lecture Hall Lies",
        "bref" => "I challenge listeners to spot ridiculous facts smuggled into a deadpan academic script.",
        "desc" => "I test my guest's straight-faced bluffing and the audience's active listening. The Lecture: I hand my guest a short, highly academic-sounding 60-second script on a normal topic. The Smuggle: Hidden seamlessly inside the text are three completely fabricated, absurd fake facts. The Buzzer: A live caller or I must hit the buzzer the exact second we spot a lie; correct guesses win points, but false alarms cost you."
    ],
    [
        "num" => "12",
        "title" => "Lost in the Archives",
        "bref" => "I unearth throwback musical nostalgia from 10 to 15 years ago.",
        "desc" => "I indulge in an unashamed throwback to retro-nostalgia. The Time Leap: I play a song that was a top-tier banger exactly 10 or 15 years ago; it taps perfectly into late-2000s and early-2010s childhood nostalgia. The Memory Lane Chat: My guest and I discuss where we were and what we were doing when this track ruled the charts."
    ],
    [
        "num" => "13",
        "title" => "The Great Student Bake-Off",
        "bref" => "I review weird single-mug budget cooking recipe hacks.",
        "desc" => "I celebrate the highly creative, highly stressful, and budget-conscious culinary hacks of student life. The Challenge: Students submit their ultimate under-£3 recipes or creative, affordable dishes. The Live Tasting/Review: My guest and I attempt to evaluate or assemble these low-cost recipes live in the studio, rating them from 'Gourmet Masterpiece' to 'Culinary Catastrophe'.",
        "submission" => [
            "value" => "Bake_Off_Recipe",
            "label" => "Submit a Recipe or Bake Off Challenge"
        ]
    ],
    [
        "num" => "14",
        "title" => "Spotlight Section",
        "bref" => "I provide an uninterrupted platform for a guest's passionate topic.",
        "desc" => "I give my guest an uninterrupted platform to talk passionately about their specialized topic. The Passion Pitch: The guest gets 3 to 5 minutes to speak about whatever they want; this includes dissertation topics, niche hobbies, or creative projects they are currently launching.",
        "submission" => [
            "value" => "Come_On_The_Show",
            "label" => "I Want To Come On Air and Talk About"
        ]
    ],
    [
        "num" => "15",
        "title" => "A Pair of Fresh Ears",
        "bref" => "I share songs we wish we could hear for the first time again.",
        "desc" => "I revisit significant musical milestones with my guests. We share, dissect, and listen to one or two legendary tracks that we wish we could hear for the absolute first time again; we focus on the emotional magic, initial goosebumps, and production layers of that discovery.",
        "submission" => [
            "value" => "Fresh_Ears",
            "label" => "I Wish I Could Listen to This Again"
        ]
    ],
    [
        "num" => "16",
        "title" => "Read the Room",
        "bref" => "I run a mind-reading game of numbers and situational categories.",
        "desc" => "I host a mind-reading game based on numbers and situational categories. The Setup: Two people pick a secret number inside their head from 1 to 10. The Category: The third person picks a category, i.e., condiments. The Alignment: The players choose an item matching their secret rating (e.g., an 8 merits saying 'sweet chili'). The Guess: The category selector must guess the exact numbers; breaking the logic of the rating docks you a point.",
        "submission" => [
            "value" => "Read_The_Room",
            "label" => "A Topic For Read The Room"
        ]
    ],
    [
        "num" => "17",
        "title" => "Beyond The Border",
        "bref" => "I explore the best music and customs from global cultures.",
        "desc" => "I take a few structured minutes of each broadcast to step completely away from local news. I travel outside our borders to look at the finest cultural components; I track exceptional music releases, indie charts, specialized games, and fascinating historical traditions from nations across the globe.",
        "submission" => [
            "value" => "A_New_Country",
            "label" => "Can You To Talk All About My Favourite Culture"
        ]
    ],
    [
        "num" => "18",
        "title" => "Agony Uncle",
        "bref" => "I solve student dilemmas with witty, sarcastic, and occasionally insightful advice.",
        "desc" => "I solve life's most ridiculous student dilemmas with witty, sarcastic, and occasionally insightful advice. The Crisis: Listeners submit their funniest, most trivial daily crises via the studio text line or social media. My Diagnosis: I analyze the problem, deliver funny, highly sarcastic advice, and wrap up each segment with one surprisingly useful, genuine life lesson to catch everyone off guard.",
        "submission" => [
            "value" => "An_Agony",
            "label" => "I've Got an Agony That Needs Sorting"
        ]
    ],
    [
        "num" => "19",
        "title" => "60 Seconds To Stand Up",
        "bref" => "I give student comics a high-pressure 60-second stand-up showcase.",
        "desc" => "I collaborate with the BU Stand-Up Society to feature local campus talent. The Clock: Guest comedians get exactly 60 seconds on a hot mic to deliver their best skit or one-liners. The Pressure: A loud buzzer sounds the exact millisecond the timer hits zero. The Feedback: The studio desk and I give a light-hearted, instant micro-review of the set.",
        "submission" => [
            "value" => "Comedy_Minute",
            "label" => "Apply for The Comedy Minute Open Mic"
        ]
    ],
    [
        "num" => "20",
        "title" => "The Great Campus Debates",
        "bref" => "I defend the absolute worst hills to die on in student life.",
        "desc" => "I dedicate a segment to passionate, highly dramatic arguments over things that do not matter. The Hill: Listeners submit the oddly specific rule or opinion they live by (e.g., 'Cereal is better with warm milk'). The Defense: My guest and I take opposing sides; we spend two minutes acting as high-court lawyers defending or destroying the claim.",
        "submission" => [
            "value" => "Campus_Debate",
            "label" => "Submit a Ridiculous Hill to Die On"
        ]
    ],
    [
        "num" => "21",
        "title" => "3 Lies and Maybe a Truth",
        "bref" => "I make guests guess wild claims before forcing a partner to defend them.",
        "desc" => "I host a high-stakes game of deception and blind loyalty. The Statements: Person A drops three completely wild personal or general statements; anywhere from zero to all three could be true. The Guess: Person B must guess which statements are facts and which are blatant lies. The Defense: I immediately put Person C on the spot, forcing them to dramatically justify and defend Person A's ridiculous claims regardless of the truth."
    ],
    [
        "num" => "22",
        "title" => "Where did all the music go?",
        "bref" => "I dig up fresh, undiscovered student producers and local bands.",
        "desc" => "I shine a spotlight on brand-new, under-the-radar musical creators. The Discovery: I select and play 1 to 3 standout tracks from artists who are completely undiscovered, or tracks I think people should hear. The Critique: We chat live about their unique sound, production style, and potential. The Connection: Listeners can submit their original tracks or nominate local musical friends making waves on campus.",
        "submission" => [
            "value" => "New_Artists",
            "label" => "Submit a Fresh, Undiscovered Track"
        ]
    ],
    [
        "num" => "23",
        "title" => "Oi DJ, crank that up!",
        "bref" => "I host a high-energy, rapid-fire mini-mix of student dance anthems.",
        "desc" => "I turn the studio into a high-energy dancefloor for a few fast-paced minutes. The Mix: I hand control over to a guest student DJ or trigger a customized 3-minute mini-mix of clean, crowd-pleasing dance anthems and creative remixes. The Rule: Every track must transition smoothly within 45 seconds to keep the momentum soaring.",
        "submission" => [
            "value" => "DJ_Bangers",
            "label" => "Suggest a High-Energy Track for the Mini-Mix"
        ]
    ],
    [
        "num" => "24",
        "title" => "Talbot Campus Castaway",
        "bref" => "I challenge guests to select three essentials to survive a semester locked inside Weymouth House.",
        "desc" => "I run a local spin on a legendary format. The Scenario: You are locked inside the Weymouth House media building on Talbot Campus for an entire semester with no way out. The Inventory: You are permitted to bring exactly three essential items to keep you sane: one specific snack from the campus shop, one track to blast over the student radio speakers, and one completely useless personal item from your flat. My guest and I rate your kit from 'Survival Genius' to 'Dorm Room Disaster'.",
        "submission" => [
            "value" => "Campus_Castaway",
            "label" => "Submit My Survival Kit for Talbot Campus Castaway"
        ]
    ],
    [
        "num" => "25",
        "title" => "The Alarm Clock Review",
        "bref" => "I blast and review the absolute worst songs students use to wake up in the morning.",
        "desc" => "I analyze the sounds of morning dread. The Sound: Students submit the exact alarm ringtone or song they use to force themselves out of bed for early lectures. The Breakdown: I play the track live on air, analyze its panic-inducing qualities, and suggest a ridiculous, hyper-energetic replacement song to completely change your morning routine.",
        "submission" => [
            "value" => "Alarm_Review",
            "label" => "Submit Your Dreaded Morning Alarm Sound"
        ]
    ]
];