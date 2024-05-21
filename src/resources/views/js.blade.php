<script>
     const chatButton = document.getElementById("chat-button");
        const chatContainer = document.getElementById("chatContainer");
        const minimizeButton = document.getElementById("minimize-button");
        const chatInput = document.getElementById("chat-input");
        const chatMessages = document.getElementById("conversation-group");
        const sendButton = document.getElementById("SentButton");

        if (chatButton) {
            chatButton.addEventListener("click", function () {
                if (chatContainer) {
                    chatContainer.classList.add("open");
                    chatButton.classList.add("clicked");
                }
            });
        }

        if (minimizeButton) {
            minimizeButton.addEventListener("click", function () {
                if (chatContainer) {
                    chatContainer.classList.remove("open");
                    chatButton.classList.remove("clicked");
                }
            });
        }

        function createMessage(message, isUser = true) {
            const newMessage = document.createElement('div');
            newMessage.classList.add(isUser ? 'sentText' : 'botText');
            newMessage.textContent = message;
            chatMessages.appendChild(newMessage);
            return newMessage;
        }

        function chatbotResponse(message) {
            // const messages = ["Hello!", "How can I assist you?", "Let me know if you have any further questions"];
            // const randomIndex = Math.floor(Math.random() * messages.length);
            // const message = messages[randomIndex];

            fetch("/sendmessage", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ content: message })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json(); // Parse the JSON from the response
                })
                .then(data => {
                    if(data.success == false){
                        const botMessage = createMessage(data.data.message, false);
                        botMessage.scrollIntoView();
                        sendButton.classList.add("svgsent");
                    } else {
                        const botMessage = createMessage(data.data.content, false);
                        botMessage.scrollIntoView();
                        sendButton.classList.add("svgsent");
                    }
                }) // Handle the parsed data
                .catch(error => console.error('Error:', error));

            
        }

        chatInput.addEventListener("input", function (event) {
            if (event.target.value) {
                sendButton.classList.add("svgsent");
            } else {
                sendButton.classList.remove("svgsent");
            }
        });

        chatInput.addEventListener("keypress", function (event) {
            if (event.keyCode === 13) {
                const message = chatInput.value;
                chatInput.value = "";
                const userMessage = createMessage(message);
                userMessage.scrollIntoView();
                chatbotResponse(message);
                sendButton.classList.add("svgsent");
            }
        });

        if (sendButton) {
            sendButton.addEventListener("click", function () {
                const message = chatInput.value;
                chatInput.value = "";
                const userMessage = createMessage(message);
                chatbotResponse(message);
            });
        }


</script>