<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Premium Features</title>
    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Quicksand&display=swap');

        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background-color: #E1CBD7;
            color: #2f2f2f;
            padding: 40px 20px 40px; /* Reduced top padding to 40px */
        }

        /* HEADER */
        .header {
          background-color: #86A8CF;
          height: 56px;
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 0 20px;
          position: fixed;
          width: 100%;
          top: 0;
          z-index: 1100;
          box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .nav {
          display: flex;
          align-items: center;
          justify-content: space-between;
          width: 100%;
        }

        /* Container for premium content */
        .container {
            background: #fff;
            padding: 40px;
            max-width: 700px;
            margin: auto;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #333;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 25px;
        }

        ul {
            list-style: none;
            padding: 0;
            text-align: left;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        ul li {
            font-size: 16px;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            color: #444;
        }

        ul li i {
            color: #5D7EA3; /* map blue accent */
            margin-right: 10px;
        }

        .btn-premium {
            display: inline-block;
            padding: 14px 28px;
            background-color: #86A8CF; /* map blue */
            color: white;
            font-weight: 700;
            font-size: 16px;
            border: none;
            border-radius: 30px;
            text-decoration: none;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 6px 12px rgba(134, 168, 207, 0.8);
            cursor: pointer;
        }

        .btn-premium:hover {
            background-color: #5D7EA3;
            box-shadow: 0 8px 18px rgba(93, 126, 163, 0.9);
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #5D7EA3;
            font-size: 14px;
            text-decoration: none;
            font-weight: 600;
            border-radius: 30px;
            padding: 10px 20px;
            background-color: #f4edf2;
            box-shadow: 0 4px 10px rgba(134, 168, 207, 0.6);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .back-link:hover {
            background-color: #5D7EA3;
            color: white;
            box-shadow: 0 6px 14px rgba(93, 126, 163, 0.8);
            text-decoration: none;
        }

        .back-link i {
            margin-right: 8px;
        }

        /* Responsive adjustments */
        @media (max-width: 700px) {
            .container {
                padding: 30px 20px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Upgrade to Premium</h1>
    <p>Enjoy exclusive features that make your traffic monitoring smarter and more efficient:</p>

    <ul>
        <li><i class="fas fa-bolt"></i> Real-time traffic and incident alerts</li>
        <li><i class="fas fa-map-marked-alt"></i> Offline map access</li>
        <li><i class="fas fa-route"></i> Save routes and preferences</li>
        <li><i class="fas fa-chart-line"></i> Traffic heatmaps and analytics</li>
    </ul>

    <a href="{{ route('payment') }}" class="btn-premium">Subscribe Now for ₱150/month</a>

    <br>
    <a href="{{ route('map') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Map</a>
</div>

</body>
</html>
