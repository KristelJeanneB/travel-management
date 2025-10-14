<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Payment</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Quicksand&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('/images/background.png') no-repeat center center fixed;
            background-size: cover;
            color: #2f2f2f;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .card {
            background-color: rgba(244, 237, 242, 0.95);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(134, 168, 207, 0.6);
            width: 90%;
            max-width: 500px;
            padding: 40px 30px;
            text-align: left;
        }

        h1 {
            color: #5D7EA3;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        p {
            font-family: 'Quicksand', sans-serif;
            color: #2f2f2f;
            margin-bottom: 20px;
            line-height: 1.6;
            font-size: 15px;
        }

        .gcash-info {
            margin: 20px 0;
            text-align: center;
        }

        .qr-code {
            width: 100%;
            max-width: 220px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 0 auto;
            display: block;
            box-shadow: 0 4px 12px rgba(134, 168, 207, 0.4);
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-family: 'Quicksand', sans-serif;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-family: 'Quicksand', sans-serif;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #2f2f2f;
            font-weight: 600;
            font-family: 'Quicksand', sans-serif;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Quicksand', sans-serif;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #5D7EA3;
            box-shadow: 0 0 8px rgba(93, 126, 163, 0.5);
        }

        .btn-pay, .btn-back {
            padding: 14px 24px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-family: 'Quicksand', sans-serif;
            box-shadow: 0 4px 12px rgba(93, 126, 163, 0.4);
            margin-top: 10px;
        }

        .btn-pay {
            background-color: #5D7EA3;
            color: white;
        }

        .btn-pay:hover {
            background-color: #3f5e82;
            transform: translateY(-2px);
        }

        .btn-back {
            background-color: #d966a0;
            color: white;
            box-shadow: 0 4px 12px rgba(217, 102, 160, 0.6);
        }

        .btn-back:hover {
            background-color: #b34d83;
            transform: translateY(-2px);
        }

        @media screen and (max-width: 600px) {
            .card {
                padding: 30px 20px;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="card">
        <h1>Payment</h1>
        <p>Please send your payment to the GCash number below or scan the QR code. After payment, fill in your details to confirm.</p>

        <div class="gcash-info">
            <img src="{{ asset('images/qr.jpg') }}" alt="GCash QR Code" class="qr-code" />
        </div>

        @if(session('success'))
    <div class="alert-success" id="success-alert">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(() => {
            window.location.href = "{{ route('map') }}";
        }, 8000); 
    </script>
    @endif

        @if($errors->any())
            <div class="alert-error">
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
            <button type="button" onclick="window.location='{{ route('premium') }}'" class="btn-back"><i class="fas fa-arrow-left"></i> Back</button>
        </form>
    </div>

</body>
</html>
