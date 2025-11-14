<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .certificate {
            background: white;
            width: 800px;
            height: 600px;
            padding: 60px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .certificate::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #002147, #4F46E5, #7C3AED, #EC4899);
        }

        .certificate::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #002147, #4F46E5, #7C3AED, #EC4899);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #002147;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: #6B7280;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .title {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            color: #002147;
            margin: 30px 0 20px;
            text-align: center;
        }

        .content {
            text-align: center;
            margin-bottom: 40px;
        }

        .awarded-text {
            font-size: 18px;
            color: #374151;
            margin-bottom: 20px;
        }

        .student-name {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            color: #002147;
            margin: 20px 0;
            text-decoration: underline;
            text-decoration-color: #E5E7EB;
            text-underline-offset: 8px;
        }

        .course-info {
            margin: 30px 0;
        }

        .course-title {
            font-size: 24px;
            font-weight: 600;
            color: #002147;
            margin-bottom: 10px;
        }

        .course-details {
            color: #6B7280;
            font-size: 16px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: end;
            margin-top: 60px;
            border-top: 2px solid #F3F4F6;
            padding-top: 30px;
        }

        .date-section,
        .signature-section {
            text-align: center;
        }

        .date-section h4,
        .signature-section h4 {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .date,
        .signature {
            font-weight: 600;
            color: #002147;
            font-size: 16px;
            border-bottom: 2px solid #E5E7EB;
            padding-bottom: 5px;
            min-width: 150px;
            display: inline-block;
        }

        .certificate-number {
            position: absolute;
            bottom: 20px;
            right: 60px;
            font-size: 12px;
            color: #9CA3AF;
            font-family: 'Courier New', monospace;
        }

        .verification-link {
            position: absolute;
            bottom: 20px;
            left: 60px;
            font-size: 12px;
            color: #6366F1;
            text-decoration: none;
        }

        .seal {
            position: absolute;
            top: 40px;
            right: 40px;
            width: 80px;
            height: 80px;
            border: 4px solid #002147;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            font-size: 12px;
            font-weight: 700;
            color: #002147;
            text-align: center;
            line-height: 1.2;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .certificate {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <div class="certificate">
        <div class="seal">
            BLI<br>ACADEMY
        </div>

        <div class="header">
            <div class="logo">BLI Academy</div>
            <div class="subtitle">Certificate of Completion</div>
        </div>

        <div class="title">Certificate</div>

        <div class="content">
            <div class="awarded-text">This is to certify that</div>

            <div class="student-name">{{ $certificate->certificate_data['student_name'] }}</div>

            <div class="awarded-text">has successfully completed the course</div>

            <div class="course-info">
                <div class="course-title">{{ $certificate->certificate_data['course_title'] }}</div>
                <div class="course-details">
                    Duration: {{ $certificate->certificate_data['course_duration'] }} •
                    Instructor: {{ $certificate->certificate_data['instructor_name'] }}
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="date-section">
                <h4>Date of Completion</h4>
                <div class="date">{{ $certificate->certificate_data['completion_date'] }}</div>
            </div>

            <div class="signature-section">
                <h4>Authorized Signature</h4>
                <div class="signature">BLI Academy</div>
            </div>
        </div>

        <div class="verification-link">
            Verify: {{ route('certificates.verify', $certificate->certificate_number) }}
        </div>

        <div class="certificate-number">
            {{ $certificate->certificate_number }}
        </div>
    </div>
</body>

</html>