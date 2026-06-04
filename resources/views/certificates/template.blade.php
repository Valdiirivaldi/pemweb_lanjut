<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion</title>
    <style>
        @page {
            size: 330mm 210mm landscape;
            margin: 8mm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            background: #eef1f5;
        }

        .certificate {
            width: 100%;
            height: 194mm;
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }

        /* ── Background Decorative Bars ── */
        .bar-top {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 10mm;
            background: #1e3c72;
        }

        .bar-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 10mm;
            background: #1e3c72;
        }

        .bar-left {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 3mm;
            background: #2a5298;
        }

        .bar-right {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 3mm;
            background: #2a5298;
        }

        /* ── Thin Gold Accent Lines ── */
        .accent-top {
            position: absolute;
            top: 10mm;
            left: 3mm;
            right: 3mm;
            height: 0.6mm;
            background: #c9a84c;
        }

        .accent-bottom {
            position: absolute;
            bottom: 10mm;
            left: 3mm;
            right: 3mm;
            height: 0.6mm;
            background: #c9a84c;
        }

        /* ── Inner Border ── */
        .inner-border {
            position: absolute;
            top: 14mm;
            left: 7mm;
            right: 7mm;
            bottom: 14mm;
            border: 1.5px solid #d4dce8;
        }

        /* ── Content ── */
        .content {
            position: absolute;
            top: 18mm;
            left: 14mm;
            right: 14mm;
            bottom: 18mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .seal {
            width: 22mm;
            height: 22mm;
            background: #1e3c72;
            color: #ffffff;
            font-size: 10mm;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 6mm;
            line-height: 1;
        }

        .seal-text {
            font-size: 2.5mm;
            letter-spacing: 0.5mm;
        }

        .title-premium {
            font-size: 3mm;
            letter-spacing: 2mm;
            text-transform: uppercase;
            color: #c9a84c;
            font-weight: 700;
            margin-bottom: 1mm;
        }

        .title-main {
            font-size: 7mm;
            font-weight: 800;
            color: #1e3c72;
            letter-spacing: 0.5mm;
            margin-bottom: 3mm;
            line-height: 1.2;
        }

        .title-divider {
            width: 40mm;
            height: 0.5mm;
            background: #c9a84c;
            margin: 0 auto 4mm;
        }

        .body-text {
            font-size: 3.2mm;
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 1mm;
        }

        .student-name {
            font-size: 7mm;
            font-weight: 800;
            color: #1e3c72;
            margin: 2mm 0;
            letter-spacing: 0.3mm;
            line-height: 1.3;
        }

        .student-name-underline {
            width: 60mm;
            height: 0.3mm;
            background: #c9a84c;
            margin: 0 auto 3mm;
        }

        .detail-line {
            font-size: 3mm;
            color: #4a5568;
            line-height: 1.7;
        }

        .detail-line strong {
            color: #1e3c72;
        }

        .score-box {
            display: inline-block;
            background: #1e3c72;
            color: #ffffff;
            padding: 2mm 10mm;
            font-weight: 700;
            font-size: 5mm;
            margin-top: 3mm;
            letter-spacing: 0.5mm;
        }

        /* ── Footer ── */
        .footer {
            position: absolute;
            bottom: 18mm;
            left: 14mm;
            right: 14mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer .signature-block {
            text-align: center;
            width: 60mm;
        }

        .footer .signature-line {
            width: 50mm;
            height: 0.3mm;
            background: #cbd5e0;
            margin: 0 auto 2mm;
        }

        .footer .signature-label {
            font-size: 2.5mm;
            color: #a0aec0;
            font-weight: 600;
            letter-spacing: 0.3mm;
            text-transform: uppercase;
        }

        .footer .date-block {
            text-align: center;
        }

        .footer .date-block .date-label {
            font-size: 2.5mm;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 0.5mm;
            margin-bottom: 0.5mm;
        }

        .footer .date-block .date-value {
            font-size: 3mm;
            color: #1e3c72;
            font-weight: 600;
        }

        /* ── Badge ── */
        .badge-right {
            position: absolute;
            top: 22mm;
            right: 12mm;
            text-align: center;
        }

        .badge-right .badge-box {
            width: 18mm;
            height: 18mm;
            border: 1.5px solid #c9a84c;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #1e3c72;
        }

        .badge-right .badge-box .badge-icon {
            font-size: 6mm;
            font-weight: 400;
            margin-bottom: 1mm;
        }

        .badge-right .badge-box .badge-text {
            font-size: 1.8mm;
            font-weight: 700;
            letter-spacing: 0.3mm;
            text-transform: uppercase;
            color: #c9a84c;
        }

        .badge-right .badge-line {
            width: 1.5px;
            height: 8mm;
            background: #c9a84c;
            margin: 2mm auto 0;
        }

        .badge-right .badge-sub {
            font-size: 1.8mm;
            color: #a0aec0;
            margin-top: 1mm;
            text-transform: uppercase;
            letter-spacing: 0.3mm;
        }
    </style>
</head>
<body>
    <div class="certificate">
        {{-- Background Bars --}}
        <div class="bar-top"></div>
        <div class="bar-bottom"></div>
        <div class="bar-left"></div>
        <div class="bar-right"></div>
        <div class="accent-top"></div>
        <div class="accent-bottom"></div>
        <div class="inner-border"></div>

        {{-- Right Badge --}}
        <div class="badge-right">
            <div class="badge-box">
                <div class="badge-icon">&#9733;</div>
                <div class="badge-text">{{ $score }}%</div>
            </div>
            <div class="badge-line"></div>
            <div class="badge-sub">Score</div>
        </div>

        {{-- Main Content --}}
        <div class="content">
            <div class="seal">
                <div>
                    <div style="font-size: 4mm; letter-spacing: 0.5mm; font-weight: 800;">E</div>
                    <div class="seal-text">EDURIA</div>
                </div>
            </div>

            <div class="title-premium">Certificate of Completion</div>

            <div class="title-main">{{ $courseTitle }}</div>

            <div class="title-divider"></div>

            <div class="body-text">This certificate is proudly presented to</div>

            <div class="student-name">{{ $studentName }}</div>

            <div class="student-name-underline"></div>

            <div class="detail-line">for successfully completing the quiz</div>
            <div class="detail-line"><strong>{{ $quizTitle }}</strong></div>

            <div class="score-box">{{ $score }}%</div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-label">Tentor</div>
            </div>
            <div class="date-block">
                <div class="date-label">Date Issued</div>
                <div class="date-value">{{ $date }}</div>
            </div>
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-label">Administrator</div>
            </div>
        </div>
    </div>
</body>
</html>
