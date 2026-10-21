<?php
// Start Session for CSRF protection and flash messages
session_start();

// Generate CSRF Token if not present
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle Contact Form Submission
$formMessage = '';
$formStatus = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    // Validate CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $formMessage = 'Security validation failed (CSRF token mismatch).';
        $formStatus = 'error';
    } else {
        // Sanitize and validate inputs
        $name    = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $subject = htmlspecialchars(trim($_POST['subject'] ?? ''), ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');

        if (!$name || !$email || !$subject || !$message) {
            $formMessage = 'Please fill out all fields with valid information.';
            $formStatus = 'error';
        } else {
            // Processing logic (e.g., mail() or saving to database)
            /*
            $to = "your-email@example.com";
            $headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";
            mail($to, "Portfolio Contact: $subject", "Name: $name\nEmail: $email\n\n$message", $headers);
            */

            $formMessage = "Thank you, {$name}! Your message has been received successfully.";
            $formStatus = 'success';
            
            // Regenerate CSRF token after successful submission
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}

// Portfolio Data Arrays
$skills = [
    'Frontend Focus' => [
        ['name' => 'HTML5 / CSS3 / SCSS', 'level' => '95%'],
        ['name' => 'JavaScript (ES6+) / React JS', 'level' => '90%'],
        ['name' => 'Tailwind CSS / Bootstrap 5', 'level' => '92%'],
        ['name' => 'DOM Manipulation / Web APIs', 'level' => '88%'],
    ],
    'Backend & Database' => [
        ['name' => 'PHP 8.x / Native PHP', 'level' => '88%'],
        ['name' => 'Django / Python', 'level' => '82%'],
        ['name' => 'MySQL / SQLite3', 'level' => '85%'],
        ['name' => 'RESTful APIs & JSON', 'level' => '90%'],
    ],
    'Tools & Architecture' => [
        ['name' => 'Git / GitHub Version Control', 'level' => '90%'],
        ['name' => 'Linux CLI & Apache/XAMPP', 'level' => '85%'],
        ['name' => 'Responsive UI/UX & Figma', 'level' => '88%'],
        ['name' => 'Web Security & Performance', 'level' => '80%'],
    ]
];

?>


<?php
/* database Connection */

if(isset($_POST['name'])){
$server = "localhost";
$username = "root";
$password = "";

$con = mysqli_connect($server, $username, $password);

if(!$con){
    die("Database not Connected..".mysqli_connect_error());
}

$username = $_POST['name'];
$useremail = $_POST['email'];
$usersubject = $_POST['subject'];
$usermessage = $_POST['message'];

$sql = "INSERT INTO `phpdata`.`contact`(`name`,`email`,`subject`,`message`) VALUES ('$username','$useremail','$usersubject','$usermessage');";
if($con->query($sql)==true){
    echo "successully inserted";
}
else{
    echo "ERROR: $sql <br> $con->error";
}


$con->close();
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Stack & Frontend Developer | Portfolio</title>
    
    <!-- Google Fonts & FontAwesome icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-body: #0b0f19;
            --bg-card: rgba(23, 31, 48, 0.7);
            --bg-card-border: rgba(255, 255, 255, 0.08);
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --accent-color: #6366f1;
            --accent-hover: #4f46e5;
            --accent-glow: rgba(99, 102, 241, 0.25);
            --tech-badge: rgba(99, 102, 241, 0.15);
            --tech-text: #a5b4fc;
            --header-bg: rgba(11, 15, 25, 0.85);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-theme="light"] {
            --bg-body: #f8fafc;
            --bg-card: rgba(255, 255, 255, 0.85);
            --bg-card-border: rgba(0, 0, 0, 0.08);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --accent-color: #4f46e5;
            --accent-hover: #4338ca;
            --accent-glow: rgba(79, 70, 229, 0.15);
            --tech-badge: rgba(79, 70, 229, 0.1);
            --tech-text: #4338ca;
            --header-bg: rgba(248, 250, 252, 0.85);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.6;
            transition: background-color 0.3s, color 0.3s;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Navigation */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: var(--header-bg);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--bg-card-border);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .logo {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-main);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .logo span {
            color: var(--accent-color);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--accent-color);
        }

        .theme-toggle-btn {
            background: none;
            border: 1px solid var(--bg-card-border);
            color: var(--text-main);
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: var(--transition);
        }

        .theme-toggle-btn:hover {
            border-color: var(--accent-color);
            color: var(--accent-color);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 100px;
            position: relative;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 3rem;
            align-items: center;
        }

        .badge {
            display: inline-block;
            padding: 0.35rem 0.85rem;
            background: var(--tech-badge);
            color: var(--tech-text);
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            border: 1px solid var(--accent-glow);
        }

        .hero h1 {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1.2rem;
            letter-spacing: -1px;
        }

        .hero h1 .highlight {
            color: var(--accent-color);
            background: linear-gradient(135deg, var(--accent-color), #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.15rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
            max-width: 580px;
        }

        .cta-btns {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.85rem 1.8rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background-color: var(--accent-color);
            color: #ffffff;
            box-shadow: 0 4px 14px var(--accent-glow);
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-main);
            border: 1px solid var(--bg-card-border);
        }

        .btn-secondary:hover {
            border-color: var(--accent-color);
            color: var(--accent-color);
            transform: translateY(-2px);
        }

        /* Terminal Preview Box */
        .terminal-box {
            background: #0d1117;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            font-family: 'Fira Code', monospace;
            font-size: 0.85rem;
        }

        .terminal-header {
            background: #161b22;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .dot-red { background: #ff5f56; }
        .dot-yellow { background: #ffbd2e; }
        .dot-green { background: #27c93f; }

        .terminal-title {
            color: #8b949e;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }

        .terminal-body {
            padding: 1.25rem;
            color: #c9d1d9;
            line-height: 1.7;
        }

        .t-keyword { color: #ff7b72; }
        .t-var { color: #79c0ff; }
        .t-string { color: #a5d6ff; }
        .t-comment { color: #8b949e; }

        /* Sections General */
        section {
            padding: 5rem 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .section-title {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* Skills Section */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .skill-card {
            background: var(--bg-card);
            border: 1px solid var(--bg-card-border);
            padding: 1.8rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .skill-card h3 {
            font-size: 1.2rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--accent-color);
        }

        .skill-item {
            margin-bottom: 1rem;
        }

        .skill-item:last-child {
            margin-bottom: 0;
        }

        .skill-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.35rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .progress-bar {
            height: 8px;
            background: var(--bg-card-border);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent-color), #818cf8);
            border-radius: 4px;
        }

     
   

    
        
        /* Contact Section */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 3rem;
        }

        .contact-info-card {
            background: var(--bg-card);
            border: 1px solid var(--bg-card-border);
            padding: 2rem;
            border-radius: 12px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-icon {
            width: 42px;
            height: 42px;
            background: var(--tech-badge);
            color: var(--accent-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .contact-form {
            background: var(--bg-card);
            border: 1px solid var(--bg-card-border);
            padding: 2rem;
            border-radius: 12px;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(0, 0, 0, 0.15);
            border: 1px solid var(--bg-card-border);
            border-radius: 6px;
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .alert {
            padding: 0.85rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.2rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        /* Footer */
        footer {
            border-top: 1px solid var(--bg-card-border);
            padding: 2rem 0;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Responsive Breakpoints */
        @media (max-width: 900px) {
            .hero-grid, .contact-grid {
                grid-template-columns: 1fr;
            }
            .hero h1 {
                font-size: 2.5rem;
            }
            .nav-links {
                display: none; 
            }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="container nav-container">
            <a href="#" class="logo">&lt;ABHI<span>SHEK</span> /&gt;</a>
            <ul class="nav-links">
                <li><a href="#about">About</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <button class="theme-toggle-btn" id="themeToggle" aria-label="Toggle Theme">
                <i class="fa-solid fa-moon"></i>
            </button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="about">
        <div class="container hero-grid">
            <div>
                <span class="badge"><i class="fa-solid fa-code"></i> Full Stack Developer</span>
                <h1>Crafting Seamless UI & <span class="highlight">Robust Web Backend</span></h1>
                <p>Hi, I'm a developer passionate about building lightweight, pixel-perfect frontend experiences backed by performant PHP & database architectures.</p>
                <div class="cta-btns">
                   
                    <a href="#contact" class="btn btn-secondary"><i class="fa-solid fa-envelope"></i> Get In Touch</a>
                </div>
            </div>

            <!-- Interactive Code Window Visual -->
            <div class="terminal-box">
                <div class="terminal-header">
                    <div class="dot dot-red"></div>
                    <div class="dot dot-yellow"></div>
                    <div class="dot dot-green"></div>
                    <span class="terminal-title">Developer.php</span>
                </div>
                <div class="terminal-body">
                    <span class="t-keyword">&lt;?php</span><br>
                    <span class="t-keyword">class</span> <span class="t-var">Developer</span> {<br>
                    &nbsp;&nbsp;<span class="t-keyword">public string</span> <span class="t-var">$frontend</span> = <span class="t-string">'React / JavaScript / Tailwind'</span>;<br>
                    &nbsp;&nbsp;<span class="t-keyword">public string</span> <span class="t-var">$backend</span>  = <span class="t-string">'PHP 8 / MySQL / REST APIs'</span>;<br>
                    &nbsp;&nbsp;<span class="t-keyword">public bool</span>   <span class="t-var">$focused</span>  = <span class="t-keyword">true</span>;<br><br>
                    &nbsp;&nbsp;<span class="t-keyword">public function</span> <span class="t-var">buildApp</span>(): <span class="t-keyword">string</span> {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="t-keyword">return</span> <span class="t-string">'Fast, Responsive & Secure Applications'</span>;<br>
                    &nbsp;&nbsp;}<br>
                    }<br>
                    <span class="t-comment">// Output: Ready to turn ideas into code!</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Technical Expertise</h2>
                <p class="section-subtitle">Core technologies and frameworks I use daily</p>
            </div>

            <div class="skills-grid">
                <?php foreach ($skills as $category => $items): ?>
                    <div class="skill-card">
                        <h3>
                            <i class="fa-solid <?php echo $category === 'Frontend Focus' ? 'fa-desktop' : ($category === 'Backend & Database' ? 'fa-server' : 'fa-wrench'); ?>"></i>
                            <?php echo $category; ?>
                        </h3>
                        <?php foreach ($items as $skill): ?>
                            <div class="skill-item">
                                <div class="skill-info">
                                    <span><?php echo $skill['name']; ?></span>
                                    <span><?php echo $skill['level']; ?></span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $skill['level']; ?>;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Get In Touch</h2>
                <p class="section-subtitle">Have a project in mind or want to collaborate? Send a message!</p>
            </div>

            <div class="contact-grid">
                <!-- Info Column -->
                <div class="contact-info-card">
                    <h3>Contact Information</h3>
                    <p style="color: var(--text-muted); margin: 0.8rem 0 2rem;">Feel free to reach out via email or connect with me on social platforms.</p>
                    
                    <div class="info-item">
                        <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                        <div>
                            <strong>Email</strong>
                            <p style="color: var(--text-muted);">abhichu1240@example.com</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div>
                            <strong>Location</strong>
                            <p style="color: var(--text-muted);">Mumbai, Maharashtra, India</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fa-solid fa-user-check"></i></div>
                        <div>
                            <strong>Availability</strong>
                            <p style="color: var(--text-muted);">Available for Full-time & Freelance Work</p>
                        </div>
                    </div>
                </div>

                <!-- PHP Dynamic Contact Form -->
                <div class="contact-form">
                    <?php if (!empty($formMessage)): ?>
                        <div class="alert alert-<?php echo $formStatus; ?>">
                            <?php echo $formMessage; ?>
                        </div>
                    <?php endif; ?>

                    <form action="#contact" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" class="form-control" required placeholder="John Doe">
                        </div>

                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <input type="email" id="email" name="email" class="form-control" required placeholder="john@example.com">
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" required placeholder="Project Proposal / Inquiry">
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" class="form-control" required placeholder="Write your message here..."></textarea>
                        </div>

                        <button type="submit" name="submit_contact" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fa-solid fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Developer Portfolio. Built with Native PHP, HTML5 & CSS3.</p>
        </div>
    </footer>

    <!-- JavaScript for Theme Toggle & Interaction -->
    <script>
        const themeToggleBtn = document.getElementById('themeToggle');
        const icon = themeToggleBtn.querySelector('i');

        // Check Local Storage theme
        const savedTheme = localStorage.getItem('theme') || 'dark';
        if (savedTheme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
            icon.className = 'fa-solid fa-sun';
        }

        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            if (currentTheme === 'light') {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'dark');
                icon.className = 'fa-solid fa-moon';
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
                icon.className = 'fa-solid fa-sun';
            }
        });
    </script>
</body>
</html>