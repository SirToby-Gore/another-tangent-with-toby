document.addEventListener('DOMContentLoaded', function () {
    // Media Player DOM elements
    var playToggle = document.getElementById('playToggle');
    var playIcon = document.getElementById('playIcon');
    var pauseIcon = document.getElementById('pauseIcon');
    var volumeSlider = document.getElementById('volumeSlider');
    var heroListenBtn = document.getElementById('heroListenBtn');
    // UI Metadata elements
    var playerShowTitle = document.getElementById('playerShowTitle');
    var playerSubTitle = document.getElementById('playerSubTitle');
    var playerLiveBadge = document.getElementById('playerLiveBadge');
    // Timeline elements
    var playerTimelineContainer = document.getElementById('playerTimelineContainer');
    var currentTimeStamp = document.getElementById('currentTimeStamp');
    var durationTimeStamp = document.getElementById('durationTimeStamp');
    var timelineBarWrapper = document.getElementById('timelineBarWrapper');
    var timelineProgress = document.getElementById('timelineProgress');
    // Track state details
    var audioStream = null;
    var isPlaying = false;
    var activeSourceUrl = STREAM_URL; // Defaults to live feed
    // Helper to format seconds to M:SS
    function formatTime(secs) {
        if (isNaN(secs) || !isFinite(secs))
            return "0:00";
        var minutes = Math.floor(secs / 60);
        var seconds = Math.floor(secs % 60);
        return "".concat(minutes, ":").concat(seconds < 10 ? '0' : '').concat(seconds);
    }
    // Helper to update progress bar layout dynamically
    function setupAudioEvents(audio, isLive) {
        if (isLive) {
            if (playerTimelineContainer)
                playerTimelineContainer.style.display = 'none';
            return;
        }
        // Show the timeline for catch-up content
        if (playerTimelineContainer)
            playerTimelineContainer.style.display = 'flex';
        // Listen for metadata loading to retrieve the audio length
        audio.addEventListener('loadedmetadata', function () {
            if (durationTimeStamp) {
                durationTimeStamp.textContent = formatTime(audio.duration);
            }
        });
        // Track and animate progress
        audio.addEventListener('timeupdate', function () {
            if (audio.duration) {
                var percentage = (audio.currentTime / audio.duration) * 100;
                if (timelineProgress) {
                    timelineProgress.style.width = "".concat(percentage, "%");
                }
                if (currentTimeStamp) {
                    currentTimeStamp.textContent = formatTime(audio.currentTime);
                }
            }
        });
    }
    // Interactive scrubbing/clicking on timeline to jump to a time
    if (timelineBarWrapper) {
        timelineBarWrapper.addEventListener('click', function (e) {
            if (audioStream && !activeSourceUrl.includes(STREAM_URL)) {
                var rect = timelineBarWrapper.getBoundingClientRect();
                var clickX = e.clientX - rect.left;
                var width = rect.width;
                var percentage = clickX / width;
                audioStream.currentTime = percentage * audioStream.duration;
            }
        });
    }
    // Helper to update player badge text and timeline visibility
    function updatePlayerMeta(title, subtitle, badge, isLive) {
        if (playerShowTitle)
            playerShowTitle.textContent = title;
        if (playerSubTitle)
            playerSubTitle.textContent = subtitle;
        if (playerLiveBadge) {
            playerLiveBadge.textContent = badge;
            var dot = playerLiveBadge.previousElementSibling;
            if (dot) {
                dot.style.backgroundColor = isLive ? '#4CAF50' : '#FF7A00';
            }
        }
    }
    // Loads a source URL and controls the hardware playback
    function loadAndPlay(sourceUrl, isLive) {
        if (audioStream) {
            audioStream.pause();
            audioStream = null;
        }
        activeSourceUrl = sourceUrl;
        audioStream = new Audio(activeSourceUrl);
        if (volumeSlider) {
            audioStream.volume = parseFloat(volumeSlider.value);
        }
        // Bind progress visualizer events
        setupAudioEvents(audioStream, isLive);
        audioStream.play()
            .then(function () {
            isPlaying = true;
            if (playIcon)
                playIcon.style.display = 'none';
            if (pauseIcon)
                pauseIcon.style.display = 'block';
        })
            .catch(function (err) {
            console.error("Audio engine failed to load stream:", err);
        });
    }
    // Toggle logic for the main play/pause button
    function handlePlayToggle() {
        var isLive = activeSourceUrl === STREAM_URL;
        if (!isPlaying) {
            loadAndPlay(activeSourceUrl, isLive);
        }
        else {
            if (audioStream) {
                audioStream.pause();
                audioStream = null;
            }
            isPlaying = false;
            if (playIcon)
                playIcon.style.display = 'block';
            if (pauseIcon)
                pauseIcon.style.display = 'none';
        }
    }
    if (playToggle) {
        playToggle.addEventListener('click', handlePlayToggle);
    }
    // Listen for clicks on catch-up episodes
    var episodeButtons = document.querySelectorAll('.btn-play-episode');
    episodeButtons.forEach(function (btnElement) {
        var btn = btnElement;
        btn.addEventListener('click', function () {
            var streamUrl = btn.getAttribute('data-stream');
            var epTitle = btn.getAttribute('data-title') || "Catch-up Episode";
            var epNumber = btn.getAttribute('data-episode') || "Archive Playback";
            if (streamUrl) {
                updatePlayerMeta(epTitle, epNumber, "Catch-Up", false);
                loadAndPlay(streamUrl, false);
                var playerBar = document.querySelector('.bottom-player');
                if (playerBar) {
                    playerBar.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });
    // Reset back to Live Feed
    if (heroListenBtn) {
        heroListenBtn.addEventListener('click', function () {
            var defaultShowName = "Another Tangent with Toby";
            updatePlayerMeta(defaultShowName, "Live on Nerve Radio", "Live Stream", true);
            loadAndPlay(STREAM_URL, true);
            var playerBar = document.querySelector('.bottom-player');
            if (playerBar) {
                playerBar.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
    // Volume controls
    if (volumeSlider) {
        volumeSlider.addEventListener('input', function (e) {
            var target = e.target;
            if (audioStream) {
                audioStream.volume = parseFloat(target.value);
            }
        });
    }
});
