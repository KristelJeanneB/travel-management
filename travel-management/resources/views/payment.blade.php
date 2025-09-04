<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Payment</title>

    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Quicksand&display=swap" rel="stylesheet">

    <style>
        body {
            background-image: url("/images/background.png");
            background-size: cover;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .payment-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            padding: 30px;
            text-align: center;
        }

        h1 {
            color: #4CAF50;
            margin-bottom: 15px;
            border: 1px dashed #ddd;
            padding: 10px;
            border-radius: 6px;
            font-size: 1.5rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
        }

        p {
            color: #555;
            margin-bottom: 20px;
            line-height: 1.5;
            font-family: 'Quicksand', sans-serif;
        }

        .gcash-info {
            margin: 20px 0;
            text-align: left;
        }

        .gcash-number {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
            font-family: 'Quicksand', sans-serif;
        }

        .qr-code {
            width: 100%;
            max-width: 200px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 0 auto;
            display: block;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-family: 'Quicksand', sans-serif;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-family: 'Quicksand', sans-serif;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 600;
            font-family: 'Quicksand', sans-serif;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border 0.3s ease;
            font-family: 'Quicksand', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .btn-pay {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
            font-weight: bold;
            font-family: 'Poppins', sans-serif;
            transition: background 0.3s ease;
        }

        .btn-pay:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h1>Payment</h1>
        <p>Please send your payment to the GCash number below or scan the QR code. After payment, fill in your details to confirm.</p>

        <div class="gcash-info">
            <img src="{{ asset('images/qr.jpg') }}" alt="GCash QR Code" class="qr-code" />
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

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
            <div class="form-group">
                <label for="payer_name">Your Name</label>
                <input type="text" id="payer_name" name="payer_name" placeholder="Enter your full name" value="{{ old('payer_name') }}" required class="form-control" />
            </div>

            <div class="form-group">
                <label for="payer_email">Email Address</label>
                <input type="email" id="payer_email" name="payer_email" placeholder="Enter your email" value="{{ old('payer_email') }}" required class="form-control" />
            </div>

            <div class="form-group">
                <label for="contact">Contact No.</label>
                <input type="text" id="contact" name="contact" placeholder="Enter your Contact No." value="{{ old('contact') }}" required class="form-control" />
            </div>


            <div class="form-group">
                <label for="amount">Amount Paid (₱)</label>
                <input type="number" id="amount" name="amount" placeholder="Enter amount paid" min="150" step="0.01" value="{{ old('amount') }}" required class="form-control" />
            </div>

            <button type="submit" class="btn-pay">Confirm Payment</button>
            <button type="button" onclick="window.location='{{ route('premium')}}'" class="btn-pay">Back</button>
        </form>
    </div>
</body>
</html>