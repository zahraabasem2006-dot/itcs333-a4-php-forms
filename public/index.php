<?php
declare(strict_types=1);

error_reporting(E_ALL);

/**
 * ITCS333 A4 starter – contact form.
 * Requirements (see README rubric):
 *  - GET: render the form
 *  - POST: validate name (required, 2-60 chars), email (valid), message (required, 10-1000 chars)
 *  - invalid POST: re-render with inline errors + repopulated fields (200)
 *  - valid POST: redirect (302/303) to a success page (PRG)
 *  - escape all output with htmlspecialchars
 */

require_once __DIR__ . '/../src/validate.php';

function escape_html(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$formData = [
    'name' => '',
    'email' => '',
    'message' => '',
];

$fieldErrors = [
    'name' => '',
    'email' => '',
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['name'] = isset($_POST['name']) && is_string($_POST['name']) ? $_POST['name'] : '';
    $formData['email'] = isset($_POST['email']) && is_string($_POST['email']) ? $_POST['email'] : '';
    $formData['message'] = isset($_POST['message']) && is_string($_POST['message']) ? $_POST['message'] : '';

    if (!validate_required($formData['name'])) {
        $fieldErrors['name'] = 'Name is required.';
    } elseif (!validate_length($formData['name'], 2, 60)) {
        $fieldErrors['name'] = 'Name must be between 2 and 60 characters.';
    }

    if (!validate_required($formData['email'])) {
        $fieldErrors['email'] = 'Email is required.';
    } elseif (!validate_email($formData['email'])) {
        $fieldErrors['email'] = 'Please enter a valid email address.';
    }

    if (!validate_required($formData['message'])) {
        $fieldErrors['message'] = 'Message is required.';
    } elseif (!validate_length($formData['message'], 10, 1000)) {
        $fieldErrors['message'] = 'Message must be between 10 and 1000 characters.';
    }

    $hasValidationErrors =
        $fieldErrors['name'] !== '' ||
        $fieldErrors['email'] !== '' ||
        $fieldErrors['message'] !== '';

    if (!$hasValidationErrors) {
        header('Location: success.php', true, 303);
        exit;
    }

    http_response_code(200);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <style>
        .error { color: #b00020; font-size: 0.9rem; display: block; margin-top: 4px; }
    </style>
</head>
<body>
    <h1>Contact Us</h1>

    <form method="post" action="index.php" novalidate>
        <div>
            <label for="name">Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= escape_html($formData['name']) ?>"
            >
            <?php if ($fieldErrors['name'] !== ''): ?>
                <span class="error"><?= escape_html($fieldErrors['name']) ?></span>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= escape_html($formData['email']) ?>"
            >
            <?php if ($fieldErrors['email'] !== ''): ?>
                <span class="error"><?= escape_html($fieldErrors['email']) ?></span>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label for="message">Message</label>
            <textarea
                id="message"
                name="message"
                rows="5"
            ><?= escape_html($formData['message']) ?></textarea>
            <?php if ($fieldErrors['message'] !== ''): ?>
                <span class="error"><?= escape_html($fieldErrors['message']) ?></span>
            <?php endif; ?>
        </div>
        <br>

        <button type="submit">Send</button>
    </form>
</body>
</html>