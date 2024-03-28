<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Contact Form Submission</title>
    <style>
        /* Add styles for email layout here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        li {
            margin-bottom: 10px;
        }
        p {
            margin-bottom: 20px;
        }
        p.signature {
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact Form Submission</h1>

        <p>You have received a new contact form submission:</p>

        <ul>
            <li><strong>Name:</strong> {{ $contact['full_name'] }}</li>
            <li><strong>Email:</strong> {{ $contact['email'] }}</li>
            @if (isset($contact['service_name']))
            <li><strong>Service Name:</strong> {{ $contact['service_name'] }}</li>
            @endif
            @if (isset($contact['phone_number']))
                <li><strong>Phone Number:</strong> {{ $contact['phone_number'] }}</li>
            @endif
            <li><strong>Message:</strong></li>
            <p>{{ $contact['message'] }}</p>
        </ul>

        <p class="signature">Thanks,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>
