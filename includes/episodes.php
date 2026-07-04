<?php
/**
 * "Another Tangent with Toby" - Interactive Episode Directory
 * Dynamically scans the /episodes directory and renders a sortable grid
 */
require_once __DIR__ . '/../config.php';

// Re-index the master segments database by its 'num' key for instant runtime mapping
$segmentsByNum = array_column($segments ?? [], null, 'num');

// Prepare lists of all episodes
$episodesList = [];
$episodesDir = __DIR__ . '/../episodes';

if (is_dir($episodesDir)) {
    // Scan for our new dynamic PHP configuration manifests
    $files = glob($episodesDir . '/*.php');
    foreach ($files as $file) {
        // Safe check to prevent including index/config files accidentally
        $epData = require $file;

        if (is_array($epData) && isset($epData['meta']['episode_number'])) {
            $numString = $epData['meta']['episode_number'];
            $meta = $epData['meta'];

            // Build the catalog item out of the structured manifest data
            $episodesList[] = [
                'num' => intval($numString),
                'num_string' => str_pad($numString, 3, '0', STR_PAD_LEFT), // Formats cleanly to "001"
                'title' => $meta['title'] ?? "Tangent Episode #" . $numString,
                'date' => $meta['record_date'] ?? "Broadcast Session",
                'guest' => $meta['guest_name'] ?? "Studio Guest",
                'description' => $meta['description'] ?? "",
                'url' => $meta['audio_src'] ?? "", // The dynamic playback path
                'lineup' => $epData['lineup'] ?? []
            ];
        }
    }
}

// Default sort: highest episode number first (newest detours upfront)
usort($episodesList, function ($a, $b) {
    return $b['num'] <=> $a['num'];
});
?>

<section id="catch-up">
    <section>
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
                            <div class="episode-card" data-number="<?= $ep['num']; ?>">
                                <div>
                                    <div class="ep-meta">
                                        <span class="ep-tag">Show #<?= $ep['num_string']; ?></span>
                                        <span class="ep-duration">Guest: <?= htmlspecialchars($ep['guest']); ?></span>
                                    </div>
                                    <h3><?= htmlspecialchars($ep['title']); ?></h3>
                                    <span class="ep-date"><?= htmlspecialchars($ep['date']); ?></span>

                                    <?php if (!empty($ep['description'])): ?>
                                        <p class="ep-card-desc" style="margin: 10px 0; font-size: 0.9em; opacity: 0.85;">
                                            <?= htmlspecialchars($ep['description']); ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($ep['lineup'])): ?>
                                        <div class="ep-segments-lineup"
                                            style="margin-top: 12px; display: flex; flex-wrap: wrap; gap: 6px;">
                                            <?php foreach ($ep['lineup'] as $segNum): ?>
                                                <?php if (isset($segmentsByNum[$segNum])): ?>
                                                    <span class="segment-pill"
                                                        style="font-size: 0.75em; padding: 3px 8px; background: rgba(255,255,255,0.1); border-radius: 4px;"
                                                        title="<?= htmlspecialchars($segmentsByNum[$segNum]['bref']); ?>">
                                                        <?= htmlspecialchars($segmentsByNum[$segNum]['title']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <button type="button" class="btn-listen-card btn-play-episode" style="margin-top: 15px;"
                                    data-stream="<?= htmlspecialchars($ep['url']); ?>"
                                    data-title="<?= htmlspecialchars($ep['title']); ?>"
                                    data-episode="Show #<?= $ep['num_string']; ?>">
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