<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MediGuide AI ChatBot</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous" />

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Open Sans', sans-serif;
            background-color: #1a202c;
            color: #d1d5db;
        }

        .chat-container {
            height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            background-color: #2d3748;
        }

        .chat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #1a202c;
            border-bottom: 1px solid #0e7490;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .dot-red {
            background-color: #ef4444;
        }

        .dot-yellow {
            background-color: #f59e0b;
        }

        .dot-green {
            background-color: #22c55e;
        }

        .chat-header h3 {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #a3e635;
        }

        .nav-link-custom {
            color: #22d3ee;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
        }

        .nav-link-custom:hover {
            color: #a3e635;
            text-decoration: underline;
        }

        .chat-messages {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            scroll-behavior: smooth;
        }

        .message-bubble {
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 12px;
            position: relative;
            word-wrap: break-word;
        }

        .message-bubble.user {
            align-self: flex-end;
            background-color: #06b6d4;
            color: white;
            border-bottom-right-radius: 2px;
        }

        .message-bubble.bot {
            align-self: flex-start;
            background-color: #1a202c;
            color: #d1d5db;
            border: 1px solid #0e7490;
            border-bottom-left-radius: 2px;
        }

        .message-bubble.user::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: -8px;
            border-bottom: 8px solid #06b6d4;
            border-right: 8px solid transparent;
        }

        .message-bubble.bot::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: -8px;
            border-bottom: 8px solid #1a202c;
            border-left: 8px solid transparent;
        }

        .chat-input-area {
            padding: 15px 20px;
            border-top: 1px solid #0e7490;
            display: flex;
            gap: 10px;
            background-color: #1a202c;
            align-items: center;
        }

        .chat-input-area textarea {
            flex-grow: 1;
            min-height: 40px;
            max-height: 120px;
            border-radius: 20px;
            padding: 10px 15px;
            resize: none;
            background-color: #1a202c;
            color: #d1d5db;
            border: 1px solid #0e7490;
            font-size: 16px;
        }

        .chat-input-area textarea:focus {
            outline: none;
            border-color: #06b6d4;
            box-shadow: 0 0 0 0.25rem rgba(6, 182, 212, 0.25);
        }

        .chat-input-area button {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #06b6d4;
            color: white;
            border: none;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            transition: all 0.3s ease-in-out;
        }

        .chat-input-area button:hover {
            background-color: #0e7490;
            box-shadow: 0 0 12px #22d3ee;
            transform: translateY(-1px);
        }

        .typing-indicator-container {
            padding: 0px 20px 10px;
            display: flex;
            justify-content: flex-start;
        }

        .typing {
            font-style: italic;
            color: #999;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .typing .dot-loader {
            display: flex;
            align-items: center;
        }

        .typing .dot {
            width: 8px;
            height: 8px;
            background-color: #999;
            border-radius: 50%;
            margin: 0 2px;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .typing .dot:nth-child(1) {
            animation-delay: -0.32s;
        }

        .typing .dot:nth-child(2) {
            animation-delay: -0.16s;
        }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
    </style>
</head>

<body>
    <div class="chat-container">
        <div class="chat-header">
            <div class="header-left">
                <span class="dot dot-red"></span>
                <span class="dot dot-yellow"></span>
                <span class="dot dot-green"></span>
                <h3 class="mb-0 ms-2">MediGuide AI ChatBot</h3>
            </div>
            <a href="/Search" class="nav-link-custom">Back</a>
        </div>

        <div id="chatMessages" class="chat-messages">
            <div class="message-bubble bot">Hello! I'm MediGuide. How can I assist you today regarding medicines or symptoms?</div>
        </div>

        <div id="typingIndicatorContainer" class="typing-indicator-container" style="display:none;">
            <div class="typing">
                MediGuide is typing
                <div class="dot-loader">
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
        </div>

        <form id="chatForm" autocomplete="off" class="chat-input-area">
            @csrf
            <textarea name="message" id="message" placeholder="Ask something..." required></textarea>
            <button type="submit"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>

    <script>
        const form = document.getElementById('chatForm');
        const chatMessages = document.getElementById('chatMessages');
        const typingIndicatorContainer = document.getElementById('typingIndicatorContainer');
        const messageInput = document.getElementById('message');

        function appendMessage(sender, content, isHTML = false) {
            const bubble = document.createElement('div');
            bubble.classList.add('message-bubble', sender);
            bubble[isHTML ? 'innerHTML' : 'textContent'] = content;
            chatMessages.appendChild(bubble);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            appendMessage('user', message);
            messageInput.value = '';
            messageInput.style.height = 'auto';
            typingIndicatorContainer.style.display = 'flex';

            navigator.geolocation.getCurrentPosition(async function (position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                await sendMessage(message, latitude, longitude);
            }, async function () {
                // if location not available
                await sendMessage(message, null, null);
            });
        });

        async function sendMessage(message, latitude, longitude) {
            try {
                const token = document.querySelector('input[name="_token"]').value;

                const res = await fetch("{{ route('chatbot.process') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ message, latitude, longitude })
                });

                if (!res.ok) throw new Error('Network response was not ok');
                const data = await res.json();
                typingIndicatorContainer.style.display = 'none';
                typeText(data.response, true);

            } catch (err) {
                typingIndicatorContainer.style.display = 'none';
                appendMessage('bot', 'Oops! Something went wrong. Please try again.');
                console.error(err);
            }
        }

        messageInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        let currentBotMessageElement = null;

        function typeText(text, isHTML = false, i = 0, currentOutput = '') {
            if (i === 0) {
                currentBotMessageElement = document.createElement('div');
                currentBotMessageElement.classList.add('message-bubble', 'bot');
                chatMessages.appendChild(currentBotMessageElement);
            }

            if (i < text.length) {
                currentOutput += text.charAt(i);
                if (isHTML) {
                    currentBotMessageElement.innerHTML = currentOutput;
                } else {
                    currentBotMessageElement.textContent = currentOutput;
                }
                chatMessages.scrollTop = chatMessages.scrollHeight;
                setTimeout(() => typeText(text, isHTML, i + 1, currentOutput), 25);
            } else {
                currentBotMessageElement = null;
            }
        }
    </script>
</body>

</html>
