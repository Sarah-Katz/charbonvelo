document.addEventListener('DOMContentLoaded', () => {
    const stageElements = document.querySelectorAll('#stage-container .stage');
    const stages = Array.from(stageElements).map(stage => ({
        id: stage.dataset.id,
        title: stage.dataset.title,
        description: stage.dataset.description,
        gpxFilename: stage.dataset.gpxFilename
    }));

    let currentStageIndex = 0;

    const map = L.map('map').setView([50.8, 2.6], 9);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const colors = ['blue', 'grey', 'red', 'yellow', 'black'];
    const gpxLayers = [];
    let currentHighlightedLayer = null;

    const loadGPX = (stage, index) => {
        return new Promise((resolve) => {
            const color = colors[index % colors.length];
            const gpxLayer = new L.GPX(`/uploads/gpx/${stage.gpxFilename}`, {
                async: true,
                marker_options: {
                    startIconUrl: '/js/leaflet/images/pin-icon-start.png',
                    endIconUrl: '/js/leaflet/images/pin-icon-end.png',
                    shadowUrl: '/js/leaflet/images/pin-shadow.png'
                },
                polyline_options: {
                    color: color,
                    weight: 5,
                    opacity: 0.7,
                    lineJoin: 'round',
                }
            });

            gpxLayer.on('loaded', (e) => {
                gpxLayers.push({ layer: e.target, originalColor: color });
                resolve(e.target);
            });
        });
    };

    const loadAllGPX = async () => {
        for (let i = 0; i < stages.length; i++) {
            await loadGPX(stages[i], i);
        }

        gpxLayers.forEach(item => item.layer.addTo(map));
        const bounds = L.latLngBounds(gpxLayers.map(item => item.layer.getBounds()));
        map.fitBounds(bounds, { padding: [50, 50] });
        updateStageDetails();
    };

    const updateStageDetails = () => {
        const stage = stages[currentStageIndex];
        document.getElementById('stage-title').textContent = stage.title;
        document.getElementById('stage-description').innerHTML = stage.description;

        gpxLayers.forEach(item => {
            item.layer.setStyle({ color: item.originalColor, weight: 5, opacity: 0.7 });
        });

        if (currentHighlightedLayer) {
            currentHighlightedLayer.setStyle({ color: gpxLayers[currentStageIndex].originalColor, weight: 5, opacity: 0.7 });
        }
        currentHighlightedLayer = gpxLayers[currentStageIndex].layer;
        currentHighlightedLayer.setStyle({ color: 'green', weight: 8, opacity: 1 });
        currentHighlightedLayer.bringToFront();

        const bounds = currentHighlightedLayer.getBounds();
        map.fitBounds(bounds, { padding: [50, 50] });

        const downloadButton = document.getElementById('download-gpx');
        downloadButton.href = `/uploads/gpx/${stage.gpxFilename}`;
        downloadButton.download = stage.gpxFilename;

        document.getElementById('prev-stage').disabled = currentStageIndex === 0;
        document.getElementById('next-stage').disabled = currentStageIndex === stages.length - 1;
    };

    document.getElementById('prev-stage').addEventListener('click', () => {
        if (currentStageIndex > 0) {
            currentStageIndex--;
            updateStageDetails();
        }
    });

    document.getElementById('next-stage').addEventListener('click', () => {
        if (currentStageIndex < stages.length - 1) {
            currentStageIndex++;
            updateStageDetails();
        }
    });

    document.getElementById('file-input').addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const content = e.target.result;
                loadLocalGPX(content);
            };
            reader.readAsText(file);
        }
    });

    const loadLocalGPX = (gpxContent) => {
        const localGpxLayer = new L.GPX(gpxContent, {
            async: true,
            marker_options: {
                startIconUrl: '/js/leaflet/images/pin-icon-start.png',
                endIconUrl: '/js/leaflet/images/pin-icon-end.png',
                shadowUrl: '/js/leaflet/images/pin-shadow.png'
            },
            polyline_options: {
                color: 'purple',
                weight: 5,
                opacity: 0.7,
                lineJoin: 'round',
            }
        }).addTo(map);

        localGpxLayer.on('loaded', (e) => {
            const bounds = e.target.getBounds();
            map.fitBounds(bounds, { padding: [50, 50] });
        });

        console.log('Fichier GPX local chargé avec succès.');
    };

    loadAllGPX();
});
