<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #002147 0%, #003875 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: #ffffff; margin: 0; font-size: 24px;">New Contact Form Submission</h1>
    </div>

    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">You have received a new message from the contact form:</p>

        <div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <p style="margin: 0 0 10px 0;"><strong style="color: #002147;">Name:</strong> {{ $name }}</p>
            <p style="margin: 0 0 10px 0;"><strong style="color: #002147;">Email:</strong> <a href="mailto:{{ $email }}" style="color: #00a651;">{{ $email }}</a></p>
        </div>

        <div style="background: #f9fafb; padding: 20px; border-radius: 8px; border-left: 4px solid #00a651;">
            <p style="margin: 0 0 10px 0;"><strong style="color: #002147;">Message:</strong></p>
            <p style="margin: 0; white-space: pre-wrap;">{{ $message }}</p>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center;">
            <p style="font-size: 14px; color: #6b7280; margin: 0;">
                This message was sent from the Beacon Leadership Institute contact form.
            </p>
        </div>
    </div>
</body>
</html>
