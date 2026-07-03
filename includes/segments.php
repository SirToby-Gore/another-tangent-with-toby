<!-- Segments Showcase Section -->
<section class="segment-section" id="segments">
    <div class="container">
        <div class="section-header">
            <h2>Our Favourite Segments</h2>
            <p>We keep the show fast, unpredictable, and exciting by rotating these legendary games and community
                features.</p>
        </div>

        <div class="grid">
            <?php foreach ($segments as $seg): ?>
                <button type="button" class="card segment-trigger-card"
                    popovertarget="popover-seg-<?= $seg['num']; ?>">
                    <div class="number-badge"><?= $seg['num']; ?></div>
                    <h3><?= htmlspecialchars($seg['title']); ?></h3>
                    <p class="bref-text">
                        <?= htmlspecialchars($seg['bref']) ?>
                    </p>
                    <span class="tap-hint">Tap to expand rules &rarr;</span>
                </button>

                <div id="popover-seg-<?= $seg['num']; ?>" popover class="segment-popover-modal">
                    <div class="popover-header">
                        <div class="number-badge"><?= $seg['num']; ?></div>
                        <h2><?= htmlspecialchars($seg['title']); ?></h2>
                        <button type="button" class="btn-close-popover"
                            popovertarget="popover-seg-<?= $seg['num']; ?>" popovertargetaction="hide">
                            &times;
                        </button>
                    </div>
                    <div class="popover-body">
                        <h4>Segment Mechanics &amp; Studio Rules:</h4>
                        <p><?= htmlspecialchars($seg['desc']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>