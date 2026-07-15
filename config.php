<?php

require_once __DIR__ . '/vendor/autoload.php';

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
        $_ENV[trim($name)] = trim($value, " \t
        \r\0\x0B\"'");
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


$i = 1;

$segments = [
    // ==========================================
    // 1. SPECIALIZED MUSIC SHOWCASES & FEATURES
    // ==========================================
    [
        "id" => "278c8e5f50",
        "num" => $i++,
        "title" => "The Campus Theme Song",
        "bref" => "I am chaining student music tastes into a **continuous loop**.",
        "desc" => "
            My goal is to create an *infinite chain* of musical tastes across campus; it relies entirely on a continuous loop of student recommendations.
            
            * **Step 1:** I play the track selected by the previous week's final interviewee.
            * **Step 2:** I stop a random student on campus, play them a *15-second* snippet, and ask for a rating out of 10, and go to **Step 1**.
            * **Step 3:** I ask for their absolute favourite song right now; that track becomes the start of next week's chain.
            
            The loop always begins or ends with my in-studio guest.
        ",
    ],
    [
        "id" => "0cb2988177",
        "num" => $i++,
        "title" => "Linked by a Thread",
        "bref" => "I connect songs through increasingly ridiculous threads of **trivia and musical coincidences**.",
        "desc" => "
            I attempt to build a musical chain where every track connects to the previous one via highly convoluted and absurd relationships.
            
            The link might be:
            * A shared chord progression
            * A shared background session singer
            * Highly obscure trivia (e.g., *both singers visited the same restaurant on their fifth birthdays*)
            
            The studio guest must try to guess the secret thread of logic before the track ends.
        ",
    ],
    [
        "id" => "37748e8b90",
        "num" => $i++,
        "title" => "Live in the Studio",
        "bref" => "I host raw, minimal **acoustic sessions** tracking local creators.",
        "desc" => "
            I promote local student musical talent in a raw, acoustic setting. Bands or solo artists perform `1` or `2` songs live in the studio.
            
            *Note: Instrumentation is kept minimal to prevent technical lag.*
            
            During the interview, I have a quick chat with them about their songwriting process, influences, and upcoming local gigs.
        ",
        "submission" => [
            "value" => "Live_In_The_Studio",
            "label" => "I'd Love to Perform Live On Air"
        ]
    ],
    [
        "id" => "d5b127ef9a",
        "num" => $i++,
        "title" => "In The Archives With A Pair Of Fresh Ears",
        "bref" => "Guests react to a **mystery track** completely blind before predicting its legacy 15 years from now.",
        "desc" => "
            We put our guest's musical instincts to the ultimate test! We slide headphones onto them and play a mystery track they have completely never heard before—ranging from fresh, undiscovered campus demos to hidden global indie gems.
            
            * **The Blind Reaction:** The guest reacts live to the raw production layers, trying to guess the artist's vibe, genre, or background purely on first-listen goosebumps.
            * **The Time Capsule Test:** Once the track is revealed, we fast-forward 15 years. The panel must debate: *Does this song have the hook to survive a decade? What core memories will future students associate with it?*
            
            We wrap up by rating its potential to become a nostalgic throwback classic on a scale of 1 to 10.
        ",
        "submission" => [
            "value" => "Blind_Future_Classic",
            "label" => "Submit an Original Track for the Blind Time Capsule Test"
        ]
    ],
    [
        "id" => "a756363da0",
        "num" => $i++,
        "title" => "Where did all the music go?",
        "bref" => "I dig up fresh, **undiscovered** producers and bands.",
        "desc" => "
            I shine a spotlight on brand-new or under-the-radar musical creators. I select and play 1 to 3 standout tracks from artists who are completely undiscovered, or tracks I think people should hear.
            
            We chat live about their unique sound, production style, and potential. Listeners can **submit their original tracks** or nominate local musical friends making waves on campus.
        ",
        "submission" => [
            "value" => "New_Artists",
            "label" => "Submit a Fresh, Undiscovered Track"
        ]
    ],
    [
        "id" => "b3bf92f3ac",
        "num" => $i++,
        "title" => "Oi DJ, crank that up!",
        "bref" => "I host a high-energy, rapid-fire mini-mix of **student dance anthems**.",
        "desc" => "
            I turn the studio into a high-energy dancefloor for a few fast-paced minutes. I hand control over to a guest student DJ or trigger a customized `3-minute mini-mix` of clean, crowd-pleasing dance anthems and creative remixes.
            
            *Crucial Rule: Every track must transition smoothly within 45 seconds to keep the momentum soaring.*
        ",
        "submission" => [
            "value" => "DJ_Bangers",
            "label" => "Suggest a High-Energy Track for the Mini-Mix"
        ]
    ],
    [
        "id" => "0ca59490bf",
        "num" => $i++,
        "title" => "A Touch of Class",
        "bref" => "I play a classical masterpiece and chat about its **epic energy**.",
        "desc" => "
            I step away from modern radio playlists to explore classical compositions; it highlights the pure, timeless intensity of orchestral music. I play one or two legendary classical movements.
            
            My guest and I discuss the dramatic instrumentation and historical context. *No formal musical training is required*; we rate each track purely on its cinematic power out of 10.
        ",
        "submission" => [
            "value" => "Touch_of_Class",
            "label" => "Suggest a Classical Track"
        ]
    ],

    // ==========================================
    // 2. STUDIO INTERACTIVE GAMES & RIDDLES
    // ==========================================
    [
        "id" => "2d87bd747d",
        "num" => $i++,
        "title" => "The Headline Game",
        "bref" => "I challenge guests to deduce **real local news** from my fakes.",
        "desc" => "
            I sift through the wonderfully bizarre world of local UK journalism. Each week, I select one specific region of the UK (e.g., *Cornwall* or *Yorkshire*).
            
            I read out three bizarre local news headlines from that region:

            1. Real local headline
            2. Real local headline
            3. **Completely fabricated by me**
            
            The guest or live callers must deduce which headline is the fake.
        ",
    ],
    [
        "id" => "aa2fba88fc",
        "num" => $i++,
        "title" => "Lecture Hall Lies",
        "bref" => "I challenge listeners to spot **ridiculous facts** smuggled into a deadpan academic script.",
        "desc" => "
            I test my guest's straight-faced bluffing and the audience's active listening. I hand my guest a short, highly academic-sounding 60-second script on a normal topic.
            
            Hidden seamlessly inside the text are **three completely fabricated, absurd fake facts**. A live caller or I must hit the buzzer the exact second we spot a lie; *correct guesses win points*, but *false alarms cost you*.
        ",
        "submission" => [
            "value" => "Lecture_Hall_Lies",
            "label" => "I Want to Nominate My Lecturer to Give A False Talk"
        ]
    ],
    [
        "id" => "c0bd77a866",
        "num" => $i++,
        "title" => "Read the Room",
        "bref" => "I run a mind-reading game of **numbers and situational categories**.",
        "desc" => "
            I host a mind-reading game based on numbers and situational categories.
            
            1. Two people pick a secret number inside their head from `1 to 10`.
            2. The third person picks a category (e.g., *condiments*).
            3. The players choose an item matching their secret rating (e.g., an 8 merits saying *'sweet chili'*).
            
            The category selector must guess the exact numbers; breaking the logical boundaries of the rating scale docks you a point.
        ",
        "submission" => [
            "value" => "Read_The_Room",
            "label" => "A Topic For Read The Room"
        ]
    ],
    [
        "id" => "8784e8da83",
        "num" => $i++,
        "title" => "3 Lies and Maybe a Truth",
        "bref" => "I make guests guess wild claims before forcing a partner to **blindly defend** them.",
        "desc" => "
            I host a high-stakes game of deception and blind loyalty.
            
            * **Step 1:** Person A drops three completely wild personal or general statements (anywhere from zero to all three could be true).
            * **Step 2:** Person B must guess which statements are facts and which are blatant lies.
            * **Step 3:** I immediately put Person C on the spot, forcing them to dramatically justify and defend Person A's ridiculous claims *regardless of the actual truth*.
        ",
    ],
    [
        "id" => "92be7142fa",
        "num" => $i++,
        "title" => "You'd Never Guess",
        "bref" => "Guests try to uncover the bizarre but entirely real logic hidden behind an **impossible-sounding statement**.",
        "desc" => "
            This high-concept riddle segment challenges our studio panel to crack cases of obscure trivia. I kick off by dropping an unbelievable scenario that sounds totally fabricated, such as a man leaping from an aeroplane annually without a parachute.
            
            Listeners and guests must cross-examine me using quick fire **yes-or-no questions** before the clock runs down, trying to piece together a technicality or unusual profession—like a *safety specialist evaluating high-altitude free-diving recovery gear*.
        ",
        "submission" => [
            "value" => "you_would_never_guess",
            "label" => "Submit a Bizarre Fact for You'd Never Guess"
        ]
    ],
    [
        "id" => "a3e8c91f4b",
        "num" => $i++,
        "title" => "4 Questions, 1 Link",
        "bref" => "I challenge guests to solve four quick trivia questions and find the **one hidden thread** that links them all.",
        "desc" => "
            A fast-paced, high-energy trivia challenge! The guests are presented with four seemingly unrelated questions. The goal isn't just to get the answers right—it's to crack the secret connection that binds them all together before the clock runs out.
            
            ### Example Round:
            * **Q1:** What is the surname of the artist behind the massive hit 'Uptown Funk'? *(A1: Mars)*
            * **Q2:** What is the name of the red planet, fourth from the Sun? *(A2: Mars)*
            * **Q3:** What chocolate brand makes a nougat-filled bar named after a galaxy? *(A3: Milky Way)*
            * **Q4:** What classic 1972 David Bowie track features the lyrics 'There's a starman waiting in the sky'? *(A4: Starman)*
            
            **The Link:** Outer Space / Galactic Themes!
        ",
    ],

    // ==========================================
    // 3. IMPROVISATIONAL COMEDY & AUDIO DRAMA
    // ==========================================
    [
        "id" => "76b0ceaeba",
        "num" => $i++,
        "title" => "In Another World",
        "bref" => "I host a studio panel show solving **useless hypothetical debates**.",
        "desc" => "
            I settle completely useless, hilarious hypothetical arguments with my guests. I pitch a bizarre scenario to the guest panel (e.g., *'You get £10,000 every time you trip, but you can only ever walk in zig-zags'*).
            
            I act as the mock-serious judge, questioning the feasibility of their logic. The studio desk or listeners via text decide the final winner.
        ",
        "submission" => [
            "value" => "Another_World_Hypothetical",
            "label" => "Submit an 'In Another World' Question"
        ]
    ],
    [
        "id" => "a7f39d2c8b",
        "num" => $i++,
        "title" => "The Three Talking Heads",
        "bref" => "A chaotic debate where a panel of conflicting **emotional extremes** forces a mediator to find a middle ground.",
        "desc" => "
            This segment divides the studio panel into three distinct personas:
            * **Head 1:** Pure anger and aggression
            * **Head 2:** Pure love and compassion
            * **Head 3:** The grounded voice of reason
            
            After one of the emotional extremes reads out a listener-submitted scenario, the first two voices launch into a dramatic, polarized argument from their respective viewpoints. It is up to the *voice of reason* to step into the crossfire, appease both sides, and construct a ridiculous yet functional compromise.
        ",
        "submission" => [
            "value" => "three_talking_heads",
            "label" => "Submit a Scenario for The Three Talking Heads"
        ]
    ],
    [
        "id" => "b8e42f1a6c",
        "num" => $i++,
        "title" => "The Cardboard Time Machine",
        "bref" => "A satirical, **boots-on-the-ground audio drama** exploring history through a forgotten studio project.",
        "desc" => "
            This meta-narrative audio drama follows the discovery of a bizarre, retro prop left behind in the dark corners of the Nerve Radio Studio. Far from being simple junk, this taped-together contraption actually works, hurling the presenters back through the centuries.
            
            Each episode delivers a fast-paced, sketch-comedy look at major historical eras from a thoroughly ordinary, everyday perspective, trading grand textbook speeches for the **mud, complaints, and awkward realities of life on the ground**.
        ",
        "submission" => [
            "value" => "cardboard_time_machine",
            "label" => "Suggest an Era for The Cardboard Time Machine"
        ]
    ],
    [
        "id" => "c3f8e21d9a",
        "num" => $i++,
        "title" => "Adventures of the Useless Superheroes",
        "bref" => "A live, improvisational radio drama where the panel performs stand-up style sketches about heroes cursed with **entirely pointless powers**.",
        "desc" => "
            This fast-paced comedy segment combines on-the-fly voice acting with live stand-up elements. I assign each guest a completely ridiculous, low-stakes superpower—such as *the ability to turn any liquid into lukewarm tea*, or *being completely invisible only when eyes are closed*.
            
            We then throw these mismatched characters into a dramatic emergency scenario submitted by the audience. The panel has to **riff live on the air**, performing monologues and descriptive scenes about how they would attempt to save the day using their utterly flawed abilities.
        ",
        "submission" => [
            "value" => "useless_superheroes",
            "label" => "Suggest a Pointless Power or Crisis Scenario"
        ]
    ],

    // ==========================================
    // 4. CAMPUS CULTURE, LIFESTYLE & BANTER
    // ==========================================
    [
        "id" => "85cd9633ef",
        "num" => $i++,
        "title" => "Society Showdown",
        "bref" => "I pit two wildly different university groups against each other in a direct debate to prove **who is truly the best**.",
        "desc" => "
            I host a grand, head-to-head clash between two different student societies; they must argue directly why their group is objectively the finest on campus. Representatives defend their own activities, community, and membership achievements while highlighting the *hilarious flaws* of their rivals.
            
            I grant each side a final, quick-fire chance to win over the microphone. With the decider when I judge, jury, and executioner determine the ultimate winner of the coveted **'Tangent Trophy'**.",
        "submission" => [
            "value" => "Society_showdown",
            "label" => "Nominate Why Your Society is the Best on Campus"
        ]
    ],
    [
        "id" => "1bc01eedee",
        "num" => $i++,
        "title" => "Bournemouth Bucket List",
        "bref" => "I execute classic local student challenges on a **strict budget**.",
        "desc" => "
            I find out how much of the classic local experience a student can achieve. I announce a weekly challenge (e.g., *walking from Bournemouth Pier to Boscombe Pier in fancy dress*).
            
            My guest or I recount the experience of trying to execute the challenge, complete with **audio logs** and **photos**.
        ",
        "submission" => [
            "value" => "Bmouth_Bucket_List",
            "label" => "I've Got Something for your Bucket List"
        ]
    ],
    [
        "id" => "4c265eec8c",
        "num" => $i++,
        "title" => "The Great Student Bake-Off",
        "bref" => "I review weird **single-mug budget** cooking recipe hacks.",
        "desc" => "
            I celebrate the highly creative, highly stressful, and budget-conscious culinary hacks of student life. Students submit their ultimate **under-£3** recipes or creative, affordable dishes.
            
            My guest and I attempt to evaluate or assemble these low-cost recipes live in the studio, rating them on a scale from *'Gourmet Masterpiece'* to *'Culinary Catastrophe'*.
        ",
        "submission" => [
            "value" => "Bake_Off_Recipe",
            "label" => "Submit a Recipe or Bake Off Challenge"
        ]
    ],
    [
        "id" => "642eb70ddd",
        "num" => $i++,
        "title" => "Agony Uncle",
        "bref" => "I solve student dilemmas with **witty, sarcastic**, and occasionally insightful advice.",
        "desc" => "
            I solve life's most ridiculous student dilemmas with witty, sarcastic, and occasionally insightful advice. Listeners submit their funniest, most trivial daily crises via the studio text line or social media.
            
            I analyze the problem, deliver funny, highly sarcastic solutions, and wrap up each segment with **one surprisingly useful, genuine life lesson** to catch everyone off guard.
        ",
        "submission" => [
            "value" => "An_Agony",
            "label" => "I've Got an Agony That Needs Sorting"
        ]
    ],
    [
        "id" => "365ef9aa71",
        "num" => $i++,
        "title" => "60 Seconds To Stand Up",
        "bref" => "I give student comics a high-pressure **60-second stand-up showcase**.",
        "desc" => "
            I collaborate with the *BU Stand-Up Society* to feature local campus talent. Guest comedians get exactly `60 seconds` on a hot mic to deliver their best skit or one-liners.
            
            A loud buzzer sounds the exact millisecond the timer hits zero. The studio desk and I give a light-hearted, instant micro-review of the set.
        ",
        "submission" => [
            "value" => "Comedy_Minute",
            "label" => "Apply for The Comedy Minute Open Mic"
        ]
    ],
    [
        "id" => "0ad33de056",
        "num" => $i++,
        "title" => "Talbot Campus Castaway",
        "bref" => "I challenge guests to select **three essentials** to survive a semester locked inside Weymouth House.",
        "desc" => "
            I run a local spin on a legendary format. You are locked inside the *Weymouth House* media building on Talbot Campus for an entire semester with no way out.
            
            You are permitted to bring exactly **three essential items** to keep you sane:
            1. One specific snack from the campus shop
            2. One track to blast over the student radio speakers
            3. One completely useless personal item from your flat
            
            My guest and I rate your kit from *'Survival Genius'* to *'Dorm Room Disaster'*.
        ",
        "submission" => [
            "value" => "Campus_Castaway",
            "label" => "Submit My Survival Kit for Talbot Campus Castaway"
        ]
    ],
    [
        "id" => "653c45a996",
        "num" => $i++,
        "title" => "The Alarm Clock Review",
        "bref" => "I blast and review the absolute **worst songs** students use to wake up in the morning.",
        "desc" => "
            I analyze the sounds of morning dread. Students submit the exact alarm ringtone or song they use to force themselves out of bed for early lectures.
            
            I play the track live on air, analyze its panic-inducing qualities, and suggest a *ridiculous, hyper-energetic replacement song* to completely change your morning routine.
        ",
        "submission" => [
            "value" => "Alarm_Review",
            "label" => "Submit Your Dreaded Morning Alarm Sound"
        ]
    ],
    [
        "id" => "8958333e2a",
        "num" => $i++,
        "title" => "Beyond The Border",
        "bref" => "I explore and appreciate the finest music and customs from **global cultures**.",
        "desc" => "
            I take a few structured minutes of each broadcast to step completely away from local news. I travel outside our borders to appreciate exceptional cultural components from nations across the globe.
            
            We track:
            * Exceptional music releases and indie charts
            * Traditional and specialised localized games
            * Fascinating historical customs and arts
        ",
        "submission" => [
            "value" => "A_New_Country",
            "label" => "Can You To Talk All About My Favourite Culture"
        ]
    ],
];

$parse_down = new Parsedown();

$seen_ids = [];
foreach ($segments as &$segment) {
    if (in_array($segment['id'], $seen_ids)) {
        die("
        ERROR: ID \"{$segment['id']} already in use
        ");
    }
    $seen_ids[] = $segment['id'];

    $rawBref = trim($segment['bref']);
    $rawDesc = trim($segment['desc']);

    $cleanBref = preg_replace('/^[ ]{4,}/m', '', $rawBref);
    $cleanDesc = preg_replace('/^[ ]{4,}/m', '', $rawDesc);

    $segment['bref'] = $parse_down->line($cleanBref);
    $segment['desc'] = $parse_down->text($cleanDesc);

}

unset($segment);