<div align="center">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap');
    
    .glitch-wrapper {
      font-family: 'Share Tech Mono', monospace;
      background: #000;
      padding: 40px;
      border-radius: 10px;
      border: 1px solid #0f0;
      box-shadow: 0 0 20px rgba(0, 255, 0, 0.3);
      margin: 20px;
    }
    
    .glitch-title {
      font-size: 3em;
      font-weight: bold;
      text-transform: uppercase;
      position: relative;
      text-shadow: 0.05em 0 0 #00fffc, -0.03em -0.04em 0 #fc00ff,
                   0.025em 0.04em 0 #fffc00;
      animation: glitch 725ms infinite;
      color: #0f0;
      letter-spacing: 5px;
    }
    
    .glitch-title span {
      position: absolute;
      top: 0;
      left: 0;
    }
    
    .glitch-title span:first-child {
      animation: glitch 650ms infinite;
      clip-path: polygon(0 0, 100% 0, 100% 45%, 0 45%);
      transform: translate(-0.04em, -0.03em);
      opacity: 0.8;
    }
    
    .glitch-title span:last-child {
      animation: glitch 375ms infinite;
      clip-path: polygon(0 80%, 100% 20%, 100% 100%, 0 100%);
      transform: translate(0.04em, 0.03em);
      opacity: 0.8;
    }
    
    @keyframes glitch {
      0% { text-shadow: 0.05em 0 0 #00fffc, -0.03em -0.04em 0 #fc00ff, 0.025em 0.04em 0 #fffc00; }
      15% { text-shadow: 0.05em 0 0 #00fffc, -0.03em -0.04em 0 #fc00ff, 0.025em 0.04em 0 #fffc00; }
      16% { text-shadow: -0.05em -0.025em 0 #00fffc, 0.025em 0.025em 0 #fc00ff, -0.05em -0.05em 0 #fffc00; }
      49% { text-shadow: -0.05em -0.025em 0 #00fffc, 0.025em 0.025em 0 #fc00ff, -0.05em -0.05em 0 #fffc00; }
      50% { text-shadow: 0.025em 0.05em 0 #00fffc, 0.05em 0 0 #fc00ff, 0 -0.05em 0 #fffc00; }
      99% { text-shadow: 0.025em 0.05em 0 #00fffc, 0.05em 0 0 #fc00ff, 0 -0.05em 0 #fffc00; }
      100% { text-shadow: -0.025em 0 0 #00fffc, -0.025em -0.025em 0 #fc00ff, -0.025em -0.05em 0 #fffc00; }
    }
    
    .scanline {
      position: relative;
      overflow: hidden;
    }
    
    .scanline::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to bottom, transparent 50%, rgba(0, 255, 0, 0.03) 50%);
      background-size: 100% 4px;
      animation: scan 8s linear infinite;
      pointer-events: none;
    }
    
    @keyframes scan {
      0% { background-position: 0 0; }
      100% { background-position: 0 100%; }
    }
    
    .crt::before {
      content: " ";
      display: block;
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      background: rgba(0, 255, 0, 0.02);
      opacity: 0.3;
      pointer-events: none;
      animation: flicker 0.15s infinite;
    }
    
    @keyframes flicker {
      0% { opacity: 0.27861; } 5% { opacity: 0.34769; } 10% { opacity: 0.23604; }
      15% { opacity: 0.10626; } 20% { opacity: 0.46828; } 25% { opacity: 0.80629; }
      30% { opacity: 0.42994; } 35% { opacity: 0.30775; } 40% { opacity: 0.18419; }
      45% { opacity: 0.97679; } 50% { opacity: 0.15192; } 55% { opacity: 0.69845; }
      60% { opacity: 0.92489; } 65% { opacity: 0.65801; } 70% { opacity: 0.30502; }
      75% { opacity: 0.91639; } 80% { opacity: 0.08536; } 85% { opacity: 0.29368; }
      90% { opacity: 0.82167; } 95% { opacity: 0.20363; } 100% { opacity: 0.23984; }
    }
    
    .typing-text {
      font-size: 1.5em;
      color: #0f0;
      font-family: 'Courier New', monospace;
      margin: 20px 0;
      border-right: 3px solid #0f0;
      white-space: nowrap;
      overflow: hidden;
      width: 0;
      animation: typing 3.5s steps(40, end) forwards, blink-caret 0.75s step-end infinite;
    }
    
    @keyframes typing {
      from { width: 0; }
      to { width: 100%; }
    }
    
    @keyframes blink-caret {
      from, to { border-color: transparent; }
      50% { border-color: #0f0; }
    }
    
    .matrix-rain {
      color: #0f0;
      font-family: 'Courier New', monospace;
      font-size: 12px;
      line-height: 1;
      opacity: 0.5;
      margin: 10px 0;
      white-space: pre;
    }
    
    .neon-box {
      border: 2px solid #0f0;
      padding: 30px;
      border-radius: 10px;
      animation: neon-pulse 2s infinite;
      background: rgba(0, 0, 0, 0.9);
    }
    
    @keyframes neon-pulse {
      0% { box-shadow: 0 0 5px #0f0; }
      50% { box-shadow: 0 0 20px #0f0, 0 0 30px #0f0; }
      100% { box-shadow: 0 0 5px #0f0; }
    }
  </style>
  
  <div class="glitch-wrapper scanline crt">
    <div class="neon-box">
      
      <!-- Matrix rain effect -->
      <div class="matrix-rain">
        01001000 01100101 01101100 01101100 01101111 00100000 01000111 01110101 01111001 01110011
      </div>
      
      <!-- Main glitch text -->
      <div class="glitch-title">
        HELLO GUYS
        <span aria-hidden="true">HELLO GUYS</span>
        <span aria-hidden="true">HELLO GUYS</span>
      </div>
      
      <div class="glitch-title" style="font-size: 2em; margin-top: 10px;">
        SELAMAT DATANG
        <span aria-hidden="true">SELAMAT DATANG</span>
        <span aria-hidden="true">SELAMAT DATANG</span>
      </div>
      
      <div class="glitch-title" style="font-size: 2.5em; margin: 15px 0;">
        DI GITHUB
        <span aria-hidden="true">DI GITHUB</span>
        <span aria-hidden="true">DI GITHUB</span>
      </div>
      
      <div class="glitch-title" style="font-size: 3.5em; color: #ff0; text-shadow: 0.05em 0 0 #f00, -0.03em -0.04em 0 #0ff, 0.025em 0.04em 0 #f0f;">
        LIFXCODETZ
        <span aria-hidden="true">LIFXCODETZ</span>
        <span aria-hidden="true">LIFXCODETZ</span>
      </div>
      
      <!-- Typing animation -->
      <div class="typing-text" style="margin: 30px auto; max-width: 400px;">
        &gt; SYSTEM: GLITCH MODE ACTIVATED
      </div>
      
      <!-- Additional matrix line -->
      <div class="matrix-rain" style="opacity: 0.3; margin-top: 20px;">
        01100111 01101100 01101001 01110100 01100011 01101000 00100000 01100101 01100110 01100110 01100101 01100011 01110100 00100000 01100001 01100011 01110100 01101001 01110110 01100101
      </div>
      
      <!-- Status bar -->
      <div style="color: #0f0; font-family: 'Courier New'; font-size: 14px; text-align: left; margin-top: 20px; border-top: 1px solid #0f0; padding-top: 15px;">
        <span style="color: #ff0;">[LIFXCODETZ@github]</span><span style="color: #fff;">:$</span> <span style="animation: blink 1s infinite;">_</span>
        <br>
        <span style="color: #0f0;">&gt; Status: <span style="color: #0f0; animation: pulse 2s infinite;">██████████ ACTIVE</span></span>
        <br>
        <span style="color: #0f0;">&gt; Glitch: <span style="color: #f0f;">██████████ 100%</span></span>
        <br>
        <span style="color: #0f0;">&gt; Mode: <span style="color: #ff0;">DARK HACKER</span></span>
      </div>
      
      <style>
        @keyframes pulse {
          0% { opacity: 1; }
          50% { opacity: 0.5; }
          100% { opacity: 1; }
        }
        @keyframes blink {
          0%, 100% { opacity: 1; }
          50% { opacity: 0; }
        }
      </style>
      
    </div>
  </div>
  
  <!-- Extra glitch text at bottom -->
  <div style="margin-top: 20px; font-family: 'Courier New'; color: #0f0; font-size: 12px; opacity: 0.6;">
    ═══════════════════════════════════════
    <span style="animation: glitch-text 2s infinite;">[ SYSTEM GLITCH // ENTERING DARK MODE // LIFXCODETZ ]</span>
    ═══════════════════════════════════════
  </div>
  
  <style>
    @keyframes glitch-text {
      0%, 100% { opacity: 1; transform: skew(0deg); }
      95% { opacity: 0.8; transform: skew(2deg); text-shadow: -1px 0 #f0f, 1px 0 #0ff; }
      96% { opacity: 0.9; transform: skew(-2deg); text-shadow: 1px 0 #f0f, -1px 0 #0ff; }
      97% { opacity: 0.7; transform: skew(1deg); text-shadow: -1px 0 #ff0, 1px 0 #f0f; }
      98% { opacity: 1; transform: skew(0deg); text-shadow: 0 0 5px #0f0; }
    }
  </style>
  
</div>
