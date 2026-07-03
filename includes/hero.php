<!-- Main Hero Banner -->
<main class="hero">
    <div class="container">
        <div class="tangent-tag">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z" />
                <path
                    d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A5.978 5.978 0 0 1 8 2c2.036 0 3.858 1.015 4.966 2.574a.5.5 0 1 1-.813.583A4.978 4.978 0 0 0 8 3zm0 10c1.552 0 2.94-.707 3.857-1.818a.5.5 0 1 1 .771.636A5.978 5.978 0 0 1 8 14c-2.036 0-3.858-1.015-4.966-2.574a.5.5 0 1 1 .813-.583A4.978 4.978 0 0 0 8 13z" />
            </svg>
            LIVE WEEKLY DETOURS
        </div>
        <h1>The Show with <span>Too Many Tangents.</span></h1>
        <p class="subtitle"><?= htmlspecialchars($showSlogan); ?></p>

        <!-- Listen Now and Catch Up Action Buttons -->
        <div class="hero-actions">
            <button class="btn-action btn-listen" id="heroListenBtn">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M11.596 8.697l-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
                </svg>
                Listen Now
            </button>
            <a class="btn-action btn-catchup" href="#catch-up">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15h9.286zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1zM.8 1a.8.8 0 0 0-.8.8V3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V1.8a.8.8 0 0 0-.8-.8H.8z" />
                </svg>
                Catch Up
            </a>
        </div>

        <div class="meta-pills">
            <div class="pill">
                <svg fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                </svg>
                <?= htmlspecialchars($broadcastTime); ?>
            </div>
            <div class="pill">
                <svg fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                </svg>
                <?= htmlspecialchars($studioLoc); ?>
            </div>
            <div class="pill">
                <svg fill="currentColor" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                </svg>
                Hosted by <?= htmlspecialchars($hostName); ?>
            </div>
        </div>
    </div>
</main>