<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        p {
            color: #555;
        }
        .event-details {
            border-top: 1px solid #eee;
            margin-top: 20px;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Event Reminder: {{ $event->title }}</h2>
        <p>Dear User,</p>
        <p>This is a reminder for the upcoming event:</p>

        <div class="event-details">
            <p><strong>Title:</strong> {{ $event->title }}</p>
            <p><strong>Description:</strong> {{ $event->description }}</p>
            <p><strong>Start Time:</strong> {{ $event->start_time }}</p>
            <p><strong>End Time:</strong> {{ $event->end_time }}</p>
        </div>

        <p>Best regards,<br>Your Event Reminder App</p>
    </div>
</body>
</html>
