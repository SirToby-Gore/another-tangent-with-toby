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
                <div class="card">
                    <div class="number-badge"><?php echo $seg['num']; ?></div>
                    <h3><?php echo htmlspecialchars($seg['title']); ?></h3>
                    <p><?php echo htmlspecialchars($seg['desc']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>