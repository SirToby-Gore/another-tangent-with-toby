<!-- Fixed Bottom Audio Player -->
<div class="bottom-player">
    <div class="container player-wrapper"> <!-- Keeps everything in a horizontal line -->
        
        <div class="show-info">
            <div class="logo-box">T</div>
            <div class="text">
                <h4 id="playerShowTitle">Another Tangent with Toby</h4>
                <span id="playerSubTitle">Live on Nerve Radio</span>
            </div>
        </div>

        <!-- Dynamic Playbar Container for Catch-up (Hidden by default) -->
        <div class="player-timeline-container" id="playerTimelineContainer" style="display: none;">
            <span class="time-stamp" id="currentTimeStamp">0:00</span>
            <div class="timeline-bar-wrapper" id="timelineBarWrapper">
                <div class="timeline-progress" id="timelineProgress"></div>
            </div>
            <span class="time-stamp" id="durationTimeStamp">0:00</span>
        </div>

        <div class="controls"> <!-- Encloses both the badge AND the button -->
            <div class="status-badge">
                <span class="dot"></span>
                <span id="playerLiveBadge">Live Stream</span>
            </div>
            
            <button class="play-btn" id="playToggle" aria-label="Play Live Stream">
                <!-- Play Icon -->
                <svg id="playIcon" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11.596 8.697l-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
                </svg>
                <!-- Pause Icon (hidden by default) -->
                <svg id="pauseIcon" fill="currentColor" viewBox="0 0 16 16" style="display:none;">
                    <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z" />
                </svg>
            </button>
        </div> <!-- Closes .controls -->

        <div class="volume-box"> <!-- Stays inside the .player-wrapper flexbox -->
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11.536 14.01A8.473 8.473 0 0 0 14 8c0-2.34-.94-4.46-2.464-6.01a.5.5 0 0 0-.707.707C12.181 4.093 13 5.952 13 8c0 2.048-.819 3.907-2.171 5.303a.5.5 0 0 0 .707.707z" />
                <path d="M3.5 6.5A.5.5 0 0 1 4 6h1a.5.5 0 0 1 .474.341L7.15 11.23a.5.5 0 0 1-.812.48L4.35 9.5H4a.5.5 0 0 1-.5-.5v-2z" />
            </svg>
            <input type="range" id="volumeSlider" min="0" max="1" step="0.1" value="0.8" aria-label="Volume Control">
        </div>

    </div> <!-- Closes .player-wrapper -->
</div> <!-- Closes .bottom-player -->