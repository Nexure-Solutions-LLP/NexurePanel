<?php

    // This is the core loader for the Emmie by Nexure AI system part of
    // Nexure's Enterprise Managememnt Monetary Infrastructure Engine used
    // in unifying corporate operations for modern enterprises.

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title></title>
</head>
<body>
<h2>Talk to Emmie</h2>
<p id="status">Waiting for wake word...</p>
<p id="response"></p>

<script>
let conversationMode = false;
let recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
recognition.continuous = true;
recognition.interimResults = true;
recognition.lang = "en-US";

recognition.onresult = async (event) => {
    let transcript = "";
    for (let i = event.resultIndex; i < event.results.length; i++) {
        transcript += event.results[i][0].transcript;
    }
    transcript = transcript.trim().toLowerCase();
    document.getElementById("status").innerText = "Heard: " + transcript;

    if (!conversationMode && transcript.includes("emmie")) {
        conversationMode = true;
        document.getElementById("status").innerText = "Wake word detected! Listening for command...";
    } else if (conversationMode) {
        try {
            const response = await fetch("http://130.12.30.4:8000/ask", {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({question: transcript})
            });
            const data = await response.json();
            document.getElementById("response").innerText = data.response;

            const utterance = new SpeechSynthesisUtterance(data.response);
            speechSynthesis.speak(utterance);

        } catch (err) {
            console.error("Error sending command:", err);
        }

        conversationMode = false;
        document.getElementById("status").innerText = "Waiting for wake word...";
    }
};

recognition.onerror = (event) => {
    console.error("Speech recognition error:", event);
};

recognition.onend = () => {
    recognition.start();
};

recognition.start();
</script>
</body>
</html>
