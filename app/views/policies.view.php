<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrimeCare | Company Policies</title>
    <style>
        :root {
            --primary-color: #FCA311;
            --background-color: #f5f5fa;
            --black-color: #333;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
            color: var(--black-color);
            line-height: 1.6;
        }

        header {
            background: url('https://images.unsplash.com/photo-1580587771525-78b9dba3b914') no-repeat center center/cover;
            color: white;
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }

        header h1 {
            font-size: 3em;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            padding: 20px;
            background-color: white;
            margin-top: -50px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        section {
            margin-bottom: 40px;
        }

        section h2 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        section img {
            width: 100%;
            border-radius: 8px;
            margin: 15px 0;
            max-height: 300px;
            object-fit: cover;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: var(--primary-color);
            color: white;
            margin-top: 40px;
        }

        ul {
            margin-left: 20px;
        }

        li {
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <header style="background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('./../../public/assets/images/hero.png') no-repeat center center/cover; height: 150px; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; color: white; text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
        <a href="http://localhost/php_mvc_backend/public/home" style="display: flex; align-items: center; text-decoration: none;">
            <img src="<?= ROOT ?>/assets/images/logo.png" alt="PrimeCare Logo" style="height: 80px;">
        </a>
        <h2 style="font-size: 2.5rem; margin: 0;">PrimeCare Company Policies</h2>
    </header>

    <div class="container">

        <section>
            <h2>1. User and Account Management Policy</h2>
            <ul>
                <li>Users must provide accurate information and maintain secure accounts.</li>
                <li>Sharing of login credentials is strictly prohibited.</li>
                <li>Managers must manually approve agent accounts.</li>
            </ul>
        </section>

        <section>
            <h2>2. Property Listing and Management Policy</h2>
            <ul>
                <li>Only verified property owners may list properties.</li>
                <li>Property details must be accurate and kept up-to-date.</li>
                <li>PrimeCare reserves the right to remove fraudulent listings.</li>
            </ul>
        </section>

        <section>
            <h2>3. Payment and Financial Policy</h2>
            <ul>
                <li>All transactions occur via the PayHere secure gateway.</li>
                <li>PrimeCare facilitates but does not mediate payment disputes.</li>
                <li>Financial reports are available in user dashboards.</li>
            </ul>
        </section>

        <section>
            <h2>4. Maintenance and Service Management Policy</h2>
            <ul>
                <li>Service requests must be logged within the system.</li>
                <li>Agents and service employees must update maintenance statuses promptly.</li>
                <li>PrimeCare does not physically perform services but tracks them.</li>
            </ul>
        </section>

        <section>
            <h2>5. Customer Rental Policy</h2>
            <ul>
                <li>Properties are only confirmed after full payment.</li>
                <li>Cancellations follow owner-specific policies.</li>
            </ul>
        </section>

        <section>
            <h2>6. Inspection and Pre-Approval Policy</h2>
            <ul>
                <li>Agents must inspect properties before approval.</li>
                <li>Reports must be submitted within 48 hours.</li>
            </ul>
        </section>

        <section>
            <h2>7. Data Privacy and Security Policy</h2>
            <ul>
                <li>User data is encrypted and securely stored.</li>
                <li>Role-based access control is enforced across the platform.</li>
            </ul>
        </section>

        <section>
            <h2>8. Communication and Notification Policy</h2>
            <ul>
                <li>All users consent to receive system notifications via SMS and email.</li>
                <li>Communication tools are integrated through Dialog Ideamart and SMTP.</li>
            </ul>
        </section>

        <section>
            <h2>9. Conduct and Behavior Policy</h2>
            <ul>
                <li>Users must engage respectfully and avoid harassment.</li>
                <li>Accounts may be suspended for repeated misconduct.</li>
            </ul>
        </section>

        <section>
            <h2>10. Dispute Resolution Policy</h2>
            <ul>
                <li>PrimeCare does not handle legal disputes directly.</li>
                <li>Users are encouraged to resolve conflicts amicably.</li>
            </ul>
        </section>

        <section>
            <h2>11. Platform Usage Policy</h2>
            <ul>
                <li>System misuse, hacking attempts, and fraud are prohibited.</li>
                <li>Violators will be banned and may face legal actions.</li>
            </ul>
        </section>

        <section>
            <h2>12. Policy Updates</h2>
            <ul>
                <li>PrimeCare may modify policies with prior notice via email and platform announcements.</li>
            </ul>
        </section>

    </div>

    <footer>
        &copy; 2025 PrimeCare Property Management | All Rights Reserved
    </footer>

</body>

</html>