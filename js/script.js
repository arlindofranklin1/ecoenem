const container = document.getElementById("earth-container");

// Cena
const scene = new THREE.Scene();

// Câmera
const camera = new THREE.PerspectiveCamera(
    75,
    container.clientWidth / container.clientHeight,
    0.1,
    1000
);

camera.position.z = 8;

// Renderizador
const renderer = new THREE.WebGLRenderer({
    alpha: true,
    antialias: true
});

renderer.setSize(
    container.clientWidth,
    container.clientHeight
);

renderer.setPixelRatio(
    Math.min(window.devicePixelRatio, 2)
);

container.appendChild(
    renderer.domElement
);

let earth;

// Carregar mapa
const loader = new THREE.TextureLoader();

loader.load(
    "imgs/mapamundi.jpg",

    function (texture) {

        createEarth(texture);
    },

    undefined,

    function (error) {

        console.error(
            "Erro ao carregar imagem:",
            error
        );
    }
);

function createEarth(texture) {

    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");

    canvas.width = texture.image.width;
    canvas.height = texture.image.height;

    ctx.drawImage(texture.image, 0, 0);

    const imageData = ctx.getImageData(
        0,
        0,
        canvas.width,
        canvas.height
    ).data;

    const positions = [];
    const colors = [];

    const radius = 3;

    // quantidade de linhas horizontais
    const latSteps = 90;

    // quantidade de pontos por linha
    const lonSteps = 180;

    const continentColor =
        new THREE.Color("#B5C9BA");

    const oceanColor =
        new THREE.Color("#2B4334");

    for (let latIndex = 0; latIndex <= latSteps; latIndex++) {

        const lat =
            (latIndex / latSteps) * Math.PI;


        for (let lonIndex = 0; lonIndex < lonSteps; lonIndex++) {

            const lon =
                (1 - (lonIndex / lonSteps)) *
                Math.PI * 2;

            const x =
                radius *
                Math.sin(lat) *
                Math.cos(lon);

            const y =
                radius *
                Math.cos(lat);

            const z =
                radius *
                Math.sin(lat) *
                Math.sin(lon);

            positions.push(
                x,
                y,
                z
            );

            // Coordenadas da imagem
            const mapX = Math.floor(
                (lonIndex / lonSteps) *
                canvas.width
            );

            const mapY = Math.floor(
                (latIndex / latSteps) *
                canvas.height
            );

            const index =
                (mapY * canvas.width + mapX) * 4;

            const r = imageData[index];
            const g = imageData[index + 1];
            const b = imageData[index + 2];

            const brightness =
                (r + g + b) / 3;

            const isPolarRegion =
                latIndex < 4 ||
                latIndex > latSteps - 4;

            if (isPolarRegion) {

                // Cor dos polos
                const polarColor =
                    new THREE.Color("#2B4334");

                colors.push(
                    polarColor.r,
                    polarColor.g,
                    polarColor.b
                );

            } else if (brightness < 128) {

                colors.push(
                    continentColor.r,
                    continentColor.g,
                    continentColor.b
                );

            } else {

                colors.push(
                    oceanColor.r,
                    oceanColor.g,
                    oceanColor.b
                );
            }
        }
    }

    const geometry =
        new THREE.BufferGeometry();

    geometry.setAttribute(
        "position",
        new THREE.Float32BufferAttribute(
            positions,
            3
        )
    );

    geometry.setAttribute(
        "color",
        new THREE.Float32BufferAttribute(
            colors,
            3
        )
    );

    const material =
        new THREE.PointsMaterial({

            size: 0.045,

            vertexColors: true,

            transparent: true,

            opacity: 1,

            blending:
                THREE.AdditiveBlending,

            depthWrite: false
        });

    earth =
        new THREE.Points(
            geometry,
            material
        );

    scene.add(earth);
}

// Controle do mouse

let dragging = false;

let previousMouse = {
    x: 0,
    y: 0
};

renderer.domElement.addEventListener(
    "mousedown",

    function (e) {

        dragging = true;

        previousMouse.x =
            e.clientX;

        previousMouse.y =
            e.clientY;
    }
);

window.addEventListener(
    "mouseup",

    function () {

        dragging = false;
    }
);

window.addEventListener(
    "mousemove",

    function (e) {

        if (!dragging || !earth)
            return;

        const deltaX =
            e.clientX -
            previousMouse.x;

        const deltaY =
            e.clientY -
            previousMouse.y;

        earth.rotation.y +=
            deltaX * 0.01;

        earth.rotation.x +=
            deltaY * 0.005;

        previousMouse.x =
            e.clientX;

        previousMouse.y =
            e.clientY;
    }
);

// Animação

function animate() {

    requestAnimationFrame(
        animate
    );

    if (earth) {

        earth.rotation.y +=
            0.0015;
    }

    renderer.render(
        scene,
        camera
    );
}

animate();

// Responsivo

window.addEventListener(
    "resize",

    function () {

        camera.aspect =
            container.clientWidth /
            container.clientHeight;

        camera.updateProjectionMatrix();

        renderer.setSize(
            container.clientWidth,
            container.clientHeight
        );
    }
);