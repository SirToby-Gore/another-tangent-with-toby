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
        "bref" => "Chaining student music tastes into a continuous loop.",
        "desc" => "To create an infinite, continuous chain of musical tastes across the student body. The Hand-off: Play the song selected by the previous week's final interviewee. The Interview: Stop a random student on campus, play them a 15-second snippet of that song, and ask them to rate it out of 10. The Tangent: Ask them what their absolute favorite song is right now. That song becomes the start of the next chain. The Tie-in: The loop always begins or ends with the episode's in-studio guest."
    ],
    [
        "num" => "2",
        "title" => "The Bus Stop Diaries",
        "bref" => "Raw, unedited quick-fire chats live from the bus queue.",
        "desc" => "Capturing raw, unedited student thoughts during their daily commute. The U1/U2 Lineup: Toby wanders down to the Talbot Campus bus hub with a microphone. The Quick-Fire Hook: Ask students waiting in line one highly specific question (e.g., 'What is the weirdest thing you've seen in the library?' or 'If you had to swap degrees with your flatmate right now, would you pass?'). The Departure: Cut the package off abruptly with the sound of a bus engine revving or air-brakes releasing."
    ],
    [
        "num" => "3",
        "title" => "Lost in the Triangle",
        "bref" => "On-the-ground reviews of quirky independent spots.",
        "desc" => "Exploring Bournemouth's independent, alternative cultural hub. Independent Showcase: Highlight one quirky indie cafe, vintage store, or record shop in Westbourne or The Triangle. The Micro-Review: Toby does a quick, humorous, on-the-ground review of a local product (e.g., 'Rating this vintage shop’s strangest retro jacket'). The Interactive Map: Post the spot on the show's social media accounts, building a crowd-sourced 'Tangent Guide to Bournemouth' using the show's signature color theme.",
        "submission" => [
            "value" => "Lost_In_The_Triangle",
            "label" => "I Know A Place That's Nice"
        ]
    ],
    [
        "num" => "4",
        "title" => "In Another World",
        "bref" => "A studio panel show solving useless hypothetical debates.",
        "desc" => "Settling completely useless, hilarious hypothetical arguments. The Dilemma: Pitch a bizarre scenario to the guest panel (e.g., 'You get £10,000 every time you trip over a curb, but you can only walk in zig-zags. Are you taking it?'). The Cross-Examination: Toby acts as the mock-serious judge, questioning the feasibility of their logic. The Verdict: The studio desk or listeners via text decide the winner.",
        "submission" => [
            "value" => "Another_World_Hypothetical",
            "label" => "Submit an 'In Another World' Question"
        ]
    ],
    [
        "num" => "5",
        "title" => "Mr. vs. Mrs. of the Flat",
        "bref" => "Testing flatmates live on air to win funny prizes.",
        "desc" => "Putting flatmate relationships to the ultimate test live on air. The Confession: Bring two flatmates into the studio. One goes out of the room (or wears soundproof headphones). The Question: Ask the remaining flatmate a question (e.g., 'Who is most likely to leave a dirty pan in the sink for five days?' or 'What is their ultimate comfort food?'). The Match: Bring the second flatmate back in and see if their answers match. The Prize: Correct answers win a minor, funny prize (e.g., a giant cookie, a free smoothie voucher at the SU cafe, or a customized flat trophy).",
        "submission" => [
            "value" => "Mr_Vs_Mrs",
            "label" => "Volunteer Your Flatmates to Come on Air"
        ]
    ],
    [
        "num" => "6",
        "title" => "Society Showdown",
        "bref" => "Pitting two wildly different university groups against each other.",
        "desc" => "Pitting two wildly different university societies against each other in a battle of wits. The Meet-Cute: Representatives from two distinct groups (e.g., BU Dance vs. BU Chess) pitch why their society is superior. The Trivial Debate: A 60-second debate on a completely unrelated, minor topic (e.g., 'Is soup a drink?' or 'Should pajamas be worn to lectures?'). The Decider: Toby or a Twitter poll determines who wins the prestigious 'Tangent Trophy' (a decorated reusable cup).",
        "submission" => [
            "value" => "Society_showdown",
            "label" => "I Want to See My Society Pitted Against"
        ]
    ],
    [
        "num" => "7",
        "title" => "The Headline Game",
        "bref" => "Deducing real local news headlines from fake ones.",
        "desc" => "Sifting through the wonderfully bizarre world of local UK journalism. The Regional Spotlight: Each week, Toby selects one specific region of the UK (e.g., Cornwall, Yorkshire, the Scottish Highlands). The Lineup: Toby reads out three bizarre local news headlines from that region. The Twist: Two are real local headlines; one is completely fabricated by Toby. The Guess: The guest or live callers must deduce which headline is the fake."
    ],
    [
        "num" => "8",
        "title" => "Campus Trivia Challenge",
        "bref" => "A high-speed micro-quiz between guests and live callers.",
        "desc" => "A high-speed, trivia-based duel that keeps listeners on the edge of their seats. The Contenders: In-studio guest vs. a live phone caller. The Rapid-Fire Round: Exactly 5 questions. No multiple choice, keeping the pace lightning-fast. The Penalty: Tie-breaker questions must be resolved with a quick-draw guess (e.g., 'How many steps are there in Weymouth House?')."
    ],
    [
        "num" => "9",
        "title" => "Live in the Studio",
        "bref" => "Raw, minimal acoustic sessions tracking local creators.",
        "desc" => "Promoting local student musical talent in a raw, acoustic setting. The Acoustic Strip-Down: Bands or solo artists perform 1-2 songs live. Instrumentation is kept minimal (acoustic guitar, keyboard, or vocals over backing tracks) to prevent technical lag. The Behind-the-Music Interview: A quick chat about their songwriting process, musical influences, and local student gigs coming up in Bournemouth.",
        "submission" => [
            "value" => "Live_In_The_Studio",
            "label" => "I'd Love to Perform Live On Air"
        ]
    ],
    [
        "num" => "10",
        "title" => "Bournemouth Bucket List",
        "bref" => "Executing classic local student challenges on a budget.",
        "desc" => "Finding out how much of the classic local experience a student can actually achieve. The Challenge Drop: Toby announces a weekly challenge (e.g., The £5 Date Night Challenge, Walking the entire distance from Bournemouth Pier to Boscombe Pier in fancy dress, or Finding the absolute best milkshake in town). The Review: Toby or a guest recounts their experience trying to execute the challenge, complete with audio logs and photos posted to socials.",
        "submission" => [
            "value" => "Bucket_List_Idea",
            "label" => "Suggest a Bournemouth Bucket List Challenge"
        ]
    ],
    [
        "num" => "11",
        "title" => "Local Legends",
        "bref" => "Spotlighting the non-student heroes of Bournemouth campus.",
        "desc" => "Spotlighting the everyday heroes of Bournemouth who make the student experience memorable. The Profile: A short, warm-hearted interview with a student or non-student icon. The Nominations: Ideas include the legendary campus barista, a beloved security guard, a local bus driver, or a stand-out staff member at the student shop.",
        "submission" => [
            "value" => "Unsung_Hero",
            "label" => "Nominate an Unsung Hero"
        ]
    ],
    [
        "num" => "12",
        "title" => "Lost in the Archives",
        "bref" => "Throwback musical nostalgia tracks from 10-15 years ago.",
        "desc" => "An unashamed throwback to retro-nostalgia. The Time Leap: Play a song that was a top-tier banger exactly 10 or 15 years ago (perfect for tapping into late-2000s and early-2010s childhood nostalgia). The Memory Lane Chat: Toby and the guest discuss where they were and what they were doing when this song was ruling the charts."
    ],
    [
        "num" => "13",
        "title" => "The Great Student Bake-Off",
        "bref" => "Reviewing weird single-mug budget cooking recipe hacks.",
        "desc" => "Celebrating the highly creative, highly stressful, and budget-conscious culinary hacks of student life. The Challenge: Students submit their ultimate under-£3 recipes or creative, affordable, home-cooked dishes. The Live Tasting/Review: Toby and the guest attempt to evaluate or assemble these low-cost recipes live in the studio, celebrating clever budget planning and rating their viability on a scale from 'Gourmet Masterpiece' to 'Culinary Catastrophe'.",
        "submission" => [
            "value" => "Bake_Off_Recipe",
            "label" => "Submit a Recipe or Bake Off Challenge"
        ]
    ],
    [
        "num" => "14",
        "title" => "Spotlight Section",
        "bref" => "An uninterrupted platform for a guest's passionate topic.",
        "desc" => "Giving the guest an uninterrupted platform to talk passionately about their specialized topic. The Passion Pitch: The guest gets 3-5 minutes to speak about whatever they want—whether it's their dissertation topic, a niche hobby, or a creative project they are launching.",
        "submission" => [
            "value" => "Come_On_The_Show",
            "label" => "I Want To Come On Air and Talk About"
        ]
    ],
    [
        "num" => "15",
        "title" => "A Pair of Fresh Ears",
        "bref" => "Songs we wish we could hear for the first time again.",
        "desc" => "Revisiting musical milestones. We share, dissect, and listen to one or two legendary songs that we wish with all our hearts we could hear for the absolute first time again, discussing the emotional magic, initial goosebumps, and production layers of that discovery.",
        "submission" => [
            "value" => "Fresh_Ears",
            "label" => "I Wish I Could Listen to This Again"
        ]
    ],
    [
        "num" => "16",
        "title" => "Read the Room",
        "bref" => "A mind-reading game of numbers and situational categories.",
        "desc" => "Two people pick a secret number inside their head on a scale from 1 to 10. The third person picks a category, e.g., condiments. The two players then choose an item that aligns with their secret rating (e.g., an 8 or 9 merits saying 'sweet chili', while a 3 or 4 merits saying 'mustard'). The person who chose the category must guess their exact numbers. If either player gives a weird choice that breaks the logic of their rating (e.g., rating a 3 and naming 'tzatziki'), they get docked a point!",
        "submission" => [
            "value" => "Read_The_Room",
            "label" => "A Topic For Read The Room"
        ]
    ],
    [
        "num" => "17",
        "title" => "Beyond The Border",
        "bref" => "Exploring the best music and customs from global cultures.",
        "desc" => "Taking a few structured minutes of each broadcast to step completely away from local news. We travel outside our borders to look at the finest cultural components, tracking exceptional music releases, indie charts, specialized games, and fascinating historical traditions from one or more nations across the globe.",
        "submission" => [
            "value" => "A_New_Country",
            "label" => "Can You To Talk All About My Favourite Culture"
        ]
    ],
    [
        "num" => "18",
        "title" => "Agony Uncle",
        "bref" => "Solving student dilemmas with witty, unhelpful, and insightful advice.",
        "desc" => "Toby solves life's most ridiculous student dilemmas with witty, sarcastic, and occasionally insightful advice. Listeners submit their funniest, most trivial daily crises via the studio text line or social media (e.g., 'My flatmate is using my mug to water their cactus' or 'How do I escape an awkward conversation in the library?'). Toby diagnoses the problem, delivering funny, witty, and highly sarcastic advice, wrapping up each segment with one surprisingly useful and genuinely helpful life lesson to catch everyone off guard.",
        "submission" => [
            "value" => "An_Agony",
            "label" => "I've Got an Agony That Needs Sorting"
        ]
    ],
    [
        "num" => "19",
        "title" => "The Comedy Minute",
        "bref" => "A high-pressure 60-second stand-up showcase for student comics.",
        "desc" => "Collaborating with the BU Stand-Up Society to feature local campus talent. The Clock: Guest comedians get exactly 60 seconds on a hot mic to deliver their best skit, stand-up routine, or one-liners. The Pressure: A loud buzzer sounds the exact millisecond the timer hits zero. The Feedback: Toby and the studio desk give a light-hearted, instant micro-review of the set.",
        "submission" => [
            "value" => "Comedy_Minute",
            "label" => "Apply for The Comedy Minute Open Mic"
        ]
    ],
    [
        "num" => "20",
        "title" => "The Great Campus Debates",
        "bref" => "Defending the absolute worst hills to die on in student life.",
        "desc" => "A segment dedicated to passionate, highly dramatic arguments over things that do not matter. The Hill: Listeners submit the oddly specific rule or opinion they live by (e.g., 'Cereal is better with warm milk' or 'The library third floor is objectively haunted'). Toby and the guest take opposing sides and spend two minutes acting as high-court lawyers defending or destroying the claim.",
        "submission" => [
            "value" => "Campus_Debate",
            "label" => "Submit a Ridiculous Hill to Die On"
        ]
    ],
    [
        "num" => "21",
        "title" => "3 Lies and Maybe a Truth",
        "bref" => "Guessing which wild claims are facts before forcing a partner to defend them.",
        "desc" => "A high-stakes game of deception and blind loyalty. The Statements: Person A drops three completely wild personal or general statements—the catch being that anywhere from zero to all three of them could actually be true. The Guess: Person B has to guess which statements are cold hard facts and which are blatant lies. The Defense: Person C (the guest or a live caller) is then immediately put on the spot and forced to dramatically justify and defend Person A's ridiculous claims, regardless of whether they are true or not.",
    ],
    [
        "num" => "22",
        "title" => "Where did all the music go?",
        "bref" => "Digging up fresh, undiscovered producers bands.",
        "desc" => "Shining a spotlight on brand-new, under-the-radar musical creators. The Discovery: Toby selects and plays 1 to 3 standout tracks from artists who are completely undiscovered or he thinks people should listen to. We chat live about their unique sound, production style, and potential. Listeners can submit their own original tracks or nominate local musical friends making waves on campus.",
        "submission" => [
            "value" => "New_Artists",
            "label" => "Submit a Fresh, Undiscovered Track"
        ]
    ],
    [
        "num" => "23",
        "title" => "Oi DJ, crank that up!",
        "bref" => "A high-energy, rapid-fire mini-mix of student dance anthems.",
        "desc" => "Turning the studio into a high-energy dancefloor for a few fast-paced minutes. Toby hands control over to a guest student DJ or triggers a customized 3-minute mini-mix of clean, crowd-pleasing dance anthems and creative remixes. The Rule: Every track must transition smoothly within 45 seconds to keep the momentum soaring.",
        "submission" => [
            "value" => "DJ_Bangers",
            "label" => "Suggest a High-Energy Track for the Mini-Mix"
        ]
    ]
];