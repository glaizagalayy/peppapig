@if ($reportType === 'total_paid_per_student')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Total Paid</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row->student_id }}</td>
                    <td>₱{{ number_format($row->total_paid, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@elseif ($reportType === 'total_paid_per_batch')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Batch Year</th>
                <th>Total Paid</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row->batch_year }}</td>
                    <td>₱{{ number_format($row->payments_sum_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif