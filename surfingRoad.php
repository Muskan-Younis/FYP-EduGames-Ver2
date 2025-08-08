<?php
use backend\models\BackgroundImages;
use backend\models\GameModels;
use backend\models\Avatar;
$request = Yii::$app->request;
$topic = $request->get('topic') === null ? 0 : $request->get('topic'); // If topic is null, set it to 0
$lpid = $request->get('lpid') === null ? 0 : $request->get('lpid');   // If lpid is null, set it to 0
$qsid = $request->get('qid') === null ? 0 : $request->get('qid');  
$assid = $request->get('assid') === null ? 0 : $request->get('assid');    // If qsid is null, set it to 0
$backgroundimg=$model->selected_background_id;
$bgimg = BackgroundImages::findOne(['image_id' => $model->selected_background_id]);
if ($bgimg) {
    $backgroundPath = $bgimg->file_path;
    // Use $backgroundPath in your game rendering
}
$game_model=$model->selected_model_id;
$gmodel = GameModels::findOne(['model_id' => $model->selected_model_id]);
if ($gmodel) {
    $modelPath = $gmodel->model_path;
    // Use $modelPath in your game rendering
}
$avatar=$model->selected_avatar_id;
$avt = Avatar::findOne(['avatar_id' => $model->selected_avatar_id]);
if ($avt) {
    $avatarPath = $avt->avatar_path;
    // Use $avatarPath in your game rendering
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= Yii::$app->request->csrfToken ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/arabic-reshaper@1.1.0/index.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    
    <style>
        body {
            font-family: 'Luckiest Guy', sans-serif;
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

        #score-container {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 1.5em;
            z-index: 1000; /* Ensure it is on top of other elements */
        }

        #gameTitle {
            position:fixed;
            top:10px; left:10px;
            text-align: center; 
            font-size: 2.3em;
            font-weight: bold;
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
        #timer {
            position: fixed;
            top: 10px;
            right: 130px; /* Adjust position to the left of score */
            background: black;
            color: white;
            padding: 10px;
            font-size: 1.5em;
            border-radius: 5px;
            z-index: 1000;
            display: none; /* Initially hidden */
        }
        /* Pause button styling */
        .pause-btn {
            position: fixed;
            top: 10px;
            right: 130px;
            width: 40px;
            height: 55px;
            background-color: black;
            color: white;
            font-size: 24px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Center the modal vertically and horizontally */
        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        #pauseModal .modal-content {
            width: 360px; 
            height: 290px; 
            background-color:rgb(125, 215, 243) !important; 
            color:rgb(54, 80, 110);
            text-align: center;
            border-radius: 15px; 
            padding: 10px;            
            font-size: 1.1em;
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
        .quit {
            background-color: white;
            color:rgb(54, 80, 110);
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }
        .quit:hover {
            background-color:rgb(54, 80, 110);
            color:rgb(75, 180, 212)
        }
        /* Flex container for horizontal button layout */
        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px; /* spacing between buttons */
            margin-bottom: 20px;
        }

        #progress-container {
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            width: 300px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
            opacity: 1;
            visibility: visible;
        }
        #progress-container.hidden {
            opacity: 0;
            visibility: hidden;
        }
        #progress-label {
            font-family: Arial, sans-serif;
            color: #333;
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
        #question-container {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            font-size: 24px;
            text-align: center;
            border-radius: 10px;
            display: none;
            z-index: 1000;
        }

        @media (max-width: 768px) {
            #score-container {
                top: 60px;
                right: 5px;
                font-size: 1.2em;
                padding: 8px;
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

            #timer {
                top: 10px;
                right: 5px;
                font-size: 1.2em;
                padding: 8px;
            }

            .pause-btn {
                top: 10px;
                right: 5px;
                width: 30px;
                height: 40px;
                font-size: 1.2em;
            }
            .modal-dialog {
                width: 90%; /* Make the modal wider on smaller screens */
                margin: auto;
            }

            /* Adjust game over modal */
            #gameOverModal .modal-content {
                width: 100%; /* Take full width */
                padding: 15px;
                font-size: 1.2em;
            }

            #gameOverModal .modal-title {
                font-size: 1.5em;
                text-align: center;
            }

            #gameOverModal .modal-body {
                text-align: center;
                font-size: 1.2em;
            }

            #gameOverModal .modal-footer {
                display: flex;
                justify-content: center;
            }

            #goToAssignments {
                width: 100%;
                font-size: 1.2em;
            }

            /* Adjust pause modal for mobile */
            #pauseModal .modal-content {
                width: 90%;
                height: auto;
                font-size: 1em;
                padding: 15px;
            }
        }

    </style>
</head>
<body>
    <!-- Progress container (initially hidden) -->
    <div id="progress-container" style="display: none; text-align: center; padding: 10px;">
        <div id="progress-label" style="margin-bottom: 5px; font-weight: bold;">Loading...</div>
        <div style="width: 100%; background: #ddd; height: 20px; border-radius: 10px; overflow: hidden;">
            <div id="progress-bar" style="width: 0%; height: 100%; background: #3b82f6;"></div>
        </div>
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
    <div id="score-container">
        Score: <span id="current-score">0</span>
    </div>
    <div id="headerContainer">
        <div id="gameTitle">Surfing Road</div>
        <div id="difficultyDisplay">Level: Loading...</div>
    </div>
    <div id="question-container"></div>
    <div id="timer">
         <span id="timer-count">5</span>
    </div>
    <!-- Pause Button -->
    <button id="pauseButton" class="pause-btn">
        ❚❚
    </button>

    <!-- Pause Modal (Centered) -->
    <div class="modal fade" id="pauseModal" tabindex="-1" role="dialog" aria-labelledby="pauseModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title">Game Paused</h5>
                    <button type="button" class="close" id="modalCloseButton" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>The game is paused!</p>
                </div>
                <div class="modal-footer button-group">
                    <button id="resumeButton" class="btn-custom">Resume</button>
                    <button id="quitButton" class="quit">Quit</button>
                </div>
            </div>
        </div>
    </div>
    <div id="instructionsOverlay">
        <div class="overlay-content" id="instructionContent">
            <!-- Dynamic content will be injected here -->
        </div>
    </div>
<script>
    const instructionContent = document.getElementById('instructionContent');
    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

    if (isMobile) {
        instructionContent.innerHTML = `
            <h2>🎮 Ready to Play?</h2>
            <p><strong>Gestures:</strong><br>
            👈 Swipe Left – Move Left<br>
            👉 Swipe Right – Move Right<br>
            👆 Swipe Up – Jump
            </p>
            <p>Your mission: Hit the right option</p>
            <p>Stay sharp and see how high you can score. 🚀</p>
            <button id="startGameBtn">Start the Adventure!</button>
        `;
    } else {
        instructionContent.innerHTML = `
            <h2>🎮 Ready to Play?</h2>
            <p><strong>Key Presses:</strong><br>
            <strong>A</strong> – Move Left<br>
            <strong>D</strong> – Move Right<br>
            <strong>Spacebar</strong> – Jump
            </p>
            <p>Your mission: Hit the right option</p>
            <p>Stay sharp and see how high you can score. 🚀</p>
            <button id="startGameBtn">Start the Adventure!</button>
        `;
    }
</script>
</body>
    <script src="https://unpkg.com/es-module-shims@1.6.3/dist/es-module-shims.js" async></script>
    <script type="importmap">
        {
            "imports": {
                "three": "https://unpkg.com/three@0.150.1/build/three.module.js",
                "three/addons/": "https://unpkg.com/three@0.150.1/examples/jsm/"
            }
        }

    </script>
    <script type="module">
        import * as THREE from 'three'
    import { OrbitControls } from 'three/addons/controls/OrbitControls.js'
    import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
    import { DRACOLoader } from 'three/addons/loaders/DRACOLoader.js';
    import { FontLoader } from 'three/addons/loaders/FontLoader.js';
    import { TextGeometry } from 'three/addons/geometries/TextGeometry.js';
    import { Water } from 'three/addons/objects/Water.js';
    import { Sky } from 'three/addons/objects/Sky.js';

    const scene = new THREE.Scene()
    const camera = new THREE.PerspectiveCamera(
      75,
      window.innerWidth / window.innerHeight,
      0.1,
      1000
    )
    camera.position.set(0.9, 2, 100)
  
    const renderer = new THREE.WebGLRenderer({
        alpha: true,
        antialias: !isMobile(),
        powerPreference: "high-performance"
    })
    renderer.shadowMap.enabled = true
    renderer.shadowMap.type = THREE.BasicShadowMap; // Faster shadows
    renderer.setSize(window.innerWidth, window.innerHeight)
    document.body.appendChild(renderer.domElement)
  
    const controls = new OrbitControls(camera, renderer.domElement)

    const loadingManager = new THREE.LoadingManager();

    loadingManager.onStart = function (url, itemsLoaded, itemsTotal) {
        console.log('Started loading:', url, 'Loaded', itemsLoaded, 'of', itemsTotal);
        const container = document.getElementById('progress-container');
        container.style.display = 'block';       // make sure it's visible
        container.classList.remove('hidden');    // reset fade-out
    };

    // This is called when everything is loaded
    loadingManager.onLoad = function () {
        console.log('All assets loaded!');
        const container = document.getElementById('progress-container');
        container.classList.add('hidden');
        
        // Optional: fully remove it from DOM after fade-out finishes
        setTimeout(() => {
            container.style.display = 'none';
        }, 500); // match the transition duration (0.5s = 500ms)
    };

    // This is called during loading to show progress
    loadingManager.onProgress = function (url, itemsLoaded, itemsTotal) {
        const progress = (itemsLoaded / itemsTotal) * 100;
        document.getElementById('progress-bar').style.width = progress + '%';

        const label = document.getElementById('progress-label');
        if (progress < 100) {
            label.textContent = `Loading (${Math.round(progress)}%)...`;
        } else {
            label.textContent = `Finalizing...`;
        }
    };

    // This is called if there's an error during loading
    loadingManager.onError = function (url) {
        console.error(`Error loading resource: ${url}`);
    };
    
    const loader = new GLTFLoader(loadingManager);
    const textureLoader = new THREE.TextureLoader(loadingManager);
    const fontLoader = new FontLoader(loadingManager);

    function isMobile() {
        return /Android|iPhone|iPad|iPod|Windows Phone/i.test(navigator.userAgent);
    }

    if (isMobile()) {
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5)); // Reduce resolution for better performance on mobile
        renderer.setSize(window.innerWidth, window.innerHeight, false);
    } 
    // Procedural Sky for Night
    const nightSky = new Sky();
    nightSky.scale.setScalar(450000);
    scene.add(nightSky);

    // Adjust for a Dark Blue Night Sky
    const nightUniforms = nightSky.material.uniforms;
    nightUniforms['turbidity'].value = 1.5;   // Slight haze for a moonlit night
    nightUniforms['rayleigh'].value = 7.0;    // Increase blue scattering
    nightUniforms['mieCoefficient'].value = 0.0001; // Less sun glow
    nightUniforms['mieDirectionalG'].value = 0.75;  // Softer, diffused light

    // Lower the sun to simulate night
    const nightSunPosition = new THREE.Vector3(-1, -1, -1); 
    nightUniforms['sunPosition'].value.copy(nightSunPosition);

    // Add ambient light for slight illumination
    const ambientLight = new THREE.AmbientLight(0x112244, 0.5); // Dim, cool blue light
    scene.add(ambientLight);

    // Moon
    const moonGeometry = new THREE.SphereGeometry(12000, 64, 64); // Larger size
    const moonMaterial = new THREE.MeshPhongMaterial({
    map: new THREE.TextureLoader().load('moon.jpg'),
    emissive: 0x666666,  // Bright enough to see the moon clearly
    emissiveIntensity: 1
    });
    const moon = new THREE.Mesh(moonGeometry, moonMaterial);
    moon.position.set(100000, 30, -100000); // More distant, higher up
    scene.add(moon);

    // Moonlight
    const moonLight = new THREE.PointLight(0xaaaaaa, 1.5, 500000); // Stronger, cooler light
    moonLight.position.set(100000, 30, -100000);
    scene.add(moonLight);



    // Water at y = -2
    const waterGeometry = new THREE.PlaneGeometry(5000, 5000);
    const water = new Water(waterGeometry, {
        textureWidth: 256, // Lowered from 512
        textureHeight: 256, 
        waterNormals: new THREE.TextureLoader().load(
            'https://threejs.org/examples/textures/waternormals.jpg', 
            (texture) => { texture.wrapS = texture.wrapT = THREE.RepeatWrapping; }
        ),
        sunDirection: new THREE.Vector3(),
        sunColor: 0xffffff,
        waterColor: 0x1ca3ec, // More blue
        distortionScale: 1.5,
        fog: true
    });

    water.rotation.x = -Math.PI / 2;
    water.position.y = -2.5; // 🔹 Adjusted to match your ground
    scene.add(water);
    

    const skyTexture = new THREE.TextureLoader().load('sunrisebg.png');
    scene.background = skyTexture; 

    class Player extends THREE.Object3D {
        constructor({
            radius,
            widthSeg = 8,
            heightSeg = 8,
            position = { x: 0, y: 0, z: 0 },
            velocity = { x: 0, y: 0, z: 0 },
            color = '#00ff00',
            jumpHeight = 0.09,
            zAcceleration = false,
            modelPath = null // <-- NEW
        }) {
            super();

            this.radius = radius;
            this.velocity = velocity;
            this.gravity = -0.002;
            this.isOnGround = true;
            this.jumpHeight = jumpHeight;
            this.zAcceleration = zAcceleration;

            this.modelLoaded = false; // Track model loading

            if (modelPath) {
                loader.load(modelPath, gltf => {
                    this.model = gltf.scene;
                    this.model.scale.set(0.3, 0.3, 0.3); 
                    
                    this.alignModelToGround(this.model);

                    this.add(this.model);
                    this.modelLoaded = true;
                });
            } else {
                // Default sphere
                const mesh = new THREE.Mesh(
                    new THREE.SphereGeometry(radius, widthSeg, heightSeg),
                    new THREE.MeshStandardMaterial({ color })
                );
                mesh.castShadow = true;
                mesh.position.set(position.x, position.y, position.z);
                this.add(mesh);
            }

            this.position.set(position.x, position.y, position.z);
        }

        jump() {
            if (this.isOnGround) {
                this.velocity.y = this.jumpHeight;
                this.isOnGround = false;
            }
        }

        updateSides() {
            const speed = 0.11;
            this.position.z -= speed;
        }

        update(ground) {
            this.updateSides();

            if (this.zAcceleration) this.velocity.z += 0.0003;

            this.position.x += this.velocity.x;
            this.position.z += this.velocity.z;

            this.applyGravity(ground);
            
            if (this.model) {
                this.model.rotation.x -= 0.11;
            }
        }

        applyGravity(ground) {
            if (!this.isOnGround) {
                this.velocity.y += this.gravity;
            } else {
                this.velocity.y = 0;
            }

            this.position.y += this.velocity.y;

            const groundY = ground.position.y + (ground.height / 2) + this.radius;

            if (this.position.y <= groundY) {
                this.isOnGround = true;
                this.position.y = groundY;
            } else {
                this.isOnGround = false;
            }
        }
        
        alignModelToGround(model) {
            // Compute bounding box
            const box = new THREE.Box3().setFromObject(model);

            // Get size and center
            const size = new THREE.Vector3();
            const center = new THREE.Vector3();
            box.getSize(size);
            box.getCenter(center);

            // Align base of object to Y=0
            const yOffset = box.min.y;

            model.position.y -= yOffset;

            // Optional: Center it in XZ (depends on your needs)
            model.position.x -= center.x;
            model.position.z -= center.z;
        }
    }
    const avatarPath = <?= json_encode($avatarPath ?? null) ?>;

    const sphere = new Player({
        radius: 0.34,
        widthSeg: 32,
        heightSeg: 16,
        position: { x: 0, y: 0, z: 0 },
        velocity: { x: 0, y: -0.01, z: 0 },
        modelPath: avatarPath // <-- attach model if selected
    });
    scene.add(sphere);


    class Box extends THREE.Mesh {
        constructor({
            width,
            height,
            depth, 
            color = '#00ff00', 
            velocity = {x: 0, y: 0, z: 0},
            position = {x: 0, y: 0, z: 0},
            zAcceleration = false,
            textureUrl = null
        }) {
            const geometry = new THREE.BoxGeometry(width, height, depth);

            // Create the material based on whether a texture URL is provided
            let material;
            if (textureUrl) {
                
                const texture = textureLoader.load(textureUrl);
                texture.wrapS = THREE.RepeatWrapping;
                texture.wrapT = THREE.RepeatWrapping;
                texture.repeat.set(3, 20);
                material = new THREE.MeshStandardMaterial({ map: texture });
            } else {
                material = new THREE.MeshStandardMaterial({ color });
            }

            super(geometry, material);

            this.width = width;
            this.height = height;
            this.depth = depth;

            this.position.set(position.x, position.y, position.z);

            // Initialize bounds only once
            this.updateSides();
            
            this.velocity = velocity;
            this.gravity = -0.002;
            this.zAcceleration = zAcceleration;
        }

        updateSides() {
            // Calculate bounds based on current position
            this.right = this.position.x + this.width / 2;
            this.left = this.position.x - this.width / 2;
            this.bottom = this.position.y - this.height / 2;
            this.top = this.position.y + this.height / 2;
            this.front = this.position.z + this.depth / 2;
            this.back = this.position.z - this.depth / 2;
        }

        update(ground) {
            this.updateSides();

            if (this.zAcceleration) this.velocity.z += 0.0003;

            // Update only x and z positions
            this.position.x += this.velocity.x;
            this.position.z += this.velocity.z;

            // Handle gravity and ground collision
            this.applyGravity(ground);
        }

        applyGravity(ground) {
            // Apply gravity
            this.velocity.y += this.gravity;

            // Check for ground collision only if falling
            if (this.position.y + this.velocity.y <= ground.position.y + (ground.height / 2) + (this.height / 2)) {
                const friction = 0.5;
                this.velocity.y *= -friction; // Simulate bounce
                this.position.y = ground.position.y + (ground.height / 2) + (this.height / 2); // Adjust position on the ground
            } else {
                this.position.y += this.velocity.y; // Move position if not colliding
            }
        }
    }

    function boxCollision({ box1, box2 }) {
        // Box collision check using pre-calculated bounds
        return (
            box1.right >= box2.left &&
            box1.left <= box2.right &&
            box1.bottom + box1.velocity.y <= box2.top &&
            box1.top >= box2.bottom &&
            box1.front >= box2.back &&
            box1.back <= box2.front
        );
    }

    function playerCollision({ player, box }) {
        let collisionRadius;

        if (player.modelLoaded) {
            // Use bounding box of model as approximation
            const bbox = new THREE.Box3().setFromObject(player);
            const center = new THREE.Vector3();
            bbox.getCenter(center);
            const size = new THREE.Vector3();
            bbox.getSize(size);
            collisionRadius = Math.max(size.x, size.y, size.z) / 2;
            return new THREE.Vector3(
                Math.max(box.left, Math.min(center.x, box.right)),
                Math.max(box.bottom, Math.min(center.y, box.top)),
                Math.max(box.back, Math.min(center.z, box.front))
            ).distanceTo(center) <= collisionRadius;
        } else {
            // Use default sphere collision
            const sphereCenter = new THREE.Vector3(player.position.x, player.position.y, player.position.z);
            const sphereRadius = player.radius;

            const closestPoint = new THREE.Vector3(
                Math.max(box.left, Math.min(sphereCenter.x, box.right)),
                Math.max(box.bottom, Math.min(sphereCenter.y, box.top)),
                Math.max(box.back, Math.min(sphereCenter.z, box.front))
            );

            return closestPoint.distanceTo(sphereCenter) <= sphereRadius;
        }
    }

    const concrete = 'road.jpg';

    const ground = new Box({
        width: 3,
        height: 1.5,
        depth: 50,
        color: '#000000',
        position: {
            x: 0,
            y: -3,
            z: 0
        },
        textureUrl: concrete
    })
    ground.receiveShadow = true
    scene.add(ground);

    // Define track properties
    const trackWidth = ground.width / 3
    const trackHeight = 1.5
    const trackDepth = 50

    // Create three separate tracks
    const track1 = new Box({
        width: trackWidth,
        height: trackHeight,
        depth: trackDepth,
        color: '#4B3A2D',
        position: {
            x: -trackWidth,  // Left track
            y: -3,
            z: 0
        }
    })
    track1.receiveShadow = true

    const track2 = new Box({
        width: trackWidth,
        height: trackHeight,
        depth: trackDepth,
        color: '#6A584C',
        position: {
            x: 0,  // Center track
            y: -3,
            z: 0
        }
    })
    track2.receiveShadow = true

    const track3 = new Box({
        width: trackWidth,
        height: trackHeight,
        depth: trackDepth,
        color: '#4B3A2D',
        position: {
            x: trackWidth,  // Right track
            y: -3,
            z: 0
        }
    })
    track3.receiveShadow = true

    // Set up lighting
    const light = new THREE.DirectionalLight(0xffffff, 1);
    light.position.set(0, 3, 1);
    light.castShadow = true;
    scene.add(light);

    scene.add(new THREE.AmbientLight(0xffffff, 0.5));

    camera.position.z = 5;
    console.log(ground.top);
    
    const keys = {
        a: {
            pressed: false
        },
        d: {
            pressed: false
        }
    }

    let targetLanePosition = sphere.position.x; // Add a target position for smooth movement
    const moveSpeed = 0.1; // Speed for the lane movement

    window.addEventListener('keydown', (event) => {
        switch (event.code) {
            case 'KeyA':  // Move left
                if (currentLane > 0) {
                    currentLane--; // Move to the left lane
                    targetLanePosition = lanes[currentLane]; // Set target position for the new lane
                }
                break;
            case 'KeyD': // Move right
                if (currentLane < lanes.length - 1) {
                    currentLane++; // Move to the right lane
                    targetLanePosition = lanes[currentLane]; // Set target position for the new lane
                }
                break;
            case 'Space':
                sphere.jump(); // Jump action
                break;
        }
    });
    let touchStartX = 0;
    let touchStartY = 0;
    const swipeThreshold = 30; // Minimum distance for a swipe

    const difficulty = "<?= $difficultyLevel ?>";
    console.log("Difficulty Level:", difficulty);
    document.getElementById("difficultyDisplay").textContent = "Level: " + difficulty.charAt(0).toUpperCase() + difficulty.slice(1);
    
    document.addEventListener("touchstart", (event) => {
        touchStartX = event.touches[0].clientX;
        touchStartY = event.touches[0].clientY;
    });

    document.addEventListener("touchend", (event) => {
        let touchEndX = event.changedTouches[0].clientX;
        let touchEndY = event.changedTouches[0].clientY;
        
        let deltaX = touchEndX - touchStartX;
        let deltaY = touchEndY - touchStartY;

        // Determine if swipe is horizontal or vertical
        if (Math.abs(deltaX) > Math.abs(deltaY)) {
            // **Horizontal Swipe (Left/Right)**
            if (deltaX > swipeThreshold) {
                if (currentLane < lanes.length - 1) {
                    currentLane++; // Move to the right lane
                    targetLanePosition = lanes[currentLane]; // Set target position for the new lane
                }
            } else if (deltaX < -swipeThreshold) {
                if (currentLane > 0) {
                    currentLane--; // Move to the left lane
                    targetLanePosition = lanes[currentLane]; // Set target position for the new lane
                }
            }
        } else {
            // **Vertical Swipe (Up)**
            if (deltaY < -swipeThreshold) {
                sphere.jump(); // Swipe up → Jump
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function () { 
        const pauseButton = document.getElementById("pauseButton");
        const modalCloseButton = document.getElementById("modalCloseButton");
        const pauseModalBody = document.querySelector("#pauseModal .modal-body");

        let isPaused = false;

        function resumeGame() {
            gamePaused = false;
            isPaused = false;
            $('#pauseModal').modal('hide');
            pauseButton.innerHTML = "❚❚"; // Pause icon
        }

        pauseButton.addEventListener("click", function () {
            if (!isPaused) {
                gamePaused = true;
                isPaused = true;

                // Display the stored question when paused
                pauseModalBody.innerHTML = `<p>The game is paused!</p>
                    <p><strong>Current Question:</strong> ${currentQuestionText || "No question loaded yet."}</p>`
                    // <button id="resumeButton" class="btn-custom">Resume</button>;

                $('#pauseModal').modal('show');
                pauseButton.innerHTML = "▶"; // Play icon

                // Ensure the Resume button works correctly
                document.getElementById("resumeButton").addEventListener("click", resumeGame);
            } else {
                resumeGame();
            }
        });
         quitButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to quit?')) {
            // window.location.href = '<?= \yii\helpers\Url::to(['student/assgall']) ?>';
                saveScore();
                history.back();
            }
        });

        modalCloseButton.addEventListener("click", resumeGame);
    });

    function smoothLaneSwitch() {
        // Calculate the difference between the current position and the target position
        const xDifference = targetLanePosition - sphere.position.x;

        // Move the sphere towards the target position
        sphere.position.x += xDifference * moveSpeed; 

        if (Math.abs(xDifference) < 0.01) {
            sphere.position.x = targetLanePosition; // Snap to target position if close
        }
    }
  
    let frames = 0
    let spawnRate = Math.floor(Math.random() * 200) + 50;  // Random rate between 50 and 250

    const lanes = [-trackWidth, 0, trackWidth];
    let currentLane = 1; 

    const groundSegmentPool = [];
    const segmentLength = 50; 
    const totalSegments = 6; 
    const dracoLoader = new DRACOLoader();

    dracoLoader.setDecoderPath('https://www.gstatic.com/draco/v1/decoders/');

    // Attach DRACOLoader to GLTFLoader
    loader.setDRACOLoader(dracoLoader);
    

    // Create initial ground segments and store them in the pool
    function createGroundSegment(position) {
        const segment = new Box({
            width: 3,
            height: 1.5,
            depth: segmentLength,
            color: '#000000',
            position: { x: 0, y: position.y, z: position.z },
            textureUrl: concrete
        });
        segment.receiveShadow = true;

        const track1segment = new Box({
            width: trackWidth,
            height: 1.5,
            depth: segmentLength,
            color: '#4B3A2D',
            position: { x: -trackWidth, y: position.y, z: position.z }
        });
        track1segment.receiveShadow = true;

        const track2segment = new Box({
            width: trackWidth,
            height: 1.5,
            depth: segmentLength,
            color: '#6A584C',
            position: { x: 0, y: position.y, z: position.z },
        });
        track2segment.receiveShadow = true;

        const track3segment = new Box({
            width: trackWidth,
            height: 1.5,
            depth: segmentLength,
            color: '#4B3A2D',
            position: { x: trackWidth, y: position.y, z: position.z }
        });
        track3segment.receiveShadow = true;

        scene.add(segment);

        // Return the segment (and track segments) so it can be tracked for recycling
        return segment;
    }

    // Modify the ground segment initialization to capture the returned segment
    for (let i = 0; i < totalSegments; i++) {
        const segment = createGroundSegment({ y: -3, z: -i * segmentLength });
        groundSegmentPool.push(segment);
    }

    function recycleGroundSegment(segment) {
        // Find the furthest back segment and place the new one behind it
        const lastSegment = groundSegmentPool.reduce((prev, curr) => prev.position.z < curr.position.z ? prev : curr);
        const newZ = lastSegment.position.z - segmentLength;

        segment.position.z = newZ;

        // console.log(`Recycled segment at Z: ${newZ}, tracks repositioned correctly.`);
    }


    // Ensure ground segments are recycled and managed properly
    function manageGroundSegments() {
        const sphereZ = sphere.position.z;

        for (let i = 0; i < groundSegmentPool.length; i++) {
            if (groundSegmentPool[i].position.z > sphereZ + 60) {
                recycleGroundSegment(groundSegmentPool[i]);
                groundSegmentPool[i].visible = false;
            } else {
                groundSegmentPool[i].visible = true;
            }
        }

        // Ensure there are enough segments in the pool
        while (groundSegmentPool.length < totalSegments) {
            const lastSegment = groundSegmentPool[groundSegmentPool.length - 1];
            const newZ = lastSegment.position.z - segmentLength;

            groundSegmentPool.push(createGroundSegment({ y: -3, z: newZ }));
        }
    }


    function checkGroundCollision(enemy) {
        const boundingBox = new THREE.Box3().setFromObject(enemy);

        const depthReduction = 1.35; 
        boundingBox.max.z -= depthReduction; // Shrink back side

        for (let segment of groundSegmentPool) {
            const segmentBox = new THREE.Box3().setFromObject(segment);
            if (boundingBox.intersectsBox(segmentBox)) {
                return segment.position.y + (segment.geometry.parameters.height / 2);
            }
        }
        return null; // No collision
    }

    const lampPostPool = [];
    let lampPostModel;
    const lampPostCount = 14;

    let alternateSide = true; // To alternate between left and right sides
    
    const modelPath = '<?= $modelPath ?? "lamp_old.glb" ?>';
    
    loader.load(modelPath, (gltf) => {
        lampPostModel = gltf.scene;
        initializeLampPosts(); // Initialize lamp posts after loading
    }, undefined, (error) => {
        console.error("Failed to load lamp model:", error);
    });
    
    // Function to initialize lamp posts
    function initializeLampPosts() {
        if (!lampPostModel) {
            console.warn("Lamp post model not loaded yet.");
            return;
        }

        for (let i = 0; i < lampPostCount; i++) {
            const position = {
                x: alternateSide ? -1.9 : 1.9, // Alternate left and right sides
                y: -2.7, // Adjust based on model origin
                z: i * 15, // Even spacing along the Z-axis
            };

            const lampPost = createLampPost(position);
            lampPost.rotation.y = Math.PI / 2;
            lampPostPool.push(lampPost);

            alternateSide = !alternateSide; // Switch side for the next lamp post
        }
    }

    // Function to create a lamp post from the model
    function createLampPost(position) {
        const lampPost = lampPostModel.clone(true); // Deep clone, including child meshes
        lampPost.position.set(position.x, position.y, position.z);

        lampPost.scale.set(0.7, 0.7, 0.7); 

        scene.add(lampPost);
        return lampPost;
    }

    function recycleLampPost(lampPost) {
        const farthestLampPostZ = Math.min(...lampPostPool.map((lp) => lp.position.z));
        const newZ = farthestLampPostZ - 4.5;

        lampPost.position.set(alternateSide ? -1.9 : 1.9, -2.7, newZ);
        if (Math.abs(lampPost.position.x - 1.9) < 0.01) {
            lampPost.rotation.y = -Math.PI / 2;
        } else {
            lampPost.rotation.y = Math.PI / 2;
        }
        alternateSide = !alternateSide;
    }

    function manageLampPosts() {
        const sphereZ = sphere.position.z;

        // Check at a lower frequency to optimize performance
        if (performance.now() % 500 < 16) {
            lampPostPool.forEach((lampPost) => {
                if (lampPost.position.z > sphereZ + 30) {
                    recycleLampPost(lampPost);
                }
            });
        }
    }


    function updateCamera() {
        camera.position.x = sphere.position.x;
        camera.position.y = sphere.position.y + 1.65;
        camera.position.z = sphere.position.z + 3.5;
        camera.lookAt(sphere.position);
    }

    
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

        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        //const returnButton=document.getElementById('gotoAssignments');
        
        let optionCubes = [];
        let questionText;
        let currentQuestionIndex = 0;
        let score = 0,accuracy=0,speed=0;
        let questions = [];
        let gamePaused = false;
        let startTime = Date.now();
        let total=0;
        let currentQuestionText = ""; // Store the current question text
        let time = 0;


        const optionArrow = 'arrow_texture.jpg';

        let englishFont = null;
        let arabicFont = null;

        // Load English Font
        fontLoader.load('luckiestGuyRegular.json', function (font) {
            englishFont = font;
            console.log("English Font Loaded:", englishFont);
        });

        // Load Arabic Font
        fontLoader.load('notoSansArabic_Regular.json', function (font) {
            arabicFont = font;
            console.log("Arabic Font Loaded:", arabicFont);
        });

        document.addEventListener("DOMContentLoaded", () => {
            function unlockSpeech() {
                const utterance = new SpeechSynthesisUtterance("");
                speechSynthesis.speak(utterance);
                speechSynthesis.cancel(); 
                document.removeEventListener("click", unlockSpeech);
            }
            document.addEventListener("click", unlockSpeech, { once: true });
        });

        function narrateText(text) {
            return new Promise((resolve, reject) => {
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'en-GB'; // UK English
                    utterance.rate = 1; // Normal speed
                    utterance.pitch = 1; // Normal pitch

                    // Fetch available voices
                    const loadVoices = () => {
                        const voices = speechSynthesis.getVoices();

                        // Log available voices
                        console.log("Available voices:", voices);

                        // Find "Google UK English Female"
                        const selectedVoice = voices.find(voice => voice.name === "Google UK English Female");

                        if (selectedVoice) {
                            console.log("Selected voice:", selectedVoice.name);
                            utterance.voice = selectedVoice;
                        } else {
                            console.warn("Google UK English Female not found. Using default voice.");
                        }

                        // Set up the event listener to resolve when the speech ends
                        utterance.onend = () => {
                            resolve(); // Resolve the promise when speech ends
                        };

                        utterance.onerror = (error) => {
                            reject(error); // Reject the promise if there's an error
                        };

                        // Speak the text
                        speechSynthesis.speak(utterance);
                    };

                    // Ensure voices are loaded
                    if (speechSynthesis.getVoices().length) {
                        loadVoices();
                    } else {
                        speechSynthesis.onvoiceschanged = loadVoices;
                    }
                } else {
                    reject("Your browser does not support speech synthesis.");
                }
            });
        }
        let isLoading = false;
        let allQuestions = [];
        let questionsFetched = false;

        function fetchQuestionsOnce() {
            if (questionsFetched) return Promise.resolve(allQuestions);
            return fetchQuestions().then((questions) => {
                allQuestions = questions;
                questionsFetched = true;
                return allQuestions;
            });
        }

        let optionsCache = {};

        function fetchOptionsOnce(questionNo) {
            if (optionsCache[questionNo]) {
                return Promise.resolve(optionsCache[questionNo]);
            }
            return fetchOptions(questionNo).then((options) => {
                optionsCache[questionNo] = options;
                return options;
            });
        }


        function loadNewQuestionAndOptions(font, animationId) {
            if (isLoading) return;
            isLoading = true;

            fetchQuestionsOnce().then(function (questions) {
                total = questions.length;
                console.log("Total questions:", total);

                if (questions.length > 0 && currentQuestionIndex < questions.length) {
                    const question = questions[currentQuestionIndex];
                    console.log(question.QuestionStatement);

                    currentQuestionText = question.QuestionStatement;

                    //let qsText = question.QuestionStatement ?? "";
                    let qsText = <?= json_encode($qsText) ?>;
                    console.log(qsText);
                    narrateText(qsText);

                    // Display the question on screen and pause the game
                    showQuestionWithTimer(question.QuestionStatement, font, function() {
                        // After 5 seconds, load options and resume the game
                        fetchOptionsOnce(question.QuestionNo).then(function (options) {

                            if (options.length > 0) {
                                optionCubes.forEach(option => {
                                    scene.remove(option.sphere);
                                    option.sphere.geometry.dispose();
                                    option.sphere.material.dispose();
                                });

                                optionCubes.length = 0;

                                options.forEach((option, index) => {
                                    const optionText = createTextMesh(option.option_text, font);
                                    console.log(option.option_text);
                                    const optionCube = createOptionCube(optionText, {
                                        x: lanes[Math.floor(Math.random() * lanes.length)],
                                        y: -1.5,
                                        z: sphere.position.z - (Math.random() * 50) - 35
                                    });
                                    scene.add(optionCube);

                                    optionCubes.push({
                                        sphere: optionCube,
                                        text: optionText,
                                        correct: option.option_type === "correct"
                                    });
                                });

                                gamePaused = false;  // Resume the game
                                renderer.render(scene, camera);
                            } else {
                                console.error("No options fetched");
                            }
                            }).catch(function (error) {
                                console.error("Error fetching options:", error);
                            }).then(function () {
                                // Acts like finally
                                isLoading = false;
                            });
                    });
                } else {
                    console.log("All questions answered");
                    saveScore();
                    gameOver();
                    isLoading = false;
                }
            }).catch(function (error) {
                console.error("Error fetching questions:", error);
                isLoading = false;
            });
        }
        //'Amiri_Regular.json'
        function showQuestionWithTimer(questionText, font, callback) {
            const questionContainer = document.getElementById('question-container');
            const timerElement = document.getElementById('timer');
            const timerCount = document.getElementById('timer-count');
            const pauseButton = document.getElementById("pauseButton"); // Get pause button

            if (!questionContainer || !timerElement || !timerCount || !pauseButton) {
                console.error("Required elements not found");
                return;
            }

            gamePaused = true; // Pause the game
            pauseButton.disabled = true; // Disable pause button during countdown

            questionContainer.innerText = questionText;
            questionContainer.style.display = "block"; // Show question

            let timeLeft = 5;
            timerElement.style.display = "block"; // Show timer
            timerCount.innerText = timeLeft;

            const countdown = setInterval(() => {
                timeLeft--;
                timerCount.innerText = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    timerElement.style.display = "none"; // Hide timer
                    questionContainer.style.display = "none"; // Hide question

                    pauseButton.disabled = false; // Re-enable pause button
                    callback(); // Continue with game logic
                }
            }, 1000);
        }


        fontLoader.load('luckiestGuyRegular.json', function (font) {
            console.log('Loaded font:', font);
            loadNewQuestionAndOptions(font);

            let reloadOptionsFlag = false; // Flag to determine if options should be reloaded
            let gameOverFlag = false; // Flag to ensure game logic halts after game over
            let currentQuestionMissed = false; // Flag to track if current question was missed
            
            function monitorSphereAndOptions(font, animationId) {
                // If there are no option cubes, nothing to check
                if (optionCubes.length === 0) return;

                const sphereZ = sphere.position.z;
                const buffer = 30; // Distance threshold behind the sphere

                // Check if every option cube is behind the sphere by the buffer distance.
                const allCubesPassed = optionCubes.every(option => option.sphere.position.z > sphereZ + buffer);

                // When all cubes have passed, decide whether to reload (or move on)
                if (allCubesPassed && !reloadOptionsFlag) {
                    reloadOptionsFlag = true;
                    if (!currentQuestionMissed) {
                        // First miss: give the player another chance with new options
                        currentQuestionMissed = true;
                        loadNewQuestionAndOptions(font, animationId);
                    } else {
                        // Second miss: proceed to the next question
                        currentQuestionMissed = false;  // Reset missed flag
                        currentQuestionIndex++;
                        loadNewQuestionAndOptions(font, animationId);
                    }
                }
                // Reset the flag once any cube is within the threshold again.
                else if (!allCubesPassed) {
                    reloadOptionsFlag = false;
                }
            }

            function animate() {
                if (gameOverFlag) return;
                const animationId = requestAnimationFrame(animate);
                time += 0.1; // Controls speed of wave motion

                water.position.z = Math.sin(time) * 3.5; // Moves in and out
                water.position.y = -2.5 + Math.sin(time * 0.5) * 0.1; // Slight rise & fall

                if (gamePaused) return; // Exit if the game is paused

                smoothLaneSwitch();
                sphere.update(ground);
                updateCamera();

                manageGroundSegments();
                manageLampPosts();
                monitorSphereAndOptions(font, animationId);
            
                let isProcessingCollision = false;

                // Update the logic inside the `animate` function where the collision occurs:
                optionCubes.forEach(option => {
                    if (playerCollision({ player: sphere, box: option.sphere }) && !isProcessingCollision) {
                        isProcessingCollision = true;
                        console.log('Collision detected!');
                        gamePaused = true;

                        if (option.correct) {
                            score++;
                        }
                    
                        currentQuestionMissed = false;  
                        document.getElementById('current-score').innerText = score;
                        currentQuestionIndex++;  // Move to the next question
                        loadNewQuestionAndOptions(font, animationId);

                        setTimeout(() => {
                            isProcessingCollision = false;
                        }, 500);
                    }
                });

                frames++;
                renderer.render(scene, camera);
            }
            animate();
        });
        
        const screenWidth = window.innerWidth;
        const textSize = screenWidth < 600 ? 0.2 : 0.3;
        function createTextMesh(text) {
            if (!text) {
                console.error("Invalid text:", text);
                return null;
            }

            const isArabic = /[\u0600-\u06FF]/.test(text); // Detect Arabic script
            const font = isArabic ? arabicFont : englishFont;

            if (!font) {
                console.error("Font not loaded yet!");
                return null;
            }

            // If the text is Arabic, reshape it and reverse it
            if (isArabic) {
                text = ArabicReshaper.convertArabic(text); // Reshape Arabic text
                text = text.split('').reverse().join(''); // Reverse the text to fix the letter order
            }

            const textGeometry = new TextGeometry(text, {
                font: font,
                size: 0.2,
                height: 0.01,
                curveSegments: 8,
            });

            textGeometry.computeBoundingBox();
            const boundingBox = textGeometry.boundingBox;
            const textCenter = boundingBox.getCenter(new THREE.Vector3());
            textGeometry.translate(-textCenter.x, -textCenter.y, -textCenter.z);

            const textMaterial = new THREE.MeshBasicMaterial({ color: '#FFFFFF' });
            const textMesh = new THREE.Mesh(textGeometry, textMaterial);

            return textMesh;
        }

        function createOptionCube(textMesh, position) {
            // Create the cube using the Box class
            const opcube = new Box({
                width: 0.5,
                height: 0.5,
                depth: 0.5,
                color: 'red',
                position: position,
                textureUrl: optionArrow, // Ensure the texture URL is correctly set in the Box class
            });

            // Apply emissive properties only if material exists
            if (opcube.material) {
                opcube.material.emissive = new THREE.Color(0xffa500); // Orange emissive glow
                opcube.material.emissiveIntensity = 0.3; // Reduced for better performance
            }

            // Add the text mesh as a child of the cube
            opcube.add(textMesh);

            // Calculate and cache the bounding box once for efficiency
            const boundingBox = new THREE.Box3().setFromObject(opcube);
            const cubeSize = boundingBox.getSize(new THREE.Vector3());
            const cubeCenter = boundingBox.getCenter(new THREE.Vector3());

            // Position the text mesh slightly above the cube
            textMesh.position.set(
                0, // Center horizontally relative to the cube
                cubeSize.y / 2 + 0.5, // Slightly above the top face of the cube
                0 // Center depth-wise relative to the cube
            );

            return opcube;
        }


        function saveScore(){
            const endTime = Date.now();
            speed = Math.round((endTime - startTime) / 1000); // Time in seconds
            accuracy = Math.round((score / total) * 100); // Percentage of correct answers
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
                    total: total,
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

            $('#gameOverModal').modal('show');
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