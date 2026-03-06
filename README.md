<p align="center">
  <svg width="100%" height="140" viewBox="0 0 900 140" xmlns="http://www.w3.org/2000/svg">
    <foreignObject width="100%" height="100%">
      <div xmlns="http://www.w3.org/1999/xhtml" style="font-family: 'Courier New', monospace; text-align: center; height: 100%; display: flex; align-items: center; justify-content: center; background: transparent;">
        <style>
          @keyframes glitch {
            0% {
              transform: translate(0);
              text-shadow: 0 0 5px #ff00ff, 0 0 10px #00ffff;
              opacity: 1;
            }
            2% {
              transform: translate(-2px, 2px);
              text-shadow: 2px 0 #ff0000, -2px 0 #0000ff;
              opacity: 0.8;
            }
            4% {
              transform: translate(2px, -2px);
              text-shadow: -2px 0 #00ff00, 2px 0 #ffff00;
              opacity: 0.9;
            }
            6%, 8% {
              transform: translate(0);
              text-shadow: 0 0 20px #ff00ff, 0 0 30px #00ffff;
              opacity: 1;
            }
            10% {
              transform: skew(5deg);
              text-shadow: none;
              opacity: 0.7;
            }
            100% {
              transform: translate(0) skew(0);
              text-shadow: 0 0 5px #ff00ff, 0 0 10px #00ffff;
              opacity: 1;
            }
          }
          .glitch-title {
            font-size: 72px;
            font-weight: bold;
            letter-spacing: 6px;
            color: #00ffff;
            text-shadow: 0 0 10px #00ffff, 0 0 20px #ff00ff;
            animation: glitch 3s infinite steps(1);
            position: relative;
            background: linear-gradient(90deg, #ff00ff, #00ffff, #ff00ff);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
          }
          .glitch-title::before,
          .glitch-title::after {
            content: "LIFXCODETZ";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.8;
          }
          .glitch-title::before {
            left: 2px;
            text-shadow: -2px 0 #ff0000;
            clip: rect(0, 900px, 0, 0);
            animation: glitch 2s infinite linear alternate-reverse;
          }
          .glitch-title::after {
            left: -2px;
            text-shadow: 2px 0 #0000ff;
            clip: rect(0, 900px, 140px, 0);
            animation: glitch 4s infinite linear alternate-reverse;
          }
        </style>
        <h1 class="glitch-title">LIFXCODETZ</h1>
      </div>
    </foreignObject>
  </svg>
</p>

<!-- Badge & teks bawahnya tetep -->
<p align="center">
  <img src="https://img.shields.io/badge/VERSION-1.0.0-blue?style=for-the-badge&logo=github" alt="Version">
  <img src="https://img.shields.io/badge/RELEASE-2025-success?style=for-the-badge" alt="Release Year">
  <img src="https://img.shields.io/badge/STABLE-brightgreen?style=for-the-badge" alt="Stable">
</p>
