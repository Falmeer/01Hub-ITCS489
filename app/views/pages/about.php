<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>About Us - 01 HUB</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <style>
      body {
        font-family: "Segoe UI", sans-serif;
        margin: 0;
        background: linear-gradient(to right, #e0f7fa, #ffffff);
        color: #333;
      }

      header {
        background-color: #00bcd4;
        color: white;
        padding: 30px 20px;
        text-align: center;
        position: relative;
      }

      .back-btn {
        position: absolute;
        left: 20px;
        top: 30px;
        background-color: white;
        color: #00bcd4;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: background 0.3s ease;
      }

      .back-btn:hover {
        background-color: #e0f7fa;
      }

      .container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 20px;
      }

      .section {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 40px;
      }

      h2 {
        color: #00bcd4;
        margin-bottom: 15px;
      }

      .team {
        display: flex;
        flex-wrap: nowrap;
        justify-content: center;
        gap: 20px;
        overflow-x: auto;
        padding-bottom: 10px;
      }

      .member {
        background: #fff;
        padding: 20px;
        text-align: center;
        min-width: 200px;
        flex-shrink: 0;
        border-radius: 10px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
      }

      .member img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        border: 2px solid #00bcd4;
      }

      .member h4 {
        margin: 5px 0;
        font-size: 18px;
        color: #222;
      }

      .member p {
        font-size: 14px;
        color: #555;
        line-height: 1.4;
      }

      footer {
        background: #1e1e2f;
        color: #ccc;
        text-align: center;
        padding: 20px;
        font-size: 14px;
      }

      @media (max-width: 600px) {
        .team {
          flex-direction: column;
          align-items: center;
        }
      }
    </style>
  </head>
  <body>
    <header>
<a href="<?= htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'index.php?url=pages/home') ?>" class="back-btn">
  <i class="fas fa-arrow-left"></i> Back
    </a>
      <h1>About 01 HUB</h1>
      <p>Where Technology Meets Performance</p>
    </header>

    <div class="container">
      <div class="section">
        <h2>Our Story</h2>
        <p>
          01 HUB is a fictional online platform designed to showcase an online
          PC component store, developed as part of the Software Engineering II
          course (ITCS489) at the University of Bahrain. The project aims to
          implement core principles of software engineering including full-stack
          design, database integration, session management, and
          admin/staff/customer roles using PHP and MySQL.
        </p>
        <p>
          From showcasing trending products to implementing admin dashboards and
          order management systems, this platform simulates a real-world online
          shopping experience for PC components and accessories.
        </p>
      </div>

      <div class="section">
        <h2>Meet the Development Team</h2>
        <div class="team">
          <div class="member">
            <img src="images/fawaz.jpg" alt="Fawaz Almeer" />
            <h4>Fawaz Almeer</h4>
            <p>202011989<br />Backend Developer</p>
          </div>
          <div class="member">
            <img src="images/abdullah.jpg" alt="Abdullah Moatazbellah" />
            <h4>Abdullah Moatazbellah</h4>
            <p>202108867<br />Database & Integration</p>
          </div>
          <div class="member">
            <img src="images/mohammed.jpg" alt="Mohammed Janahi" />
            <h4>Mohammed Janahi</h4>
            <p>202011111<br />Frontend & UI Designer</p>
          </div>
          <div class="member">
            <img src="images/omar.jpg" alt="Omar Adnan" />
            <h4>Omar Adnan</h4>
            <p>202246545<br />Order & Staff Module</p>
          </div>
        </div>
      </div>

      <div class="section">
        <h2>Project Details</h2>
        <p>
          This project was developed as a course requirement for
          <strong>Software Engineering II (ITCS489)</strong>, instructed by
          <strong>Dr. Taher Saleh</strong>.
        </p>
        <p>
          All development, design, and implementation were carried out by the
          students listed above, applying Agile principles, modular MVC
          architecture, and full-stack programming skills.
        </p>
      </div>
    </div>

    <footer>
      &copy; 2025 01 HUB Project. All rights reserved.<br />
      Course: ITCS489 - Software Engineering II<br />
      University of Bahrain
    </footer>
  </body>
</html>
