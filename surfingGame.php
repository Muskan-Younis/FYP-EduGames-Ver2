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
            padding: 10px 8px;
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
        #coin-counter {
            position: absolute;
            top: 70px;
            right: 10px;  /* Move it to the right */
            font-size: 1.5em;
            color: yellow;
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 8px;
            border-radius: 5px;
            text-align: center;
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
        #lives-container {
            position: absolute;
            top: 90px;
            left: 10px;
            display: flex;
            gap: 6px;
            padding: 6px 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(3px);
            font-size: 26px;
            z-index: 1000;
        }

        .heart {
            display: inline-block;
            transition: opacity 0.4s ease;
        }

        .heart.fade-out {
            animation: blinkOut 0.6s forwards;
        }

        @keyframes blinkOut {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(1.2); }
            100% { opacity: 0; transform: scale(0.8); }
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

            /* Parent container styles */
            #gameTitle, #difficultyDisplay {
                display: flex;
                align-items: center;
                justify-content: center; /* centers title horizontally */
                position: relative; /* so we can absolutely place difficulty */
            }

            /* Title stays centered */
            #gameTitle {
                font-size: 1.2em;
                padding: 6px 12px;
                background: linear-gradient(90deg, rgb(235, 50, 121), rgb(32, 211, 211));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            /* Difficulty fixed to the left side within same row */
            #difficultyDisplay {
                position: absolute;
                left: 10px;
                top: 80%;
                transform: translateY(-50%); /* vertically align with title */
                font-size: 12px;
                padding: 4px 6px;
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

            #coin-counter {
                top: 115px;
                right: 5px;
                font-size: 1.2em;
                padding: 8px 10px;
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
            #lives-container {
                position: absolute;
                top: 70px; /* pushes it below the difficulty display */
                left: 10px;
                font-size: 16px;
                padding: 4px 6px;
                gap: 4px;
                z-index: 1000;
            }

            .heart {
                font-size: 18px;
                transform: scale(1);
                transition: transform 0.2s ease;
            }

            .heart:active {
                transform: scale(1.3);
            }

        }

    </style>
</head>
<body>
<!-- 
<div id="progress-container" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 300px; height: 30px; background-color: #e0e0e0; border-radius: 5px; display: none;">
    <div id="progress-bar" style="height: 100%; width: 0; background-color: #4caf50; border-radius: 5px;"></div>
</div> -->

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
            Coins Collected: <span id="coinDisplay"></span>
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
        <div id="gameTitle">Surfing Game</div>
        <div id="difficultyDisplay">Level: Loading...</div>
    </div>
    <div id="question-container"></div>
    <div id="timer">
         <span id="timer-count">5</span>
    </div>
    <div id="coin-counter">Coins: 0</div>
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
    <div id="lives-container">
        <span class="heart" id="heart1">❤️</span>
        <span class="heart" id="heart2">❤️</span>
        <span class="heart" id="heart3">❤️</span>
        <span class="heart" id="heart4">❤️</span>
        <span class="heart" id="heart5">❤️</span>
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
    const clock = new THREE.Clock();

    function isMobile() {
        return /Android|iPhone|iPad|iPod|Windows Phone/i.test(navigator.userAgent);
    }

    if (isMobile()) {
        // Decide cap based on devicePixelRatio
        let cap;
        if (window.devicePixelRatio <= 2) {
            cap = 2; // Mid or low DPI phones get sharper display
        } else {
            cap = 1.75; // Very high DPI phones get a balance of sharpness & performance
        }

        renderer.setPixelRatio(Math.min(window.devicePixelRatio, cap));
        renderer.setSize(window.innerWidth, window.innerHeight, false);
    }

    // Procedural Sky
    const sky = new Sky();
    sky.scale.setScalar(450000); // Large enough for the scene
    scene.add(sky);

    // Add Sunlight to Sky
    const sun = new THREE.Vector3();
    const skyUniforms = sky.material.uniforms;

    skyUniforms['turbidity'].value = 2;  // Less hazy (default is 10)
    skyUniforms['rayleigh'].value = 0.5; // Reduce blue scatter (default is 2)
    skyUniforms['mieCoefficient'].value = 0.002; // Reduce sun glow (default is 0.005)
    skyUniforms['mieDirectionalG'].value = 0.5;  // Softer sunlight (default is 0.8)

    // Move the sun slightly to reduce intensity
    const sunPosition = new THREE.Vector3(5, 3, -10); // Lowering the sun a bit
    sky.material.uniforms['sunPosition'].value.copy(sunPosition);

    // Water at y = -2
    const waterGeometry = new THREE.PlaneGeometry(5000, 5000);
    const water = new Water(waterGeometry, {
        textureWidth: 256, // Lowered from 512
        textureHeight: 256, 
        waterNormals: textureLoader.load(
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
    const sandGeometry = new THREE.PlaneGeometry(100, 100);
    const sandTexture = textureLoader.load('footsand.jpg');

    sandTexture.wrapS = sandTexture.wrapT = THREE.RepeatWrapping;
    sandTexture.repeat.set(10, 10);

    const sandMaterial = new THREE.MeshStandardMaterial({
        map: sandTexture,
        roughness: 1.0,
        displacementMap: sandTexture, // Use texture to create sand dunes
        displacementScale: 0.5, // Controls bumpiness
    });

    const sand = new THREE.Mesh(new THREE.PlaneGeometry(100, 100, 50, 50), sandMaterial);
    sand.rotation.x = -Math.PI / 2;
    sand.position.y = -2.48;
    scene.add(sand);

    loader.load('palmtree_texture_Optimized.glb', (gltf) => {
        console.log("GLB Loaded:", gltf);

        const model = gltf.scene;
        let palmTreeMesh = null;

        // Find the actual mesh inside the GLB scene
        model.traverse((child) => {
            if (child.isMesh) {
                palmTreeMesh = child;
            }
        });

        if (!palmTreeMesh) {
            console.error("ERROR: No mesh found in palm_tree.glb!");
            return;
        }

        const count = 15; // Number of trees
        const instancedPalmTrees = new THREE.InstancedMesh(
            palmTreeMesh.geometry,
            palmTreeMesh.material,
            count
        );

        const dummy = new THREE.Object3D();

        for (let i = 0; i < count; i++) {
            dummy.position.set((Math.random() * 80) - 40, -1, (Math.random() * 80) - 40);
            dummy.updateMatrix();
            instancedPalmTrees.setMatrixAt(i, dummy.matrix);
        }

        scene.add(instancedPalmTrees);
    }, undefined, (error) => {
        console.error("GLB Load Failed:", error);
    });


    const foamTexture = textureLoader.load('seafoamtexture.jpg', (texture) => {
        texture.wrapS = texture.wrapT = THREE.RepeatWrapping;
        texture.repeat.set(5, 1); // Stretch foam horizontally
    });

    const foamMaterial = new THREE.MeshBasicMaterial({ 
        map: foamTexture, 
        transparent: true,   
        opacity: 0.8,       
    });

    const foamPlane = new THREE.Mesh(new THREE.PlaneGeometry(100, 15), foamMaterial);
    foamPlane.rotation.x = -Math.PI / 2;
    foamPlane.position.set(0, -2.4, -100); 
    scene.add(foamPlane);

    let coinModel = null;

    const skyTexture = textureLoader.load('sunrisebg.png');
    scene.background = skyTexture; 

   class Player extends THREE.Object3D {
        constructor({
            radius,
            widthSeg = 8,
            heightSeg = 8,
            position = { x: 0, y: 0, z: 0 },
            velocity = { x: 0, y: 0, z: 0 },
            color = '#00ff00',
            jumpHeight = 3.09,
            zAcceleration = false,
            modelPath = null // <-- NEW
        }) {
            super();

            this.radius = radius;
            this.velocity = velocity;
            this.gravity = -2.8;
            this.isOnGround = true;
            this.jumpHeight = jumpHeight;
            this.zAcceleration = zAcceleration;

            this.modelLoaded = false; // Track model loading
            this.mixer = null; // For animation
            this.clock = new THREE.Clock(); // Clock for animations

            if (modelPath) {
                loader.load(modelPath, gltf => {
                    this.model = gltf.scene;
                    this.model.position.set(position.x, position.y, position.z);
                    this.add(this.model);
                    this.modelLoaded = true;
                    this.model.rotation.y = Math.PI;
                    console.log("Available animations:", gltf.animations.map(a => a.name));

                    // Check and apply animation if exists
                    if (gltf.animations && gltf.animations.length > 0) {
                        this.model.scale.set(0.005, 0.005, 0.005); 
                        this.mixer = new THREE.AnimationMixer(this.model);
                        const action = this.mixer.clipAction(gltf.animations[0]);
                        action.play();

                        this.mixer.timeScale = 2.5; 
                    }
                    else{  
                        this.model.scale.set(0.3, 0.3, 0.3); 
                    }
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
                this.model = mesh;
            }

            this.position.set(position.x, position.y, position.z);
        }

        jump() {
            if (this.isOnGround) {
                this.velocity.y = this.jumpHeight;
                this.isOnGround = false;
            }
        }

        updateSides(delta) {
            const speed = 5;
            this.position.z -= speed * delta;
        }

        update(ground, delta) {
            this.updateSides(delta);

            if (this.zAcceleration) {
                this.velocity.z += 0.0003 * delta;  // acceleration per second
            }

            this.position.x += this.velocity.x * delta;
            this.position.z += this.velocity.z * delta;

            this.applyGravity(ground, delta);  // This function should also be delta-aware if it modifies position/velocity

            if (this.mixer) {
                this.mixer.update(delta);  // Replacing internal clock with passed delta
            } else if (this.model) {
                this.model.rotation.x -= 0.11 * delta;
            }
        }

        applyGravity(ground, delta) {
            if (!this.isOnGround) {
                this.velocity.y += this.gravity * delta;  // gravity per second
            } else {
                this.velocity.y = 0;
            }

            this.position.y += this.velocity.y * delta;

            const groundY = ground.position.y + (ground.height / 2) + this.radius;

            if (this.position.y <= groundY) {
                this.isOnGround = true;
                this.position.y = groundY;
            } else {
                this.isOnGround = false;
            }
        }

    }
    const avatarPath = <?= json_encode($avatarPath ?? null) ?>;

    const sphere = new Player({
        radius: 0.34,
        widthSeg: 32,
        heightSeg: 16,
        position: { x: 0, y: 0, z: 0 },
        velocity: { x: 0, y: -0.01, z: 0 },
        modelPath: avatarPath 
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
                texture.repeat.set(8, 80);
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
            collisionRadius = Math.max(size.x, size.y, size.z) / 6;
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

    function detectCollision({ player, trainModel }) {
        const sphereCenter = player.position.clone();
        const sphereRadius = player.radius;

        const boundingBox = new THREE.Box3().setFromObject(trainModel);

        const depthReduction = 1.35; 
        boundingBox.max.z -= depthReduction; // Shrink right side
        boundingBox.min.x += 0.1;        
        boundingBox.max.x -= 0.1;
        boundingBox.max.y -= 0.1;

        // const boxHelper = new THREE.Box3Helper(boundingBox, 0xff0000); // Red wireframe
        // scene.add(boxHelper);

        const closestPoint = new THREE.Vector3();
        boundingBox.clampPoint(sphereCenter, closestPoint);

        return closestPoint.distanceTo(sphereCenter) <= sphereRadius;
    }

    let collisionCooldown = false;
    let collisionCooldownTime = 1000; // in milliseconds

    function handleCollision() {
        if (collisionCooldown) return;
        const heart = document.getElementById(`heart${lives}`);

        triggerCameraShake(0.15, 0.4);
        console.log('Collision detected!');

        if (lives > 0) {
            heart.classList.add('fade-out');
            setTimeout(() => {
                heart.style.visibility = 'hidden';
            }, 600);
            lives--;
        }

        if (lives <= 0) {
            saveScore();
            gameOver();
        }

        collisionCooldown = true;
        setTimeout(() => {
            collisionCooldown = false;
        }, collisionCooldownTime);
    }

    function coinCollision(player, coin) {
        const playerBoundingSphere = new THREE.Sphere(player.position, player.radius);

        const coinBoundingBox = new THREE.Box3().setFromObject(coin);
        
        // Get the center and size of the bounding box
        const coinCenter = new THREE.Vector3();
        coinBoundingBox.getCenter(coinCenter);
        
        const coinSize = new THREE.Vector3();
        coinBoundingBox.getSize(coinSize);

        // Approximate coin radius using its largest dimension
        const coinRadius = Math.max(coinSize.x, coinSize.z) / 2; 

        // Create a bounding sphere for the coin
        const coinBoundingSphere = new THREE.Sphere(coinCenter, coinRadius);

        // Check for intersection
        return playerBoundingSphere.intersectsSphere(coinBoundingSphere);
    }

    const concrete = 'footsand.jpg';

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
    let trackModel = null;
    const trainTrackPool = [];
    const dracoLoader = new DRACOLoader();
    
    const trackTexture = textureLoader.load('bronze_texture.jpg', (texture) => {
        texture.wrapS = THREE.ClampToEdgeWrapping;
        texture.wrapT = THREE.ClampToEdgeWrapping;
        texture.needsUpdate = true;
    });

    dracoLoader.setDecoderPath('https://www.gstatic.com/draco/v1/decoders/');

    // Attach DRACOLoader to GLTFLoader
    loader.setDRACOLoader(dracoLoader);

    // Load the train track model
    loader.load('simple_train_track.glb', (gltf) => {
        trackModel = gltf.scene;
        trackModel.traverse((child) => {
            if (child.isMesh) {
                child.castShadow = false;  
                child.receiveShadow = true;

                child.material = new THREE.MeshStandardMaterial({
                    map: trackTexture,  // Apply texture
                    roughness: 0.8,      // Adjust roughness
                    metalness: 0.2       // Adjust metallic property
                });
            }
        });
        trackModel.scale.set(0.03, 0.03, 0.03);
        trackModel.frustumCulled = true;

        for (let i = 0; i < totalSegments; i++) {
            const position = { y: -2.15, z: -i * segmentLength };
            const trackSegment = createTrainTrack(position);

            if (trackSegment) {
                trainTrackPool.push(trackSegment);
            } else {
                console.error("Failed to create train track at position:", position);
            }
        }

        console.log("trackModel is loaded!");
    });

    // Modify createTrainTrack to match segmentLength
    function createTrainTrack(position) {
        if (!trackModel) {
            console.error("trackModel is not loaded!");
            return null;
        }

        const trackGroup = new THREE.Group();
        const numTracksPerLane = 35;
        const trackSpacing = segmentLength / numTracksPerLane;

        try {
            for (let lane = 0; lane < lanes.length; lane++) {
                for (let i = 0; i < numTracksPerLane; i++) {
                    const trackClone = trackModel.clone();
                    trackClone.position.set(lanes[lane], position.y, position.z - i * trackSpacing);
                    trackClone.position.x -= 1.15;
                    trackGroup.add(trackClone);
                }
            }

            scene.add(trackGroup);
            return trackGroup;
        } catch (error) {
            console.error("Error cloning trackModel:", error);
            return null;
        }
    } 
    

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

    function recycleGroundSegment(segment, trackSegment) {
        // Find the furthest back segment and place the new one behind it
        const lastSegment = groundSegmentPool.reduce((prev, curr) => prev.position.z < curr.position.z ? prev : curr);
        const newZ = lastSegment.position.z - segmentLength;

        segment.position.z = newZ;

        const numTracksPerLane = 35; // Same as in createTrainTrack
        const trackSpacing = segmentLength / numTracksPerLane;

        // Recycle lane by lane
        for (let lane = 0; lane < lanes.length; lane++) {
            for (let i = 0; i < numTracksPerLane; i++) {
                const trackIndex = lane * numTracksPerLane + i;
                const track = trackSegment.children[trackIndex];

                // Keep the original X position, set Z position systematically
                track.position.set(track.position.x, -2.15, newZ - i * trackSpacing);
            }
        }

        // console.log(`Recycled segment at Z: ${newZ}, tracks repositioned correctly.`);
    }


    // Ensure ground segments are recycled and managed properly
    function manageGroundSegments() {
        const sphereZ = sphere.position.z;

        for (let i = 0; i < groundSegmentPool.length; i++) {
            if (groundSegmentPool[i].position.z > sphereZ + 60) {
                recycleGroundSegment(groundSegmentPool[i], trainTrackPool[i]);
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

    function createTunnel(groundSegment) {
        const tunnelWidth = trackWidth * 2.5; // Covers all tracks and ground
        const tunnelHeight = 6; // Height of the tunnel
        const tunnelDepth = 50; // Matches the ground segment's depth

        const tunnelTexture = textureLoader.load('concreteTunnel.jpg'); // Replace with your tunnel texture
        tunnelTexture.wrapS = THREE.RepeatWrapping;
        tunnelTexture.wrapT = THREE.RepeatWrapping;
        tunnelTexture.repeat.set(1, 2);

        // Create walls
        const wallGeometry = new THREE.BoxGeometry(0.6, tunnelHeight, tunnelDepth);
        const wallMaterial = new THREE.MeshBasicMaterial({
            map: tunnelTexture,
            side: THREE.DoubleSide,
        });

        const leftWall = new THREE.Mesh(wallGeometry, wallMaterial);
        const rightWall = new THREE.Mesh(wallGeometry, wallMaterial);

        // Position walls on either side of the ground
        leftWall.position.set(-tunnelWidth, tunnelHeight / 2, 0); // Left side
        rightWall.position.set(tunnelWidth, tunnelHeight / 2, 0); // Right side

        // Create rounded top
        const archGeometry = new THREE.CylinderGeometry(tunnelWidth, tunnelWidth, tunnelDepth, 32, 1, true, 0, Math.PI);
        const archMaterial = new THREE.MeshBasicMaterial({
            map: tunnelTexture,
            side: THREE.DoubleSide,
        });

        const arch = new THREE.Mesh(archGeometry, archMaterial);

        // Rotate and position the arch to connect the walls correctly
        arch.rotation.x = Math.PI / 2; // Rotate to align vertically
        arch.rotation.y = Math.PI / 2;
        arch.position.set(0, tunnelHeight, 0); // At the top of the walls

        // Group all parts of the tunnel
        const tunnelGroup = new THREE.Group();
        tunnelGroup.add(leftWall, rightWall, arch);

        // Position the entire tunnel on the ground segment
        tunnelGroup.position.copy(groundSegment.position);

        scene.add(tunnelGroup);

        return tunnelGroup;
    }


    function manageTunnel(tunnel, groundSegment) {
        if (groundSegment.position.z > sphere.position.z + 60) {
            // Recycle tunnel along with the ground segment
            tunnel.position.z = groundSegment.position.z - totalSegments * segmentLength;
        }
    }

    function placeRandomTunnel(groundSegments) {
        // const randomIndex = Math.floor(Math.random() * groundSegments.length);
        const groundSegment = groundSegments[3];
        const tunnel = createTunnel(groundSegment);

        return { tunnel, groundSegment };
    }

    let collectedCoins = 0;
    const coinCounterElement = document.getElementById('coin-counter');
    const coinPool = [];
    const recycleThreshold = 10;
    const updateFrequency = 5;
    let frameCount = 0;

    let currentLaneIndex = 0;
    let coinsPerLane = 5;
    let coinsPlaced = 0;

    // ✅ Get model path from PHP
    const modelPath = '<?= $modelPath ?? "goldcoin_Optimized.glb" ?>';

    loader.load(modelPath, function (gltf) {
        coinModel = gltf.scene;
        console.log('Coin model loaded successfully from:', modelPath);

        function createObject({ type, position }) {
            let object;

            if (type === 'coin' && coinModel) {
                object = coinModel.clone();
                object.scale.set(0.2, 0.2, 0.2);
                object.position.set(position.x, position.y, position.z);
                object.rotation.x = Math.PI / 2;
                object.visible = true;
                object.receiveShadow = true;
                object.userData.type = 'coin';
            } else {
                object = new THREE.Mesh(
                    new THREE.CylinderGeometry(0.24, 0.24, 0.06, 32),
                    new THREE.MeshBasicMaterial({ color: 0xffff00 })
                );
                object.position.set(position.x, position.y, position.z);
                console.log("default model being created");
            }

            return object;
        }

        function createCoinSegment(position) {
            const coin = createObject({
                type: 'coin',
                position: {
                    x: lanes[currentLaneIndex],
                    y: position.y + 0.4,
                    z: position.z
                }
            });

            scene.add(coin);
            coinPool.push(coin);
            coinsPlaced++;

            if (coinsPlaced >= coinsPerLane) {
                currentLaneIndex = (currentLaneIndex + 1) % lanes.length;
                coinsPlaced = 0;
            }
        }

        for (let i = 0; i < 10; i++) {
            createCoinSegment({ y: -2, z: -i * 5 });
        }
    }, undefined, function (error) {
        console.error('Error loading coin model:', error);
    });

    function recycleCoin(coin) {
        const farthestCoinZ = Math.min(...coinPool.map((c) => c.position.z));
        const newZ = farthestCoinZ - 5;

        coin.position.set(lanes[currentLaneIndex], -1.6, newZ);
        coin.visible = true;
        coinsPlaced++;

        if (coinsPlaced >= coinsPerLane) {
            currentLaneIndex = (currentLaneIndex + 1) % lanes.length;
            coinsPlaced = 0;
        }
    }

    function manageCoins(delta) {
        // Use time-accumulated version instead of frame count
        manageCoins.timeSinceLastUpdate = (manageCoins.timeSinceLastUpdate || 0) + delta;

        if (manageCoins.timeSinceLastUpdate < updateFrequency * (1 / 60)) return;
        manageCoins.timeSinceLastUpdate = 0;

        const sphereZ = sphere.position.z;

        coinPool.forEach((coin) => {
            // Apply rotation to the coin scaled by delta
            coin.rotation.z += 0.2 * delta * 60; // Normalize to 60 FPS base

            // Recycle coins that are behind the player
            if (coin.position.z > sphereZ + recycleThreshold) {
                recycleCoin(coin);
            }

            // Check for collection
            if (coin.visible && coinCollision(sphere, coin)) {
                console.log('Coin collected!');
                collectedCoins++;
                coinCounterElement.innerText = `Coins: ${collectedCoins}`;
                recycleCoin(coin);
            }
        });
    }


  let currentCameraY = 2.5;
    let currentCameraX = 0;

    let shakeTime = 0;
    let shakeIntensity = 0;
    let shakeDuration = 0;

    function triggerCameraShake(intensity = 0.1, duration = 0.3) {
        shakeIntensity = intensity;
        shakeDuration = duration;
        shakeTime = duration;
    }

    function updateCamera(delta) {
        const followSpeed = isMobile ? Math.min(10 * delta, 1) : Math.min(5 * delta, 1);
        const yOffset = isMobile ? 1.8 : 1.5;

        const targetY = sphere.position.y + yOffset;
        const minCameraY = 1.5;
        const clampedTargetY = Math.max(targetY, minCameraY);

        currentCameraY += (clampedTargetY - currentCameraY) * followSpeed;
        currentCameraX += (sphere.position.x - currentCameraX) * followSpeed;

        const desiredPosition = new THREE.Vector3(
            currentCameraX,
            currentCameraY,
            sphere.position.z + 3.5
        );

        // Step 1: Lerp camera position as usual
        camera.position.lerp(desiredPosition, followSpeed);

        // Step 2: Store smoothed position
        const smoothedCameraPosition = camera.position.clone();

        // Step 3: Apply camera shake visually (not logically)
        if (shakeTime > 0) {
            const shakeProgress = shakeTime / shakeDuration;
            const dampenedIntensity = shakeIntensity * shakeProgress;

            const shakeX = (Math.random() - 0.5) * 2 * dampenedIntensity;
            const shakeY = (Math.random() - 0.5) * 2 * dampenedIntensity;
            const shakeZ = (Math.random() - 0.5) * 2 * dampenedIntensity;

            camera.position.set(
                smoothedCameraPosition.x + shakeX,
                smoothedCameraPosition.y + shakeY,
                smoothedCameraPosition.z + shakeZ
            );

            shakeTime -= delta;
            if (shakeTime < 0) shakeTime = 0;
        } else {
            // Ensure position is exactly the smoothed one when shake ends
            camera.position.copy(smoothedCameraPosition);
        }

        // Always look slightly above the sphere
        const lookAtPosition = sphere.position.clone();
        lookAtPosition.y += 1.2;
        camera.lookAt(lookAtPosition);
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
        let lives = 5;
        let isGameOver = false;
        let invincible = false; // temporary buffer to prevent multiple hits in a row


        const { tunnel, groundSegment } = placeRandomTunnel(groundSegmentPool);

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
                                let lastZ = sphere.position.z;

                                options.forEach((option, index) => {
                                    const optionText = createTextMesh(option.option_text, font);
                                    console.log(option.option_text);
                                    // Increase spacing — guarantee each cube is at least 20 units behind the previous
                                    const minGap = 30; // minimum distance between cubes
                                    const extraGap = Math.random() * 30; // add some randomness
                                    const zPos = lastZ - (minGap + extraGap);
                                    
                                    const optionCube = createOptionCube(optionText, {
                                        x: lanes[Math.floor(Math.random() * lanes.length)],
                                        y: -0.3,
                                        z: zPos,
                                    });
                                    scene.add(optionCube);
                                    lastZ = zPos;

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

            const enemies = [];
            let trainModel;
            // const textureLoader = new THREE.TextureLoader();
            // const steelTexture = textureLoader.load('red_train2.jpg', () => {
            //     console.log("Texture loaded successfully");
            // }, undefined, (err) => {
            //     console.error("Error loading texture:", err);
            // });

            loader.load('my_bullet_train1_Optimized.glb', (gltf) => {
                trainModel = gltf.scene;
                trainModel.traverse((child) => {
                    if (child.isMesh) {
                        child.castShadow = false;
                        child.receiveShadow = true;
                        // steelTexture.wrapS = THREE.RepeatWrapping;
                        // steelTexture.wrapT = THREE.RepeatWrapping;
                        // steelTexture.repeat.set(4, 10); // Adjust as needed
                        // child.material = new THREE.MeshBasicMaterial({ 
                        //     map: steelTexture,
                        // });
                    }
                    trainModel.frustumCulled = true;
                });
                console.log("train model loaded")
            }, undefined, (error) => {
                console.error('Error loading train model:', error);
            });

            function spawnEnemy() {
                if (!trainModel) return; // Ensure the model is loaded before spawning

                const randomTrackIndex = Math.floor(Math.random() * lanes.length);
                const enemy = trainModel.clone();

                enemy.position.set(
                    lanes[randomTrackIndex], 
                    -1.4, 
                    sphere.position.z - 30
                );

                enemy.scale.set(0.38, 0.38, 0.38); // Adjust scale if needed
                enemy.velocity = { x: 0, y: 0, z: 5 };

                scene.add(enemy);
                enemies.push(enemy);
            }

            function animate() {
                if (gameOverFlag) return;
                const animationId = requestAnimationFrame(animate);
                
                let delta = clock.getDelta(); // delta in seconds 
                delta = Math.min(delta, 0.05); // Max ~20 FPS gap
                time += delta * 0.15; // Controls speed of wave motion

                water.position.z = Math.sin(time) * 3.5; // Moves in and out
                water.position.y = -2.5 + Math.sin(time * 0.5) * 0.2; // Slight rise & fall

                foamPlane.position.z = -50 + Math.sin(time) * 3.5; // Moves forward & backward with waves
                foamPlane.position.y = -2.5 + Math.cos(time * 0.5) * 0.35; // Small rise & fall for realism

                if (foamMaterial.map) {
                    foamMaterial.map.offset.y += delta * 0.5; // Instead of fixed 0.01
                    foamMaterial.map.repeat.y = 1 + Math.sin(time) * 0.2; // Foam expands & contracts slightly
                }

                if (gamePaused) return; // Exit if the game is paused

                smoothLaneSwitch();
                sphere.update(ground, delta); // pass delta here
                updateCamera(delta); // update camera based on delta if needed

                manageGroundSegments();
                manageTunnel(tunnel, groundSegment);
                manageCoins(delta);
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

                // Enemy updates
                for (let i = enemies.length - 1; i >= 0; i--) {
                    const enemy = enemies[i];

                    if (enemy.position.z > sphere.position.z - 50) {
                        enemy.position.z += enemy.velocity.z * delta;

                        const groundHeight = checkGroundCollision(enemy);
                        if (groundHeight !== null) {
                            enemy.velocity.y = 0;
                        } else {
                            enemy.position.y -= 0.1 * delta;
                        }

                        if (enemy.position.z > sphere.position.z + 5) continue;

                        const distance = sphere.position.distanceTo(enemy.position);
                        if (distance < 10) { 
                            if (detectCollision({ player: sphere, trainModel: enemy })) {
                                handleCollision();
                            }
                        }
                    } else {
                        enemies.splice(i, 1);
                        scene.remove(enemy);
                    }
                }

                // Spawn new enemies
                if (frames % spawnRate === 0) {
                    spawnEnemy();
                    if (spawnRate > 20) spawnRate = Math.floor(Math.random() * 200) + 50;
                }

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

            // Reshape Arabic if needed
            if (isArabic) {
                text = ArabicReshaper.convertArabic(text);
                text = text.split('').reverse().join('');
            }

            // Word wrapping
            let words = text.split(' ');
            let lines = [];
            let line = '';
            words.forEach(word => {
                if ((line + word).length > 15) { // Wrap point
                    lines.push(line.trim());
                    line = word + ' ';
                } else {
                    line += word + ' ';
                }
            });
            lines.push(line.trim());

            // Find the widest line width
            let maxWidth = 0;
            const tempGeo = new TextGeometry('A', { font, size: 0.2, height: 0.01 });
            lines.forEach(l => {
                const geo = new TextGeometry(l, { font, size: 0.2, height: 0.01 });
                geo.computeBoundingBox();
                const width = geo.boundingBox.max.x - geo.boundingBox.min.x;
                if (width > maxWidth) maxWidth = width;
            });

            // Create a group to hold each centered line
            const textGroup = new THREE.Group();
            lines.forEach((l, i) => {
                const geo = new TextGeometry(l, { font, size: 0.2, height: 0.01, curveSegments: 8 });
                geo.computeBoundingBox();
                const width = geo.boundingBox.max.x - geo.boundingBox.min.x;
                geo.translate(-width / 2, 0, 0); // Center this line
                const mat = new THREE.MeshBasicMaterial({ color: '#000000' });
                const mesh = new THREE.Mesh(geo, mat);
                mesh.position.y = -(i * 0.25); // Space between lines
                textGroup.add(mesh);
            });

            // Center the whole block vertically
            textGroup.position.y += (lines.length - 1) * 0.125;

            return textGroup;
        }


        function createOptionCube(textMesh, position) {
            // Create the cube using the Box class
            const opcube = new Box({
                width: 0.6,
                height: 0.6,
                depth: 0.6,
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
                cubeSize.y / 2 + 1, // Slightly above the top face of the cube
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
            document.getElementById('coinDisplay').innerText = collectedCoins;

            $('#gameOverModal').modal('show');
            gamePaused = true;
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