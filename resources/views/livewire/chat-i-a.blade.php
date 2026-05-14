<div
    class="clinicaly-chat-shell"
    x-data="{
        sidebarOpen: false,
        chatSearch: '',
        editSession: { open: false, id: null, title: '' },
        deleteSessionModal: { open: false, id: null },
        openEditSession(id, title) {
            this.editSession = { open: true, id, title: title || '' };
        },
        openDeleteSession(id) {
            this.deleteSessionModal = { open: true, id };
        },
        closeSessionModals() {
            this.editSession.open = false;
            this.deleteSessionModal.open = false;
        }
    }"
    :class="{ 'sidebar-open': sidebarOpen }"
>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }

        .clinicaly-chat-shell {
            --in: #6d55b1;
            --in2: #7e68c0;
            --in3: #9880d4;
            --bg: #f6f8fb;
            --grid: rgba(109, 85, 177, .055);
            --sf: rgba(255, 255, 255, .78);
            --sf2: rgba(255, 255, 255, .92);
            --sf3: #eef2f8;
            --sf4: #e6ebf4;
            --bd: #dbe2ee;
            --bd2: #cbd5e5;
            --bd3: #aebbd0;
            --tx: #192132;
            --tx2: #41506a;
            --mu: #6e7a91;
            --fa: #9aa6ba;
            --gr: #20a66c;
            --grb: #e8f7f0;
            --grd: #9edfc0;
            --bl: #3578d8;
            --cy: #0891b2;
            --cyb: #e7f8fc;
            --rd: #dc2626;
            --rdb: #fff0f0;
            --wn: #d97706;
            --wnb: #fff7e6;
            --glow-in: 0 18px 50px rgba(109,85,177,.16);
            --glow-gr: 0 0 16px rgba(32,166,108,.22);
            --sh: 0 10px 30px rgba(24, 32, 50, .08), 0 2px 8px rgba(24, 32, 50, .06);
            --sh2: 0 18px 48px rgba(24, 32, 50, .12);
            --r: 18px;
            --rm: 12px;
            --rs: 8px;
            --sidebar-w: 288px;
            position: relative;
            z-index: 1;
            display: flex;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            background:
                radial-gradient(circle at 60% -10%, rgba(34, 211, 238, .16), transparent 34%),
                linear-gradient(var(--grid) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid) 1px, transparent 1px),
                var(--bg);
            background-size: auto, 48px 48px, 48px 48px, auto;
            color: var(--tx);
            font-family: 'Dosis', sans-serif;
        }

        html[data-theme="dark"] .clinicaly-chat-shell {
            --bg: #07060f;
            --grid: rgba(109,85,177,.04);
            --sf: rgba(14,12,28,.86);
            --sf2: rgba(19,17,40,.92);
            --sf3: #1a1735;
            --sf4: #201d3f;
            --bd: #252040;
            --bd2: #302a55;
            --bd3: #3d3570;
            --tx: #e8e4f8;
            --tx2: #c0bad8;
            --mu: #8880aa;
            --fa: #463e6a;
            --gr: #34c98a;
            --grb: #0d2e20;
            --grd: #1a5c3c;
            --cy: #22d3ee;
            --cyb: #0a2535;
            --rd: #ef4444;
            --rdb: #2e0d0d;
            --wn: #f59e0b;
            --wnb: #2e1d00;
            --sh: 0 2px 8px rgba(0,0,0,.5), 0 1px 2px rgba(0,0,0,.4);
            --sh2: 0 4px 20px rgba(0,0,0,.6), 0 2px 6px rgba(0,0,0,.4);
            --glow-in: 0 0 24px rgba(109,85,177,.35), 0 0 48px rgba(109,85,177,.12);
        }

        .clinicaly-chat-shell *, .clinicaly-chat-shell *::before, .clinicaly-chat-shell *::after {
            box-sizing: border-box;
        }

        .clinicaly-chat-shell button,
        .clinicaly-chat-shell input,
        .clinicaly-chat-shell textarea {
            font: inherit;
        }

        .clinicaly-chat-shell button {
            cursor: pointer;
        }

        .clinicaly-chat-shell a {
            color: inherit;
            text-decoration: none;
        }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideInL { from { opacity: 0; transform: translateX(-14px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes slideInR { from { opacity: 0; transform: translateX(14px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes pulseDot { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: .45; transform: scale(.72); } }
        @keyframes typing { 0%,60%,100% { opacity: 1; } 30% { opacity: .25; } }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
        @keyframes caretBlink { 0%,100% { opacity: 1; } 50% { opacity: 0; } }

        .chat-sidebar {
            width: var(--sidebar-w);
            flex: 0 0 var(--sidebar-w);
            display: flex;
            flex-direction: column;
            min-height: 0;
            padding: 18px;
            border-right: 1px solid var(--bd);
            background: var(--sf);
            backdrop-filter: blur(18px);
            box-shadow: 8px 0 32px rgba(25, 33, 50, .04);
        }

        .brand-row {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
        }

        .brand-logo-link {
            display: inline-flex;
            align-items: center;
            min-width: 0;
        }

        .brand-logo {
            width: 158px;
            max-height: 44px;
            object-fit: contain;
            display: block;
        }

        .new-chat {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--in), var(--in2));
            color: #fff;
            font-size: .86rem;
            font-weight: 800;
            box-shadow: var(--glow-in);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .new-chat:hover { transform: translateY(-1px); }
        .new-chat svg { width: 15px; height: 15px; }

        .chat-search {
            position: relative;
            margin-top: 12px;
        }
        .chat-search input {
            width: 100%;
            min-height: 38px;
            border: 1px solid var(--bd);
            border-radius: 14px;
            background: var(--sf2);
            color: var(--tx);
            padding: 9px 12px 9px 34px;
            outline: 0;
            font-size: .82rem;
            font-weight: 700;
        }
        .chat-search input:focus {
            border-color: var(--in3);
            box-shadow: 0 0 0 3px rgba(109,85,177,.13);
        }
        .chat-search svg {
            position: absolute;
            left: 12px;
            top: 50%;
            width: 14px;
            height: 14px;
            transform: translateY(-50%);
            color: var(--mu);
            pointer-events: none;
        }

        .sidebar-section {
            min-height: 0;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid var(--bd);
        }

        .sidebar-section.history {
            flex: 1;
            overflow-y: auto;
            padding-right: 2px;
        }

        .sidebar-section.history::-webkit-scrollbar,
        .messages::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-section.history::-webkit-scrollbar-thumb,
        .messages::-webkit-scrollbar-thumb {
            background: var(--bd2);
            border-radius: 999px;
        }

        .sb-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 12px;
            color: var(--in3);
            font-family: 'Space Mono', monospace;
            font-size: .52rem;
            text-transform: uppercase;
            letter-spacing: .12em;
        }
        .sb-title svg { width: 13px; height: 13px; }

        .conv-row {
            position: relative;
            display: flex;
            align-items: stretch;
            gap: 6px;
            margin-bottom: 6px;
        }

        .conv-item {
            flex: 1;
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px;
            border: 1px solid transparent;
            border-radius: 13px;
            background: transparent;
            color: var(--tx2);
            text-align: left;
            transition: .2s ease;
        }
        .conv-item:hover,
        .conv-item.active {
            background: var(--sf2);
            border-color: var(--bd2);
            color: var(--tx);
        }

        .conv-ico {
            width: 30px;
            height: 30px;
            flex: 0 0 auto;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--sf3);
            border: 1px solid var(--bd2);
            color: var(--in3);
        }
        .conv-ico svg { width: 14px; height: 14px; }
        .conv-info { min-width: 0; }
        .conv-title {
            display: block;
            font-size: .84rem;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .conv-sub {
            display: block;
            margin-top: 1px;
            color: var(--mu);
            font-size: .7rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .conv-menu-trigger {
            width: 28px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: var(--fa);
            opacity: 0;
            transition: .2s ease;
        }
        .conv-row:hover .conv-menu-trigger { opacity: 1; }
        .conv-menu-trigger:hover { background: var(--sf3); color: var(--in3); }

        .popover {
            position: absolute;
            right: 0;
            top: 38px;
            z-index: 9999;
            width: 140px;
            padding: 7px;
            border: 1px solid var(--bd);
            border-radius: 13px;
            background: var(--sf2);
            box-shadow: var(--sh2);
            backdrop-filter: blur(18px);
        }
        .popover button {
            width: 100%;
            padding: 8px 10px;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: var(--tx2);
            text-align: left;
            font-size: .74rem;
            font-weight: 700;
        }
        .popover button:hover { background: var(--sf3); color: var(--in3); }
        .popover button.danger { color: var(--rd); }

        .modal-backdrop {
            --sf2: rgba(255, 255, 255, .96);
            --sf3: #eef2f8;
            --bd: #dbe2ee;
            --bd2: #cbd5e5;
            --tx: #192132;
            --tx2: #41506a;
            --mu: #6e7a91;
            --in: #6d55b1;
            --in2: #7e68c0;
            --in3: #9880d4;
            --rd: #dc2626;
            --sh2: 0 18px 48px rgba(24, 32, 50, .12);
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(3, 5, 12, .45);
            backdrop-filter: blur(10px);
        }
        html[data-theme="dark"] .modal-backdrop {
            --sf2: rgba(19,17,40,.96);
            --sf3: #1a1735;
            --bd: #252040;
            --bd2: #302a55;
            --tx: #e8e4f8;
            --tx2: #c0bad8;
            --mu: #8880aa;
            --rd: #ef4444;
            --sh2: 0 4px 20px rgba(0,0,0,.6), 0 2px 6px rgba(0,0,0,.4);
        }
        .modal-card {
            width: min(330px, 100%);
            padding: 20px;
            border: 1px solid var(--bd);
            border-radius: 20px;
            background: var(--sf2);
            color: var(--tx);
            box-shadow: var(--sh2);
        }
        .modal-card h3 {
            margin: 0 0 12px;
            font-size: 1rem;
            font-weight: 800;
        }
        .modal-card p {
            margin: 0 0 16px;
            color: var(--mu);
            font-size: .82rem;
            line-height: 1.45;
        }
        .modal-card input {
            width: 100%;
            margin-bottom: 14px;
            padding: 10px 12px;
            border: 1px solid var(--bd2);
            border-radius: 12px;
            background: var(--sf3);
            color: var(--tx);
            outline: 0;
        }
        .modal-actions { display: flex; gap: 8px; }
        .modal-actions button {
            flex: 1;
            min-height: 36px;
            border: 0;
            border-radius: 11px;
            font-size: .78rem;
            font-weight: 800;
        }
        .btn-muted { background: var(--sf3); color: var(--tx2); }
        .btn-save { background: linear-gradient(135deg, var(--in), var(--in2)); color: #fff; }
        .btn-danger { background: var(--rd); color: #fff; }

        .sidebar-footer {
            margin-top: 14px;
            display: grid;
            gap: 10px;
        }

        .status-pill {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 10px 12px;
            border: 1px solid var(--bd);
            border-radius: 14px;
            background: var(--sf2);
            color: var(--gr);
            font-family: 'Space Mono', monospace;
            font-size: .5rem;
            text-transform: uppercase;
            letter-spacing: .1em;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--gr);
            box-shadow: var(--glow-gr);
            animation: pulseDot 2s ease infinite;
        }

        .dashboard-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            border: 1px solid var(--bd);
            border-radius: 14px;
            background: var(--sf2);
            color: var(--tx2);
            font-size: .82rem;
            font-weight: 800;
            transition: .2s ease;
        }
        .dashboard-link:hover { color: var(--in3); border-color: var(--bd2); }

        .chat-main {
            position: relative;
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .mobile-chatbar {
            display: none;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-bottom: 1px solid var(--bd);
            background: var(--sf);
            backdrop-filter: blur(16px);
        }
        .hamb-chat {
            width: 38px;
            height: 38px;
            border: 1px solid var(--bd);
            border-radius: 12px;
            background: var(--sf2);
            color: var(--tx2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .hamb-chat svg { width: 18px; height: 18px; }
        .mobile-chatbar-title {
            color: var(--tx);
            font-weight: 800;
            font-size: .95rem;
        }
        .sidebar-scrim {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 70;
            background: rgba(3,5,12,.48);
            backdrop-filter: blur(3px);
        }

        .messages {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding: 34px 24px 170px;
            scroll-behavior: smooth;
        }

        .messages-inner {
            width: min(900px, 100%);
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .chat-welcome {
            min-height: 55vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
            text-align: center;
            animation: fadeUp .35s ease both;
        }
        .cw-orb {
            width: 78px;
            height: 78px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--sf2);
            border: 1px solid var(--bd2);
            box-shadow: var(--glow-in);
            animation: float 4s ease-in-out infinite;
        }
        .cw-orb svg { width: 34px; height: 34px; color: var(--in3); }
        .cw-title {
            margin: 0;
            font-size: clamp(1.7rem, 4vw, 2.4rem);
            font-weight: 800;
            color: var(--tx);
        }
        .cw-sub {
            max-width: 430px;
            margin: 0;
            color: var(--mu);
            font-size: .96rem;
            line-height: 1.6;
        }

        .msg {
            display: flex;
            gap: 12px;
            max-width: 100%;
            position: relative;
            animation: fadeUp .3s ease both;
        }
        .msg-ai { align-items: flex-start; animation-name: slideInL; }
        .msg-user { justify-content: flex-end; animation-name: slideInR; }

        .ai-avatar {
            width: 36px;
            height: 36px;
            flex: 0 0 auto;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--sf2);
            border: 1px solid var(--bd2);
            color: var(--in3);
            box-shadow: var(--sh);
            position: relative;
        }
        .ai-avatar::after {
            content: '';
            position: absolute;
            right: -2px;
            bottom: -2px;
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: var(--gr);
            border: 2px solid var(--sf2);
        }
        .ai-avatar svg { width: 18px; height: 18px; }

        .bubble-wrap {
            position: relative;
            width: fit-content;
            min-width: 0;
            max-width: min(76ch, calc(100% - 48px));
            overflow: visible;
        }
        .msg-user .bubble-wrap {
            max-width: min(68ch, calc(100% - 12px));
        }
        .bubble {
            position: relative;
            width: 100%;
            min-width: 0;
            padding: 14px 17px;
            border-radius: 6px var(--r) var(--r) var(--r);
            background: var(--sf2);
            border: 1px solid var(--bd2);
            box-shadow: var(--sh);
            color: var(--tx2);
            overflow: hidden;
        }
        .msg-ai .bubble::before {
            content: '';
            position: absolute;
            left: -1px;
            top: 0;
            width: 3px;
            height: 40px;
            border-radius: 0 0 3px 3px;
            background: linear-gradient(to bottom, var(--in), transparent);
        }
        .msg-user .bubble {
            border: 0;
            border-radius: var(--r) 6px var(--r) var(--r);
            background: linear-gradient(135deg, var(--in), var(--in2));
            color: #fff;
            box-shadow: 0 10px 28px rgba(109,85,177,.24), var(--sh);
        }

        .bubble-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 7px;
        }
        .bubble-name {
            color: var(--in3);
            font-family: 'Space Mono', monospace;
            font-size: .5rem;
            text-transform: uppercase;
            letter-spacing: .1em;
        }
        .msg-user .bubble-name { color: rgba(255,255,255,.74); }
        .bubble-time {
            margin-left: auto;
            color: var(--fa);
            font-family: 'Space Mono', monospace;
            font-size: .46rem;
        }
        .msg-user .bubble-time { color: rgba(255,255,255,.58); }

        .bubble-text {
            font-size: .93rem;
            line-height: 1.66;
            font-weight: 500;
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        .bubble-text pre {
            max-width: 100%;
            overflow-x: auto;
            padding: 10px 12px;
            border-radius: 10px;
            background: color-mix(in srgb, var(--sf3) 84%, transparent);
            white-space: pre;
        }
        .bubble-text code {
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        .bubble-text img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .bubble-text.is-typing::after {
            content: '';
            display: inline-block;
            width: 7px;
            height: 1.1em;
            margin-left: 3px;
            vertical-align: -.16em;
            border-radius: 999px;
            background: var(--in3);
            animation: caretBlink .8s step-end infinite;
        }
        .bubble-text :first-child { margin-top: 0; }
        .bubble-text :last-child { margin-bottom: 0; }
        .bubble-text p { margin: 0 0 .72em; }
        .bubble-text ul, .bubble-text ol { margin: .7em 0 .7em 1.2em; }
        .bubble-text li { margin: .25em 0; }
        .bubble-text h1, .bubble-text h2, .bubble-text h3 {
            margin: .8em 0 .35em;
            color: var(--tx);
            font-size: 1rem;
            font-weight: 800;
        }
        .msg-user .bubble-text h1,
        .msg-user .bubble-text h2,
        .msg-user .bubble-text h3 { color: #fff; }
        .bubble-text table {
            display: block;
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            margin: .8em 0;
            border-collapse: collapse;
            font-size: .84rem;
        }
        .bubble-text th, .bubble-text td {
            padding: 7px;
            border: 1px solid var(--bd2);
            text-align: left;
        }
        .msg-user .bubble-text th,
        .msg-user .bubble-text td { border-color: rgba(255,255,255,.25); }

        .msg-tools {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            z-index: 9998;
            transition: .2s ease;
        }
        .bubble-wrap:hover .msg-tools { opacity: 1; }
        .msg-ai .msg-tools { right: -34px; }
        .msg-user .msg-tools { left: -34px; }
        .tool-dot {
            width: 26px;
            height: 26px;
            border: 0;
            border-radius: 999px;
            background: var(--sf2);
            color: var(--fa);
            box-shadow: var(--sh);
        }
        .tool-dot:hover { color: var(--rd); }

        .typing-dots {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .typing-dots span {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: var(--in3);
            animation: typing 1.4s ease infinite;
        }
        .typing-dots span:nth-child(2) { animation-delay: .2s; }
        .typing-dots span:nth-child(3) { animation-delay: .4s; }

        .composer-dock {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 20;
            display: flex;
            justify-content: center;
            padding: 10px 22px 18px;
            pointer-events: none;
            background: linear-gradient(to top, var(--bg) 0%, color-mix(in srgb, var(--bg) 80%, transparent) 58%, transparent 100%);
        }
        .composer-inner {
            width: min(680px, 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 9px;
            pointer-events: auto;
        }

        .chips-area {
            width: 100%;
            display: flex;
            justify-content: center;
        }
        .chips-scroll {
            display: flex;
            gap: 8px;
            max-width: 100%;
            overflow-x: auto;
            padding: 2px 2px 7px;
        }
        .chips-scroll::-webkit-scrollbar { height: 0; }
        .chip {
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            max-width: 260px;
            padding: 7px 13px;
            border: 1px solid var(--bd2);
            border-radius: 999px;
            background: var(--sf2);
            color: var(--tx2);
            box-shadow: var(--sh);
            backdrop-filter: blur(16px);
            font-size: .78rem;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: .2s ease;
        }
        .chip:hover { color: var(--in3); border-color: var(--bd3); transform: translateY(-1px); }
        .chip svg { width: 13px; height: 13px; flex: 0 0 auto; color: var(--in3); }

        .input-wrap {
            width: min(640px, 100%);
            display: flex;
            align-items: flex-end;
            gap: 8px;
            padding: 8px 9px 8px 14px;
            border: 1.5px solid var(--bd2);
            border-radius: 18px;
            background: var(--sf2);
            box-shadow: var(--sh2);
            backdrop-filter: blur(18px);
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .input-wrap:focus-within {
            border-color: var(--bd2);
            box-shadow: var(--sh2);
        }
        .chat-input {
            flex: 1;
            min-height: 24px;
            max-height: 92px;
            resize: none;
            border: 0;
            outline: 0;
            background: transparent;
            color: var(--tx);
            font-size: .92rem;
            font-weight: 600;
            line-height: 1.45;
        }
        .chat-input::placeholder { color: var(--fa); }
        .send-btn {
            width: 38px;
            height: 38px;
            flex: 0 0 auto;
            border: 0;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--in), var(--in2));
            color: #fff;
            box-shadow: 0 8px 18px rgba(109,85,177,.32);
            transition: .2s ease;
        }
        .send-btn:hover { transform: translateY(-1px); }
        .send-btn:disabled { opacity: .55; cursor: wait; transform: none; }
        .send-btn svg { width: 17px; height: 17px; }
        .input-footer {
            width: min(620px, 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 0 6px;
            color: var(--fa);
            font-family: 'Space Mono', monospace;
            font-size: .46rem;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        @media (max-width: 900px) {
            .clinicaly-chat-shell { --sidebar-w: 238px; }
            .chat-sidebar { padding: 14px; }
            .messages { padding-inline: 16px; }
        }

        @media (max-width: 720px) {
            .clinicaly-chat-shell {
                flex-direction: column;
                height: 100dvh;
            }
            .chat-sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 80;
                width: min(310px, 88vw);
                flex: none;
                max-height: none;
                transform: translateX(-102%);
                transition: transform .25s ease;
                border-right: 1px solid var(--bd);
                border-bottom: 0;
                box-shadow: var(--sh2);
            }
            .clinicaly-chat-shell.sidebar-open .chat-sidebar { transform: translateX(0); }
            .clinicaly-chat-shell.sidebar-open .sidebar-scrim { display: block; }
            .brand-row { display: none; }
            .mobile-chatbar { display: flex; }
            .sidebar-section.history { display: block; }
            .sidebar-footer { display: grid; }
            .messages { padding: 22px 14px 165px; }
            .bubble-wrap,
            .msg-user .bubble-wrap {
                max-width: calc(100% - 44px);
            }
            .composer-dock { padding-inline: 14px; }
            .input-footer { display: none; }
        }
    </style>

    <button type="button" class="sidebar-scrim" @click="sidebarOpen = false" aria-label="Fechar histórico"></button>

    <aside class="chat-sidebar" aria-label="Histórico de conversas">
        <button type="button" wire:click="createNewChat" @click="sidebarOpen = false" class="new-chat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Novo Chat
        </button>

        <label class="chat-search" aria-label="Pesquisar chats">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
            <input type="search" x-model="chatSearch" placeholder="Pesquisar chats">
        </label>

        <section class="sidebar-section history">
            <h2 class="sb-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                Histórico
            </h2>

            @foreach(\App\Models\ChatSession::where('user_id', auth()->id())->latest()->get() as $session)
                <div class="conv-row" x-data="{ menuOpen: false }" data-title="{{ strtolower($session->title) }}" x-show="$el.dataset.title.includes(chatSearch.toLowerCase())">
                    <button type="button" wire:click="switchSession({{ $session->id }})" @click="sidebarOpen = false" class="conv-item {{ $currentSessionId == $session->id ? 'active' : '' }}">
                        <span class="conv-ico" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>
                            </svg>
                        </span>
                        <span class="conv-info">
                            <span class="conv-title">{{ $session->title }}</span>
                            <span class="conv-sub">{{ optional($session->updated_at)->diffForHumans() }}</span>
                        </span>
                    </button>

                    <button type="button" class="conv-menu-trigger" @click="menuOpen = !menuOpen" aria-label="Opções da conversa">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
                        </svg>
                    </button>

                    <div x-show="menuOpen" @click.away="menuOpen = false" x-cloak class="popover">
                        <button type="button" @click="openEditSession({{ $session->id }}, @js($session->title)); menuOpen = false">Renomear</button>
                        <button type="button" class="danger" @click="openDeleteSession({{ $session->id }}); menuOpen = false">Excluir chat</button>
                    </div>
                </div>
            @endforeach
        </section>

        <div class="sidebar-footer">
            <div class="status-pill" role="status">
                <span class="status-dot" aria-hidden="true"></span>
                IA Online
            </div>
            <a href="{{ route('dashboard') }}" class="dashboard-link">Voltar ao início</a>
        </div>
    </aside>

    <template x-teleport="body">
        <div x-show="editSession.open" x-cloak class="modal-backdrop" @click.self="editSession.open = false" x-transition.opacity>
            <div @click.away="editSession.open = false" class="modal-card" role="dialog" aria-modal="true" aria-labelledby="rename-session-title">
                <h3 id="rename-session-title">Renomear conversa</h3>
                <input type="text" x-model="editSession.title" x-ref="titleInput" x-effect="if (editSession.open) $nextTick(() => $refs.titleInput?.focus())" aria-label="Novo nome da conversa">
                <div class="modal-actions">
                    <button type="button" class="btn-muted" @click="editSession.open = false">Cancelar</button>
                    <button type="button" class="btn-save" @click="$wire.renameSession(editSession.id, editSession.title); editSession.open = false">Salvar</button>
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="deleteSessionModal.open" x-cloak class="modal-backdrop" @click.self="deleteSessionModal.open = false" x-transition.opacity>
            <div @click.away="deleteSessionModal.open = false" class="modal-card" role="dialog" aria-modal="true" aria-labelledby="delete-session-title">
                <h3 id="delete-session-title">Eliminar conversa?</h3>
                <p>Esta ação remove todo o histórico desta conversa.</p>
                <div class="modal-actions">
                    <button type="button" class="btn-muted" @click="deleteSessionModal.open = false">Manter</button>
                    <button type="button" class="btn-danger" @click="$wire.deleteSession(deleteSessionModal.id); deleteSessionModal.open = false">Excluir</button>
                </div>
            </div>
        </div>
    </template>

    <main class="chat-main" role="main">
        <header class="mobile-chatbar">
            <button type="button" class="hamb-chat" @click="sidebarOpen = true" aria-label="Abrir histórico de chats">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <span class="mobile-chatbar-title">Chat IA Médica</span>
        </header>
        <section id="chat-box" class="messages" aria-label="Histórico de mensagens" aria-live="polite">
            <div class="messages-inner">
                @if(count($messages) > 0)
                    @foreach($messages as $msg)
                        @php
                            $isUser = ($msg['role'] ?? '') === 'user';
                            $createdAt = isset($msg['created_at']) ? \Carbon\Carbon::parse($msg['created_at'])->format('H:i') : '';
                        @endphp

                        <article class="msg {{ $isUser ? 'msg-user' : 'msg-ai' }}" aria-label="{{ $isUser ? 'Mensagem do usuário' : 'Resposta da IA' }}">
                            @unless($isUser)
                                <span class="ai-avatar" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="8" y="2" width="8" height="4" rx="1"/>
                                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                        <path d="M12 11v6"/><path d="M9 14h6"/>
                                    </svg>
                                </span>
                            @endunless

                            <div class="bubble-wrap" x-data="{ open: false }">
                                <div class="msg-tools">
                                    <button type="button" class="tool-dot" @click="open = !open" aria-label="Opções da mensagem">
                                        <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M12 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak class="popover {{ $isUser ? '' : '' }}">
                                        <button type="button" class="danger" wire:click="deleteMessage({{ $msg['id'] }})" @click="open = false">Deletar</button>
                                    </div>
                                </div>

                                <div class="bubble">
                                    <div class="bubble-meta">
                                        <span class="bubble-name">{{ $isUser ? (Auth::user()->name ?? 'Você') : 'IA Médica Clinicaly' }}</span>
                                        <span class="bubble-time">{{ $createdAt }}</span>
                                    </div>
                                    <div class="bubble-text {{ $isUser ? '' : 'typewriter-text' }}" @unless($isUser) data-message-id="{{ $msg['id'] }}" @endunless>
                                        {!! \Illuminate\Support\Str::markdown($msg['message'] ?? '') !!}
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <div class="chat-welcome">
                        <span class="cw-orb" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="8" y="2" width="8" height="4" rx="1"/>
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                <path d="M12 11v6"/><path d="M9 14h6"/>
                            </svg>
                        </span>
                        <h1 class="cw-title">Olá, {{ explode(' ', Auth::user()->name)[0] }}.</h1>
                        <p class="cw-sub">Descreva um caso clínico, sintomas, exames ou dúvidas sobre CID e prescrições. As próximas sugestões aparecerão geradas pela própria IA.</p>
                    </div>
                @endif

                <div wire:loading wire:target="sendMessage,sendSuggestedPrompt" class="msg msg-ai">
                    <span class="ai-avatar" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="8" y="2" width="8" height="4" rx="1"/>
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                            <path d="M12 11v6"/><path d="M9 14h6"/>
                        </svg>
                    </span>
                    <div class="bubble-wrap">
                        <div class="bubble">
                            <div class="typing-dots" aria-label="IA está escrevendo">
                                <span></span><span></span><span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="composer-dock" aria-label="Sugestões e mensagem">
            <div class="composer-inner">
                <div class="input-wrap">
                    <textarea
                        class="chat-input"
                        rows="1"
                        maxlength="4000"
                        wire:model="userInput"
                        x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 92) + 'px'"
                        x-on:keydown.enter="if (!$event.shiftKey) { $event.preventDefault(); $wire.sendMessage(); }"
                        placeholder="Descreva o caso clínico ou faça uma pergunta médica..."
                        aria-label="Mensagem para a IA médica"
                    ></textarea>
                    <button type="button" wire:click="sendMessage" wire:loading.attr="disabled" wire:target="sendMessage,sendSuggestedPrompt" class="send-btn" aria-label="Enviar mensagem">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                    </button>
                </div>

                <div class="input-footer">
                    <span>Enter envia · Shift+Enter quebra linha</span>
                    <span>Clinicaly AI pode cometer erros</span>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('livewire:init', () => {
            const scrollToBottom = () => {
                const container = document.getElementById('chat-box');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            };

            const animatedMessages = new Set();

            const collectInitialMessages = () => {
                document.querySelectorAll('.typewriter-text[data-message-id]').forEach((el) => {
                    animatedMessages.add(String(el.dataset.messageId));
                    el.dataset.typed = 'done';
                });
            };

            const animateTextNodes = (el) => {
                const messageId = String(el.dataset.messageId || '');
                if (!messageId || animatedMessages.has(messageId) || el.dataset.typed === 'typing') return;

                animatedMessages.add(messageId);
                el.dataset.typed = 'typing';
                el.classList.add('is-typing');

                const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, {
                    acceptNode(node) {
                        return node.nodeValue.trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
                    }
                });

                const nodes = [];
                while (walker.nextNode()) {
                    nodes.push({ node: walker.currentNode, text: walker.currentNode.nodeValue });
                    walker.currentNode.nodeValue = '';
                }

                let nodeIndex = 0;
                let charIndex = 0;
                let lastTick = 0;

                const step = (time) => {
                    if (time - lastTick < 12) {
                        requestAnimationFrame(step);
                        return;
                    }

                    lastTick = time;

                    for (let i = 0; i < 2 && nodeIndex < nodes.length; i++) {
                        const current = nodes[nodeIndex];
                        current.node.nodeValue += current.text.charAt(charIndex);
                        charIndex++;

                        if (charIndex >= current.text.length) {
                            nodeIndex++;
                            charIndex = 0;
                        }
                    }

                    scrollToBottom();

                    if (nodeIndex < nodes.length) {
                        requestAnimationFrame(step);
                    } else {
                        el.dataset.typed = 'done';
                        el.classList.remove('is-typing');
                    }
                };

                requestAnimationFrame(step);
            };

            const revealPendingAiMessages = () => {
                document.querySelectorAll('.typewriter-text[data-message-id]:not([data-typed])').forEach(animateTextNodes);
            };

            setTimeout(() => {
                collectInitialMessages();
                scrollToBottom();
            }, 50);

            Livewire.on('scroll-to-bottom', () => {
                setTimeout(() => {
                    revealPendingAiMessages();
                    scrollToBottom();
                }, 80);
            });
        });
    </script>
</div>
