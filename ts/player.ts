declare const STREAM_URL: string;

document.addEventListener('DOMContentLoaded', (): void => {
    // Media Player DOM elements
    const playToggle = document.getElementById('playToggle') as HTMLButtonElement | null;
    const playIcon = document.getElementById('playIcon') as SVGElement | HTMLElement | null;
    const pauseIcon = document.getElementById('pauseIcon') as SVGElement | HTMLElement | null;
    const volumeSlider = document.getElementById('volumeSlider') as HTMLInputElement | null;
    const heroListenBtn = document.getElementById('heroListenBtn') as HTMLButtonElement | null;
    
    // UI Metadata elements
    const playerShowTitle = document.getElementById('playerShowTitle') as HTMLElement | null;
    const playerSubTitle = document.getElementById('playerSubTitle') as HTMLElement | null;
    const playerLiveBadge = document.getElementById('playerLiveBadge') as HTMLElement | null;

    // Timeline elements
    const playerTimelineContainer = document.getElementById('playerTimelineContainer') as HTMLElement | null;
    const currentTimeStamp = document.getElementById('currentTimeStamp') as HTMLElement | null;
    const durationTimeStamp = document.getElementById('durationTimeStamp') as HTMLElement | null;
    const timelineBarWrapper = document.getElementById('timelineBarWrapper') as HTMLElement | null;
    const timelineProgress = document.getElementById('timelineProgress') as HTMLElement | null;

    // Track state details
    let audioStream: HTMLAudioElement | null = null;
    let isPlaying = false;
    let activeSourceUrl: string = STREAM_URL; // Defaults to live feed

    // Helper to format seconds to M:SS
    function formatTime(secs: number): string {
        if (isNaN(secs) || !isFinite(secs)) return "0:00";
        const minutes = Math.floor(secs / 60);
        const seconds = Math.floor(secs % 60);
        return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    }

    // Helper to update progress bar layout dynamically
    function setupAudioEvents(audio: HTMLAudioElement, isLive: boolean): void {
        if (isLive) {
            if (playerTimelineContainer) playerTimelineContainer.style.display = 'none';
            return;
        }

        // Show the timeline for catch-up content
        if (playerTimelineContainer) playerTimelineContainer.style.display = 'flex';

        // Listen for metadata loading to retrieve the audio length
        audio.addEventListener('loadedmetadata', () => {
            if (durationTimeStamp) {
                durationTimeStamp.textContent = formatTime(audio.duration);
            }
        });

        // Track and animate progress
        audio.addEventListener('timeupdate', () => {
            if (audio.duration) {
                const percentage = (audio.currentTime / audio.duration) * 100;
                if (timelineProgress) {
                    timelineProgress.style.width = `${percentage}%`;
                }
                if (currentTimeStamp) {
                    currentTimeStamp.textContent = formatTime(audio.currentTime);
                }
            }
        });
    }

    // Interactive scrubbing/clicking on timeline to jump to a time
    if (timelineBarWrapper) {
        timelineBarWrapper.addEventListener('click', (e: MouseEvent): void => {
            if (audioStream && !activeSourceUrl.includes(STREAM_URL)) {
                const rect = timelineBarWrapper.getBoundingClientRect();
                const clickX = e.clientX - rect.left;
                const width = rect.width;
                const percentage = clickX / width;
                
                audioStream.currentTime = percentage * audioStream.duration;
            }
        });
    }

    // Helper to update player badge text and timeline visibility
    function updatePlayerMeta(title: string, subtitle: string, badge: string, isLive: boolean): void {
        if (playerShowTitle) playerShowTitle.textContent = title;
        if (playerSubTitle) playerSubTitle.textContent = subtitle;
        if (playerLiveBadge) {
            playerLiveBadge.textContent = badge;
            const dot = playerLiveBadge.previousElementSibling as HTMLElement | null;
            if (dot) {
                dot.style.backgroundColor = isLive ? '#4CAF50' : '#FF7A00';
            }
        }
    }

    // Loads a source URL and controls the hardware playback
    function loadAndPlay(sourceUrl: string, isLive: boolean): void {
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
            .then((): void => {
                isPlaying = true;
                if (playIcon) playIcon.style.display = 'none';
                if (pauseIcon) pauseIcon.style.display = 'block';
            })
            .catch((err: Error): void => {
                console.error("Audio engine failed to load stream:", err);
            });
    }

    // Toggle logic for the main play/pause button
    function handlePlayToggle(): void {
        const isLive = activeSourceUrl === STREAM_URL;
        if (!isPlaying) {
            loadAndPlay(activeSourceUrl, isLive);
        } else {
            if (audioStream) {
                audioStream.pause();
                audioStream = null;
            }
            isPlaying = false;
            if (playIcon) playIcon.style.display = 'block';
            if (pauseIcon) pauseIcon.style.display = 'none';
        }
    }

    if (playToggle) {
        playToggle.addEventListener('click', handlePlayToggle);
    }

    // Listen for clicks on catch-up episodes
    const episodeButtons = document.querySelectorAll('.btn-play-episode');
    episodeButtons.forEach((btnElement) => {
        const btn = btnElement as HTMLButtonElement;
        btn.addEventListener('click', (): void => {
            const streamUrl = btn.getAttribute('data-stream');
            const epTitle = btn.getAttribute('data-title') || "Catch-up Episode";
            const epNumber = btn.getAttribute('data-episode') || "Archive Playback";

            if (streamUrl) {
                updatePlayerMeta(epTitle, epNumber, "Catch-Up", false);
                loadAndPlay(streamUrl, false);

                const playerBar = document.querySelector('.bottom-player') as HTMLElement | null;
                if (playerBar) {
                    playerBar.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });

    // Reset back to Live Feed
    if (heroListenBtn) {
        heroListenBtn.addEventListener('click', (): void => {
            const defaultShowName = "Another Tangent with Toby";
            updatePlayerMeta(defaultShowName, "Live on Nerve Radio", "Live Stream", true);
            loadAndPlay(STREAM_URL, true);

            const playerBar = document.querySelector('.bottom-player') as HTMLElement | null;
            if (playerBar) {
                playerBar.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // Volume controls
    if (volumeSlider) {
        volumeSlider.addEventListener('input', (e: Event): void => {
            const target = e.target as HTMLInputElement;
            if (audioStream) {
                audioStream.volume = parseFloat(target.value);
            }
        });
    }
});