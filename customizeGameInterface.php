<?php
use yii\helpers\Html;

/** @var $backgrounds */
/** @var $avatars */
/** @var $gameModels */
/** @var $gameName */
/** @var $gameId */

$this->title = "Customize Game Interface: $gameName";
?>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #ffffff;
        color: #333;
    }

    h1 {
        text-align: left;
        font-size: 32px;
        margin-top: 40px;
        margin-bottom: 30px;
        color: #222;
        font-weight: bold;
    }

    h3 {
        font-size: 22px;
        color: #444;
        margin-top: 40px;
        margin-bottom: 20px;
        font-weight: bold;
        border-bottom: 2px solid #ddd;
        padding-bottom: 10px;
    }

    .section-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: left;
        gap: 30px; /* <-- this handles spacing between cards */
        padding: 0 40px;
        margin-top: 20px;
    }

    .preview-box,
    .model-canvas {
        width: 250px;
        height: 200px;
        border-radius: 16px;
        background-color: #fff;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }

    .preview-box:hover,
    .model-canvas:hover {
        transform: scale(1.03);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
    }

    .preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Canvas for 3D models */
    .model-canvas {
        width: 250px;
        height: 200px;
        border-radius: 16px;
        background-color: #fff;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.3s ease;
    }

    .model-canvas:hover {
        transform: scale(1.03);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
    }

    /* Optional: center container content globally */
    .content-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 60px;
    }

    p {
        text-align: left;
        color: #666;
        font-style: italic;
        margin-top: 20px;
    }
    .selectable-box {
        position: relative;
        width: 250px;
        height: 200px;
        border-radius: 16px;
        background-color: #fff;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.3s ease, border 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }

    .selectable-box:hover {
        transform: scale(1.03);
    }

    .selectable-box img,
    .selectable-box canvas {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .selectable-box.selected {
        border: 3px solid #28a745;
    }

    .selectable-box.selected::after {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.25);
    }

    .selectable-box .checkmark {
        display: none;
        position: absolute;
        top: 8px;
        right: 8px;
        background-color: #28a745;
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        line-height: 28px;
        z-index: 2;
    }

    .selectable-box.selected .checkmark {
        display: block;
    }

    .apply-container {
        text-align: left;
        margin-top: 40px;
        margin-bottom: 20px;
    }

    .apply-button {
        background: linear-gradient(135deg, #007bff, #0069d9); /* Bootstrap blue shades */
        color: white;
        border: none;
        padding: 6px 15px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        position: relative;
        transition: background 0.3s ease, transform 0.2s ease;
        margin-right: 12px; /* Add margin to create space between buttons */
    }

    .apply-button:hover {
        background: linear-gradient(135deg, #0069d9, #007bff);
        transform: translateY(-2px);
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
    }

    .spinner {
        margin-left: 10px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-top: 3px solid white;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        animation: spin 1s linear infinite;
        vertical-align: middle;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .apply-message {
        margin-top: 15px;
        font-size: 20px;
        font-weight: bold;
        color: #007bff;
        animation: fadeIn 0.5s ease-in-out;
        text-align: left;
    }

    @keyframes fadeIn {
        0% { opacity: 0; transform: scale(0.95);}
        100% { opacity: 1; transform: scale(1);}
    }

    .cancel-btn {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border: none;
        padding: 6px 15px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .cancel-btn:hover {
        background: linear-gradient(135deg, #c82333, #bd2130);
        transform: translateY(-2px);
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
    }

    .cancel-button:active {
        transform: scale(0.96);
    }
    .progress-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        background-color: #222;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        z-index: 9999;
        transition: opacity 0.5s ease;
        opacity: 1;
        display: none;
        padding: 15px;
        text-align: center;
    }

    .progress-container.hidden {
        opacity: 0;
    }

    .progress-bar {
        height: 12px;
        width: 0%;
        background-color: #4caf50;
        transition: width 0.3s ease;
        border-radius: 5px;
        margin-bottom: 8px;
    }

    .progress-label {
        color: #fff;
        font-size: 14px;
    }

    @media screen and (max-width: 768px) {
        h1 {
            font-size: 24px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        h3 {
            font-size: 18px;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .section-container {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            gap: 20px;
            padding: 0 20px;
            margin-top: 15px;
        }

        .selectable-box,
        .model-canvas {
            width: 180px !important;
            height: 150px !important;
            flex: 0 0 auto;
        }

        .preview-box {
            width: 180px !important;
            height: 150px !important;
            flex: 0 0 auto;
        }

        .apply-button,
        .cancel-btn {
            font-size: 16px;
            padding: 5px 12px;
        }

        .apply-message {
            font-size: 16px;
        }
    }

</style>
<body>
<div class="container">

    <h1><?= Html::encode($this->title) ?></h1>
    <!-- Loading Progress UI -->
    <div id="progress-container" class="progress-container hidden">
        <div id="progress-bar" class="progress-bar"></div>
        <div id="progress-label" class="progress-label">Loading...</div>
    </div>

    <!-- ==================== BACKGROUNDS ==================== -->
    <h3>Background Image Preview</h3>
    <?php if (!empty($backgrounds)): ?>
        <div class="section-container">
            <?php foreach ($backgrounds as $graphic): ?>
                <div class="selectable-box" data-group="background" data-id="<?= $graphic->image_id ?>">
                <div class="checkmark">✔</div>
                    <img src="<?= Html::encode($graphic->file_path) ?>" alt="Background Image" style="max-width: 250px; height: auto;">
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No background images available.</p>
    <?php endif; ?>


    <h3>3D Avatar Preview</h3>
    <?php if (!empty($avatars)): ?>
        <div class="section-container">
            <?php foreach ($avatars as $i => $avatar): ?>
                <div class="selectable-box" data-group="avatar" data-id="<?= $avatar->avatar_id ?>">
                    <div class="checkmark">✔</div>
                    <div class="model-canvas" id="avatar-container-<?= $i ?>"></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No avatars available.</p>
    <?php endif; ?>


    <h3>3D Game Model Preview</h3>
    <?php if (!empty($gameModels)): ?>
        <div class="section-container">
            <?php foreach ($gameModels as $i => $graphic): ?>
                <div class="selectable-box" data-group="model" data-id="<?= $graphic->model_id ?>">
                    <div class="checkmark">✔</div>
                    <div class="model-canvas" id="model-container-<?= $i ?>"></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No game models available.</p>
    <?php endif; ?>

    <!-- ==================== APPLY BUTTON ==================== -->
    <div class="apply-container">
        <button id="apply-button" class="apply-button">
            Apply
            <span class="spinner" id="apply-spinner" style="display: none;"></span>
        </button>
        <button id="cancel-button" class="cancel-btn">Cancel</button>
        <div id="apply-message" class="apply-message" style="display: none;">Selection Applied!</div>
    </div>
    
    <!-- ==================== Load Three.js Scripts Once ==================== -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/DRACOLoader.js"></script>

</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const avatarData = <?= json_encode(array_map(function ($a, $i) {
            return ['id' => $i, 'path' => $a->avatar_path];
        }, $avatars, array_keys($avatars))) ?>;

        const modelData = <?= json_encode(array_map(function ($m, $i) {
            return ['id' => $i, 'path' => $m->model_path];
        }, $gameModels, array_keys($gameModels))) ?>;

        const loadingManager = new THREE.LoadingManager();

        // Show the progress UI when loading starts
        loadingManager.onStart = function (url, itemsLoaded, itemsTotal) {
            console.log('Started loading:', url, 'Loaded', itemsLoaded, 'of', itemsTotal);
            const container = document.getElementById('progress-container');
            container.style.display = 'block';
            container.classList.remove('hidden');
        };

        // Update progress bar and label
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

        // Hide the progress UI after everything is loaded
        loadingManager.onLoad = function () {
            console.log('All assets loaded!');
            const container = document.getElementById('progress-container');
            container.classList.add('hidden');

            setTimeout(() => {
                container.style.display = 'none';
            }, 500); // match the fade duration
        };

        loadingManager.onError = (url) => {
            console.error(`Error loading ${url}`);
        };
        
        const loader = new THREE.GLTFLoader(loadingManager);
        const dracoLoader = new THREE.DRACOLoader();
        dracoLoader.setDecoderPath("https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/libs/draco/");
        loader.setDRACOLoader(dracoLoader);

        avatarData.forEach(({id, path}) => {
            loadModel(`avatar-container-${id}`, path, 2);
        });

        modelData.forEach(({id, path}) => {
            loadModel(`model-container-${id}`, path, 3);
        });

        function loadModel(containerId, modelPath, scaleFactor) {
            const container = document.getElementById(containerId);
            if (!container) return;

            const scene = new THREE.Scene();
            scene.background = new THREE.Color(0x000000);

            const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
            camera.position.set(2, 2, 5);

            const renderer = new THREE.WebGLRenderer({antialias: true});
            renderer.setSize(container.clientWidth, container.clientHeight);
            container.appendChild(renderer.domElement);

            const ambientLight = new THREE.AmbientLight(0xffffff, 1);
            scene.add(ambientLight);

            const directionalLight = new THREE.DirectionalLight(0xffffff, 2);
            directionalLight.position.set(5, 10, 7.5);
            scene.add(directionalLight);

            loader.load(modelPath, function (gltf) {
                const model = gltf.scene;
                model.scale.set(scaleFactor, scaleFactor, scaleFactor);
                model.position.set(0, 0, 0);
                scene.add(model);
                animate();
            }, undefined, function (error) {
                console.error("Failed to load model:", error);
            });

            function animate() {
                requestAnimationFrame(animate);
                renderer.render(scene, camera);
            }

            window.addEventListener("resize", function () {
                camera.aspect = container.clientWidth / container.clientHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(container.clientWidth, container.clientHeight);
            });
        }
    });

    function handleBoxSelect(e) {
        e.preventDefault(); // Important for mobile!
        const box = e.currentTarget;
        const group = box.getAttribute('data-group');
        const id = box.getAttribute('data-id');
        console.log(`Tapped box in group "${group}" with ID: ${id}`);

        const selectedInGroup = document.querySelector(`.selectable-box.selected[data-group="${group}"]`);

        if (box.classList.contains('selected')) {
            box.classList.remove('selected');
            console.log(`Deselected box from group "${group}"`);
            return;
        }

        if (selectedInGroup && selectedInGroup !== box) {
            alert(`Only one ${group} can be selected at a time.`);
            return;
        }

        box.classList.add('selected');
        if (navigator.vibrate) navigator.vibrate(30);
        console.log(`Selected box in group "${group}" with ID: ${id}`);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const boxes = document.querySelectorAll('.selectable-box');

        boxes.forEach(box => {
            box.addEventListener('click', handleBoxSelect);
            box.addEventListener('touchstart', handleBoxSelect, { passive: false });
        });

        const applyBtn = document.getElementById('apply-button');
        const spinner = document.getElementById('apply-spinner');
        const message = document.getElementById('apply-message');

        if (applyBtn) {
            applyBtn.addEventListener('click', function () {
                const selectedBackground = document.querySelector('.selectable-box.selected[data-group="background"]');
                const selectedAvatar = document.querySelector('.selectable-box.selected[data-group="avatar"]');
                const selectedModel = document.querySelector('.selectable-box.selected[data-group="model"]');

                const bgInput = document.getElementById('selected-background');
                const avatarInput = document.getElementById('selected-avatar');
                const modelInput = document.getElementById('selected-model');

                if (bgInput) {
                    bgInput.value = selectedBackground ? selectedBackground.getAttribute('data-id') : '';
                    console.log('Selected background ID:', bgInput.value);
                }

                if (avatarInput) {
                    avatarInput.value = selectedAvatar ? selectedAvatar.getAttribute('data-id') : '';
                    console.log('Selected avatar ID:', avatarInput.value);
                }

                if (modelInput) {
                    modelInput.value = selectedModel ? selectedModel.getAttribute('data-id') : '';
                    console.log('Selected model ID:', modelInput.value);
                }

                // if (!selectedBackground && !selectedAvatar && !selectedModel) {
                //     alert('Please select at least one option (background, avatar, or model) before applying.');
                //     if (spinner) spinner.style.display = 'none';
                //     return;
                // }
                // Store selections in localStorage for persistence
                const selectionData = {
                    backgroundId: selectedBackground ? selectedBackground.getAttribute('data-id') : null,
                    avatarId: selectedAvatar ? selectedAvatar.getAttribute('data-id') : null,
                    modelId: selectedModel ? selectedModel.getAttribute('data-id') : null
                };

                const urlParams = new URLSearchParams(window.location.search);
                const assid = urlParams.get('assid');
                
                localStorage.setItem(`gameSelections_${assid}`, JSON.stringify(selectionData));

                alert("Selections applied! Please click 'Save' to confirm.");

                if (!assid) {
                    alert('Assignment ID (assid) not found in URL.');
                    if (spinner) spinner.style.display = 'none';
                    return;
                }

                if (spinner) spinner.style.display = 'inline-block';

                const assignmentData = {
                    assid: assid,
                    backgroundId: selectedBackground ? selectedBackground.getAttribute('data-id') : null,
                    avatarId: selectedAvatar ? selectedAvatar.getAttribute('data-id') : null,
                    modelId: selectedModel ? selectedModel.getAttribute('data-id') : null
                };

                console.log('Data being sent to backend:', assignmentData);

                fetch('<?= \yii\helpers\Url::to(['game-assignments/apply-selection']) ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
                    },
                    body: JSON.stringify(assignmentData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Server response:', data);
                    if (spinner) spinner.style.display = 'none';
                    if (message) message.style.display = 'block';
                    setTimeout(() => window.history.back(), 1000);
                })
                .catch(error => {
                    console.error('Error while sending selection:', error);
                    if (spinner) spinner.style.display = 'none';
                });
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const cancelBtn = document.getElementById('cancel-button');
        const urlParams = new URLSearchParams(window.location.search);
        const assid = urlParams.get('assid');

        if (cancelBtn) {
            const handleCancel = async (e) => {
                e.preventDefault();

                // Remove visual selections
                document.querySelectorAll('.selectable-box.selected')
                    .forEach(box => box.classList.remove('selected'));

                // Clear any hidden inputs
                const fields = ['selected-background', 'selected-avatar', 'selected-model'];
                fields.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = '';
                });

                // Hide spinner/message if visible
                const spinner = document.getElementById('apply-spinner');
                const message = document.getElementById('apply-message');
                if (spinner) spinner.style.display = 'none';
                if (message) message.style.display = 'none';

                // Clear backend selection too
                try {
                    const response = await fetch('<?= \yii\helpers\Url::to(['game-assignments/clear-selection']) ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
                        },
                        body: JSON.stringify({
                            assid: assid,
                            background: '',
                            avatar: '',
                            model: ''
                        })
                    });

                    if (response.ok) {
                        // Only go back if the server cleared successfully
                        window.history.back();
                    } else {
                        alert('Failed to cancel selection. Please try again.');
                    }
                } catch (error) {
                    console.error('Cancel error:', error);
                    alert('An error occurred while cancelling. Check your connection.');
                }
            };

            cancelBtn.addEventListener('click', handleCancel);
            cancelBtn.addEventListener('touchstart', handleCancel, { passive: false }); // Mobile
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const assid = urlParams.get('assid');

        if (assid) {
            const storedSelections = localStorage.getItem(`gameSelections_${assid}`);
            if (storedSelections) {
                const { backgroundId, avatarId, modelId } = JSON.parse(storedSelections);

                if (backgroundId) {
                    const bgBox = document.querySelector(`.selectable-box[data-group="background"][data-id="${backgroundId}"]`);
                    if (bgBox) bgBox.classList.add('selected');
                }

                if (avatarId) {
                    const avatarBox = document.querySelector(`.selectable-box[data-group="avatar"][data-id="${avatarId}"]`);
                    if (avatarBox) avatarBox.classList.add('selected');
                }

                if (modelId) {
                    const modelBox = document.querySelector(`.selectable-box[data-group="model"][data-id="${modelId}"]`);
                    if (modelBox) modelBox.classList.add('selected');
                }
            }
        }
    });

</script>

</body>