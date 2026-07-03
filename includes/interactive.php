<?php
$successMessage = "";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['submit'])) {
    // 2. Extract database parameters using your exact lowercase .env keys
    $db_host = $_ENV['hostname'];
    $db_user = $_ENV['username'];
    $db_pass = $_ENV['password'];
    $db_name = $_ENV['database'];

    // 3. Establish the MySQLi Connection with the database explicitly chosen
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        $successMessage = "Database connection error. Please try again shortly!";
    } else {
        // 4. Capture and sanitize incoming form data
        $senderName = filter_input(INPUT_POST, 'sender_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $contactDetail = filter_input(INPUT_POST, 'contact_details', FILTER_SANITIZE_SPECIAL_CHARS);
        $submissionType = filter_input(INPUT_POST, 'submission_type', FILTER_SANITIZE_SPECIAL_CHARS);
        $messageContent = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

        // 5. Prepare the statement with your exact table column names
        $stmt = $conn->prepare("
            INSERT INTO `Submissions` (
                `sender_name`,
                `contact_detail`,
                `submission_type`,
                `message_content`
            ) VALUES (?, ?, ?, ?)
        ");

        if ($stmt) {
            // 6. Bind parameters safely ("ssss" means 4 strings)
            $stmt->bind_param("ssss", $senderName, $contactDetail, $submissionType, $messageContent);

            // 7. Execute and verify the transaction
            if ($stmt->execute()) {
                $successMessage = "Awesome, " . htmlspecialchars($senderName) . "! Your data reached Toby's dashboard screen!";
            } else {
                $successMessage = "Something went wrong saving your message. Please try again!";
            }
            $stmt->close();
        } else {
            // This is where your code was failing because no database was selected to prepare the query
            $successMessage = "Form preparation error: " . htmlspecialchars($conn->error);
        }
        $conn->close();
    }
}
?>
<!-- Interactive Studio Hub -->
<section class="interactive-section" id="hub">
    <div class="container two-column">

        <!-- Left Side: Interactive Contact Info -->
        <div class="studio-info">
            <h2>Interact with the Show</h2>
            <p>Have an idea for a hypothetical argument, a recipe to share, or want to send a shout-out? Reach to the
                show</p>

            <div class="contact-list">
                <div class="contact-item">
                    <div class="icon-box">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.884.511z" />
                        </svg>
                    </div>
                    <div class="detail">
                        <span>Direct Dial-In</span>
                        <strong><?= htmlspecialchars($studioPhone); ?></strong>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="icon-box">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.758 2.855L15 11.114V5.383zm-.03 5.672-5.48-3.29-1.49 1.033a1 1 0 0 1-1.11 0L3.51 7.765l-5.48 3.29a1 1 0 0 0 .03 1.945h13.92a1 1 0 0 0 .03-1.945zM1 5.383v5.73l4.758-2.876L1 5.383z" />
                        </svg>
                    </div>
                    <div class="detail">
                        <span>Email the Show</span>
                        <strong><?= htmlspecialchars($emailAddr); ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Submission Form -->
        <div class="submission-form">
            <h3>Submit to Toby's Dashboard</h3>
            <p class="desc">Instantly contribute to our next set of tangents or nominate someone cool!</p>

            <?php if ($successMessage !== ""): ?>
                <div class="alert-success">
                    <?= $successMessage; ?>
                </div>
            <?php else: ?>

                <form method="POST" action="./?submit">
                    <div class="form-group">
                        <label for="submission_type">Select Segment</label>
                        <select id="submission_type" name="submission_type" required>
                            <option value="" disabled selected>-- Select a Segment Topic --</option>
                            <?php foreach ($segments as $seg): ?>
                                <?php if (isset($seg['submission'])): ?>
                                    <option value="<?= htmlspecialchars($seg['submission']['value']); ?>">
                                        <?= htmlspecialchars($seg['submission']['label']); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sender_name">Your Name</label>
                        <input type="text" id="sender_name" name="sender_name" placeholder="E.g., Jamie from Poole"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="contact_details">How To Contact You (Optional)</label>
                        <input type="text" id="contact_details" name="contact_details" placeholder="07123 456789">
                    </div>

                    <div class="form-group">
                        <label for="content">Your Tangent Idea</label>
                        <textarea id="content" name="content" placeholder="Type your ideas, stories, or shout-outs here..."
                            required></textarea>
                    </div>

                    <button type="submit" class="btn">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z" />
                        </svg>
                        Send to Studio
                    </button>
                </form>
            <?php endif; ?>
        </div>

    </div>
</section>