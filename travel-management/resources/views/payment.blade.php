<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>GCash Payment</title>
    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Quicksand&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #E1CBD7;
            color: #2f2f2f;
            padding: 40px 20px;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .payment-container {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            max-width: 450px;
            width: 100%;
            text-align: center;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #5D7EA3;
        }

        p {
            font-size: 16px;
            margin-bottom: 30px;
            color: #444;
        }

        .gcash-info {
            margin-bottom: 30px;
        }

        .gcash-number {
            font-size: 20px;
            font-weight: 700;
            color: #86A8CF;
            margin-bottom: 15px;
        }

        .qr-code {
            max-width: 200px;
            margin: 0 auto 30px;
            display: block;
            border-radius: 12px;
            box-shadow: 0 6px 14px rgba(134, 168, 207, 0.3);
        }

        form {
            text-align: left;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #5D7EA3;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            font-size: 16px;
            box-shadow: inset 0 0 6px rgba(134,168,207,0.2);
        }

        .btn-pay {
            width: 100%;
            background-color: #86A8CF;
            color: white;
            font-weight: 700;
            font-size: 16px;
            padding: 14px 0;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 6px 12px rgba(134, 168, 207, 0.8);
            transition: background-color 0.3s ease;
        }

        .btn-pay:hover {
            background-color: #5D7EA3;
        }

        .alert {
            padding: 12px 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive */
        @media (max-width: 500px) {
            .payment-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="payment-container">
    <h1>Pay with GCash</h1>
    <p>Please send your payment to the GCash number below or scan the QR code. After payment, fill in your details to confirm.</p>

    <div class="gcash-info">
        <div class="gcash-number"><i class="fas fa-mobile-alt"></i> 0917-123-4567</div>
        <!-- Replace the src with your actual GCash QR code image -->
        <img src="{{ asset('images/qr.jpg') }}" alt="GCash QR Code" class="qr-code" />
    </div>

    {{-- Display success message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Display validation errors --}}
    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('payment.confirm') }}">
        @csrf
        <label for="payer_name">Your Name</label>
        <input type="text" id="payer_name" name="payer_name" placeholder="Enter your full name" value="{{ old('payer_name') }}" required />

        <label for="payer_email">Email Address</label>
        <input type="email" id="payer_email" name="payer_email" placeholder="Enter your email" value="{{ old('payer_email') }}" required />

        <label for="amount">Amount Paid (₱)</label>
        <input type="number" id="amount" name="amount" placeholder="Enter amount paid" min="150" step="0.01" value="{{ old('amount') }}" required />

        <button type="submit" class="btn-pay">Confirm Payment</button>
    </form>
</div>

</body>
</html>
