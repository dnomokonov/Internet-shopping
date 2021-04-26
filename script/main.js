let progress = document.getElementById('progressbar');
let totalHeight = document.body.scrollHeight - window.innerHeight;
window.onscroll = function() {
    let progressHeight = (window.pageYOffset / totalHeight) * 100;
    progress.style.height = progressHeight + "%";
}



scene = new THREE.Scene();
camera = new THREE.PerspectiveCamera(45, window.innerHeight / window.innerHeight, 0.1, 1000);
camera.position.z = 10;

renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
renderer.setClearColor(0x000000, 0);
renderer.setSize(1280, 720);

renderer.domElement.setAttribute("id", "gamePC");
document.body.insertBefore(renderer.domElement, document.body.firstChild);

const aLight = new THREE.AmbientLight(0x404040, 1.2);
scene.add(aLight);

const pLight = new THREE.PointLight(0xFFFFFF, 1.2);
pLight.position.set(0, -3, 7);
scene.add(pLight);

const helper = new THREE.PointLightHelper(pLight);
scene.add(helper);

let loader = new THREE.GLTFLoader();
let obj = null;

loader.load('./image/model/pc2/scene.gltf', function(gltf) {
    obj = gltf;
    obj.scene.scale.set(0.5, 0.5, 0.4);
    scene.add(obj.scene);
});

function animate() {
    requestAnimationFrame(animate);
    if (obj)
        obj.scene.rotation.y += 0.01;
    renderer.render(scene, camera);
}
animate();