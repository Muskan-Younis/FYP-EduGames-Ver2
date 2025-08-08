<?php
$request = Yii::$app->request;
$topic = $request->get('topic') === null ? 0 : $request->get('topic'); // If topic is null, set it to 0
$lpid = $request->get('lpid') === null ? 0 : $request->get('lpid');   // If lpid is null, set it to 0
$qsid = $request->get('qid') === null ? 0 : $request->get('qid');  
$assid = $request->get('assid') === null ? 0 : $request->get('assid');    // If qsid is null, set it to 0
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= Yii::$app->request->csrfToken ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/arabic-reshaper@1.1.0/index.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <title>Tap On Time Game</title>
    <style>
        body {
            font-family: 'Luckiest Guy', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #fff3e0);
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            user-select: none;
            padding: 20px;
        }
        #instructionsOverlay {
            position: fixed;
            top: 0; left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5); /* allows the game to be seen in background */
            backdrop-filter: blur(8px); /* optional: gives it a frosted glass effect */
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
        }

        .overlay-content {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            padding: 30px 40px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(12px);
        }

        .overlay-content h2 {
            font-size: 2.2em;
            margin-bottom: 15px;
            color: #00ffd5;
            text-shadow: 0 0 10px #00ffd5;
        }

        .overlay-content p {
            font-size: 1.2em;
            margin-bottom: 12px;
        }

        #startGameBtn {
            padding: 12px 28px;
            font-size: 1.1em;
            background: linear-gradient(135deg, #00ffd5, #0078ff);
            border: none;
            border-radius: 12px;
            color: black;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        #startGameBtn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 12px rgba(0, 255, 213, 0.8);
        }

        #quit {
            position: absolute;
            top:10px; right:10px;
            background-color: #152a88ff;
            color: white;
            border: none;
            padding: 18px 20px;
            cursor: pointer;
            border-radius: 5px;
            /*margin: 5px;*/
        }
        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        /* Custom Close Button */
        .modal-header .close {
            color: black;
            font-size: 1.5em;
            opacity: 1;
        }

        /* Custom Resume Button */
        .btn-custom {
            background-color: white;
            color:rgb(54, 80, 110);
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }

        .btn-custom:hover {
            background-color:rgb(54, 80, 110);
            color:rgb(75, 180, 212)
        }

        /* Prevent accidental closing when clicking outside */
        .modal {
            pointer-events: auto;
        }

        #resultContainer {
            text-align: center;
            margin-top: 40px;
            background-color: #ffffffdd;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            max-width: 600px;
            width: 90%;
            transition: all 0.3s ease;
        }

        #message {
            font-size: 22px;
            margin-bottom: 24px;
            color: #333;
            font-weight: bold;
        }

        #questionText {
            font-size: 32px;
            color: #2e7d32;
            margin: 20px auto 10px;
            text-align: center;
            background-color: #e8f5e9;
            padding: 12px 20px;
            border-radius: 12px;
            width: fit-content;
            max-width: 80%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        #optionsList {
            list-style: none;
            padding: 0;
            margin-top: 10px;
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        #optionsList li {
            background-color: #fff8e1;
            padding: 14px 20px;
            margin: 10px 0;
            border-radius: 12px;
            font-size: 30px;
            font-weight: bold;
            color: #0f0b0aff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        #optionsList li:hover {
            background-color: #ffe0b2;
            transform: translateY(-2px);
        }
        #gameTitle {
            position:fixed;
            top:10px; left:10px;
            text-align: center; 
            font-size: 2.3em;
            color: #fff;
            background: linear-gradient(90deg,rgb(235, 50, 121),rgb(32, 211, 211));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            /*margin: 20px 0;*/
            padding: 10px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            display: inline-block;
        }

        #difficultyDisplay {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            background: linear-gradient(to right, #ff3c3c, #ff9900); 
            color: #3b1e00; 
            font-size: 20px;
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

           #headerContainer {
                position: fixed;
                top: 10px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                flex-direction: column;
                align-items: center;
                z-index: 1000;
                pointer-events: none;
                width: 100%;
            }

            #gameTitle,
            #difficultyDisplay {
                position: static; /* Let them flow naturally inside the container */
                text-align: center;
            }

            #gameTitle {
                font-size: 1.4em;
                padding: 6px 12px;
                background: linear-gradient(90deg, rgb(235, 50, 121), rgb(32, 211, 211));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            #difficultyDisplay {
                font-size: 14px;
                padding: 6px 12px;
                margin-top: 5px;
                background: linear-gradient(to right, #ff3c3c, #ff9900);
                color: #3b1e00;
                border-radius: 8px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            }

            #quit {
                padding: 10px 14px;
                font-size: 14px;
                top: 10px;
                right: 10px;
            }

            #instructionsOverlay {
                padding: 20px;
                backdrop-filter: blur(6px);
            }

            .overlay-content {
                padding: 20px;
                width: 90%;
            }

            .overlay-content h2 {
                font-size: 1.5em;
            }

            .overlay-content p {
                font-size: 1em;
            }

            #startGameBtn {
                padding: 10px 20px;
                font-size: 1em;
            }

            #resultContainer {
                margin-top: 20px;
                padding: 20px;
            }

            #message {
                font-size: 18px;
            }

            #questionText {
                font-size: 20px;
                padding: 10px;
            }

            #optionsList li {
                font-size: 18px;
                padding: 10px;
                width: 100%;
            }

            .btn-custom {
                font-size: 16px;
                padding: 8px 16px;
            }

            .modal-dialog {
                min-height: auto;
            }
        }
    </style>

</head>
<body>
    <div id="headerContainer">
        <div id="gameTitle">Tap On Time</div>
        <div id="difficultyDisplay">Level: Loading...</div>
    </div>

    <div id="resultContainer" class="result-container">
        <div id="message" class="start-message">
            Tap anywhere to start the game
        </div>
    </div>

    <div id="questionText" class="question-text"></div>
    
    <button id="quit">Quit</button>

    <ul id="optionsList" class="options-list">
        <!-- Option text items will be dynamically inserted here -->
    </ul>

    <!-- Correct / Wrong Sounds -->
    <audio id="correctSound" src="correct.wav"></audio>
    <audio id="wrongSound" src="wrong.wav"></audio>
    
    <div id="progress-container" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 300px; height: 30px; background-color: #e0e0e0; border-radius: 5px; display: none;">
        <div id="progress-bar" style="height: 100%; width: 0; background-color: #4caf50; border-radius: 5px;"></div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="gameOverModal" tabindex="-1" role="dialog" aria-labelledby="gameOverModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="gameOverModalLabel">Game Over!</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Your Score: <span id="scoreDisplay"></span><br>
            Your Accuracy: <span id="accuracyDisplay"></span><br>
            Your Speed (in seconds): <span id="speedDisplay"></span><br><br>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="goToAssignments">Go Back</button>
        </div>
        </div>
    </div>
    </div>
    <div id="instructionsOverlay">
        <div class="overlay-content">
            <h2>⏱️ Ready to Tap?</h2>
            <p>Instructions:</p>
            <p>🎯 Tap exactly when you hear or see the right option!</p>
            <p>🎵 Listen carefully – each sound or phrase matters.</p>
            <p>🚫 Missed or early taps will cost you points.</p>
            <p>Get focused and test your reflexes. Can you tap on time? 🎮</p>
            <button id="startGameBtn">Start the Game!</button>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    <?php $questionsUrl = \yii\helpers\Url::to(['games/getquestions']); ?>
        function fetchQuestions() {
            return $.ajax({
                url: '<?= $questionsUrl ?>',
                method: 'GET',
                dataType: 'json'
            });
        }

     <?php $optionsUrl = \yii\helpers\Url::to(['games/getoptions']); ?>
        function fetchOptions(index) {
            return $.ajax({
                url: '<?= $optionsUrl ?>',
                method: 'GET',
                data: { index: index },
                dataType: 'json'
            });
        }
        //passing difficulty level
        const difficulty = "<?= $difficultyLevel ?>";
        console.log("Difficulty Level:", difficulty);
        document.getElementById("difficultyDisplay").textContent = "Level: " + difficulty.charAt(0).toUpperCase() + difficulty.slice(1);
    
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        let currentQuestionIndex = 0;
        let options = [];
        let currentOptionIndex = 0;
        let tapped = false;
        let gameStarted = false;
        let gameEnded = false;
        let score = 0;
        let currentOptionAudio = null;
        let currentUtterance = null;
        let totalQuestions = 0;
        let startTime = Date.now();
        
        let accuracy=0,speed=0;

        // Fetch all questions once
        let allQuestions = [];
        let questionsFetched = false;

        function fetchQuestionsOnce() {
            if (questionsFetched) return Promise.resolve(allQuestions);
            return $.ajax({
                url: '<?= $questionsUrl ?>',
                method: 'GET',
                dataType: 'json'
            }).then(data => {
                allQuestions = data;
                questionsFetched = true;
                return allQuestions;
            });
        }

        let optionsCache = {};
        function fetchOptionsOnce(questionIndex) {
            if (optionsCache[questionIndex]) return Promise.resolve(optionsCache[questionIndex]);

            return $.ajax({
                url: '<?= $optionsUrl ?>',
                method: 'GET',
                data: { index: questionIndex },
                dataType: 'json'
            }).then(data => {
                optionsCache[questionIndex] = data;
                return data;
            });
        }

        function addAudioElement(id, src) {
            if (!src) return;
            const audio = document.createElement('audio');
            audio.id = id;
            audio.src = src;
            audio.classList.add('dynamic');
            document.body.appendChild(audio);
        }

        function clearOldAudios() {
            document.querySelectorAll('audio.dynamic').forEach(el => el.remove());
        }

        function playAudio(id, callback) {
            const audio = document.getElementById(id);
            if (!audio) {
                console.error("❌ No audio element with id:", id);
                callback();
                return;
            }

            audio.play()
                .then(() => {
                    console.log("🔊 Playing:", id);
                    audio.onended = callback;
                })
                .catch(err => {
                    console.error("⚠️ Error playing", id, "→", err.message);
                    callback();
                });
        }

        function speak(text, callback) {
            if (speechSynthesis.speaking) {
                speechSynthesis.cancel();
            }

            const utterance = new SpeechSynthesisUtterance(text);
            currentUtterance = utterance;
            utterance.lang = 'en-US';
            utterance.onend = () => {
                if (!gameEnded) callback();
            };
            speechSynthesis.speak(utterance);
        }

        function displayMessage(msg) {
            document.getElementById('message').innerText = msg;
        }

        let isQuestionAudioPlaying = false; // flag to block tap during question audio

        function startGame() {
            displayMessage("🎧 Choose the correct continuation...");
            document.getElementById('questionText').style.display = 'block';

            isQuestionAudioPlaying = true; // Block tap during initial instructions

            speak("Choose the correct continuation", () => {
                playAudio('question', () => {
                    displayMessage("🎧 Now the options are playing...");
                    speak("Now the options are playing. Listen carefully.", () => {
                        isQuestionAudioPlaying = false; // Re-enable tap after instructions
                        playNextOption(); // Start from the first option
                    });
                });
            });
        }

        function playNextOption() {
            if (currentOptionIndex >= options.length || gameEnded) {
                showResult();
                return;
            }

            tapped = false;
            const option = options[currentOptionIndex];
            const optionNumber = currentOptionIndex + 1;

            console.log("▶playNextOption", currentOptionIndex, options);

            // Hide all other options
            const optionElements = document.querySelectorAll('#optionsList li');
            optionElements.forEach(el => el.style.display = 'none');

            const currentOptionElement = document.getElementById(`optionText${currentOptionIndex + 1}`);
            if (currentOptionElement) {
                currentOptionElement.style.display = 'block';
            }

            // speaks "Option X", then plays its audio
            speak(`Option ${optionNumber}`, () => {
                const audio = document.getElementById(option.audioId);
                if (!audio) {
                    console.error("Audio not found:", option.audioId);
                    currentOptionIndex++;
                    playNextOption(); // fallback
                    return;
                }

                if (currentOptionAudio) {
                    currentOptionAudio.pause();
                    currentOptionAudio.currentTime = 0;
                }

                currentOptionAudio = audio;

                audio.play()
                    .then(() => {
                        console.log("🎵 Playing option audio:", option.audioId);
                        audio.onended = () => {
                            if (!tapped && !gameEnded) {
                                currentOptionIndex++;
                                playNextOption();
                            }
                        };
                    })
                    .catch(err => {
                        console.error("Audio error:", err);
                        if (!gameEnded) {
                            currentOptionIndex++;
                            playNextOption();
                        }
                    });
            });
        }

        function handleTap() {
            if (isQuestionAudioPlaying) {
                console.log("Tap ignored: question or instructions are still playing.");
                return;
            }

            if (!gameStarted) {
                gameStarted = true;
                fetchQuestionAndOptionsFromDB();
                return;
            }

            if (tapped || currentOptionIndex >= options.length || gameEnded) return;

            tapped = true;
            gameEnded = true;

            if (currentOptionAudio) {
                currentOptionAudio.pause();
                currentOptionAudio.currentTime = 0;
            }

            if (speechSynthesis.speaking) {
                speechSynthesis.cancel();
            }

            if (currentUtterance) {
                currentUtterance.onend = null;
                currentUtterance = null;
            }

            const option = options[currentOptionIndex];
            if (option.option_type === "correct") {
                score++;
                document.getElementById('correctSound').play();
                displayMessage("✅ Correct!");
            } else {
                document.getElementById('wrongSound').play();
                displayMessage("❌ Wrong!");
            }

            setTimeout(() => {
                showResult();
            }, 1500);
        }

        function showResult() {
            displayMessage(`Score: ${score}`);

            setTimeout(() => {
                currentQuestionIndex++;  // Move to next question

                if (currentQuestionIndex < totalQuestions) {
                    gameEnded = false;
                    tapped = false;
                    clearOldAudios();
                    currentOptionIndex = 0;
                    fetchQuestionAndOptionsFromDB();  // Load next question
                } else {
                    console.log("All questions answered");
                    saveScore();
                    gameOver();
                    gameStarted = false;
                }
            }, 1500); // Short delay to let user see result
        }

        function fetchQuestionAndOptionsFromDB() {
            fetchQuestionsOnce().then((questions) => {
                totalQuestions = questions.length;
                const question = questions[currentQuestionIndex];
                clearOldAudios();

                // Display the question text
                document.getElementById('questionText').textContent = question.QuestionStatement || '';

                // Use correct field name from DB
                if (question.Media_File) {
                    addAudioElement('question', question.Media_File);
                } else {
                    console.error("❌ Media_File missing in question data");
                }

                return fetchOptionsOnce(question.QuestionNo).then((opts) => {
                    console.log("📦 Fetched options:", opts); // helpful for debugging

                    options = opts.map((opt, i) => {
                        const id = 'option' + (i + 1);

                        // add audio if options_media exists
                        if (opt.options_media) {
                            addAudioElement(id, opt.options_media);
                        } else {
                            console.error(`❌ Missing options_media for option ${i + 1}`, opt);
                        }

                        return {
                            ...opt,
                            audioId: id
                        };
                    });
                    // prepare the option elements but hide them for now
                    const optionsList = document.getElementById('optionsList');
                    optionsList.innerHTML = '';
                    options.forEach((opt, index) => {
                        const li = document.createElement('li');
                        li.textContent = opt.option_text || '';
                        li.id = `optionText${index + 1}`;
                        li.style.display = 'none'; // Hidden initially
                        li.style.margin = '10px 0';
                        li.style.fontSize = '18px';
                        li.style.backgroundColor = '#f2f2f2';
                        li.style.padding = '10px';
                        li.style.borderRadius = '8px';
                        optionsList.appendChild(li);
                    });


                    console.log("🧠 Final options array:", options);

                    startGame();
                });

            }).catch(err => {
                console.error("Failed to load:", err);
                displayMessage("❌ Failed to load question.");
            });
        }

        // Register click
        document.body.addEventListener('click', handleTap);

        function saveScore(){
            const endTime = Date.now();
            speed = Math.round((endTime - startTime) / 1000); // Time in seconds
            accuracy = Math.round((score / totalQuestions) * 100); // Percentage of correct answers
            const qsid = <?php echo $qsid; ?>;
            const topic = <?php echo $topic; ?>;
            const lpid = <?php echo $lpid; ?>;
            const assid = <?php echo $assid; ?>;
            console.log(speed);
            console.log(accuracy);

            $.ajax({
                url: 'index.php?r=games/save-score',
                method: 'POST',
                data: {
                    score: score,
                    accuracy: accuracy,
                    speed: speed,
                    qsid: qsid,
                    topic: topic,
                    lpid: lpid,
                    total: totalQuestions,
                    assid: assid,
                    _csrf: csrfToken
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.status === 'success') {
                        console.log('Score saved successfully');
                    } else {
                        console.error('Error saving score:', response.errors);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', status, error);
                }
            });
            console.log("score saved");
        }
        function gameOver() {

            document.getElementById('scoreDisplay').innerText = score;
            document.getElementById('accuracyDisplay').innerText = accuracy;
            document.getElementById('speedDisplay').innerText = speed;

            let modal = new bootstrap.Modal(document.getElementById('gameOverModal'));
            modal.show();

            document.getElementById('goToAssignments').addEventListener('click', function() {
                var minorId = <?= \Yii::$app->session->get('minorId') !== null ? 'true' : 'false'; ?>;
                if (minorId) {
                    window.location.href = '<?= \yii\helpers\Url::to(['site/index']) ?>';
                } else {
                    history.back();
                }
            //history.back();
            });
            // document.getElementById('goToAssignments').addEventListener('click', function() {
            //     history.back();
            // });
        }
        document.getElementById('quit').addEventListener('click', function() {
            if (confirm('Are you sure you want to quit?')) {
            // window.location.href = '<?= \yii\helpers\Url::to(['student/assgall']) ?>';
                // Stop all possible audio/speech
                if (currentOptionAudio) {
                    currentOptionAudio.pause();
                    currentOptionAudio.currentTime = 0;
                }

                if (speechSynthesis.speaking) {
                    speechSynthesis.cancel();
                }

                if (currentUtterance) {
                    currentUtterance.onend = null;
                    currentUtterance = null;
                }
                saveScore();
                history.back();
            }
        });
        const overlay = document.getElementById('instructionsOverlay');
        const startBtn = document.getElementById('startGameBtn');

        startBtn.addEventListener('click', () => {
            // Smooth fade-out
            overlay.style.transition = 'opacity 0.5s ease';
            overlay.style.opacity = 0;

            setTimeout(() => {
                overlay.remove();
            }, 500);
        });
    </script>
