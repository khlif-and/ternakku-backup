{{-- resources/views/core/error/error_case.blade.php --}}
@extends('layouts.app')

@section('title', 'Error Log')

@section('content')
<div class="container py-5">
    <h3 class="mb-4 text-danger">🚨 Error Log Report</h3>

    @if(isset($logs) && count($logs) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Time</th>
                        <th>Context</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $i => $log)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $log['time'] ?? '-' }}</td>
                            <td><pre class="mb-0">{{ $log['context'] ?? '-' }}</pre></td>
                            <td class="text-danger"><strong>{{ $log['message'] ?? '-' }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-success">No error logs found 🎉</div>
    @endif
</div>
@endsection
