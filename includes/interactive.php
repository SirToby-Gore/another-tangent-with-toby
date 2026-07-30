<?php
$dbError = false;
$successMessage = "";
$mailMessage = "";
$mailStatus = "";

$senderName = "";
$contactDetail = "";
$submissionType = "";
$messageContent = "";

$subscriberName = "";
$email = "";

$db_host = $_ENV['hostname'] ?? $_SERVER['hostname'] ?? null;
$db_user = $_ENV['username'] ?? $_SERVER['username'] ?? null;
$db_pass = $_ENV['password'] ?? $_SERVER['password'] ?? null;
$db_name = $_ENV['database'] ?? $_SERVER['database'] ?? null;

$conn = null;

if ($db_host && $db_user && $db_name) {
    try {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    } catch (Throwable $e) {
        $dbError = true;
        $conn = null;
    }
} else {
    $dbError = true;
}

if ($conn && !$conn->connect_error) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['submit'])) {
        $senderName = filter_input(INPUT_POST, 'sender_name', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $contactDetail = filter_input(INPUT_POST, 'contact_details', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $submissionType = filter_input(INPUT_POST, 'submission_type', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $messageContent = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

        try {
            $stmt = $conn->prepare("
                INSERT INTO `Submissions` (
                    `sender_name`,
                    `contact_detail`,
                    `submission_type`,
                    `message_content`
                ) VALUES (?, ?, ?, ?)
            ");

            if ($stmt) {
                $stmt->bind_param("ssss", $senderName, $contactDetail, $submissionType, $messageContent);
                if ($stmt->execute()) {
                    $successMessage = "Awesome, " . htmlspecialchars($senderName) . "! Your data reached Toby's dashboard screen!";
                    $senderName = "";
                    $contactDetail = "";
                    $submissionType = "";
                    $messageContent = "";
                } else {
                    $successMessage = "Error: Something went wrong saving your message. Please try again.";
                }
                $stmt->close();
            }
        } catch (Throwable $e) {
            $successMessage = "Error: Something went wrong saving your message. Please try again.";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_mailing_list'])) {
        $subscriberName = filter_input(INPUT_POST, 'subscriber_name', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?? '';
        $action = $_POST['action_type'] ?? 'subscribe';

        if (!$email) {
            $email = $_POST['email'] ?? '';
            $mailMessage = "Please enter a valid email address.";
            $mailStatus = "error";
        } else {
            try {
                if ($action === 'subscribe') {
                    if (empty($subscriberName)) {
                        $mailMessage = "Please provide your name to subscribe.";
                        $mailStatus = "error";
                    } else {
                        $stmt = $conn->prepare("SELECT `id`, `status` FROM `mailing_list` WHERE `email` = ?");
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $existing = $stmt->get_result()->fetch_assoc();
                        $stmt->close();

                        if ($existing) {
                            if ($existing['status'] === 'subscribed') {
                                $mailMessage = "You are already subscribed to the mailing list!";
                                $mailStatus = "warning";
                            } else {
                                $updateStmt = $conn->prepare("UPDATE `mailing_list` SET `name` = ?, `status` = 'subscribed', `subscribed_at` = NOW() WHERE `id` = ?");
                                $updateStmt->bind_param("si", $subscriberName, $existing['id']);
                                $updateStmt->execute();
                                $updateStmt->close();

                                $mailMessage = "Welcome back, " . htmlspecialchars($subscriberName) . "! Your subscription has been reactivated.";
                                $mailStatus = "success";
                                $subscriberName = "";
                                $email = "";
                            }
                        } else {
                            $insertStmt = $conn->prepare("INSERT INTO `mailing_list` (`name`, `email`) VALUES (?, ?)");
                            $insertStmt->bind_param("ss", $subscriberName, $email);
                            if ($insertStmt->execute()) {
                                $mailMessage = "Success! Thanks for joining, " . htmlspecialchars($subscriberName) . ".";
                                $mailStatus = "success";
                                $subscriberName = "";
                                $email = "";
                            } else {
                                $mailMessage = "Error submitting mailing list data. Please try again.";
                                $mailStatus = "error";
                            }
                            $insertStmt->close();
                        }
                    }
                } elseif ($action === 'unsubscribe') {
                    $stmt = $conn->prepare("SELECT `id`, `status` FROM `mailing_list` WHERE `email` = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $existing = $stmt->get_result()->fetch_assoc();
                    $stmt->close();

                    if (!$existing || $existing['status'] === 'unsubscribed') {
                        $mailMessage = "That email is not currently on our active subscriber list.";
                        $mailStatus = "warning";
                    } else {
                        $updateStmt = $conn->prepare("UPDATE `mailing_list` SET `status` = 'unsubscribed' WHERE `id` = ?");
                        $updateStmt->bind_param("i", $existing['id']);
                        $updateStmt->execute();
                        $updateStmt->close();

                        $mailMessage = "You have been successfully unsubscribed.";
                        $mailStatus = "success";
                        $subscriberName = "";
                        $email = "";
                    }
                }
            } catch (Throwable $e) {
                $mailMessage = "Error submitting mailing list data. Please try again.";
                $mailStatus = "error";
            }
        }
    }

    $conn->close();
}
?>

<section class="interactive-section" id="hub">
    <div class="container two-column">

        <div class="studio-info">
            <h2>Interact with the Show</h2>
            <p>Have an idea for a hypothetical argument, a recipe to share, or want to send a shout-out? Reach out to
                the show!</p>

            <div class="contact-list">
                <div class="contact-item">
                    <div class="icon-box">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.884.511z" />
                        </svg>
                    </div>
                    <a class="detail" href="tel:<?= htmlspecialchars($studioPhone ?? '') ?>">
                        <span>Direct Dial-In</span>
                        <strong><?= htmlspecialchars($studioPhone ?? '') ?></strong>
                    </a>
                </div>
                <div class="contact-item">
                    <div class="icon-box">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.758 2.855L15 11.114V5.383zm-.03 5.672-5.48-3.29-1.49 1.033a1 1 0 0 1-1.11 0L3.51 7.765l-5.48 3.29a1 1 0 0 0 .03 1.945h13.92a1 1 0 0 0 .03-1.945zM1 5.383v5.73l4.758-2.876L1 5.383z" />
                        </svg>
                    </div>
                    <a class="detail" href="mailto:<?= htmlspecialchars($emailAddr ?? '') ?>">
                        <span>Email the Show</span>
                        <strong><?= htmlspecialchars($emailAddr ?? '') ?></strong>
                    </a>
                </div>
            </div>

            <div class="submission-form" style="margin-top: 30px;">
                <h3>Join the Tangent Mailing List</h3>
                <p class="desc">Get episode releases and studio announcements directly to your inbox.</p>

                <?php if ($dbError): ?>
                    <div class="alert-error" style="margin-top: 15px;">
                        Database Connection Error. Mailing list sign-ups are currently offline.
                    </div>
                <?php else: ?>

                    <?php if (!empty($mailMessage)): ?>
                        <div class="<?= $mailStatus === 'error' ? 'alert-error' : 'alert-success' ?>"
                            style="margin-bottom: 20px;">
                            <?= htmlspecialchars($mailMessage) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="./#hub">
                        <input type="hidden" name="action_mailing_list" value="1">

                        <div class="form-group">
                            <label for="subscriber_name">Your Name</label>
                            <input type="text" id="subscriber_name" name="subscriber_name"
                                value="<?= htmlspecialchars($subscriberName) ?>" placeholder="Jamie Smith" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>"
                                placeholder="example@email.com" required>
                        </div>

                        <div style="display: flex; gap: 10px; align-items: center;">
                            <button type="submit" name="action_type" value="subscribe" class="btn" style="flex: 1;">
                                Subscribe
                            </button>
                            <button type="submit" name="action_type" value="unsubscribe" class="btn"
                                style="background: rgba(255,255,255,0.1); color: #ccc;"
                                onclick="return confirm('Unsubscribe from updates?');">
                                Unsubscribe
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

        </div>

        <div class="submission-form">
            <h3>Submit to Toby's Dashboard</h3>
            <p class="desc">Instantly contribute to our next set of tangents or nominate someone cool!</p>

            <?php if ($dbError): ?>
                <div class="alert-error" style="margin-top: 15px;">
                    Database Connection Error. Submissions are currently offline. Please try again later.
                </div>
            <?php else: ?>

                <?php if (!empty($successMessage)): ?>
                    <div class="<?= str_contains(strtolower($successMessage), 'error') ? 'alert-error' : 'alert-success' ?>"
                        style="margin-bottom: 20px;">
                        <?= htmlspecialchars($successMessage) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="./?submit#hub">
                    <div class="form-group">
                        <label for="submission_type">Select Segment</label>
                        <select id="submission_type" name="submission_type" required>
                            <option value="" disabled <?= empty($submissionType) ? 'selected' : '' ?>>-- Select a Segment
                                Topic --</option>
                            <?php foreach ($segments as $seg): ?>
                                <?php if (isset($seg['submission'])): ?>
                                    <option value="<?= htmlspecialchars($seg['submission']['value']) ?>"
                                        <?= $submissionType === $seg['submission']['value'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($seg['submission']['label']) ?>
                                    </option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sender_name">Your Name</label>
                        <input type="text" id="sender_name" name="sender_name" value="<?= htmlspecialchars($senderName) ?>"
                            placeholder="Jamie Smith from Poole" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_details">How To Contact You (Optional)</label>
                        <input type="text" id="contact_details" name="contact_details"
                            value="<?= htmlspecialchars($contactDetail) ?>" placeholder="07123 456789 | example@email.com">
                    </div>

                    <div class="form-group">
                        <label for="content">Your Tangent Idea</label>
                        <textarea id="content" name="content" placeholder="Type your ideas, stories, or shout-outs here..."
                            required><?= htmlspecialchars($messageContent) ?></textarea>
                    </div>

                    <button type="submit" class="btn">
                        Send to Studio
                    </button>
                </form>
            <?php endif; ?>
        </div>

    </div>
</section>