<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mark Sheet</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        .info-grid { display: table; width: 100%; margin-bottom: 20px; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; width: 30%; padding: 5px; font-weight: bold; }
        .info-value { display: table-cell; width: 70%; padding: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .grade-box { text-align: center; padding: 20px; margin: 20px 0; background: #f9f9f9; border: 2px solid #333; }
        .grade-box .grade { font-size: 48px; font-weight: bold; color: #333; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; }
        .signature-section { margin-top: 60px; display: table; width: 100%; }
        .signature-box { display: table-cell; width: 33%; text-align: center; }
        .signature-line { border-top: 1px solid #333; width: 80%; margin: 0 auto; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'LMS') }}</h1>
        <p>Official Mark Sheet</p>
    </div>
    
    <div class="info-grid">
        <div class="info-row">
            <span class="info-label">Student Name:</span>
            <span class="info-value">{{ $student->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Student ID:</span>
            <span class="info-value">{{ $student->student_id }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Batch:</span>
            <span class="info-value">{{ $student->batch?->name ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Course:</span>
            <span class="info-value">{{ $student->batch?->course?->name ?? 'N/A' }}</span>
        </div>
    </div>
    
    @if($type === 'single' && isset($exam))
    <h3>Exam: {{ $exam->name }}</h3>
    <table>
        <tr>
            <th>Exam Type</th>
            <td>{{ strtoupper($exam->type) }}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>{{ $exam->scheduled_at?->format('M d, Y') ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Marks Obtained</th>
            <td>{{ $result?->marks ?? 0 }}</td>
        </tr>
        <tr>
            <th>Total Marks</th>
            <td>{{ $result?->total_marks ?? $exam->total_marks }}</td>
        </tr>
        <tr>
            <th>Percentage</th>
            <td>{{ $result?->percentage ?? 0 }}%</td>
        </tr>
    </table>
    
    <div class="grade-box">
        <p>Grade Obtained</p>
        <div class="grade">{{ $result?->grade ?? 'N/A' }}</div>
    </div>
    @else
    <h3>Complete Performance Summary</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Exam Name</th>
                <th>Type</th>
                <th>Marks</th>
                <th>Total</th>
                <th>%</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $r)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $r->exam?->name ?? 'Exam' }}</td>
                <td>{{ strtoupper($r->exam?->type ?? 'N/A') }}</td>
                <td>{{ $r->marks }}</td>
                <td>{{ $r->total_marks }}</td>
                <td>{{ $r->percentage }}%</td>
                <td>{{ $r->grade }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold;">
                <td colspan="3">Overall</td>
                <td>{{ $total_marks }}</td>
                <td>{{ $total_possible }}</td>
                <td>{{ $overall_percentage }}%</td>
                <td>{{ $overall_grade }}</td>
            </tr>
        </tfoot>
    </table>
    
    <div class="grade-box">
        <p>Overall Grade</p>
        <div class="grade">{{ $overall_grade }}</div>
        <p>{{ $overall_percentage }}%</p>
    </div>
    @endif
    
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">Student</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Teacher</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Principal</div>
        </div>
    </div>
    
    <div class="footer">
        <p>Generated on {{ $generated_at->format('F d, Y h:i A') }}</p>
        <p>This is a computer-generated document.</p>
    </div>
</body>
</html>
