document.addEventListener('DOMContentLoaded', () => {
    const stages = Array.from(document.querySelectorAll('.stage')).map(stage => ({
        id: stage.dataset.id,
        title: stage.dataset.title,
        description: stage.dataset.description,
        gpxFilename: stage.dataset.gpxFilename,
    }));

    let currentStageIndex = 0;

    // Initialiser la carte
    const map = L.map('map').setView([50.8, 2.6], 9);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let gpxLayer = null;
    const segmentLayers = []; // Stocker les couches des segments

    // Charger un fichier GPX (format XML)
    const loadGPX = (filePath, color = 'blue') => {
        if (gpxLayer) map.removeLayer(gpxLayer);

        gpxLayer = new L.GPX(filePath, {
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
        })
        .on('loaded', (e) => {
            const bounds = e.target.getBounds();
            map.fitBounds(bounds, { padding: [50, 50] });
        })
        .addTo(map);

        const downloadButton = document.getElementById('download-gpx');
        downloadButton.href = filePath;
        downloadButton.download = filePath.split('/').pop();
    };

    // Charger les segments du tracé global
    const loadGlobalSegments = (globalFilePath, segments) => {
        fetch(globalFilePath)
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(data, "text/xml");
                
                segments.forEach((segment, index) => {
                    const { color } = segment;
    
                    // Extraire les segments du tracé global
                    const trkpts = Array.from(xmlDoc.getElementsByTagName('trkpt'))
                        .slice(index * Math.floor(xmlDoc.getElementsByTagName('trkpt').length / segments.length), 
                               (index + 1) * Math.floor(xmlDoc.getElementsByTagName('trkpt').length / segments.length))
                        .map(pt => [
                            parseFloat(pt.getAttribute('lat')),
                            parseFloat(pt.getAttribute('lon'))
                        ]);
    
                    const polyline = L.polyline(trkpts, {
                        color: color,
                        weight: 5,
                        opacity: 0.7,
                    }).addTo(map);
    
                    polyline.on('mouseover', () => {
                        polyline.setStyle({ weight: 6, opacity: 1 });
                    });
    
                    polyline.on('mouseout', () => {
                        polyline.setStyle({ weight: 5, opacity: 0.7 });
                    });
    
                    polyline.on('click', () => {
                        currentStageIndex = index + 1;
                        updateStageDetails();
                    });
    
                    segmentLayers.push(polyline); // Ajouter la couche segment pour référence future
                });
    
                console.log('Tracé global avec segments chargé.');
                map.fitBounds(segmentLayers.reduce((bounds, layer) => bounds.extend(layer.getBounds()), L.latLngBounds()), { padding: [50, 50] });
            })
            .catch(error => console.error('Erreur lors du chargement du tracé global:', error));
    };

    // Mettre à jour les informations du stage
    const updateStageDetails = () => {
        const stage = stages[currentStageIndex];
        document.getElementById('stage-title').textContent = stage.title;
        document.getElementById('stage-description').innerHTML = stage.description;
        loadGPX(`/uploads/gpx/${stage.gpxFilename}`, 'blue');

        // Restaurer les couleurs des segments
        segmentLayers.forEach((layer, index) => {
            const { color } = segments[index];
            layer.setStyle({
                color: color,
                weight: 5,
                opacity: 0.7,
            });
        });

        // Désactiver/activer les boutons suivant et précédent
        document.getElementById('prev-stage').disabled = currentStageIndex === 0;
        document.getElementById('next-stage').disabled = currentStageIndex === stages.length - 1;
    };

    // Gestion des boutons
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

    // Gestion du téléchargement d'un fichier GPX local
    document.getElementById('file-input').addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const content = e.target.result; // Contenu du fichier GPX
                loadLocalGPX(content); // Appel de la fonction de chargement du fichier local
            };
            reader.readAsText(file); // Lire le fichier en tant que texte
        }
    });

    // Fonction pour charger un GPX local (fichier téléchargé)
    const loadLocalGPX = (gpxContent) => {
        if (gpxLayer) map.removeLayer(gpxLayer);

        gpxLayer = new L.GPX(gpxContent, {
            async: true,
            marker_options: {
                startIconUrl: '/js/leaflet/images/pin-icon-start.png',
                endIconUrl: '/js/leaflet/images/pin-icon-end.png',
                shadowUrl: '/js/leaflet/images/pin-shadow.png'
            },
            polyline_options: {
                color: 'blue',
                weight: 5,
                opacity: 0.7,
                lineJoin: 'round',
            }
        })
        .on('loaded', (e) => {
            const bounds = e.target.getBounds();
            map.fitBounds(bounds, { padding: [50, 50] });
        })
        .addTo(map);

        console.log('Fichier GPX local chargé avec succès.');
    };

    // Initialisation
    const globalFilePath = '/uploads/gpx/eurovelo-5-via-romea-676e5fe0e64ad692906972.xml';
    const segments = [
        { gpxFilename: '1-calais-st-omer-6773d110a588e057960527.xml', color: 'blue' },
        { gpxFilename: '2-st-omer-bethunes-6773d14809d2b875170057.xml', color: 'grey' },
        { gpxFilename: '3-bethunes-lens-6773d169ec75d411725225.xml', color: 'red' },
        { gpxFilename: '4-lens-arras-6773d189736ee4b7284265.xml', color: 'yellow' },
        { gpxFilename: '5-arras-amiens-6773d1b78ff3032a2ac0a7.xml', color: 'black' }
    ];

    loadGlobalSegments(globalFilePath, segments);
    updateStageDetails();
});
