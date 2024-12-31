document.addEventListener('DOMContentLoaded', () => {
    const stages = Array.from(document.querySelectorAll('.stage')).map(stage => ({
        id: stage.dataset.id,
        title: stage.dataset.title,
        description: stage.dataset.description,
        gpxFilename: stage.dataset.gpxFilename,
    }));

    let currentStageIndex = 0;

    // Initialiser la carte
    const map = L.map('map').setView([50.8, 2.6], 9); // Coordonnées HDF
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let gpxLayer = null;

    // Charger un fichier GPX (format XML)
    const loadGPX = (filePath) => {
        console.log(`Chargement du fichier GPX : ${filePath}`);
        if (gpxLayer) map.removeLayer(gpxLayer);

        gpxLayer = new L.GPX(filePath, {
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
            map.fitBounds(bounds, { padding: [50, 50] }); // Ajoute un padding pour dézoomer légèrement
        })
        .on('error', (e) => {
            console.error('Erreur lors du chargement du fichier GPX', e);
        })
        .addTo(map);

        // Mettre à jour le lien de téléchargement du fichier GPX
        const downloadButton = document.getElementById('download-gpx');
        downloadButton.href = filePath;
        downloadButton.download = filePath.split('/').pop(); // Utilise le nom du fichier pour le téléchargement
    };

    // Mettre à jour les informations du stage
    const updateStageDetails = () => {
        const stage = stages[currentStageIndex];
        document.getElementById('stage-title').textContent = stage.title;
        document.getElementById('stage-description').innerHTML = stage.description;
        loadGPX(`/uploads/gpx/${stage.gpxFilename}`);

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

    // Ajouter un gestionnaire d'événements pour le champ de fichier
    document.getElementById('file-input').addEventListener('change', (event) => {
        const file = event.target.files[0]; // Récupérer le fichier sélectionné
        if (file && file.name.endsWith('.gpx')) {
            const reader = new FileReader();

            reader.onload = (e) => {
                const gpxData = e.target.result; // Contenu du fichier GPX
                const filePath = URL.createObjectURL(new Blob([gpxData], { type: 'application/gpx+xml' }));

                loadGPX(filePath); // Charger et afficher le fichier GPX sur la carte
            };

            reader.readAsText(file); // Lire le fichier en tant que texte
        } else {
            alert('Veuillez sélectionner un fichier GPX.');
        }
    });

    // Initialisation
    updateStageDetails();
});
