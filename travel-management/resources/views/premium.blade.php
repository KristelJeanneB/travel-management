<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Premium Plan</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">

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
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            padding: 30px;
            text-align: left;
        }

        h1 {
            color: #1a1a1a;
            margin-bottom: 15px;
            font-size: 2rem;
            font-weight: 700;
        }

        p {
            color: #333;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .features {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .features li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            color: #333;
            font-size: 16px;
        }

        .features i {
            color: #4CAF50;
            font-weight: bold;
        }

        .price {
            font-size: 1rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 20px 0;
        }

        .btn-subscribe {
            background: #4CAF50;
            color: #1a1a1a;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-subscribe:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

        .btn-back {
            margin-top: 12px;
            background: #4CAF50;
            color: #1a1a1a;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #45a049;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="card">
    <h1>Premium</h1>
    <p>Stream 80,000+ hours of the best in TV, movies, and sports.</p>

    <ul class="features">
        <li><i class="fas fa-check"></i> New & Hit Shows, Films & Originals</li>
        <li><i class="fas fa-check"></i> LIVE Sports & Events</li>
        <li><i class="fas fa-check"></i> Current NBC & Bravo Shows</li>
        <li><i class="fas fa-check"></i> 50+ Always-On Channels</li>
    </ul>

    <div class="price">Subscribe Now for ₱150/month</div>

    <button type="button" onclick="window.location='{{ route('payment')}}'" class="btn-subscribe">Get Premium</button>
    <button type="button" onclick="window.location='{{ route('map')}}'" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Map</button>
</div>
</div>
</div>

</body>
</html>