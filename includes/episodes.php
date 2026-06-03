<?php
/**
 * "Another Tangent with Toby" - Interactive Episode Directory
 * Dynamically scans the /episodes directory and renders a sortable grid
 */
require_once __DIR__ . '/../config.php';

// Prepare lists of all episodes
$episodesList = [];
$episodesDir = __DIR__ . '/../episodes';

if (is_dir($episodesDir)) {
    $files = glob($episodesDir . '/*.url');
    foreach ($files as $file) {
        $filename = basename($file);
        $num = pathinfo($filename, PATHINFO_FILENAME); // e.g., "001"

        // Parse the standard INI-style .url file format
        $iniData = parse_ini_file($file, true, INI_SCANNER_RAW);
        if (isset($iniData['InternetShortcut']['URL'])) {
            $url = $iniData['InternetShortcut']['URL'];

            // Allow optional custom metadata within the .url file
            $title = $iniData['InternetShortcut']['Title'] ?? "Tangent Episode #" . ltrim($num, '0');
            $date = $iniData['InternetShortcut']['Date'] ?? "Broadcast Session";
            $duration = $iniData['InternetShortcut']['Duration'] ?? "1 Hour";

            $episodesList[] = [
                'num' => intval($num),
                'num_string' => $num,
                'title' => $title,
                'date' => $date,
                'duration' => $duration,
                'url' => $url
            ];
        }
    }
}

// Default sort: highest episode number first (newest)
usort($episodesList, function ($a, $b) {
    return $b['num'] <=> $a['num'];
});
?>

<section id="catch-up">
    <section>
        <!-- Main Episode Archive Hub -->
        <section class="episodes-archive">
            <div class="container">

                <div class="archive-header">
                    <div class="archive-title">
                        <h2>Catch-Up Vault</h2>
                        <p>Missed an episode live on Nerve Radio? Tune in back-to-back right here.</p>
                    </div>

                    <?php if (!empty($episodesList)): ?>
                        <div class="filter-control">
                            <label for="sortToggle">Ordering:</label>
                            <button class="btn-sort" id="sortToggle" data-sort="desc">
                                <span id="sortText">Newest First</span>
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z" />
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (empty($episodesList)): ?>
                    <div class="empty-state">
                        <h3>No catch-up episodes recorded yet!</h3>
                        <p>Check back shortly as we load up show segments directly from our Weymouth House archives.</p>
                    </div>
                <?php else: ?>
                    <div class="episodes-grid" id="episodesGrid">
                        <?php foreach ($episodesList as $ep): ?>
                            <div class="episode-card" data-number="<?php echo $ep['num']; ?>">
                                <div>
                                    <div class="ep-meta">
                                        <span class="ep-tag">Show #<?php echo $ep['num_string']; ?></span>
                                        <span class="ep-duration"><?php echo htmlspecialchars($ep['duration']); ?></span>
                                    </div>
                                    <h3><?php echo htmlspecialchars($ep['title']); ?></h3>
                                    <span class="ep-date"><?php echo htmlspecialchars($ep['date']); ?></span>
                                </div>

                                <button type="button" class="btn-listen-card btn-play-episode"
                                    data-stream="<?php echo htmlspecialchars($ep['url']); ?>"
                                    data-title="<?php echo htmlspecialchars($ep['title']); ?>"
                                    data-episode="Show #<?php echo $ep['num_string']; ?>">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                        <path
                                            d="M6.271 5.055a.5.5 0 0 1 .52.038l3.5 2.5a.5.5 0 0 1 0 .814l-3.5 2.5A.5.5 0 0 1 6 10.5v-5a.5.5 0 0 1 .271-.445z" />
                                    </svg>
                                    Play Episode
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </section>

        <!-- Interactive Client-side Sort Script -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const sortToggle = document.getElementById('sortToggle');
                const sortText = document.getElementById('sortText');
                const grid = document.getElementById('episodesGrid');

                if (sortToggle && grid) {
                    sortToggle.addEventListener('click', () => {
                        const cards = Array.from(grid.querySelectorAll('.episode-card'));
                        const currentSort = sortToggle.getAttribute('data-sort');
                        let nextSort = 'desc';

                        if (currentSort === 'desc') {
                            // Switch to ascending (oldest first)
                            cards.sort((a, b) => parseInt(a.dataset.number) - parseInt(b.dataset.number));
                            sortText.textContent = "Oldest First";
                            nextSort = 'asc';
                        } else {
                            // Switch to descending (newest first)
                            cards.sort((a, b) => parseInt(b.dataset.number) - parseInt(a.dataset.number));
                            sortText.textContent = "Newest First";
                            nextSort = 'desc';
                        }

                        // Re-render sorted card positions
                        grid.innerHTML = '';
                        cards.forEach(card => grid.appendChild(card));
                        sortToggle.setAttribute('data-sort', nextSort);
                    });
                }
            });
        </script>
    </section>
</section>