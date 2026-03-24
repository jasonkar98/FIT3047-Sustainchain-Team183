<?php
/**
 * SustainChain — Landing Page
 * templates/Pages/home.php
 */
use Cake\Core\Configure;
$this->disableAutoLayout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SustainChain</title>
    <meta name="description" content="SustainChain connects buyers, sellers, manufacturers and farmers in a vibrant marketplace committed to sustainable living and ethical commerce.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,300;1,9..144,700&family=Cabinet+Grotesk:wght@400;500;700;800&family=Satoshi:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Fallback for Cabinet Grotesk / Satoshi via Fontshare CDN -->
    <link href="https://api.fontshare.com/v2/css?f[]=cabinet-grotesk@400,500,700,800&f[]=satoshi@300,400,500&display=swap" rel="stylesheet">

    <style>
    /* ══════════════════════════════════════
       RESET & TOKENS
    ══════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --g0: #0D1F14;   /* deep forest */
        --g1: #163824;   /* forest */
        --g2: #1F5235;   /* moss */
        --g3: #2E7D52;   /* fern */
        --g4: #4DAA7A;   /* sage */
        --g5: #A3CFBA;   /* mist */
        --g6: #D9EDE4;   /* dew */

        --e0: #FFE066;   /* electric lemon */
        --e1: #C8E840;   /* lime */
        --e2: #8DC63F;   /* grass */

        --s0: #FFF8F0;   /* warm cream */
        --s1: #F2EBE0;   /* sand */
        --s2: #E2D5C3;   /* tan */

        --ink: #0D1209;
        --muted: #556652;
        --white: #FFFFFF;

        --r8: 8px;
        --r16: 16px;
        --r24: 24px;
        --r999: 999px;

        --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    html { scroll-behavior: smooth; font-size: 16px; }

    body {
        background: var(--s0);
        color: var(--ink);
        font-family: 'Satoshi', 'Cabinet Grotesk', sans-serif;
        font-weight: 400;
        overflow-x: hidden;
        cursor: default;
    }

    /* grain overlay */
    body::before {
        content: '';
        position: fixed; inset: 0; z-index: 9000;
        pointer-events: none;
        opacity: .4;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='400' height='400' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    }

    img { display: block; max-width: 100%; }
    a { color: inherit; text-decoration: none; }

    /* ══════════════════════════════════════
       TYPOGRAPHY HELPERS
    ══════════════════════════════════════ */
    .t-display {
        font-family: 'Fraunces', serif;
        font-weight: 700;
        line-height: 1.0;
        letter-spacing: -0.02em;
    }
    .t-display em {
        font-style: italic;
        font-weight: 300;
        color: var(--g4);
    }
    .t-label {
        font-family: 'Cabinet Grotesk', 'Satoshi', sans-serif;
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    /* ══════════════════════════════════════
       NAV
    ══════════════════════════════════════ */
    .nav {
        position: fixed; top: 0; left: 0; right: 0; z-index: 800;
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 2.5rem;
        background: rgba(13,31,20,.92);
        backdrop-filter: blur(18px) saturate(1.4);
        border-bottom: 1px solid rgba(255,255,255,.06);
        transition: padding .3s;
    }

    .nav-logo {
        display: flex; align-items: center; gap: .6rem;
    }
    .nav-logo-icon {
        width: 36px; height: 36px;
        background: var(--e1);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 0 0 2px rgba(200,232,64,.25);
    }
    .nav-logo-name {
        font-family: 'Cabinet Grotesk', sans-serif;
        font-weight: 800; font-size: 1.1rem;
        letter-spacing: -0.03em;
        color: var(--white);
    }
    .nav-logo-name span { color: var(--e1); }

    .nav-links {
        display: flex; align-items: center; gap: 2rem;
        list-style: none;
    }
    .nav-links a {
        font-size: .875rem; font-weight: 500;
        color: rgba(255,255,255,.55);
        transition: color .2s;
        position: relative;
    }
    .nav-links a:hover { color: var(--white); }
    .nav-links a.active { color: var(--e1); }

    .nav-right { display: flex; align-items: center; gap: .75rem; }

    .btn { display: inline-flex; align-items: center; gap: .45rem; font-family: 'Cabinet Grotesk', sans-serif; font-weight: 700; font-size: .85rem; border-radius: var(--r999); padding: .55rem 1.3rem; transition: transform .18s var(--ease-spring), box-shadow .18s, background .18s; cursor: pointer; border: none; letter-spacing: -.01em; }
    .btn:hover { transform: translateY(-2px); }

    .btn-outline { background: transparent; color: rgba(255,255,255,.7); border: 1.5px solid rgba(255,255,255,.18); }
    .btn-outline:hover { border-color: rgba(255,255,255,.5); color: var(--white); }

    .btn-lime { background: var(--e1); color: var(--g0); box-shadow: 0 4px 16px rgba(200,232,64,.35); }
    .btn-lime:hover { background: var(--e0); box-shadow: 0 8px 24px rgba(200,232,64,.45); }

    .btn-forest { background: var(--g1); color: var(--white); box-shadow: 0 4px 16px rgba(22,56,36,.4); }
    .btn-forest:hover { background: var(--g2); box-shadow: 0 8px 24px rgba(22,56,36,.55); }

    .btn-lg { padding: .8rem 2rem; font-size: .95rem; border-radius: var(--r16); }

    /* ══════════════════════════════════════
       HERO
    ══════════════════════════════════════ */
    .hero {
        min-height: 100svh;
        background: var(--g0);
        display: grid;
        grid-template-rows: auto 1fr;
        position: relative;
        overflow: hidden;
        padding-top: 68px; /* nav height */
    }

    /* animated leaf-vein pattern */
    .hero-bg {
        position: absolute; inset: 0; z-index: 0;
        background:
            radial-gradient(ellipse 70% 60% at 80% 20%, rgba(46,125,82,.22) 0%, transparent 65%),
            radial-gradient(ellipse 50% 70% at 10% 90%, rgba(200,232,64,.08) 0%, transparent 55%),
            radial-gradient(ellipse 40% 40% at 50% 50%, rgba(22,56,36,.6) 0%, transparent 80%);
    }

    /* large decorative circle */
    .hero-circle {
        position: absolute;
        width: 700px; height: 700px;
        border: 1px solid rgba(77,170,122,.12);
        border-radius: 50%;
        top: 50%; right: -100px;
        transform: translateY(-50%);
        animation: spin-slow 60s linear infinite;
    }
    .hero-circle::before {
        content: '';
        position: absolute; inset: 60px;
        border: 1px solid rgba(200,232,64,.08);
        border-radius: 50%;
    }
    @keyframes spin-slow {
        to { transform: translateY(-50%) rotate(360deg); }
    }

    /* floating leaf dots */
    .hero-dots {
        position: absolute; inset: 0; z-index: 1; pointer-events: none;
        overflow: hidden;
    }
    .dot {
        position: absolute;
        border-radius: 50%;
        background: var(--e1);
        opacity: 0;
        animation: float-up 8s ease-in infinite;
    }
    .dot:nth-child(1)  { width:6px;  height:6px;  left:10%; animation-delay:0s;   animation-duration:9s; }
    .dot:nth-child(2)  { width:4px;  height:4px;  left:30%; animation-delay:1.5s; animation-duration:7s; }
    .dot:nth-child(3)  { width:8px;  height:8px;  left:50%; animation-delay:3s;   animation-duration:11s; }
    .dot:nth-child(4)  { width:5px;  height:5px;  left:70%; animation-delay:0.8s; animation-duration:8s; }
    .dot:nth-child(5)  { width:3px;  height:3px;  left:85%; animation-delay:2.2s; animation-duration:10s; }
    @keyframes float-up {
        0%   { opacity: 0; bottom: -20px; }
        10%  { opacity: .6; }
        90%  { opacity: .3; }
        100% { opacity: 0; bottom: 110%; }
    }

    .hero-inner {
        position: relative; z-index: 5;
        max-width: 1180px; margin: 0 auto; width: 100%;
        padding: 5rem 2.5rem 4rem;
        display: grid;
        grid-template-columns: 1.1fr .9fr;
        gap: 4rem;
        align-items: center;
    }

    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: .5rem;
        background: rgba(200,232,64,.12);
        border: 1px solid rgba(200,232,64,.25);
        color: var(--e1);
        padding: .35rem 1rem;
        border-radius: var(--r999);
        margin-bottom: 1.75rem;
        animation: reveal .6s ease both;
    }
    .eyebrow-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--e1);
        animation: blink 2s ease infinite;
    }
    @keyframes blink {
        0%,100% { opacity: 1; } 50% { opacity: .3; }
    }

    .hero-title {
        font-size: clamp(3rem, 6vw, 5.5rem);
        color: var(--white);
        margin-bottom: 1.75rem;
        animation: reveal .65s .1s ease both;
    }
    .hero-title em { color: var(--e1); }

    .hero-sub {
        font-size: 1.1rem; line-height: 1.8;
        color: rgba(255,255,255,.55);
        max-width: 500px;
        margin-bottom: 2.5rem;
        animation: reveal .65s .2s ease both;
    }

    .hero-actions {
        display: flex; gap: 1rem; flex-wrap: wrap;
        animation: reveal .65s .3s ease both;
    }

    /* ─ Hero right panel ─ */
    .hero-panel {
        animation: reveal .7s .35s ease both;
    }

    .founder-card {
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: var(--r24);
        padding: 2rem;
        margin-bottom: 1.25rem;
        backdrop-filter: blur(12px);
        display: flex; align-items: center; gap: 1.25rem;
        transition: border-color .2s;
    }
    .founder-card:hover { border-color: rgba(200,232,64,.3); }

    .founder-avatar {
        width: 60px; height: 60px; flex-shrink: 0;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--g3), var(--g4));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
        border: 2px solid rgba(200,232,64,.4);
    }
    .founder-info {}
    .founder-name {
        font-family: 'Cabinet Grotesk', sans-serif;
        font-weight: 700; font-size: 1rem;
        color: var(--white); letter-spacing: -.02em;
    }
    .founder-role {
        font-size: .8rem; color: var(--e1);
        margin-top: .15rem;
    }
    .founder-quote {
        font-size: .85rem; color: rgba(255,255,255,.5);
        line-height: 1.6; margin-top: .5rem;
        font-style: italic;
    }

    .hero-stats {
        display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
    }

    .stat-pill {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: var(--r16);
        padding: 1.25rem 1.5rem;
        transition: border-color .2s, background .2s;
    }
    .stat-pill:hover {
        border-color: rgba(200,232,64,.2);
        background: rgba(200,232,64,.04);
    }
    .stat-num {
        font-family: 'Fraunces', serif;
        font-size: 2rem; font-weight: 700;
        color: var(--e1); line-height: 1;
        letter-spacing: -0.04em;
    }
    .stat-desc {
        font-size: .78rem; color: rgba(255,255,255,.4);
        margin-top: .35rem; line-height: 1.4;
    }

    @keyframes reveal {
        from { opacity: 0; transform: translateY(28px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ══════════════════════════════════════
       WHO WE SERVE  (audience strip)
    ══════════════════════════════════════ */
    .audience {
        background: var(--g1);
        padding: 1.5rem 2.5rem;
        border-bottom: 1px solid rgba(255,255,255,.05);
    }
    .audience-inner {
        max-width: 1180px; margin: 0 auto;
        display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;
    }
    .audience-label { color: rgba(255,255,255,.35); white-space: nowrap; }
    .audience-tags { display: flex; gap: .75rem; flex-wrap: wrap; }
    .audience-tag {
        display: flex; align-items: center; gap: .45rem;
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.1);
        color: rgba(255,255,255,.7);
        padding: .4rem 1rem; border-radius: var(--r999);
        font-size: .82rem; font-weight: 500;
        transition: background .2s, border-color .2s, color .2s;
    }
    .audience-tag:hover {
        background: rgba(200,232,64,.12);
        border-color: rgba(200,232,64,.3);
        color: var(--e1);
    }

    /* ══════════════════════════════════════
       PLATFORM PILLARS
    ══════════════════════════════════════ */
    .pillars {
        padding: 7rem 2.5rem;
        max-width: 1180px; margin: 0 auto;
    }

    .section-header { margin-bottom: 4rem; }
    .section-tag {
        display: inline-block;
        color: var(--g3);
        margin-bottom: .75rem;
    }
    .section-title {
        font-size: clamp(2rem, 4vw, 3.25rem);
        color: var(--g0);
        max-width: 560px;
    }
    .section-title em { color: var(--g3); }
    .section-body {
        margin-top: 1rem;
        font-size: 1rem; line-height: 1.75;
        color: var(--muted); max-width: 540px;
    }

    .pillars-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 1.25rem;
    }

    .pillar {
        border-radius: var(--r24);
        padding: 2.25rem;
        position: relative; overflow: hidden;
        transition: transform .25s var(--ease-spring), box-shadow .25s;
    }
    .pillar:hover { transform: translateY(-5px); }

    .pillar-icon {
        font-size: 2.2rem; margin-bottom: 1.5rem; display: block;
        transition: transform .3s var(--ease-spring);
    }
    .pillar:hover .pillar-icon { transform: scale(1.15) rotate(-5deg); }

    .pillar-tag { margin-bottom: .5rem; }
    .pillar-title {
        font-family: 'Cabinet Grotesk', sans-serif;
        font-size: 1.2rem; font-weight: 700;
        letter-spacing: -.02em; margin-bottom: .75rem;
    }
    .pillar-desc { font-size: .875rem; line-height: 1.7; }

    /* layout spans */
    .pillar:nth-child(1) { grid-column: span 5; }
    .pillar:nth-child(2) { grid-column: span 7; }
    .pillar:nth-child(3) { grid-column: span 4; }
    .pillar:nth-child(4) { grid-column: span 4; }
    .pillar:nth-child(5) { grid-column: span 4; }

    /* colour variants */
    .pillar-dark {
        background: var(--g0);
        color: var(--white);
        box-shadow: 0 20px 60px rgba(13,31,20,.25);
    }
    .pillar-dark .pillar-desc { color: rgba(255,255,255,.5); }
    .pillar-dark .pillar-tag  { color: var(--e1); }

    .pillar-forest {
        background: var(--g1);
        color: var(--white);
        box-shadow: 0 12px 40px rgba(13,31,20,.15);
    }
    .pillar-forest .pillar-desc { color: rgba(255,255,255,.5); }
    .pillar-forest .pillar-tag  { color: var(--g5); }

    .pillar-light {
        background: var(--s1);
        color: var(--ink);
        border: 1px solid var(--s2);
    }
    .pillar-light .pillar-desc { color: var(--muted); }
    .pillar-light .pillar-tag  { color: var(--g3); }

    .pillar-lime {
        background: var(--e1);
        color: var(--g0);
        box-shadow: 0 12px 40px rgba(200,232,64,.3);
    }
    .pillar-lime .pillar-desc { color: rgba(13,31,20,.65); }
    .pillar-lime .pillar-tag  { color: var(--g2); }

    /* ══════════════════════════════════════
       DISCOVER INNOVATORS
    ══════════════════════════════════════ */
    .innovators {
        background: var(--g0);
        padding: 7rem 2.5rem;
        position: relative; overflow: hidden;
    }

    .innovators-bg {
        position: absolute; inset: 0; z-index: 0;
        background:
            radial-gradient(ellipse 60% 70% at 90% 50%, rgba(46,125,82,.2) 0%, transparent 60%),
            radial-gradient(ellipse 40% 40% at 5% 80%, rgba(200,232,64,.06) 0%, transparent 50%);
    }

    .innovators-inner {
        max-width: 1180px; margin: 0 auto;
        position: relative; z-index: 2;
    }

    .innovators-header {
        display: flex; align-items: flex-end; justify-content: space-between;
        gap: 2rem; margin-bottom: 3rem; flex-wrap: wrap;
    }
    .innovators-header .section-title { color: var(--white); }
    .innovators-header .section-tag   { color: var(--e1); }

    .inno-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .inno-card {
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: var(--r24);
        padding: 2rem;
        transition: border-color .25s, background .25s, transform .25s var(--ease-spring);
    }
    .inno-card:hover {
        border-color: rgba(200,232,64,.3);
        background: rgba(200,232,64,.04);
        transform: translateY(-4px);
    }

    .inno-badge {
        display: inline-flex; align-items: center; gap: .4rem;
        background: rgba(200,232,64,.12);
        border: 1px solid rgba(200,232,64,.2);
        color: var(--e1);
        padding: .3rem .8rem; border-radius: var(--r999);
        font-size: .7rem; font-weight: 700; letter-spacing: .1em;
        text-transform: uppercase; margin-bottom: 1.5rem;
    }

    .inno-icon { font-size: 2rem; margin-bottom: 1rem; display: block; }
    .inno-title {
        font-family: 'Cabinet Grotesk', sans-serif;
        font-weight: 700; font-size: 1.1rem;
        color: var(--white); letter-spacing: -.02em;
        margin-bottom: .6rem;
    }
    .inno-desc { font-size: .875rem; color: rgba(255,255,255,.45); line-height: 1.7; }

    .inno-cta {
        display: inline-flex; align-items: center; gap: .5rem;
        margin-top: 1.5rem;
        color: var(--e1); font-size: .82rem; font-weight: 700;
        font-family: 'Cabinet Grotesk', sans-serif;
        letter-spacing: .02em;
        transition: gap .2s;
    }
    .inno-card:hover .inno-cta { gap: .75rem; }

    /* ══════════════════════════════════════
       AI SECTION
    ══════════════════════════════════════ */
    .ai-section {
        padding: 7rem 2.5rem;
        max-width: 1180px; margin: 0 auto;
    }

    .ai-inner {
        background: linear-gradient(135deg, var(--g1) 0%, var(--g0) 60%);
        border-radius: 32px;
        padding: 5rem 4rem;
        display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;
        position: relative; overflow: hidden;
        box-shadow: 0 40px 100px rgba(13,31,20,.3);
    }
    .ai-inner::before {
        content: '';
        position: absolute; top: -100px; right: -100px;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(200,232,64,.12) 0%, transparent 70%);
        border-radius: 50%;
    }

    .ai-left { position: relative; z-index: 2; }
    .ai-tag { color: var(--e1); margin-bottom: .75rem; }
    .ai-title {
        font-size: clamp(2rem, 3.5vw, 2.75rem);
        color: var(--white); margin-bottom: 1.25rem;
    }
    .ai-title em { color: var(--e1); }
    .ai-desc {
        font-size: 1rem; line-height: 1.8;
        color: rgba(255,255,255,.5); margin-bottom: 2rem;
    }

    .ai-features { list-style: none; display: flex; flex-direction: column; gap: .75rem; margin-bottom: 2.5rem; }
    .ai-features li {
        display: flex; align-items: flex-start; gap: .75rem;
        font-size: .9rem; color: rgba(255,255,255,.65); line-height: 1.5;
    }
    .ai-features li::before {
        content: '✦';
        color: var(--e1); flex-shrink: 0; font-size: .65rem;
        margin-top: .2rem;
    }

    /* AI chat widget mock */
    .ai-right { position: relative; z-index: 2; }
    .ai-chat {
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: var(--r24);
        padding: 1.75rem;
    }
    .ai-chat-header {
        display: flex; align-items: center; gap: .75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .ai-avatar {
        width: 38px; height: 38px;
        background: linear-gradient(135deg, var(--e1), var(--g3));
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
    }
    .ai-name { font-family: 'Cabinet Grotesk', sans-serif; font-weight: 700; color: var(--white); font-size: .9rem; }
    .ai-status { font-size: .72rem; color: var(--e1); }
    .ai-online {
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--e1); margin-left: auto;
        box-shadow: 0 0 0 2px rgba(200,232,64,.3);
        animation: blink 2s ease infinite;
    }

    .chat-bubble {
        border-radius: 16px; padding: 1rem 1.25rem;
        font-size: .85rem; line-height: 1.6; margin-bottom: .75rem;
    }
    .bubble-ai {
        background: rgba(255,255,255,.08);
        color: rgba(255,255,255,.75);
        border-bottom-left-radius: 4px;
    }
    .bubble-user {
        background: var(--e1);
        color: var(--g0);
        font-weight: 500;
        margin-left: 2rem;
        border-bottom-right-radius: 4px;
        text-align: right;
    }
    .chat-typing {
        display: flex; gap: 4px; align-items: center;
        padding: .75rem 1rem;
    }
    .typing-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: rgba(255,255,255,.3);
        animation: typing .9s ease infinite;
    }
    .typing-dot:nth-child(2) { animation-delay: .15s; }
    .typing-dot:nth-child(3) { animation-delay: .3s; }
    @keyframes typing {
        0%,60%,100% { transform: translateY(0); opacity: .4; }
        30% { transform: translateY(-6px); opacity: 1; }
    }

    /* ══════════════════════════════════════
       MARKETPLACE MODES
    ══════════════════════════════════════ */
    .modes {
        background: var(--s1);
        padding: 7rem 2.5rem;
        border-top: 1px solid var(--s2);
        border-bottom: 1px solid var(--s2);
    }
    .modes-inner { max-width: 1180px; margin: 0 auto; }

    .modes-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;
        margin-top: 3rem;
    }

    .mode-card {
        border-radius: var(--r24); padding: 3rem;
        position: relative; overflow: hidden;
        transition: transform .25s var(--ease-spring), box-shadow .25s;
    }
    .mode-card:hover { transform: translateY(-6px); }

    .mode-card.b2c {
        background: var(--white);
        border: 1px solid var(--s2);
        box-shadow: 0 8px 40px rgba(13,31,20,.06);
    }
    .mode-card.b2b {
        background: var(--g0);
        color: var(--white);
        box-shadow: 0 16px 60px rgba(13,31,20,.2);
    }
    .mode-card.b2b:hover { box-shadow: 0 24px 80px rgba(13,31,20,.3); }

    .mode-chip {
        display: inline-block;
        padding: .35rem .9rem; border-radius: var(--r999);
        font-size: .7rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
        margin-bottom: 2rem;
    }
    .b2c .mode-chip { background: var(--g6); color: var(--g2); }
    .b2b .mode-chip { background: rgba(200,232,64,.15); color: var(--e1); }

    .mode-icon { font-size: 3rem; margin-bottom: 1.5rem; display: block; }

    .mode-title {
        font-family: 'Fraunces', serif;
        font-size: 1.75rem; font-weight: 700;
        letter-spacing: -0.02em; margin-bottom: 1rem;
    }
    .b2c .mode-title { color: var(--g0); }
    .b2b .mode-title { color: var(--white); }

    .mode-desc { font-size: .9rem; line-height: 1.75; }
    .b2c .mode-desc { color: var(--muted); }
    .b2b .mode-desc { color: rgba(255,255,255,.5); }

    .mode-list { list-style: none; margin-top: 1.5rem; display: flex; flex-direction: column; gap: .6rem; }
    .mode-list li { display: flex; align-items: center; gap: .6rem; font-size: .875rem; }
    .b2c .mode-list li { color: var(--muted); }
    .b2b .mode-list li { color: rgba(255,255,255,.55); }
    .mode-list li::before { content: '→'; font-weight: 700; flex-shrink: 0; }
    .b2c .mode-list li::before { color: var(--g3); }
    .b2b .mode-list li::before { color: var(--e1); }

    /* ══════════════════════════════════════
       MISSION / ABOUT
    ══════════════════════════════════════ */
    .mission {
        padding: 7rem 2.5rem;
        max-width: 1180px; margin: 0 auto;
        display: grid; grid-template-columns: 1fr 1fr; gap: 6rem; align-items: center;
    }

    .mission-visual {
        position: relative;
    }
    .mission-card-main {
        background: var(--g1);
        border-radius: var(--r24);
        padding: 3rem;
        color: var(--white);
        box-shadow: 0 24px 64px rgba(13,31,20,.2);
    }
    .mission-card-float {
        position: absolute;
        bottom: -1.5rem; right: -1.5rem;
        background: var(--e1);
        border-radius: var(--r16);
        padding: 1.25rem 1.75rem;
        font-family: 'Cabinet Grotesk', sans-serif;
        font-weight: 800; font-size: 1.5rem;
        color: var(--g0); letter-spacing: -0.04em;
        box-shadow: 0 12px 30px rgba(200,232,64,.35);
    }
    .mission-card-float small {
        display: block; font-size: .72rem;
        font-weight: 500; opacity: .7; letter-spacing: .02em;
        margin-top: .1rem;
    }

    .mission-quote {
        font-family: 'Fraunces', serif;
        font-size: 1.3rem; font-style: italic;
        font-weight: 300; line-height: 1.6;
        color: rgba(255,255,255,.8);
        margin-bottom: 1.5rem;
    }
    .mission-attr {
        font-size: .82rem; color: var(--e1);
        font-family: 'Cabinet Grotesk', sans-serif;
        font-weight: 700;
    }

    .mission-text .section-title { max-width: none; }
    .mission-text .section-body  { max-width: none; }

    .values { list-style: none; margin-top: 2rem; display: flex; flex-direction: column; gap: 1rem; }
    .values li {
        display: flex; align-items: flex-start; gap: 1rem;
        padding: 1rem 1.25rem;
        border: 1px solid var(--s2);
        border-radius: var(--r16);
        transition: border-color .2s, background .2s;
    }
    .values li:hover {
        border-color: var(--g5);
        background: var(--g6);
    }
    .values-icon { font-size: 1.3rem; flex-shrink: 0; margin-top: .1rem; }
    .values-body {}
    .values-title { font-family: 'Cabinet Grotesk', sans-serif; font-weight: 700; font-size: .95rem; color: var(--g0); }
    .values-desc  { font-size: .82rem; color: var(--muted); margin-top: .2rem; line-height: 1.5; }

    /* ══════════════════════════════════════
       DEVICES STRIP
    ══════════════════════════════════════ */
    .devices {
        background: var(--g0);
        padding: 4rem 2.5rem;
        text-align: center;
    }
    .devices-inner { max-width: 1180px; margin: 0 auto; }
    .devices-label { color: rgba(255,255,255,.35); margin-bottom: 1.5rem; }
    .devices-row { display: flex; align-items: center; justify-content: center; gap: 3rem; flex-wrap: wrap; }
    .device-item {
        display: flex; align-items: center; gap: .6rem;
        color: rgba(255,255,255,.5); font-size: .9rem; font-weight: 500;
        padding: .6rem 1.25rem;
        border: 1px solid rgba(255,255,255,.08);
        border-radius: var(--r999);
        transition: border-color .2s, color .2s;
    }
    .device-item:hover { border-color: rgba(200,232,64,.3); color: var(--e1); }
    .device-item span { font-size: 1.2rem; }

    /* ══════════════════════════════════════
       FINAL CTA
    ══════════════════════════════════════ */
    .final-cta {
        padding: 7rem 2.5rem;
        max-width: 1180px; margin: 0 auto;
        text-align: center;
    }

    .cta-blob {
        position: relative; display: inline-block;
        background: var(--g0);
        border-radius: 40px;
        padding: 6rem 4rem;
        width: 100%;
        overflow: hidden;
        box-shadow: 0 40px 120px rgba(13,31,20,.2);
    }
    .cta-blob::before {
        content: '';
        position: absolute; top: -80px; left: 50%; transform: translateX(-50%);
        width: 600px; height: 400px;
        background: radial-gradient(ellipse, rgba(200,232,64,.1) 0%, transparent 65%);
        border-radius: 50%; pointer-events: none;
    }

    .cta-blob .section-tag { color: var(--e1); }
    .cta-blob .section-title { color: var(--white); margin: 0 auto 1.25rem; text-align: center; max-width: 700px; }
    .cta-blob .section-body  { color: rgba(255,255,255,.45); margin: 0 auto 3rem; text-align: center; max-width: 520px; }

    .cta-actions { display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap; }

    /* ══════════════════════════════════════
       FOOTER
    ══════════════════════════════════════ */
    .footer {
        background: var(--ink);
        padding: 3.5rem 2.5rem;
    }
    .footer-inner {
        max-width: 1180px; margin: 0 auto;
        display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 3rem;
    }
    .footer-brand {}
    .footer-logo-wrap {
        display: flex; align-items: center; gap: .6rem; margin-bottom: 1rem;
    }
    .footer-logo-icon {
        width: 30px; height: 30px; background: var(--e1);
        border-radius: 8px; display: flex; align-items: center; justify-content: center;
        font-size: .9rem;
    }
    .footer-logo-name {
        font-family: 'Cabinet Grotesk', sans-serif; font-weight: 800;
        font-size: .95rem; color: var(--white); letter-spacing: -.02em;
    }
    .footer-logo-name span { color: var(--e1); }
    .footer-tagline { font-size: .82rem; color: rgba(255,255,255,.3); line-height: 1.6; max-width: 240px; }

    .footer-col h4 {
        font-family: 'Cabinet Grotesk', sans-serif;
        font-size: .72rem; font-weight: 700; letter-spacing: .12em;
        text-transform: uppercase; color: rgba(255,255,255,.3);
        margin-bottom: 1.25rem;
    }
    .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: .6rem; }
    .footer-col a { font-size: .85rem; color: rgba(255,255,255,.45); transition: color .2s; }
    .footer-col a:hover { color: var(--e1); }

    .footer-bottom {
        max-width: 1180px; margin: 2.5rem auto 0;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255,255,255,.06);
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;
    }
    .footer-copy { font-size: .78rem; color: rgba(255,255,255,.2); }
    .footer-olivia { font-size: .78rem; color: rgba(255,255,255,.2); }
    .footer-olivia strong { color: var(--e1); }

    /* ══════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════ */
    @media (max-width: 1024px) {
        .pillars-grid { grid-template-columns: 1fr 1fr; }
        .pillar:nth-child(1),
        .pillar:nth-child(2),
        .pillar:nth-child(3),
        .pillar:nth-child(4),
        .pillar:nth-child(5) { grid-column: span 6; }
        .inno-grid { grid-template-columns: 1fr 1fr; }
        .ai-inner  { grid-template-columns: 1fr; padding: 3rem 2rem; }
        .mission   { grid-template-columns: 1fr; gap: 3rem; }
        .footer-inner { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 768px) {
        .nav { padding: .9rem 1.5rem; }
        .nav-links { display: none; }
        .hero-inner { grid-template-columns: 1fr; padding: 3rem 1.5rem; }
        .hero-blob { display: none; }
        .pillars, .pillars-grid { padding: 4rem 1.5rem; }
        .pillar:nth-child(n) { grid-column: span 12; }
        .pillars-grid { grid-template-columns: 1fr; gap: 1rem; }
        .innovators { padding: 4rem 1.5rem; }
        .inno-grid { grid-template-columns: 1fr; }
        .modes-grid { grid-template-columns: 1fr; }
        .modes  { padding: 4rem 1.5rem; }
        .ai-section { padding: 4rem 1.5rem; }
        .final-cta  { padding: 4rem 1.5rem; }
        .cta-blob   { padding: 3rem 2rem; }
        .footer-inner { grid-template-columns: 1fr; gap: 2rem; }
    }
    </style>
</head>
<body>

<!-- ════════════════ NAV ════════════════ -->
<nav class="nav">
    <a href="/" class="nav-logo">
        <div class="nav-logo-icon">🌿</div>
        <span class="nav-logo-name">Sustain<span>Chain</span></span>
    </a>

    <ul class="nav-links">
        <li><a href="#innovators">Discover Innovators</a></li>
        <li><a href="#marketplace">Marketplace</a></li>
        <li><a href="#mission">Our Mission</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>

    <div class="nav-right">
        <a href="/users/login" class="btn btn-outline">Log in</a>
        <a href="/users/register" class="btn btn-lime">Join SustainChain</a>
    </div>
</nav>


<!-- ════════════════ HERO ════════════════ -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-inner">
        <div class="hero-left">
            <div class="hero-eyebrow t-label">
                <span class="eyebrow-dot"></span>
                Sustainable Commerce Platform
            </div>

            <h1 class="hero-title t-display">
                Commerce that's<br>
                good for the <em>planet</em>
            </h1>

            <p class="hero-sub">
                SustainChain connects buyers, sellers, manufacturers, and farmers
                in a vibrant marketplace built on transparency, sustainability, and
                ethical trade - from farm to door.
            </p>

            <div class="hero-actions">
                <a href="/users/register" class="btn btn-lime btn-lg">
                    Shop now
                </a>
            </div>
        </div>

        <div class="hero-panel">
            <div class="hero-stats">
                <div class="stat-pill">
                    <div class="stat-num">B2C</div>
                    <div class="stat-desc">Direct eco-product shopping for conscious consumers</div>
                </div>
                <div class="stat-pill">
                    <div class="stat-num">B2B</div>
                    <div class="stat-desc">Business partnerships built on sustainable values</div>
                </div>
                <div class="stat-pill">
                    <div class="stat-desc">Web & mobile - shop anywhere, anytime</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════ PLATFORM PILLARS ════════════════ -->
<section class="pillars" id="platform">
    <div class="section-header">
        <p class="t-label section-tag">The Platform</p>
        <h2 class="section-title t-display">
            Everything you need to <em>trade sustainably</em>
        </h2>
        <p class="section-body">
            SustainChain is more than a marketplace. It's a complete ecosystem for responsible commerce,
            giving every participant the tools to make a measurable difference.
        </p>
    </div>

    <div class="pillars-grid">
        <div class="pillar pillar-dark">
            <p class="t-label pillar-tag">Eco Marketplace</p>
            <p class="pillar-title">Shop with purpose</p>
            <p class="pillar-desc">Browse thousands of verified eco-friendly products — from organic food to sustainable fashion — curated so every purchase makes an impact. Full product transparency, ethical sourcing labels, and carbon-footprint scores.</p>
        </div>

        <div class="pillar pillar-forest">
            <p class="t-label pillar-tag">B2B Relationships</p>
            <p class="pillar-title">Scale your sustainable business</p>
            <p class="pillar-desc">Connect with like-minded businesses, negotiate bulk deals, and build long-term supply chain relationships grounded in shared environmental values.</p>
        </div>

        <div class="pillar pillar-light">
            <p class="t-label pillar-tag">Farmers Direct</p>
            <p class="pillar-title">Farm to marketplace</p>
            <p class="pillar-desc">Farmers list produce directly. No middlemen, fair prices, full visibility of growing practices.</p>
        </div>

        <div class="pillar pillar-lime">
            <p class="t-label pillar-tag">Impact Tracking</p>
            <p class="pillar-title">Measure your footprint</p>
            <p class="pillar-desc">Real-time sustainability scores for every purchase, seller, and supply chain node.</p>
        </div>

        <div class="pillar pillar-light">
            <p class="t-label pillar-tag">Trust & Verification</p>
            <p class="pillar-title">Certified & verified</p>
            <p class="pillar-desc">Every seller goes through our rigorous eco-certification process before joining the platform.</p>
        </div>
    </div>
</section>


<!-- ════════════════ DISCOVER INNOVATORS ════════════════ -->
<section class="innovators" id="innovators">
    <div class="innovators-bg"></div>
    <div class="innovators-inner">
        <div class="innovators-header">
            <div>
                <p class="t-label section-tag">Discover Innovators</p>
                <h2 class="section-title t-display">
                    Meet the makers<br>changing the world
                </h2>
            </div>
            <a href="/manufacturers" class="btn btn-lime">Browse all innovators →</a>
        </div>

        <div class="inno-grid">
            <div class="inno-card">
                <span class="inno-badge">Manufacturer</span>
                <p class="inno-title">Circular packaging solutions</p>
                <p class="inno-desc">Manufacturers pioneering 100% compostable and reusable packaging for the food and logistics industry.</p>
                <a href="/manufacturers" class="inno-cta">Explore category →</a>
            </div>

            <div class="inno-card">
                <span class="inno-badge">AgriTech</span>
                <p class="inno-title">Regenerative farming tech</p>
                <p class="inno-desc">Innovators bringing soil restoration, water-saving irrigation, and precision farming to smallholder farmers worldwide.</p>
                <a href="/manufacturers" class="inno-cta">Explore category →</a>
            </div>

            <div class="inno-card">
                <span class="inno-badge">Clean Energy</span>
                <p class="inno-title">Renewable-powered production</p>
                <p class="inno-desc">Manufacturers who have committed to 100% renewable energy across their production lines - verified on-chain.</p>
                <a href="/manufacturers" class="inno-cta">Explore category →</a>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════ MARKETPLACE MODES ════════════════ -->
<section class="modes" id="marketplace">
    <div class="modes-inner">
        <p class="t-label section-tag">Marketplace</p>
        <h2 class="section-title t-display">
            Built for every kind of<br><em>sustainable commerce</em>
        </h2>

        <div class="modes-grid">
            <div class="mode-card b2c">
                <span class="mode-chip">B2C · Consumer</span>
                <h3 class="mode-title">Shop consciously</h3>
                <p class="mode-desc">Discover and purchase eco-friendly products directly from verified sellers, farmers, and makers. Every product comes with full sustainability transparency.</p>
                <ul class="mode-list">
                    <li>Verified eco-labels on every product</li>
                    <li>Carbon footprint scores at checkout</li>
                    <li>Direct from farmers — no middlemen</li>
                    <li>Community reviews and impact stories</li>
                </ul>
            </div>

            <div class="mode-card b2b">
                <span class="mode-chip">B2B · Business</span>
                <h3 class="mode-title">Scale sustainably</h3>
                <p class="mode-desc">Build lasting business-to-business relationships with partners who share your commitment to responsible commerce and environmental stewardship.</p>
                <ul class="mode-list">
                    <li>Bulk purchasing with verified suppliers</li>
                    <li>Supply chain transparency dashboard</li>
                    <li>Connect with certified manufacturers</li>
                    <li>ESG reporting & compliance tools</li>
                </ul>
            </div>
        </div>
    </div>
</section>


<!-- ════════════════ MISSION / ABOUT ════════════════ -->
<section class="mission" id="mission">
    <div class="mission-text">
        <p class="t-label section-tag">Our Mission</p>
        <h2 class="section-title t-display">
            A greener future<br>starts with <em>better trade</em>
        </h2>
        <p class="section-body">
            Led by Olivia Anderson, SustainChain was founded on the belief that commerce can be a force
            for good. By connecting every participant in the supply chain - from the farmer growing the crop
            to the consumer at the door — we make sustainability the default, not the exception.
        </p>

        <ul class="values">
            <li>
                <div class="values-body">
                    <p class="values-title">Responsible consumption</p>
                    <p class="values-desc">Every product on SustainChain is vetted for genuine environmental credentials - no greenwashing.</p>
                </div>
            </li>
            <li>
                <div class="values-body">
                    <p class="values-title">Ethical commerce</p>
                    <p class="values-desc">Fair pricing for farmers, transparent margins for sellers, and trust for buyers.</p>
                </div>
            </li>
            <li>
                <div class="values-body">
                    <p class="values-title">Community-driven</p>
                    <p class="values-desc">A living, growing community of people who believe trade and ecology can co-exist.</p>
                </div>
            </li>
        </ul>
    </div>
</section>

<!-- ════════════════ FINAL CTA ════════════════ -->
<section class="final-cta">
    <div class="cta-blob">
        <p class="t-label section-tag" style="margin-bottom:.75rem">Get started today</p>
        <h2 class="section-title t-display">
            Ready to join the<br><em>sustainable commerce</em> revolution?
        </h2>
        <p class="section-body">
            Whether you're a buyer, seller, manufacturer, or farmer -
            SustainChain has a place for you. Join Olivia and a growing community
            committed to a greener future.
        </p>
        <div class="cta-actions">
            <a href="/users/register" class="btn btn-lime btn-lg">Create an account →</a>
            <a href="/users/login" class="btn btn-outline btn-lg">Sign in</a>
        </div>
    </div>
</section>


<!-- ════════════════ FOOTER ════════════════ -->
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="footer-logo-wrap">
                <span class="footer-logo-name">Sustain<span>Chain</span></span>
            </div>
            <p class="footer-tagline">A vibrant marketplace for responsible consumption and a greener future.</p>
        </div>

        <div class="footer-col">
            <h4>Platform</h4>
            <ul>
                <li><a href="#platform">Features</a></li>
                <li><a href="#marketplace">Marketplace</a></li>
                <li><a href="#innovators">Discover Innovators</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Commerce</h4>
            <ul>
                <li><a href="/buyers">For Buyers</a></li>
                <li><a href="/sellers">For Sellers</a></li>
                <li><a href="/manufacturers">For Manufacturers</a></li>
                <li><a href="/farmers">For Farmers</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Company</h4>
            <ul>
                <li><a href="#mission">Our Mission</a></li>
                <li><a href="/pages/about">About</a></li>
                <li><a href="/pages/contact">Contact</a></li>
                <li><a href="/users/login">Log in</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p class="footer-copy">© <?= date('Y') ?> SustainChain. All rights reserved.</p>
    </div>
</footer>

</body>
</html>