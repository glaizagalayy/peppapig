@extends('layouts.student')

@section('title', 'Payment Submission')
@section('page-title', 'Submit Payment Proof')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header text-white" style="background-color: #FF9933;">
                        <h5 class="mb-0">Payment Submission Form</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Student Information -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="student_id" class="form-label">Student ID</label>
                                    <input type="text" class="form-control" id="student_id" name="student_id" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="fname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="lname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="middle_initial" class="form-label">Middle Initial</label>
                                    <input type="text" class="form-control" id="middle_initial" name="middle_initial" maxlength="1">
                                </div>
                            </div>

                            <!-- File Upload with Drag and Drop -->
                            <div class="mb-3">
                                <label class="form-label">Upload Payment Proof (Screenshot/Image)</label>
                                <!-- Dropzone container -->
                                <div id="paymentProofDropzone" class="dropzone">
                                    <div class="dz-message">
                                        <i class="fas fa-cloud-upload-alt fa-3x"></i>
                                        <p>Drag and drop your file here or click to select</p>
                                    </div>
                                </div>
                                <!-- Hidden file input -->
                                <input type="file" class="d-none" id="payment_proof" name="payment_proof" accept="image/*,.pdf" required>
                                <div class="form-text">Max 2MB (JPG, PNG, or PDF)</div>
                            </div>
                            
                            <!-- Payment Details -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="payment_mode" class="form-label">Mode of Payment</label>
                                    <select class="form-select" id="payment_mode" name="payment_mode" required>
                                        <option value="" selected disabled>Select payment method</option>
                                        <option value="gcash">GCash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="amount" class="form-label">Amount Paid (PHP)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" required>
                                </div>
                            </div>
                            
                            <!-- Transaction Date/Time -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="payment_date" class="form-label">Payment Date</label>
                                    <input type="date"
                                       class="form-control"
                                       id="payment_date"
                                       name="payment_date"
                                       value="{{ date('Y-m-d') }}"
                                       max="{{ date('Y-m-d') }}"
                                    required>

                                </div>
                                <div class="col-md-6">
                                    <label for="payment_time" class="form-label">Payment Time</label>
                                    <input type="time" class="form-control" id="payment_time" name="payment_time"
                                           value="{{ date('H:i') }}" required>
                                </div>
                            </div>
                            
                            <!-- Additional Notes -->
                            <div class="mb-3">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                            
                            <!-- Submission -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="reset" class="btn btn-secondary me-md-2">Reset</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include custom CSS for dropzone -->
    <style>
        #paymentProofDropzone {
            border: 2px dashed #007bff;
            border-radius: 5px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 0.5rem;
        }
        #paymentProofDropzone.highlight {
            background-color: #f0f8ff;
        }
        #paymentProofDropzone i {
            margin-bottom: 10px;
        }
    </style>

    <!-- Include JavaScript to enable drag-and-drop -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropzone = document.getElementById('paymentProofDropzone');
            const fileInput = document.getElementById('payment_proof');

            // Trigger file input on dropzone click
            dropzone.addEventListener('click', () => {
                fileInput.click();
            });

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });

            // Add highlight styling when file is dragged over
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.add('highlight');
                });
            });

            // Remove highlight styling when drag leaves or file is dropped
            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.remove('highlight');
                });
            });

            // Update file input when file is dropped
            dropzone.addEventListener('drop', (e) => {
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                }
            });
        });
    </script>
@endsection
