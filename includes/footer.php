<!-- Premium Global Footer with Social Channels -->
<footer class="global-footer">
    <div class="container footer-grid">
        <div class="footer-about">
            <h3>Another <span>Tangent</span></h3>
            <p>Broadcast live from <?php echo $studioLoc; ?>. Connecting Bournemouth's vibrant student community one
                tangent at a time.</p>
        </div>

        <div class="footer-socials">
            <h4>Join the Detour</h4>
            <div class="social-links">
                <a href="<?php echo htmlspecialchars($instagramLink); ?>" target="_blank" class="social-badge"
                    aria-label="Instagram">
                    <span>Instagram</span>
                </a>
                <a href="<?php echo htmlspecialchars($youtubeLink); ?>" target="_blank" class="social-badge"
                    aria-label="YouTube">
                    <span>YouTube</span>
                </a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Nerve Radio &amp; Toby. Built for Bournemouth University &amp; AUB
                students.</p>
        </div>
    </div>
</footer>

<!-- Global Footer / Scripts -->
<script>
    // Inject PHP variables safely into the global JS context
    const STREAM_URL = "<?php echo $streamUrl; ?>";
</script>
<script src="js/player.js"></script>
</body>

</html>