<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Premium Plan</title>
    
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
            background-color: rgba(244, 237, 242, 0.95); /* Slight transparency */
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(134, 168, 207, 0.6);
            width: 90%;
            max-width: 440px;
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

        .features {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .features li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            font-size: 15px;
            font-family: 'Quicksand', sans-serif;
        }

        .features i {
            color: #5D7EA3;
        }

        .price {
            font-size: 18px;
            font-weight: 700;
            color: #5D7EA3;
            margin: 25px 0 15px;
            font-family: 'Poppins', sans-serif;
            text-shadow: 0 0 8px rgba(134, 168, 207, 0.2);
        }

        .btn-subscribe, .btn-back {
            padding: 12px 24px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-family: 'Quicksand', sans-serif;
            box-shadow: 0 4px 12px rgba(134, 168, 207, 0.4);
        }

        .btn-subscribe {
            background-color: #5D7EA3;
            color: white;
        }

        .btn-subscribe:hover {
            background-color: #3f5e82;
            transform: translateY(-2px);
        }

        .btn-back {
            margin-top: 12px;
            background-color: #d966a0;
            color: white;
            box-shadow: 0 4px 12px rgba(217, 102, 160, 0.6);
        }

        .btn-back:hover {
            background-color: #b34d83;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="card">
        <h1>Premium</h1>
        <p>
            Unlock full access to real-time traffic tracking across all major routes. With a premium subscription, you’ll get:
        </p>

        <ul class="features">
            <li><i class="fas fa-check-circle"></i> Live traffic updates</li>
            <li><i class="fas fa-check-circle"></i> Detailed traffic reports</li>
            <li><i class="fas fa-check-circle"></i> Route history and analytics</li>
            <li><i class="fas fa-check-circle"></i> Priority support</li>
        </ul>

        <div class="price">Subscribe Now for ₱150/month</div>

        <button type="button" onclick="window.location='{{ route('payment')}}'" class="btn-subscribe">Get Premium</button>
        <button type="button" onclick="window.location='{{ route('map')}}'" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Map</button>
    </div>

</body>
</html>
